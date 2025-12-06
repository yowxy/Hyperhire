<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PersonService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PersonController extends Controller
{
    protected $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * @OA\Get(
     *     path="/people/recommended",
     *     operationId="getRecommendedPeople",
     *     tags={"People"},
     *     summary="Get recommended people",
     *     description="Returns a paginated list of people who haven't been liked or disliked yet",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Recommended people retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Person")
     *             ),
     *             @OA\Property(property="pagination", ref="#/components/schemas/PaginationMeta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid per_page value",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function getRecommended(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            
            if ($perPage < 1 || $perPage > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Per page must be between 1 and 100'
                ], 400);
            }

            $people = $this->personService->getRecommendedPeople($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Recommended people retrieved successfully',
                'data' => $people->items(),
                'pagination' => [
                    'current_page' => $people->currentPage(),
                    'per_page' => $people->perPage(),
                    'total' => $people->total(),
                    'last_page' => $people->lastPage(),
                    'from' => $people->firstItem(),
                    'to' => $people->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommended people',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/people",
     *     operationId="getAllPeople",
     *     tags={"People"},
     *     summary="Get all people",
     *     description="Returns a paginated list of all people with likes/dislikes count",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="People retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Person")
     *             ),
     *             @OA\Property(property="pagination", ref="#/components/schemas/PaginationMeta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid per_page value",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            
            if ($perPage < 1 || $perPage > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Per page must be between 1 and 100'
                ], 400);
            }

            $people = $this->personService->getAllPeople($perPage);

            return response()->json([
                'success' => true,
                'message' => 'People retrieved successfully',
                'data' => $people->items(),
                'pagination' => [
                    'current_page' => $people->currentPage(),
                    'per_page' => $people->perPage(),
                    'total' => $people->total(),
                    'last_page' => $people->lastPage(),
                    'from' => $people->firstItem(),
                    'to' => $people->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve people',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/people/{id}",
     *     operationId="getPersonById",
     *     tags={"People"},
     *     summary="Get person by ID",
     *     description="Returns a single person with their likes/dislikes",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Person ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Person")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $person = $this->personService->getPersonById($id);

            return response()->json([
                'success' => true,
                'message' => 'Person retrieved successfully',
                'data' => $person
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Person not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve person',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/people",
     *     operationId="createPerson",
     *     tags={"People"},
     *     summary="Create a new person",
     *     description="Create a new person record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","age","location","pictures"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=28, minimum=18, maximum=120),
     *             @OA\Property(property="location", type="string", example="Jakarta, Indonesia"),
     *             @OA\Property(
     *                 property="pictures",
     *                 type="array",
     *                 @OA\Items(type="string", format="url", example="https://i.pravatar.cc/300?img=1")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Person created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Person created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Person")
     *         )
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
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:120',
                'location' => 'required|string|max:255',
                'pictures' => 'required|array',
                'pictures.*' => 'string|url'
            ]);

            $person = $this->personService->createPerson($validated);

            return response()->json([
                'success' => true,
                'message' => 'Person created successfully',
                'data' => $person
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create person',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
