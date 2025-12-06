<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Likes;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonService
{
    /**
     * Get recommended people with pagination
     * Excludes people that current user has already liked/disliked
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getRecommendedPeople(int $perPage = 10): LengthAwarePaginator
    {
        // Get people that haven't been liked or disliked yet
        // Ordered by created_at (newest first)
        return Person::whereDoesntHave('likes')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all people with pagination (optional)
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPeople(int $perPage = 10): LengthAwarePaginator
    {
        return Person::withCount(['likes as total_likes' => function ($query) {
                $query->where('type', 'like');
            }])
            ->withCount(['likes as total_dislikes' => function ($query) {
                $query->where('type', 'dislike');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get person by ID
     * 
     * @param int $personId
     * @return Person
     */
    public function getPersonById(int $personId): Person
    {
        return Person::with('likes')->findOrFail($personId);
    }

    /**
     * Create a new person
     * 
     * @param array $data
     * @return Person
     */
    public function createPerson(array $data): Person
    {
        return Person::create($data);
    }
}
