<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\LikesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikesController extends Controller
{
    protected $likesService;

    public function __construct(LikesService $likesService)
    {
        $this->likesService = $likesService;
    }

    /**
     * @OA\Post(
     *     path="/likes/like",
     *     operationId="likePerson",
     *     tags={"Likes"},
     *     summary="Like a person",
     *     description="Like a person. If the person reaches 50+ likes, an email will be sent to the admin.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_id"},
     *             @OA\Property(property="person_id", type="integer", example=1, description="ID of the person to like")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person liked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person liked successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Like")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function like(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'person_id' => 'required|integer|exists:people,id'
            ]);

            $like = $this->likesService->likePerson($validated['person_id']);

            return response()->json([
                'success' => true,
                'message' => 'Person liked successfully',
                'data' => $like
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Person not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to like person',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/likes/dislike",
     *     operationId="dislikePerson",
     *     tags={"Likes"},
     *     summary="Dislike a person",
     *     description="Dislike a person",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"person_id"},
     *             @OA\Property(property="person_id", type="integer", example=1, description="ID of the person to dislike")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Person disliked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person disliked successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Like")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function dislike(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'person_id' => 'required|integer|exists:people,id'
            ]);

            $dislike = $this->likesService->dislikePerson($validated['person_id']);

            return response()->json([
                'success' => true,
                'message' => 'Person disliked successfully',
                'data' => $dislike
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Person not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to dislike person',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/likes/liked-people",
     *     operationId="getLikedPeople",
     *     tags={"Likes"},
     *     summary="Get all liked people",
     *     description="Returns a list of all people that have been liked",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Liked people retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Person")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function getLikedPeople(): JsonResponse
    {
        try {
            $likedPeople = $this->likesService->getLikedPeople();

            return response()->json([
                'success' => true,
                'message' => 'Liked people retrieved successfully',
                'data' => $likedPeople
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve liked people',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/likes/disliked-people",
     *     operationId="getDislikedPeople",
     *     tags={"Likes"},
     *     summary="Get all disliked people",
     *     description="Returns a list of all people that have been disliked",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Disliked people retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Person")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function getDislikedPeople(): JsonResponse
    {
        try {
            $dislikedPeople = $this->likesService->getDislikedPeople();

            return response()->json([
                'success' => true,
                'message' => 'Disliked people retrieved successfully',
                'data' => $dislikedPeople
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve disliked people',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
