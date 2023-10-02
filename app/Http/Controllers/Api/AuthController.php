<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\Api\Repositories\PhoneVerificationRepositoryInterface;
use App\Interfaces\Api\Services\PhoneVerificationServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="Orzu Grand yetkazib beruvchilarning mobilniy ilovasi",
 *    version="1.0.0",
 * )
 */

class AuthController extends Controller
{
    const TYPE_ERROR = 1;
    const TYPE_SUCCESS = 2;
    const TYPE_REDIRECT = 3;
    public function __construct(
        protected PhoneVerificationServiceInterface $phoneVerificationService,
        protected PhoneVerificationRepositoryInterface $phoneVerificationRepository
    )
    {
    }
    /**
     * @OA\Post(
     * path="/register",
     * summary="Register",
     * description="name, email, password, password_confirmation",
     * operationId="authRegister",
     * tags={"Register"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enter your user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","phone_number","region_id","address","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", format="text", example="John Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="phone_number", type="string", format="text", example="+998901234567"),
     *       @OA\Property(property="region_id", type="int", format="text", example="1"),
     *       @OA\Property(property="address", type="string", format="text", example="Farg'ona viloyati, Yozyovon tumani, 1-uy"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *     response=201,
     *     description="User created successfully",
     *     @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="User created successfully"),
     *          @OA\Property(property="token", type="string", example="Bearer 1moT68VWn1EqapLgthTIQbNY4ENMboRYZVyMnpww"),
     *     ),
     * ),
     * @OA\Response(
     *    response=400,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:4',
            'phone_number' => 'required|string',
            'region_id' => 'required|integer',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-created-failed'),
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => User::STATUS_NO_VERIFIED,
            'verification_code' => rand(1000, 9999)
        ]);

        DB::beginTransaction();
        try {
            $user->save();
            $user->regions()->attach($request->region_id);
            $user->assignRole('delivery');
            $token = $user->createToken('Personal Access Token');
            $token->token->save();
            $this->phoneVerificationService->send($user->phone_number, __('api-custom.verification-code') . $user->verification_code);
            DB::commit();
            return response()->json([
                'message' => __('api-custom.user-created'),
                'token' => 'Bearer ' . $token->accessToken,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'message' => __('api-custom.user-created-failed'),
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Post(
     * path="/login",
     * summary="Login",
     * description="email, password",
     * operationId="authLogin",
     * tags={"Login"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Enter your user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *
     *
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="User login successfully",
     *     @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="User login successfully"),
     *          @OA\Property(property="token", type="string", example="Bearer 1moT68VWn1EqapLgthTIQbNY4ENMboRYZVyMnpww"),
     *          @OA\Property(property="type", type="int", example="2")
     *     ),
     * ),
     * @OA\Response(
     *    response=400,
     *    description="User login failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again"),
     *       @OA\Property(property="type", type="int", example="1")
     *        )
     *     ),
     * )
     */

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'notification_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-login-failed'),
                'errors' => $validator->errors(),
                'type' => self::TYPE_ERROR
            ], 400);
        }

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => __('api-custom.user-login-failed'),
                'errors' => __('api-custom.email-or-password-incorrect'),
                'type' => self::TYPE_ERROR
            ], 400);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();
            $token = $user->createToken('Personal Access Token');
            $token->token->save();
            if (isset($user->supplierNotificationToken->notification_token)) {
                $user->supplierNotificationToken->update([
                    'notification_token' => $request->notification_token
                ]);
            } else {
                $user->supplierNotificationToken()->create([
                    'notification_token' => $request->notification_token
                ]);
            }
            DB::commit();
            if ($user->status == User::STATUS_NO_VERIFIED) {
                $this->phoneVerificationService->send($user->phone_number, __('api-custom.verification-code') . $user->verification_code);
                return response()->json([
                    'message' => __('api-custom.phone-number-is-not-verified'),
                    'token' => 'Bearer ' . $token->accessToken,
                    'type' => self::TYPE_REDIRECT,
                ], 200);
            }
            return response()->json([
                'message' => __('api-custom.user-login-success'),
                'token' => 'Bearer ' . $token->accessToken,
                'type' => self::TYPE_SUCCESS
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('api-custom.user-login-failed'),
                'errors' => $e->getMessage(),
                'type' => self::TYPE_ERROR
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *      path="/verify-phone-number",
     *      summary="verifyPhoneNumber",
     *      description="verification_code",
     *      operationId="authVerifyPhoneNumber",
     *      tags={"Verify Phone Number"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Enter your user verification_code",
     *          @OA\JsonContent(
     *              required={"verification_code"},
     *              @OA\Property(property="verification_code", type="string", format="text", example="1999"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Code received",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Code received"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="User code failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Sorry, wrong code. Please try again")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="The entered code is incorrect",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The entered code is incorrect")
     *          )
     *      )
     * )
     */

    public function verifyPhoneNumber(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.user-code-failed'),
                'errors' => $validator->errors()
            ], 400);
        }

        if ($this->phoneVerificationRepository->getVerificationCode() != $request->verification_code) {
            return response()->json([
                'message' => __('api-custom.the-entered-code-is-incorrect'),
            ], 401);
        } else {
            $user = User::find(auth()->user()->id);
            $user->verification_code = null;
            $user->status = User::STATUS_INACTIVE;
            $user->save();
        }

        return response()->json([
            'message' => __('api-custom.code-received'),
        ], 200);
    }
}
