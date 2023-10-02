<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SupplierNotificationController;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Interfaces\Repositories\CarRepositoryInterface;
use App\Interfaces\Repositories\TicketRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\TicketServiceInterface;
use App\Models\Ticket;

class TicketController extends Controller
{

    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected TicketServiceInterface $ticketService,
        protected CarRepositoryInterface $carRepository,
        protected UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('ticket.index', [
            'tickets' => $this->ticketRepository->getOpenTickets(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('ticket.create', [
            'delivery' => $this->ticketRepository->getDelivery(),
            'cars' => [],
        ]);
    }

    public function getCarByUser($user_id)
    {
        if (request()->ajax()) {
            $view = view('ticket.cars', [
                'cars' => $this->carRepository->getCarByUser($user_id),
            ])->render();
            return [
                'content' => $view
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->createTicket($request->validated());
        if ($ticket) {
            if (isset($ticket->user->supplierNotificationToken->notification_token))
                (new SupplierNotificationController(
                    $ticket->getNotificationTitle(),
                    $ticket->address,
                    $ticket->user->supplierNotificationToken->notification_token,
                    $ticket->getNotificationDataId())
                )->send();
            return redirect()->route('ticket.index')->with('success', 'Muvaffaqiyatli yaratildi');
        }
        return redirect()->back()->withErrors($request->validated())->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $model = $this->ticketRepository->getTicket($id);
        return view('ticket.show', [
            'model' => is_null($model) ? abort(404) : $model,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.update', [
            'model' => $ticket,
            'delivery' => $this->ticketRepository->getDelivery(),
            'cars' => $this->carRepository->getCarByUser($ticket->user_id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketRequest  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        if ($this->ticketService->updateTicket($request->validated(), $ticket)) {
            return redirect()->route('ticket.index')->with('success', 'Muvaffaqiyatli yangilandi');
        }
        return redirect()->back()->withErrors($request->validated())->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->trashed() ? $ticket->restore() : $ticket->delete();
        return redirect()->back();
    }

    public function addProduct(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $count = $request['item'];
        $html = view('ticket.create-button',['i' => $count])->render();
        return response()->json([
            'status' => 'success',
            'content' => $html
        ]);
    }

    public function trashIndex()
    {
        return view('ticket.trash', [
            'tickets' => $this->ticketRepository->getTrashTickets(),
        ]);
    }

    public function acceptedIndex()
    {
        return view('ticket.accepted', [
            'tickets' => $this->ticketRepository->getAcceptedTickets(),
        ]);
    }

    public function closedIndex()
    {
        return view('ticket.closed', [
            'tickets' => $this->ticketRepository->getClosedTickets(),
        ]);
    }

    public function getActiveList()
    {
        return view('ticket.active-list', [
            'orders' => $this->userRepository->getActiveOrderList(),
        ]);
    }
}
