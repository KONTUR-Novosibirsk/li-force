<?php

namespace Modules\Shop\App\Controllers\Admin;

use Modules\Shop\App\Models\ShopAttributeCategory;
use Modules\Shop\App\Requests\AttributeCategory\StoreRequest;
use Modules\Shop\App\Requests\AttributeCategory\UpdateRequest;
use Modules\Shop\App\Resources\ShopAttributeCategoryResource;

class AttributeCategoryController extends \App\Http\Controllers\Controller
{
    public function store(StoreRequest $request)
    {
        $attrCategory = new ShopAttributeCategory($request->validated());
        $attrCategory->save();

        return new ShopAttributeCategoryResource(resource: $attrCategory);
    }

    public function update(UpdateRequest $request, ShopAttributeCategory $shopCategoryAttribute): ShopAttributeCategoryResource
    {
        $shopCategoryAttribute->update($request->validated());

        return new ShopAttributeCategoryResource(resource: $shopCategoryAttribute);
    }

    public function destroy(ShopAttributeCategory $shopCategoryAttribute)
    {
        $shopCategoryAttribute->delete();

        return response()->json(['success' => 'true']);
    }
}
