<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\SupplierNotificationController;
use App\Http\Requests\StoreAdditionalNoticeRequest;
use App\Http\Requests\UpdateAdditionalNoticeRequest;
use App\Interfaces\Repositories\AdditionalNoticeRepositoryInterface;
use App\Interfaces\Repositories\RegionRepositoryInterface;
use App\Interfaces\Services\AdditionalNoticeServiceInterface;
use App\Models\AdditionalNotice;
use App\Repositories\AdditionalNoticeRepository;
use Illuminate\Support\Facades\Request;

class AdditionalNoticeController extends Controller
{
    public function __construct(
        protected AdditionalNoticeRepositoryInterface $additionalNoticeRepository,
        protected AdditionalNoticeServiceInterface $additionalNoticeService,
        protected RegionRepositoryInterface $regionRepository
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
        return view('additional-notices.index', [
            'messages' => $this->additionalNoticeRepository->getAll()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('additional-notices.create', [
            'regions' => $this->regionRepository->getAllRegionsForSelect()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdditionalNoticeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAdditionalNoticeRequest $request)
    {
        $model = $this->additionalNoticeService->create($request);
        if ($model) {
            $this->additionalNoticeService->sendNotice($model);
            return redirect()->route('additional-notice.index');
        }

        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        return view('additional-notices.show', [
            'model' => $this->additionalNoticeRepository->getOneWithTrashed($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdditionalNotice  $additionalNotice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(AdditionalNotice $additionalNotice)
    {
        return view('additional-notices.update', [
            'regions' => $this->regionRepository->getAllRegionsForSelect(),
            'model' => $additionalNotice
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdditionalNoticeRequest  $request
     * @param  \App\Models\AdditionalNotice  $additionalNotice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAdditionalNoticeRequest $request, AdditionalNotice $additionalNotice)
    {
        if ($this->additionalNoticeService->update($request, $additionalNotice)) {
            return redirect()->route('additional-notice.index');
        }

        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdditionalNotice  $additionalNotice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AdditionalNotice $additionalNotice)
    {
        $additionalNotice->trashed() ? $additionalNotice->restore() : $additionalNotice->delete();
        return redirect()->back();
    }
}
