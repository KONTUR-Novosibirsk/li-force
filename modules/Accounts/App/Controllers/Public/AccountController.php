<?php

namespace Modules\Accounts\App\Controllers\Public;

use App\Http\Controllers\Controller;
use Galtsevt\LaravelSeo\App\Facades\Seo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\Accounts\App\Requests\UpdateRequest;
use Modules\Accounts\App\Resources\AccountResource;
use Modules\Shop\App\Models\ShopOrder;
use Modules\Shop\App\Models\ViewedProduct;
use Modules\Shop\App\Resources\Order\ShopOrderResource;
use Modules\Shop\App\Services\Public\OrderService;
use Modules\Shop\App\Services\Saby\SabyIntegration;
use Modules\Storage\App\Resources\ImageResource;

class AccountController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function profile(): Response
    {
        Seo::breadcrumbs()->add('Личный кабинет', route('account.me'));

        return response()->view('accounts::profile');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->account->logout();

        return redirect()->route('index');
    }

    public function account(Request $request): JsonResponse
    {
        return response()->json(AccountResource::make($request->account));
    }

    public function history(Request $request, SabyIntegration $saby): JsonResponse
    {
        ShopOrder::checkOrderSabyStatus($saby);
        $orders = $this->orderService->accountHistory($request->account);

        return response()->json(ShopOrderResource::collection($orders));
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $user = $request->account;

        $user->update($request->only(['full_name', 'last_name', 'phone', 'counterparty']));

        if ($request->has('current_password') && $request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Текущий пароль неверен',
                    'errors' => ['current_password' => ['Текущий пароль неверен']],
                ], 422);
            }
            $user->password = $request->password;
            $user->save();
        }

        if ($sabyId = (new SabyIntegration())->addCounterpartyInSaby($user)) {
            $user['saby_id'] = $sabyId;
            $user->save();
        }

        return response()->json(AccountResource::make($user));
    }

    public function watchedProducts(Request $request): JsonResponse
    {
        $story_watch = [];
        // все продукты с картинками
        if ($request->account) {
            $viewed = ViewedProduct::where('account_id', $request->account->id)
                ->with('product.images')
                ->orderBy('viewed_at', 'desc')
                ->get()
                ->unique('product_id')
                ->take(9);
            //            dd($viewed); всё вообще
            foreach ($viewed as $view) {
                $product = $view->product;
                if ($product) {
                    $story_watch[] = [
                        'id' => $product->id,
                        'title' => $product->title,
                        'price' => $product->price,
                        'old_price' => $product->old_price,
                        'images' => ImageResource::collection($product->images),
                        'viewed_at' => $view->viewed_at,
                        'url' => $product->createUrl(),
                    ];
                }
            }
        }

        return response()->json($story_watch);
    }
}
