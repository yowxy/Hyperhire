<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="HyperHire Backend API",
 *     version="1.0.0",
 *     description="Dating Application API with like/dislike features and automated email notifications",
 *     @OA\Contact(
 *         email="admin@hyperhire.com",
 *         name="HyperHire Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 *
 * @OA\Tag(
 *     name="People",
 *     description="Person management endpoints"
 * )
 *
 * @OA\Tag(
 *     name="Likes",
 *     description="Like and dislike management endpoints"
 * )
 *
 * @OA\Schema(
 *     schema="Person",
 *     title="Person",
 *     description="Person model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="age", type="integer", example=28),
 *     @OA\Property(property="location", type="string", example="Jakarta, Indonesia"),
 *     @OA\Property(
 *         property="pictures",
 *         type="array",
 *         @OA\Items(type="string", example="https://example.com/photo.jpg")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-06T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-06T10:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Like",
 *     title="Like",
 *     description="Like/Dislike model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="people_id", type="integer", example=1),
 *     @OA\Property(property="type", type="string", enum={"like", "dislike"}, example="like"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-12-06T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-12-06T10:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     title="Success Response",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation successful"),
 *     @OA\Property(property="data", type="object")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Operation failed"),
 *     @OA\Property(property="error", type="string", example="Error details")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     title="Validation Error Response",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validation failed"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="field_name",
 *             type="array",
 *             @OA\Items(type="string", example="The field is required.")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     title="Pagination Metadata",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="per_page", type="integer", example=10),
 *     @OA\Property(property="total", type="integer", example=100),
 *     @OA\Property(property="last_page", type="integer", example=10),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="to", type="integer", example=10)
 * )
 */
abstract class Controller
{
    //
}
