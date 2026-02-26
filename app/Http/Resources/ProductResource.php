<?php

namespace App\Http\Resources;

use App\Mixins\Cashback\CashbackRules;
use Illuminate\Http\Resources\Json\JsonResource;
use \App\Models\Product;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public $show = true;

    public function toArray($request)
    {
       
        return [
            'id' => (string)$this->id,
            'has_discount' => (bool)$this->getActiveDiscount(),
            'discount_percent' => (string)($this->getActiveDiscount()) ? (string)$this->getActiveDiscount()->percent : '',
            'thumbnail' => url($this->thumbnail),
            'label' => $this->label,
            'url' => $this->getUrl(),
            'category_title' => $this->category->title ?? null,
            'title' => $this->title,
            'rate' => (string)$this->getRate(),
            'reviews_count' => (string)$this->reviews->pluck('creator_id')->count(),
            'type' => $this->type,
            'unlimited_inventory' => (bool)$this->unlimited_inventory,
            'availability' => (string)($this->unlimited_inventory) ? trans('update.unlimited') : (string)$this->getAvailability(),
            'point' => (string)$this->point,
            'sales_count' => (string)$this->salesCount() ?? 0,
            'sales_amount' => (string)convertPriceToUserCurrency($this->sales()->sum('total_amount')) ?? 0,
            'like_count' => (string)$this->like_count ?? 0,
            'is_liked' => isset($this->is_liked) ? (bool)$this->is_liked : false, 
            'is_saved' => isset($this->is_saved) ? (bool)$this->is_saved : false,
            'share_count' => (string)$this->share_count ?? 0,
            'gift_count' => (string)$this->gift_count ?? 0,
            'comments_count' => (string)$this->comments_count ?? 0,
            'saved_count' => (string)$this->saved_count ?? 0,
            'shipping_cost' => (string)convertPriceToUserCurrency($this->delivery_fee) ?? null,
            'delivery_estimated_time' => (string)$this->delivery_estimated_time ?? null,
            'waiting_orders' => (string)$this->waiting_orders,
            'price' => (string)convertPriceToUserCurrency($this->price),
            'price_with_discount' => (string)convertPriceToUserCurrency($this->getPriceWithActiveDiscountPrice()),
            'cashback_rules' => (string)$this->cashbackRules,
            'is_purchased' => $this->purchaseStatus ?? false,
            //'is_purchased' => isset($this->purchaseStatus) ? true : false,
            $this->mergeWhen($this->show, function () {
                
                return [
                    'video_demo' => $this->video_demo ? url($this->video_demo) : null,
                    'images' => $this->images->map(function ($image) {
                        return [
                            'id' => (string)$image->id,
                            'title' => $image->title,
                            'url' => $image->path ? url($image->path) : null];
                    }),
                    'selectable_specifications' => $this->dde(),
                    'selected_specifications' => $this->getPrettySpecification(),
                    'description' => $this->description,
                    'faqs' => FaqResource::collection($this->faqs),
                    'seller' => new UserResource($this->creator),
                    $this->mergeWhen($this->checkUserHasBought(apiAuth()), function () {
                        return [
                            'files' => ProductFileResource::collection($this->files),
                        ];
                    }),
                    'reviews' => ReviewResource::collection($this->reviews),
                    'comments'=>CommentResource::collection($this->comments)
                ];
            })
        ];
    }
}
