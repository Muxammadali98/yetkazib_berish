<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileService implements \App\Interfaces\Api\Services\ProfileServiceInterface
{
    public function profileUpdate($request): bool
    {
        $user = auth()->user();
        $user->fill([
            'name' => $request->name,
            'phone_number' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);
        DB::beginTransaction();
        try {
            $user->save();
            $user->regions()->sync($request->region_id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function passwordUpdate(\Illuminate\Http\Request $request): bool
    {
        $user = auth()->user();
        if (password_verify($request->old_password, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
            return true;
        }
        return false;
    }

    public function avatarUpdate(\Illuminate\Http\Request $request): bool
    {
        $user = auth()->user();
        if (isset($user->photo) && file_exists(public_path('uploads/images/' . $user->photo))) {
            unlink(public_path('uploads/images/' . $user->photo));
        }
        $user->photo = $this->uploadFile($request->all());
        return $user->save();
    }

    public function uploadFile(array $request): string
    {
        $file = $request['avatar'];
        $type = explode(';', $file)[0];
        $img = preg_replace('/^data:image\/\w+;base64,/', '', $file);
        $type = explode('/', $type)[1];
        $file_name = time() . '.' . $type;
        $file = base64_decode($img);
        file_put_contents(public_path('uploads/images/' . $file_name), $file);
        return $file_name;
    }
}
