<?php

namespace App\Services;

use App\Models\Start;

class StartService implements \App\Interfaces\Services\StartServiceInterface
{

    public function createStartMessage($request)
    {
        $imageName = $this->upload($request);
        if ($imageName) $type = Start::TYPE_PHOTO; else $type = Start::TYPE_TEXT;

        return Start::create([
            'message_uz' => $this->stripTags($request['message_uz']),
            'message_ru' => $this->stripTags($request['message_ru']),
            'image' => $imageName,
            'type' => $type
        ]);
    }

    public function upload($request): null|string
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = date('Ymdhis') . '-' . $image->getClientOriginalName();
            if ($image->move('uploads/images/', $imageName)) {
                return $imageName;
            }
        }
        return null;
    }

    public function updateStartMessage($request, $model): bool
    {
        $imageName = null;
        if (!isset($request['img-check'])) {
            $imageName = $this->updateUpload($request, $model->id);
            if ($imageName) $type = Start::TYPE_PHOTO; else $type = Start::TYPE_TEXT;
        }
        return $model->update([
            'message_uz' => $this->stripTags($request['message_uz']),
            'message_ru' => $this->stripTags($request['message_ru']),
            'image' => !is_null($imageName) ? $imageName : $model->image,
            'type' => $type ?? $model->type
        ]);
    }

    public function updateUpload($request, $id): null|string
    {
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = date('Ymdhis') . '-' . $image->getClientOriginalName();
            $model = Start::findOrFail($id);
            $oldImageName = $model->image;
            if ($image->move('uploads/images/', $imageName)) {
                if (isset($oldImageName) && file_exists('uploads/images/' . $oldImageName)) {
                    unlink('uploads/images/' . $oldImageName);
                }
                return $imageName;
            }
        }
        return null;
    }

    public function inActiveStatus()
    {
        Start::where('status', Start::STATUS_ACTIVE)->update(['status' => Start::STATUS_INACTIVE]);
    }

    public function editStatus($id)
    {
        $model = Start::findOrFail($id);
        $this->inActiveStatus();
        $status = $model->status == Start::STATUS_ACTIVE ? Start::STATUS_INACTIVE : Start::STATUS_ACTIVE;
        $model->update(['status' => $status]);
    }

    public function stripTags($text): string
    {
        $text = $this->strReplace($text);
        return strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><tg-spoiler><a><code><pre>');
    }

    public function strReplace($text): string
    {
        return str_replace(['&nbsp;', '<br />'], [' ', PHP_EOL], $text);
    }
}
