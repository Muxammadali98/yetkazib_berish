<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProfileResource;
use App\Rules\Base64Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct(
        protected \App\Interfaces\Api\Services\ProfileServiceInterface $profileService,
        protected \App\Interfaces\Api\Repositories\ProfileRepositoryInterface $profileRepository
    )
    {
    }

    /**
     * @OA\Get (
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Get user profile",
     *     description="Get user profile",
     *     operationId="profile",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="photo", type="string", example="https://via.placeholder.com/150"),
     *              @OA\Property(property="today_ticket_count", type="integer", example=1),
     *              @OA\Property(property="this_month_ticket_count", type="integer", example=1),
     *              @OA\Property(property="job_title", type="string", example="Supplier"),
     *              @OA\Property(property="phone_number", type="string", example="+998 99 999 99 99"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="address", type="string", example="Toshkent"),
     *              @OA\Property(property="regions", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Farg'ona"),
     *                  )
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

    public function index(): ProfileResource
    {
        return new ProfileResource($this->profileRepository->getUserData());
    }

    /**
     * @OA\Patch (
     *     path="/api/profile",
     *     tags={"Profile"},
     *     summary="Update user profile",
     *     description="Update user profile",
     *     operationId="profileUpdate",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "phone", "address", "region_id"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="phone", type="string", example="+998999999999"),
     *              @OA\Property(property="address", type="string", example="Toshkent"),
     *              @OA\Property(property="region_id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated successfully")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated failed"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="name", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The name field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="email", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The email field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="phone", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The phone field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="address", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The address field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="region_id", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The region id field is required.")
     *                      )
     *                  ),
     *              )
     *          )
     *      ),
     * )
     */

    public function profileUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'region_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-updated-failed'),
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->isMethod('patch'))
            if ($this->profileService->profileUpdate($request)) {
                return response()->json([
                    'message' => __('api-custom.user-updated-successfully')
                ], 200);
            }

        return response()->json([
            'message' => __('api-custom.user-updated-failed')
        ], 422);
    }

    /**
     * @OA\Patch (
     *     path="/api/profile/password",
     *     tags={"Profile"},
     *     summary="Update user password",
     *     description="Update user password",
     *     operationId="passwordUpdate",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"old_password", "password", "password_confirmation"},
     *              @OA\Property(property="old_password", type="string", example="123456"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="password_confirmation", type="string", example="123456"),
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated successfully")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated failed"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="old_password", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The old password field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="password", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The password field is required.")
     *                      )
     *                  ),
     *                  @OA\Property(property="password_confirmation", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The password confirmation field is required.")
     *                      )
     *                  ),
     *              )
     *          ),
     *     ),
     * )
     *
    */

    public function passwordUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:4',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-password-updated-failed'),
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->isMethod('patch'))
            if ($this->profileService->passwordUpdate($request)) {
                return response()->json([
                    'message' => __('api-custom.user-password-updated-successfully')
                ], 200);
            }

        return response()->json([
            'message' => __('api-custom.user-password-updated-failed')
        ], 422);
    }

    /**
     * @OA\Patch (
     *     path="/api/profile/avatar",
     *     tags={"Profile"},
     *     summary="Update user avatar",
     *     description="Update user avatar",
     *     operationId="avatarUpdate",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"avatar"},
     *              @OA\Property(property="avatar", type="string", format="binary"),
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated successfully")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User updated failed"),
     *              @OA\Property(property="errors", type="object",
     *                  @OA\Property(property="avatar", type="array",
     *                      @OA\Items(
     *                          @OA\Property(property="0", type="string", example="The avatar field is required.")
     *                      )
     *                  ),
     *              )
     *          ),
     *     ),
     * )
     *
    */

    public function avatarUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'string', new Base64Avatar()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-avatar-updated-failed'),
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->isMethod('patch'))
            if ($this->profileService->avatarUpdate($request)) {
                return response()->json([
                    'message' => __('api-custom.user-avatar-updated-successfully')
                ], 200);
            }

        return response()->json([
            'message' => __('api-custom.user-avatar-updated-failed')
        ], 422);
    }
}
