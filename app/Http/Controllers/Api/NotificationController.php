<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use App\Interfaces\Api\Repositories\NotificationRepositoryInterface;
use App\Interfaces\Api\Services\NotificationServiceInterface;
use App\Models\AdditionalNotice;
use App\Models\AdditionalNoticeUser;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository,
        protected NotificationServiceInterface $notificationService,
    )
    {
    }

    /**
     * @OA\Get (
     *     path="/api/notifications",
     *     tags={"Notifications"},
     *     summary="Get user notifications",
     *     description="Get user notifications",
     *     operationId="notifications",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="read", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="title", type="string"),
     *                              @OA\Property(property="message", type="string"),
     *                              @OA\Property(property="image", type="string"),
     *                              @OA\Property(property="created_at", type="string"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="unread", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="title", type="string"),
     *                              @OA\Property(property="message", type="string"),
     *                              @OA\Property(property="image", type="string"),
     *                              @OA\Property(property="created_at", type="string"),
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      ),
     * )
     *
     */

    public function index(): NotificationResource
    {
        return new NotificationResource($this->notificationRepository->getAllActive());
    }

    /**
     * @OA\Patch (
     *     path="/api/notification",
     *     tags={"Notifications"},
     *     summary="Mark notification as read",
     *     description="Mark notification as read",
     *     operationId="notification",
     *     @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Notification id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="success"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          ),
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="id", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The id field is required.")
     *                      ),
     *                  ),
     *              ),
     *          ),
     *     ),
     * )
    */

    public function markAsRead(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->id) {
            return response()->json(['message' => 'The id field is required.'], 422);
        }
        $this->notificationService->markAsRead();
        return response()->json(['message' => 'success']);
    }
}
