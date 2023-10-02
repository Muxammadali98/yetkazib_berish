<?php

namespace App\Services;

use App\Http\Controllers\Api\SupplierNotificationController;
use App\Interfaces\Repositories\RegionRepositoryInterface;
use App\Models\AdditionalNotice;
use App\Models\AdditionalNoticeUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdditionalNoticeService implements \App\Interfaces\Services\AdditionalNoticeServiceInterface
{
    public function __construct(
        protected RegionRepositoryInterface $regionRepository
    )
    {
    }

    public function create($request)
    {
        $data = $this->getData($request);
        if (!is_null($data['image'])) $data['type'] = AdditionalNotice::TYPE_WITH_IMAGE;
        $regions = $this->regionRepository->getUserIds($request->regions);
        $additional_notice_user_data = $this->getAdditionalNoticeUserData($regions);

        DB::beginTransaction();
        try {
            $model = AdditionalNotice::create($data);
            $model->users()->attach($additional_notice_user_data);
            DB::commit();
            return $model;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function update($request, $model): bool
    {
        $old_image_name = $model->image;
        $data = $this->getData($request);
        if (!is_null($data['image'])) {
            $data['type'] = AdditionalNotice::TYPE_WITH_IMAGE;
            if (!is_null($old_image_name) && file_exists(public_path('uploads/images/' . $old_image_name)))
                unlink(public_path('uploads/images/' . $old_image_name));
        } else
            $data['type'] = AdditionalNotice::TYPE_TEXT;
        $regions = $this->regionRepository->getUserIds($request->regions);
        $additional_notice_user_data = $this->getAdditionalNoticeUserData($regions);

        DB::beginTransaction();
        try {
            $model->update($data);
            $model->users()->sync($additional_notice_user_data);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function sendNotice($model)
    {
        if (!empty($model->user)) {
            foreach ($model->user as $user) {
                (new SupplierNotificationController(
                    $model->title_uz,
                    $model->message_uz,
                    $user->supplierNotificationToken->notification_token,
                    $model->getNotificationDataId())
                )->send();
            }
        }
    }

    protected function getData($request)
    {
        $data = $request->except('image', 'regions');
        $data['image'] = $this->upload($request);
        return $data;
    }

    protected function upload($request): null|string
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('Ymdhis') . '-' . $image->getClientOriginalName();
            if ($image->move('uploads/images/', $imageName)) {
                return $imageName;
            }
        }
        return null;
    }

    protected function getAdditionalNoticeUserData($regions): array
    {
        $data = [];
        if (!empty($regions)) {
            foreach ($regions as $region) {
                if (!empty($region->users)) {
                    foreach ($region->users as $user) {
                        $data[] = [
                            'user_id' => $user->id,
                            'region_id' => $region->id,
                        ];
                    }
                }
            }
        }
        return $data;
    }
}
