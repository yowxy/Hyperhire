<?php

namespace App\Listeners;

use App\Events\PersonLikesThresholdReached;
use App\Mail\PersonLikesThresholdMail;
use App\Models\Person;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPersonLikesThresholdEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PersonLikesThresholdReached $event): void
    {
        try {
            // Get person details
            $person = Person::findOrFail($event->personId);

            // Send email to admin
            $adminEmail = config('mail.admin_email', 'admin@example.com');
            
            Mail::to($adminEmail)->send(
                new PersonLikesThresholdMail($person, $event->likesCount)
            );

            Log::info("Threshold email sent for person {$event->personId} with {$event->likesCount} likes");
        } catch (\Exception $e) {
            Log::error("Failed to send threshold email: " . $e->getMessage());
        }
    }
}
