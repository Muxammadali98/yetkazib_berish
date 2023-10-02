<?php

namespace App\Services;

use App\Models\Category;

class CategoryService implements \App\Interfaces\Services\CategoryServiceInterface
{
    public function createCategory($request)
    {
        $category = $request->except('file');
        $category['file'] = $this->uploadPDF($request);
        $category['step'] = $this->setStep($request);
        Category::create($category);
    }

    public function uploadPDF($request): null|string
    {
        $file_name = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/documents/', $file_name);
        }
        return $file_name;
    }

    public function setStep($request): int
    {
        return isset($request->parent_id) ? Category::SUB_CATEGORY : Category::CATEGORY;
    }

    public function updateCategory($request, $model)
    {
        $category = $request->except('file');
        if ($request->hasFile('file')) {
            $category['file'] = $this->uploadPDF($request);
            $this->unlinkPDF($model);
        }
        $category['step'] = $this->setStep($request);
        $model->update($category);
    }

    public function unlinkPDF($model)
    {
        if ($model->file && file_exists(public_path('uploads/documents/' . $model->file))) {
            unlink('uploads/documents/' . $model->file);
        }
    }

    public function softDeleteOrRestoreCategory($model)
    {
        $model->status = $model->status == Category::STATUS_ACTIVE ? Category::STATUS_INACTIVE : Category::STATUS_ACTIVE;
        $model->save();
    }
}
