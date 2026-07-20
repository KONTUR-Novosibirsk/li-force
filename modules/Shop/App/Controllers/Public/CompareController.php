<?php

namespace Modules\Shop\App\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shop\App\Models\ShopAttributeCategory;
use Modules\Shop\App\Models\ShopProduct;

class CompareController extends \App\Http\Controllers\Controller
{
    public function compare(Request $request)
    {
        return response()->view('shop::compare.index');
    }

    public function likes(Request $request)
    {
        return response()->view('shop::likes.index');
    }

    public function sravnenieTovarov($productId): ?JsonResponse
    {
        // id int
        $id = $productId; // сюда айди с фронта надо будет

        if (! is_numeric($productId)) {
            dd('не цифра');
        }

        $attributeCategories = ShopAttributeCategory::all()->unique('id')->keyBy('id');
        $products = ShopProduct::query()->with(['attributesValue.attribute', 'category', 'images'])
            ->where('id', $id)
            ->get();
        $result = [];

        foreach ($products as $product) {
            $images = [];
            foreach ($product->images as $image) {
                $images[] = [
                    'name' => $image->name,
                    'preview_url' => $image->getPreview(),
                    'full_url' => $image->getFull(),
                ];
            }

            $productData = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $product->price,
                'old_price' => $product->old_price,
                'date' => date('m.d.y'),
                'category' => $product->category()?->title ?? null,
                'url' => $product->createUrl(),
                'images' => $images,
                'shopAttributeCategory' => [
                    'attributes' => [],
                ],
            ];
            $attributesByCategory = [];

            foreach ($product->attributesValue as $attributeValue) {

                $attribute = $attributeValue->attribute;

                $categoryId = $attribute->attribute_category_id;

                if ($attributeCategories->get($categoryId) !== null) {

                    $categoryName = $attributeCategories->get($categoryId)->name;

                    if (! isset($attributesByCategory[$categoryName])) {
                        $attributesByCategory[$categoryName] = [];
                    }

                    $attributesByCategory[$categoryName][] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value' => $attributeValue->value,
                    ];
                }
            }

            $productData['shopAttributeCategory']['attributes'] = $attributesByCategory;
            $productData['attributes'] = count($product->attributes()) ? $product->attributes() : [];
            $result[] = $productData;

        }

        return response()->json($result);
    }

    public function addProductToLikes($productId): ?JsonResponse
    {

        // id int
        $id = $productId; // сюда айди с фронта надо будет

        if (! is_numeric($productId)) {
            return null;
        }

        $attributeCategories = ShopAttributeCategory::all()->unique('id')->keyBy('id');
        $products = ShopProduct::query()->with(['attributesValue.attribute', 'category', 'images'])
            ->where('id', $id)
            ->get();
        $result = [];

        foreach ($products as $product) {
            $images = [];
            foreach ($product->images as $image) {
                $images[] = [
                    'name' => $image->name,
                    'preview_url' => $image->getPreview(),
                    'full_url' => $image->getFull(),
                ];
            }

            $productData = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $product->price,
                'old_price' => $product->old_price,
                'date' => date('m.d.y'),
                'category' => $product->category()?->title ?? null,
                'url' => $product->createUrl(),
                'images' => $images,
                'shopAttributeCategory' => [
                    'attributes' => [],
                ],
            ];

            $attributesByCategory = [];

            foreach ($product->attributesValue as $attributeValue) {

                $attribute = $attributeValue->attribute;

                $categoryId = $attribute->attribute_category_id;

                if ($attributeCategories->get($categoryId) !== null) {

                    $categoryName = $attributeCategories->get($categoryId)->name;

                    if (! isset($attributesByCategory[$categoryName])) {
                        $attributesByCategory[$categoryName] = [];
                    }

                    $attributesByCategory[$categoryName][] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'value' => $attributeValue->value,
                    ];
                }
            }

            $productData['shopAttributeCategory']['attributes'] = $attributesByCategory;
            $result[] = $productData;

        }

        return response()->json($result);
    }
}
