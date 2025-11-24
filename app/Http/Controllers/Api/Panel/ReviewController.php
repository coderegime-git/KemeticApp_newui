<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Traits\ReviewTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductReview;

class ReviewController extends Controller
{
    //
    use ReviewTrait;

    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required',
            'product_quality' => 'required',
            'purchase_worth' => 'required',
            'delivery_quality' => 'required',
            'seller_quality' => 'required',
        ];

        validateParam($request->all(), $rules);
        $user = apiAuth();
        if(!$user){
            abort(403);
        }
        $data = $request->all();

        $product = Product::where('id', $data['product_id'])
            ->where('status', 'active')
            ->first();

        if (!$product) {
            abort(404);
        }

        if (!empty($product)) {
            if ($product->checkUserHasBought($user)) {
                $productReview = ProductReview::where('creator_id', $user->id)
                    ->where('product_id', $product->id)
                    ->first();

                if (!empty($productReview)) {

                    return apiResponse2(
                        0,
                        'reviewed',
                        trans('update.duplicate_review_for_product'),
                        null,
                        trans('public.request_failed')
                    );
                    
                }

                $rates = 0;
                $rates += (int) $data['product_quality'];
                $rates += (int) $data['purchase_worth'];
                $rates += (int) $data['delivery_quality'];
                $rates += (int) $data['seller_quality'];

                ProductReview::create([
                    'product_id' => $product->id,
                    'creator_id' => $user->id,
                    'product_quality' => (int) $data['product_quality'],
                    'purchase_worth' => (int) $data['purchase_worth'],
                    'delivery_quality' => (int) $data['delivery_quality'],
                    'seller_quality' => (int) $data['seller_quality'],
                    'rates' => $rates > 0 ? $rates / 4 : 0,
                    'description' => $data['message'],
                    'status' => 'pending',
                    'created_at' => time(),
                ]);

                $notifyOptions = [
                    '[p.title]' => $product->title,
                    '[u.name]' => $user->full_name,
                    '[item_title]' => $product->title,
                    '[content_type]' => trans('update.product'),
                    '[rate.count]' => $rates > 0 ? $rates / 4 : 0,
                ];
                sendNotification('product_new_rating', $notifyOptions, $product->creator_id);
                sendNotification('new_user_item_rating', $notifyOptions, 1);

                return apiResponse2(
                    1,
                    'stored',
                    trans('webinars.your_reviews_successfully_submitted_and_waiting_for_admin'),
                    trans('public.request_success')
                );
                
            } else {
                return apiResponse2(
                    0,
                    'not_purchased',
                    trans('update.you_not_purchased_this_product'),
                    null,
                    trans('public.request_failed')
                );

            }
        }

       

    }

}
