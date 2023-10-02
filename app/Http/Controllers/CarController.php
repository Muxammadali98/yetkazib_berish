<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Interfaces\Repositories\CarRepositoryInterface;
use App\Interfaces\Repositories\RegionRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\CarServiceInterface;
use App\Models\Car;
use http\Env\Request;

class CarController extends Controller
{
    public function __construct(
        protected CarRepositoryInterface $carRepository,
        protected CarServiceInterface $carService,
        protected RegionRepositoryInterface $regionRepository,
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
        return view('car.index', [
            'cars' => $this->carRepository->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('car.create', [
            'regionList' => $this->regionRepository->getAllRegionsForSelect(),
            'userList' => $this->userRepository->getSuppliersForSelect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCarRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($this->carService->create($request)) {
            return redirect()->route('car.index')->with('success', 'Car created successfully');
        }
        return redirect()->back()->with('error', 'Car created failed');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Car $car)
    {
        return view('car.update', [
            'model' => $car,
            'regionList' => $this->regionRepository->getAllRegionsForSelect(),
            'userList' => $this->userRepository->getSuppliersForSelect(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        if ($this->carService->update($request, $car)) {
            return redirect()->route('car.index')->with('success', 'Car updated successfully');
        }
        return redirect()->back()->with('error', 'Car updated failed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('car.index')->with('success', 'Car deleted successfully');
    }

    public function hybridSleep($id): \Illuminate\Http\RedirectResponse
    {
        $this->carService->hybridSleep($id);
        return redirect()->back();
    }
}
