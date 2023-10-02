<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService implements \App\Interfaces\Services\UserServiceInterface
{
    public function create($request): bool
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole($request->roles);
            $user->regions()->attach($request->region_id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function update($request, $model): bool
    {
        DB::beginTransaction();
        try {
            $model->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
            $model->syncRoles($request->roles);
            $model->regions()->sync($request->region_id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function editStatus($model)
    {
        $model->update([
            'status' => !$model->status,
        ]);
    }
}
