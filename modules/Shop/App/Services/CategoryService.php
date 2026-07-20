<?php

namespace Modules\Shop\App\Services;

use Modules\Shop\App\Models\ShopAttribute;
use Modules\Shop\App\Models\ShopCategory;
use Modules\Shop\App\Models\ShopProduct;
use Modules\Shop\App\Requests\Category\StoreCategoryRequest;
use Modules\Shop\App\Requests\Category\UpdateCategoryRequest;
use Modules\Shop\App\Resources\ShopCategoryResource;

class CategoryService
{
    public function attributesForSelect(): array
    {
        $attributes = ShopAttribute::query()->get();
        $attributesForSelect = [];
        foreach ($attributes as $attribute) {
            $attributesForSelect[] = [
                'key' => $attribute->id,
                'value' => $attribute->name,
            ];
        }

        return $attributesForSelect;
    }

    public function store(
        StoreCategoryRequest $request,
    ): ShopCategoryResource {
        $data = $request->validated();
        $parentCategory = ShopCategory::query()->find($data['parent_id']);
        $category = new ShopCategory($data);
        if ($parentCategory) {
            $category->appendToNode($parentCategory);
        }
        $category->save();

        return new ShopCategoryResource($category);
    }

    public function update(
        UpdateCategoryRequest $request,
        ShopCategory $shopCategory,
    ): ShopCategoryResource {
        $data = $request->validated();
        if ($data['parent_id'] !== $shopCategory->parent_id) {
            if ($data['parent_id'] == 0) {
                $shopCategory->makeRoot();
            } else {
                $parent = ShopCategory::query()->find($data['parent_id']);
                $shopCategory->appendToNode($parent);
            }
        }

        if ($data['parent_id'] !== $shopCategory->parent_id or $data['alias'] !== $shopCategory->alias) {
            $shopCategory->alias = $data['alias'];
        }
        $shopCategory->update($data);

        return new ShopCategoryResource($shopCategory);
    }

    public function destroy(ShopCategory $shopCategory): ?bool
    {
        $categories = $shopCategory->descendants->pluck('id');
        $categories->add($shopCategory->id);
        $products = ShopProduct::query()
            ->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('shop_categories.id', $categories);
            })
            ->orderBy('sort')
            ->get();

        foreach ($products as $product) {
            $product->delete();
        }
        foreach ($shopCategory->descendants as $category) {
            $category->delete();
        }

        return $shopCategory->delete();
    }
}
