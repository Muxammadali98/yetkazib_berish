<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DoneTicketListResource;
use App\Http\Resources\Api\InProgressTicketListResource;
use App\Http\Resources\Api\OpenTicketListResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function __construct(
        protected \App\Interfaces\Api\Services\TicketServiceInterface $ticketService,
        protected \App\Interfaces\Api\Repositories\TicketRepositoryInterface $ticketRepository
    )
    {
    }

    /**
     * @OA\Get(
     *      path="/api/tickets",
     *      summary="Ticket list",
     *      description="All open ticket list",
     *      operationId="ticketList",
     *      tags={"Open ticket list"},
     *      @OA\Parameter(
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
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All open ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
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
     *                         @OA\Items(
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
     * )
     */

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return OpenTicketListResource::collection($this->ticketRepository->getTickets(Ticket::STATUS_OPEN, Ticket::TYPE_NEW));
    }

    /**
     * @OA\Patch(
     *     path="/api/tickets//progress/{id}",
     *     summary="Set ticket progress",
     *     description="Set ticket progress",
     *     operationId="setTicketProgress",
     *     tags={"Set ticket progress"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Ticket id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          ),
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Ticket status not changed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ticket status not changed"),
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
     *          description="Ticket status changed to progress",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ticket status changed to progress"),
     *          ),
     *     ),
     * )
    */

    public function setProgress(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('patch')) {
            if (!$this->ticketService->setProgress($request->id)) {
                return response()->json(['message' => __('api-custom.ticket-status-not-changed')], 400);
            }
            return response()->json(['message' => __('api-custom.ticket-status-changed-to-progress')]);
        }
        return response()->json(['message' => __('api-custom.method-not-allowed')], 405);
    }

    /**
     * @OA\Get(
     *      path="/api/tickets/in-progress",
     *      summary="Ticket list",
     *      description="All progress ticket list",
     *      operationId="inProgressTicketList",
     *      tags={"In progress ticket list"},
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
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All progress ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
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
     *                         @OA\Items(
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
     * )
     */

    public function progress(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return InProgressTicketListResource::collection($this->ticketRepository->getTickets(Ticket::STATUS_IN_PROGRESS, Ticket::TYPE_NEW));
    }

    /**
     * @OA\Post(
     *      path="/api/tickets/done",
     *      summary="Ticket done",
     *      description="Ticket done",
     *      operationId="ticketDone",
     *      tags={"Ticket done"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"id", "lat", "lng", "files"},
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="comment", type="string"),
     *              @OA\Property(property="lat", type="number"),
     *              @OA\Property(property="lng", type="number"),
     *              @OA\Property(property="files", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="file", type="string"),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Ticket status not changed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ticket status not changed"),
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
     *          description="Ticket status changed to closed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ticket status changed to closed"),
     *          ),
     *     ),
     * )
     */

    public function done(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'comment' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'files' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('api-custom.validation-failed'),
                'errors' => $validator->errors()
            ], 400);
        }

        if ($request->isMethod('post')) {
            if (!$this->ticketService->done($request->all())) {
                return response()->json(['message' => __('api-custom.ticket-status-not-changed')], 400);
            }
            return response()->json(['message' => __('api-custom.ticket-status-changed-to-closed')]);
        }
        return response()->json(['message' => __('api-custom.method-not-allowed')], 405);
    }

    /**
     * @OA\Get(
     *      path="/api/tickets/done",
     *      summary="Done ticket list",
     *      description="Done ticket list",
     *      operationId="doneTicketList",
     *      tags={"Done ticket list"},
     *      @OA\Parameter(
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
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="From date",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default=null
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="To date",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default=null
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All done ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="car",type="object",
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="name",type="string"),
     *                          @OA\Property(property="color",type="string"),
     *                          @OA\Property(property="number",type="string"),
     *                      ),
     *                      @OA\Property(property="supplier_action", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="lat", type="string"),
     *                              @OA\Property(property="lng", type="string"),
     *                              @OA\Property(property="comment", type="string"),
     *                              @OA\Property(property="supplier_files", type="array",
     *                                  @OA\Items(
     *                                      @OA\Property(property="id", type="integer"),
     *                                      @OA\Property(property="file", type="string"),
     *                                  ),
     *                              ),
     *                          ),
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
     */

    public function doneList(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DoneTicketListResource::collection(
            $this->ticketRepository->getDoneTickets(
                type: Ticket::TYPE_NEW,
                from: $request->get('from'),
                to: $request->get('to')
            )
        );
    }

    /**
     * @OA\Get(
     *      path="/api/tickets/return",
     *      summary="Return ticket list",
     *      description="Return ticket list",
     *      operationId="returnTicketList",
     *      tags={"Return ticket list"},
     *      @OA\Parameter(
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
     *          description="All return ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
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
     */

    public function returnList(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return OpenTicketListResource::collection($this->ticketRepository->getTickets(Ticket::STATUS_OPEN, Ticket::TYPE_RETURN));
    }

    /**
     * @OA\Get(
     *      path="/api/tickets/in-progress-by-return",
     *      summary="Return Ticket list",
     *      description="All progress ticket list",
     *      operationId="inProgressTicketListByReturn",
     *      tags={"In progress ticket list by return"},
     *      @OA\Parameter(
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
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthenticated."),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All progress ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
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
     *                         @OA\Items(
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
     * )
     */

    public function progressByReturn(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return InProgressTicketListResource::collection($this->ticketRepository->getTickets(Ticket::STATUS_IN_PROGRESS, Ticket::TYPE_RETURN));
    }

    /**
     * @OA\Post(
     *      path="/api/tickets/done-by-return",
     *      summary="Ticket done by return",
     *      description="Ticket done by return",
     *      operationId="ticketDoneByReturn",
     *      tags={"Ticket done by return"},
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
     *              @OA\Property(property="message", type="string", example="Ticket status not changed"),
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
     *          description="Ticket status changed to closed",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ticket status changed to closed"),
     *          ),
     *     ),
     * )
     */

    public function doneByReturn(Request $request): \Illuminate\Http\JsonResponse
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

        if ($request->isMethod('post')) {
            if (!$this->ticketService->doneByReturn($request->all())) {
                return response()->json(['message' => __('api-custom.ticket-status-not-changed')], 400);
            }
            return response()->json(['message' => __('api-custom.ticket-status-changed-to-closed')]);
        }
        return response()->json(['message' => __('api-custom.method-not-allowed')], 405);
    }

    /**
     * @OA\Get(
     *      path="/api/tickets/done-by-return",
     *      summary="Done ticket list by return",
     *      description="Done ticket list by return",
     *      operationId="doneTicketListByReturn",
     *      tags={"Done ticket list by return"},
     *      @OA\Parameter(
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
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          description="From date",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default=null
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="to",
     *          in="query",
     *          description="To date",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="date",
     *              default=null
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="All done ticket list",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="contract_id", type="integer"),
     *                      @OA\Property(property="client_name", type="string"),
     *                      @OA\Property(property="address", type="string"),
     *                      @OA\Property(property="phone", type="string"),
     *                      @OA\Property(property="additional_phone", type="string"),
     *                      @OA\Property(property="comment", type="string"),
     *                      @OA\Property(property="created_at", type="string"),
     *                      @OA\Property(property="products", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="product_name", type="string"),
     *                              @OA\Property(property="article", type="string"),
     *                              @OA\Property(property="model", type="string"),
     *                              @OA\Property(property="quantity", type="integer"),
     *                          ),
     *                      ),
     *                      @OA\Property(property="car",type="object",
     *                          @OA\Property(property="id",type="integer"),
     *                          @OA\Property(property="name",type="string"),
     *                          @OA\Property(property="color",type="string"),
     *                          @OA\Property(property="number",type="string"),
     *                      ),
     *                      @OA\Property(property="supplier_action", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="lat", type="string"),
     *                              @OA\Property(property="lng", type="string"),
     *                              @OA\Property(property="comment", type="string"),
     *                              @OA\Property(property="supplier_files", type="array",
     *                                  @OA\Items(),
     *                              ),
     *                          ),
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
     */

    public function doneListByReturn(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DoneTicketListResource::collection(
            $this->ticketRepository->getDoneTickets(
                type: Ticket::TYPE_RETURN,
                from: $request->get('from'),
                to: $request->get('to')
            )
        );
    }
}
