<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Interfaces\Repositories\RegionRepositoryInterface;
use App\Interfaces\Services\RegionServiceInterface;
use App\Models\Region;

class RegionController extends Controller
{
    protected $regionService;
    protected $regionRepository;

    public function __construct(RegionServiceInterface $regionService, RegionRepositoryInterface $regionRepository)
    {
        $this->regionService = $regionService;
        $this->regionRepository = $regionRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('regions.index', [
            'regions' => $this->regionRepository->getRegions()
        ]);
    }

    public function show(Region $region)
    {
        return view('regions.show', [
            'model' => $region
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegionRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRegionRequest $request)
    {
        if ($this->regionService->createRegion($request)) {
            return redirect()->route('region.index');
        }
        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Region $region)
    {
        return view('regions.update', [
            'model' => $region
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegionRequest  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        if ($this->regionService->updateRegion($request, $region)) {
            return redirect()->route('region.index');
        }
        return redirect()->back()->withErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Region $region)
    {
        $this->regionService->editStatus($region);
        return redirect()->back();
    }
}
