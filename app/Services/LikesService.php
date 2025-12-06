<?php

namespace App\Services;

use App\Models\Likes;
use App\Models\Person;
use App\Events\PersonLikesThresholdReached;
use Illuminate\Support\Facades\DB;

class LikesService
{
    /**
     * Like a person
     * 
     * @param int $personId
     * @return Likes
     */
    public function likePerson(int $personId): Likes
    {
        // Verify person exists
        $person = Person::findOrFail($personId);

        // Check if already liked/disliked
        $existingLike = Likes::where('people_id', $personId)->first();
        
        if ($existingLike) {
            // Update existing record
            $existingLike->update(['type' => 'like']);
            $like = $existingLike;
        } else {
            // Create new like
            $like = Likes::create([
                'people_id' => $personId,
                'type' => 'like'
            ]);
        }

        // Check if person has reached 50+ likes
        $this->checkLikesThreshold($personId);

        return $like;
    }

    /**
     * Dislike a person
     * 
     * @param int $personId
     * @return Likes
     */
    public function dislikePerson(int $personId): Likes
    {
        // Verify person exists
        $person = Person::findOrFail($personId);

        // Check if already liked/disliked
        $existingLike = Likes::where('people_id', $personId)->first();
        
        if ($existingLike) {
            // Update existing record
            $existingLike->update(['type' => 'dislike']);
            return $existingLike;
        } else {
            // Create new dislike
            return Likes::create([
                'people_id' => $personId,
                'type' => 'dislike'
            ]);
        }
    }

    /**
     * Get all liked people
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLikedPeople()
    {
        return Likes::with('person')
            ->where('type', 'like')
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('person');
    }

    /**
     * Get all disliked people
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDislikedPeople()
    {
        return Likes::with('person')
            ->where('type', 'dislike')
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('person');
    }

    /**
     * Get likes count for a person
     * 
     * @param int $personId
     * @return int
     */
    public function getLikesCount(int $personId): int
    {
        return Likes::where('people_id', $personId)
            ->where('type', 'like')
            ->count();
    }

    /**
     * Check if person has reached likes threshold and trigger event
     * 
     * @param int $personId
     * @return void
     */
    protected function checkLikesThreshold(int $personId): void
    {
        $likesCount = $this->getLikesCount($personId);
        
        if ($likesCount >= 50) {
            // Trigger event to send email
            event(new PersonLikesThresholdReached($personId, $likesCount));
        }
    }

    /**
     * Get people who have more than 50 likes
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getPeopleWithHighLikes()
    {
        return DB::table('people')
            ->join('likes', 'people.id', '=', 'likes.people_id')
            ->where('likes.type', 'like')
            ->select('people.*', DB::raw('COUNT(likes.id) as likes_count'))
            ->groupBy('people.id')
            ->having('likes_count', '>=', 50)
            ->get();
    }
}
