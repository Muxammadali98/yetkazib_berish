<?php

namespace App\Interfaces\Repositories;

interface AdditionalNoticeRepositoryInterface
{
    public function getAll();
    public static function getIds($notice_id);
    public function getOneWithTrashed($id);
}
