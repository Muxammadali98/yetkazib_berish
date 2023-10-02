<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SupplierNotificationController;
use App\Http\Requests\StoreSupplierAssignmentRequest;
use App\Http\Requests\UpdateSupplierAssignmentRequest;
use App\Interfaces\Repositories\CarRepositoryInterface;
use App\Models\SupplierAssignment;
use http\Env\Request;
use function Composer\Autoload\includeFile;

class SupplierAssignmentController extends Controller
{
    public function __construct(
        protected \App\Interfaces\Repositories\SupplierAssignmentRepositoryInterface $supplierAssignmentRepository,
        protected \App\Interfaces\Services\SupplierAssignmentServiceInterface $supplierAssignmentService,
        protected CarRepositoryInterface $carRepository
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
        return view('supplier-assignments.index', [
            'supplier_assignments' => $this->supplierAssignmentRepository->getSupplierAssignments(SupplierAssignment::STATUS_PENDING)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('supplier-assignments.create', [
            'delivery' => $this->supplierAssignmentRepository->getDelivery(),
            'cars' => []
        ]);
    }

    public function getCarByUser($user_id)
    {
        if (request()->ajax()) {
            $view = view('supplier-assignments.cars', [
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
     * @param  \App\Http\Requests\StoreSupplierAssignmentRequest  $request
     */
    public function store(StoreSupplierAssignmentRequest $request)
    {
        $tack = $this->supplierAssignmentService->store($request->all());
        if ($tack) {
            if (isset($tack->user->supplierNotificationToken->notification_token))
                (new SupplierNotificationController(
                    $tack->title,
                    $tack->description,
                    $tack->user->supplierNotificationToken->notification_token,
                    $tack->getNotificationDataId())
                )->send();
            return redirect()->route('supplier-assignment.index');
        }

        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierAssignment  $supplierAssignment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('supplier-assignments.show', [
            'model' => $this->supplierAssignmentRepository->getSupplierAssignment($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierAssignment  $supplierAssignment
     */
    public function edit(SupplierAssignment $supplierAssignment)
    {
        if (!$supplierAssignment->isDone() && !$supplierAssignment->isRejected())
            return view('supplier-assignments.update', [
                'model' => $supplierAssignment,
                'delivery' => $this->supplierAssignmentRepository->getDelivery(),
                'cars' => $this->carRepository->getCarByUser($supplierAssignment->user_id)
            ]);
        return redirect()->back()->with('warning', 'Bu topshiriq yakunlangan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierAssignmentRequest  $request
     * @param  \App\Models\SupplierAssignment  $supplierAssignment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSupplierAssignmentRequest $request, SupplierAssignment $supplierAssignment)
    {
        if ($this->supplierAssignmentService->update($request->all(), $supplierAssignment)) {
            return redirect()->route('supplier-assignment.index');
        }

        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierAssignment  $supplierAssignment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SupplierAssignment $supplierAssignment)
    {
        $supplierAssignment->trashed() ? $this->supplierAssignmentService->restore($supplierAssignment) : $this->supplierAssignmentService->destroy($supplierAssignment);
        return redirect()->back();
    }

    public function trashed()
    {
        return view('supplier-assignments.trash', [
            'supplier_assignments' => $this->supplierAssignmentRepository->getTrash()
        ]);
    }

    public function closedIndex()
    {
        return view('supplier-assignments.closed', [
            'supplier_assignments' => $this->supplierAssignmentRepository->getSupplierAssignments(SupplierAssignment::STATUS_DONE)
        ]);
    }
}
