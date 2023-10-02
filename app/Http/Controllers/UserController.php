<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\Repositories\RegionRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\UserServiceInterface;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService,
        protected UserRepositoryInterface $userRepository,
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
        return view('users.index', [
            'users' => $this->userRepository->getAllExceptAdmin()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('users.create', [
            'roles' => $this->userRepository->getAllRoleExceptAdmin(),
            'regions' => $this->regionRepository->getAllRegionsForSelect()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        if ($this->userService->create($request)) {
            return redirect()->route('user.index');
        }
        return redirect()->back()->WithErrors($request->validator)->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        return view('users.update', [
            'model' => $user,
            'roles' => $this->userRepository->getAllRoleExceptAdmin(),
            'user_roles' => $this->userRepository->getUserRole($user),
            'regions' => $this->regionRepository->getAllRegionsForSelect(),
            'regionsId' => $this->userRepository->getRegionsId($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($this->userService->update($request, $user)) {
            return redirect()->route('user.index', [
                'regions' => $this->regionRepository->getAllRegionsForSelect(),
                'regionsId' => $this->userRepository->getRegionsId($user)
            ]);
        }
        return redirect()->back()->WithErrors($request->validator)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->userService->editStatus($user);
        return redirect()->back();
    }
}
