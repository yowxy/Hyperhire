<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Likes;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 sample people
        $people = [
            [
                'name' => 'Sarah Johnson',
                'age' => 28,
                'location' => 'New York, USA',
                'pictures' => ['https://i.pravatar.cc/300?img=1', 'https://i.pravatar.cc/300?img=2']
            ],
            [
                'name' => 'Michael Chen',
                'age' => 32,
                'location' => 'Singapore',
                'pictures' => ['https://i.pravatar.cc/300?img=3', 'https://i.pravatar.cc/300?img=4']
            ],
            [
                'name' => 'Emma Watson',
                'age' => 26,
                'location' => 'London, UK',
                'pictures' => ['https://i.pravatar.cc/300?img=5', 'https://i.pravatar.cc/300?img=6']
            ],
            [
                'name' => 'David Kim',
                'age' => 30,
                'location' => 'Seoul, South Korea',
                'pictures' => ['https://i.pravatar.cc/300?img=7', 'https://i.pravatar.cc/300?img=8']
            ],
            [
                'name' => 'Sophia Rodriguez',
                'age' => 25,
                'location' => 'Barcelona, Spain',
                'pictures' => ['https://i.pravatar.cc/300?img=9', 'https://i.pravatar.cc/300?img=10']
            ],
            [
                'name' => 'James Anderson',
                'age' => 35,
                'location' => 'Sydney, Australia',
                'pictures' => ['https://i.pravatar.cc/300?img=11', 'https://i.pravatar.cc/300?img=12']
            ],
            [
                'name' => 'Olivia Taylor',
                'age' => 27,
                'location' => 'Toronto, Canada',
                'pictures' => ['https://i.pravatar.cc/300?img=13', 'https://i.pravatar.cc/300?img=14']
            ],
            [
                'name' => 'Lucas Silva',
                'age' => 29,
                'location' => 'São Paulo, Brazil',
                'pictures' => ['https://i.pravatar.cc/300?img=15', 'https://i.pravatar.cc/300?img=16']
            ],
            [
                'name' => 'Isabella Müller',
                'age' => 31,
                'location' => 'Berlin, Germany',
                'pictures' => ['https://i.pravatar.cc/300?img=17', 'https://i.pravatar.cc/300?img=18']
            ],
            [
                'name' => 'Daniel Dubois',
                'age' => 33,
                'location' => 'Paris, France',
                'pictures' => ['https://i.pravatar.cc/300?img=19', 'https://i.pravatar.cc/300?img=20']
            ],
            [
                'name' => 'Mia Nakamura',
                'age' => 24,
                'location' => 'Tokyo, Japan',
                'pictures' => ['https://i.pravatar.cc/300?img=21', 'https://i.pravatar.cc/300?img=22']
            ],
            [
                'name' => 'Alexander Petrov',
                'age' => 36,
                'location' => 'Moscow, Russia',
                'pictures' => ['https://i.pravatar.cc/300?img=23', 'https://i.pravatar.cc/300?img=24']
            ],
            [
                'name' => 'Charlotte Martin',
                'age' => 28,
                'location' => 'Amsterdam, Netherlands',
                'pictures' => ['https://i.pravatar.cc/300?img=25', 'https://i.pravatar.cc/300?img=26']
            ],
            [
                'name' => 'William Lee',
                'age' => 30,
                'location' => 'Hong Kong',
                'pictures' => ['https://i.pravatar.cc/300?img=27', 'https://i.pravatar.cc/300?img=28']
            ],
            [
                'name' => 'Amelia O\'Connor',
                'age' => 26,
                'location' => 'Dublin, Ireland',
                'pictures' => ['https://i.pravatar.cc/300?img=29', 'https://i.pravatar.cc/300?img=30']
            ],
            [
                'name' => 'Ethan Brown',
                'age' => 34,
                'location' => 'Los Angeles, USA',
                'pictures' => ['https://i.pravatar.cc/300?img=31', 'https://i.pravatar.cc/300?img=32']
            ],
            [
                'name' => 'Ava Garcia',
                'age' => 27,
                'location' => 'Madrid, Spain',
                'pictures' => ['https://i.pravatar.cc/300?img=33', 'https://i.pravatar.cc/300?img=34']
            ],
            [
                'name' => 'Noah Fischer',
                'age' => 29,
                'location' => 'Zurich, Switzerland',
                'pictures' => ['https://i.pravatar.cc/300?img=35', 'https://i.pravatar.cc/300?img=36']
            ],
            [
                'name' => 'Lily Andersson',
                'age' => 25,
                'location' => 'Stockholm, Sweden',
                'pictures' => ['https://i.pravatar.cc/300?img=37', 'https://i.pravatar.cc/300?img=38']
            ],
            [
                'name' => 'Benjamin Cohen',
                'age' => 32,
                'location' => 'Tel Aviv, Israel',
                'pictures' => ['https://i.pravatar.cc/300?img=39', 'https://i.pravatar.cc/300?img=40']
            ],
        ];

        foreach ($people as $personData) {
            Person::create($personData);
        }

        $this->command->info('Created 20 sample people');

        // Create a person with 55 likes for testing threshold email
        $popularPerson = Person::create([
            'name' => 'Popular Person',
            'age' => 28,
            'location' => 'Jakarta, Indonesia',
            'pictures' => ['https://i.pravatar.cc/300?img=50', 'https://i.pravatar.cc/300?img=51']
        ]);

        // Add 55 likes to this person
        for ($i = 0; $i < 55; $i++) {
            Likes::create([
                'people_id' => $popularPerson->id,
                'type' => 'like'
            ]);
        }

        $this->command->info('Created 1 popular person with 55 likes for testing');
    }
}
