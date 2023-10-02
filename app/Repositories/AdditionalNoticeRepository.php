<?php

namespace App\Repositories;

use App\Models\AdditionalNotice;
use App\Models\AdditionalNoticeUser;
use App\Models\Region;

class AdditionalNoticeRepository implements \App\Interfaces\Repositories\AdditionalNoticeRepositoryInterface
{
    public function getAll()
    {
        return AdditionalNotice::withTrashed()->paginate(AdditionalNotice::PAGE_SIZE);
    }

    public static function getIds($notice_id): array
    {
        $region_ids = AdditionalNoticeUser::where('additional_notice_id', $notice_id)->get()->pluck('region_id')->toArray();
        return array_unique($region_ids);
    }
    public static function getRegions($notice_id): array
    {
        return Region::whereIn('id', static::getIds($notice_id))->get()->pluck('name_uz')->toArray();
    }

    public function getOneWithTrashed($id)
    {
        return AdditionalNotice::withTrashed()->findOrFail($id);
    }
}
