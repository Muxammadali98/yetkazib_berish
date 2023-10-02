<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements \App\Interfaces\Repositories\CategoryRepositoryInterface
{

    public function getCategories()
    {
        return Category::where('status', Category::STATUS_ACTIVE)
            ->where('step', Category::CATEGORY)
            ->with('children')
            ->paginate(Category::PAGE_SIZE);
    }

    public function getCategoryList()
    {
        return Category::select(['id', 'title_uz'])
            ->where('status', Category::STATUS_ACTIVE)
            ->where('parent_id', null)
            ->pluck('title_uz', 'id');
    }
}
