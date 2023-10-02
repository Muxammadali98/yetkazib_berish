<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SupplierAssignmentResource;
use App\Models\SupplierAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierAssignmentController extends Controller
{
    public function __construct(
        protected \App\Interfaces\Api\Services\SupplierAssignmentServiceInterface $supplierAssignmentService,
        protected \App\Interfaces\Api\Repositories\SupplierAssignmentRepositoryInterface $supplierAssignmentRepository
    )
    {
    }

    /**
     * @OA\Get (
     *     path="/api/tacks",
     *     tags={"Tacks"},
     *     summary="Get all pending tacks",
     *     description="Get all pending tacks",
     *     operationId="pendingIndex",
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32",
     *              default=1
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data",type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id",type="integer"),
     *                      @OA\Property(property="title",type="string"),
     *                      @OA\Property(property="description",type="string"),
     *                      @OA\Property(property="phone",type="string"),
     *                      @OA\Property(property="additional_phone",type="string"),
     *                      @OA\Property(property="address",type="string"),
     *                      @OA\Property(property="created_at",type="string"),
     *                      @OA\Property(property="car",type="object",
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="name",type="string"),
     *                          @OA\Property(property="color",type="string"),
     *                          @OA\Property(property="number",type="string"),
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(property="links", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="first", type="string"),
     *                      @OA\Property(property="last", type="string"),
     *                      @OA\Property(property="prev", type="string"),
     *                      @OA\Property(property="next", type="string"),
     *                  ),
     *              ),
     *              @OA\Property(property="meta", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="current_page", type="integer"),
     *                      @OA\Property(property="from", type="integer"),
     *                      @OA\Property(property="last_page", type="integer"),
     *                      @OA\Property(property="links", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="url", type="string"),
     *                              @OA\Property(property="label", type="string"),
     *                              @OA\Property(property="active", type="boolean"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="path", type="string"),
     *                      @OA\Property(property="per_page", type="integer"),
     *                      @OA\Property(property="to", type="integer"),
     *                      @OA\Property(property="total", type="integer"),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=405,
     *          description="Method not allowed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Method not allowed"),
     *          ),
     *     ),
     * )
     *
    */

    public function pendingIndex(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SupplierAssignmentResource::collection($this->supplierAssignmentRepository->getTacks(SupplierAssignment::STATUS_PENDING));
    }

    /**
     * @OA\Patch (
     *     path="/api/tacks/accept",
     *     tags={"Accept Tack"},
     *     summary="Accept tack",
     *     description="Accept tack",
     *     operationId="acceptTack",
     *     @OA\Parameter(
     *          name="id",
     *          in="query",
     *          description="Tack id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32",
     *              default=1
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="Content-Type",
     *          in="header",
     *          description="application/json",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              default="application/json",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tack status changed to approved"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tack status not changed"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=405,
     *          description="Method not allowed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Method not allowed"),
     *          ),
     *     ),
     * )
    */

    public function approve(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('patch')) {
            if (!$this->supplierAssignmentService->approve($request->id)) {
                return response()->json(['message' => __('api-custom.tack-status-not-changed')], 400);
            }
            return response()->json(['message' => __('api-custom.tack-status-changed-to-approved')]);
        }
        return response()->json(['message' => __('api-custom.method-not-allowed')], 405);
    }

    /**
     * @OA\Get (
     *     path="/api/tacks/accepted",
     *     tags={"Accepted Tacks"},
     *     summary="Get all accepted tacks",
     *     description="Get all accepted tacks",
     *     operationId="acceptedIndex",
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Page number",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32",
     *              default=1
     *          ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data",type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id",type="integer"),
     *                      @OA\Property(property="title",type="string"),
     *                      @OA\Property(property="description",type="string"),
     *                      @OA\Property(property="phone",type="string"),
     *                      @OA\Property(property="additional_phone",type="string"),
     *                      @OA\Property(property="address",type="string"),
     *                      @OA\Property(property="created_at",type="string"),
     *                      @OA\Property(property="car",type="object",
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="name",type="string"),
     *                          @OA\Property(property="color",type="string"),
     *                          @OA\Property(property="number",type="string"),
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(property="links", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="first", type="string"),
     *                      @OA\Property(property="last", type="string"),
     *                      @OA\Property(property="prev", type="string"),
     *                      @OA\Property(property="next", type="string"),
     *                  ),
     *              ),
     *              @OA\Property(property="meta", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="current_page", type="integer"),
     *                      @OA\Property(property="from", type="integer"),
     *                      @OA\Property(property="last_page", type="integer"),
     *                      @OA\Property(property="links", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="url", type="string"),
     *                              @OA\Property(property="label", type="string"),
     *                              @OA\Property(property="active", type="boolean"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="path", type="string"),
     *                      @OA\Property(property="per_page", type="integer"),
     *                      @OA\Property(property="to", type="integer"),
     *                      @OA\Property(property="total", type="integer"),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=405,
     *          description="Method not allowed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Method not allowed"),
     *          ),
     *     ),
     * )
     *
     */

    public function ApprovedIndex(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return SupplierAssignmentResource::collection($this->supplierAssignmentRepository->getTacks(SupplierAssignment::STATUS_APPROVED));
    }

    /**
     * @OA\Patch (
     *      path="/api/tacks/done",
     *      summary="Task done",
     *      description="Task done",
     *      operationId="tackDone",
     *      tags={"TackDone"},
     *      @OA\Parameter (
     *          name="id",
     *          in="query",
     *          description="Ticket id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32",
     *              default=1
     *          ),
     *      ),
     *      @OA\Parameter (
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          ),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"id"},
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="comment", type="string"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Ticket status not changed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tack status not changed"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=405,
     *          description="Method not allowed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Method not allowed"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Tack status changed to closed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Tack status changed to closed"),
     *          ),
     *     ),
     * )
     */

    public function done(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.validation-failed'),
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->isMethod('patch')) {
            if (!$this->supplierAssignmentService->done($request->all())) {
                return response()->json(['message' => __('api-custom.ticket-status-not-changed')], 400);
            }
            return response()->json(['message' => __('api-custom.ticket-status-changed-to-closed')]);
        }
        return response()->json(['message' => __('api-custom.method-not-allowed')], 405);
    }

    /**
     * @OA\Get (
     *     path="/api/tacks/done",
     *     summary="Get done tacks",
     *     description="Get done tacks",
     *     operationId="getDoneTacks",
     *     tags={"Tack Done List"},
     *     @OA\Parameter (
     *          name="from",
     *          in="query",
     *          description="From date",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default="2021-01-01"
     *          ),
     *     ),
     *     @OA\Parameter (
     *          name="to",
     *          in="query",
     *          description="To date",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default="2021-01-01"
     *          ),
     *     ),
     *     @OA\Parameter (
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *          ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Get done tacks",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="title", type="string"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="phone",type="string"),
     *                      @OA\Property(property="additional_phone",type="string"),
     *                      @OA\Property(property="address",type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="car",type="object",
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="name",type="string"),
     *                          @OA\Property(property="color",type="string"),
     *                          @OA\Property(property="number",type="string"),
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(property="links", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="first", type="string"),
     *                      @OA\Property(property="last", type="string"),
     *                      @OA\Property(property="prev", type="string"),
     *                      @OA\Property(property="next", type="string"),
     *                  ),
     *              ),
     *              @OA\Property(property="meta", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="current_page", type="integer"),
     *                      @OA\Property(property="from", type="integer"),
     *                      @OA\Property(property="last_page", type="integer"),
     *                      @OA\Property(property="path", type="string"),
     *                      @OA\Property(property="per_page", type="integer"),
     *                      @OA\Property(property="to", type="integer"),
     *                      @OA\Property(property="total", type="integer"),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Validation failed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated"),
     *          ),
     *     ),
     * )
    */

    public function doneList(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        if ($request->has('from') && $request->has('to')) {
            return SupplierAssignmentResource::collection($this->supplierAssignmentRepository->getDoneTacks(
                from: $request->get('from'),
                to: $request->get('to')
            ));
        }
        return response()->json(['message' => __('api-custom.validation-failed')], 400);
    }
}
