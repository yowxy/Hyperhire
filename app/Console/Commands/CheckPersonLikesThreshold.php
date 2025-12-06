<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LikesService;
use App\Mail\PersonLikesThresholdMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckPersonLikesThreshold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:person-likes-threshold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if any person has more than 50 likes and send email to admin';

    protected $likesService;

    /**
     * Create a new command instance.
     */
    public function __construct(LikesService $likesService)
    {
        parent::__construct();
        $this->likesService = $likesService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for people with high likes...');

        try {
            $peopleWithHighLikes = $this->likesService->getPeopleWithHighLikes();

            if ($peopleWithHighLikes->isEmpty()) {
                $this->info('No people found with 50+ likes.');
                return Command::SUCCESS;
            }

            $adminEmail = config('mail.admin_email', 'admin@example.com');
            
            foreach ($peopleWithHighLikes as $personData) {
                // Convert stdClass to Person model for email
                $person = \App\Models\Person::find($personData->id);
                
                if ($person) {
                    Mail::to($adminEmail)->send(
                        new PersonLikesThresholdMail($person, $personData->likes_count)
                    );

                    $this->info("Email sent for {$person->name} with {$personData->likes_count} likes");
                    Log::info("Cronjob: Email sent for person {$person->id} with {$personData->likes_count} likes");
                }
            }

            $this->info("Total emails sent: " . $peopleWithHighLikes->count());
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to check likes threshold: ' . $e->getMessage());
            Log::error('Cronjob failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
