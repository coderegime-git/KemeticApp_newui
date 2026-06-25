<?php

namespace App\Http\Resources;

use App\Mixins\Cashback\CashbackRules;
use Illuminate\Http\Resources\Json\JsonResource;
use \App\Models\Product;

class ProductResource extends JsonResource
{
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
            'new_inventory_warning' => (bool)(
                !empty($this->inventory) &&
                !empty($this->inventory_warning) &&
                $this->inventory_warning >= $this->getAvailability()
            ),
            'inventory_warning_message' => (
                !empty($this->inventory) &&
                !empty($this->inventory_warning) &&
                $this->inventory_warning >= $this->getAvailability()
            ) ? trans('update.only_n_left', ['count' => $this->getAvailability()]) : null,

            'point' => (string)$this->point,
            'sales_count' => (string)$this->salesCount() ?? 0,
            'sales_amount' => (string)convertPriceToUserCurrency($this->sales()->sum('total_amount')) ?? 0,
            'like_count' => (string)($this->likes->count() ?? 0),
            'is_liked' => isset($this->is_liked) ? (bool)$this->is_liked : false,
            'is_saved' => isset($this->is_saved) ? (bool)$this->is_saved : false,
            'share_count' => (string)$this->share_count ?? 0,
            'gift_count' => (string)$this->gift_count ?? 0,
            'comments_count' => (string)$this->comments()->where('status', 'active')->count() ?? 0,
            'saved_count' => (string)$this->saved_count ?? 0,
            'shipping_cost' => (string)convertPriceToUserCurrency($this->delivery_fee) ?? null,
            'delivery_estimated_time' => (string)$this->delivery_estimated_time ?? null,
            'waiting_orders' => (string)$this->waiting_orders,
            'price' => (string)convertPriceToUserCurrency($this->price),
            'price_with_discount' => (string)convertPriceToUserCurrency($this->getPriceWithActiveDiscountPrice()),
            'cashback_rules' => (string)$this->cashbackRules,
            'is_purchased' => $this->purchaseStatus ?? false,

            // ── CJ Dropshipping variant data ──────────────────────────────────
            'is_cj_product'    => (bool)($this->is_cj_product ?? false),
            'cj_variant_count' => $this->relationLoaded('cjVariants')
                ? $this->cjVariants->count()
                : 0,
            // Grouped unique option values per axis, mirrors the blade $variantKeys loop:
            // e.g. { "0": ["Red","Blue"], "1": ["S","M","XL"] }
            'cj_variant_keys'  => $this->relationLoaded('cjVariants')
                ? (function () {
                    $variantKeys = [];
                    foreach ($this->cjVariants as $v) {
                        if (!empty($v->variant_key)) {
                            foreach (explode('-', $v->variant_key) as $i => $part) {
                                $variantKeys[$i][] = trim($part);
                            }
                        }
                    }
                    foreach ($variantKeys as &$arr) {
                        $arr = array_values(array_unique($arr));
                    }
                    unset($arr);
                    return $variantKeys;
                })()
                : [],
            // Full variant list — one entry per selectable combination
            'cj_variants'      => $this->relationLoaded('cjVariants')
                ? $this->cjVariants->map(function ($v) {
                    // parsed_options mirrors JS selectedOptions: { "0":"Red", "1":"XL" }
                    $parsedOptions = [];
                    if (!empty($v->variant_key)) {
                        foreach (explode('-', $v->variant_key) as $i => $part) {
                            $parsedOptions[$i] = trim($part);
                        }
                    }
                    return [
                        'vid'            => $v->vid,
                        'variant_name'   => $v->variant_name,
                        'variant_key'    => $v->variant_key,
                        'variant_sku'    => $v->variant_sku,
                        'sell_price'     => (float)$v->sell_price,
                        'variant_image'  => !empty($v->variant_image) ? url($v->variant_image) : null,
                        'parsed_options' => $parsedOptions,
                    ];
                })
                : [],
            // ── end CJ data ───────────────────────────────────────────────────

            $this->mergeWhen($this->show, function () {
                return [
                    'video_demo' => $this->video_demo ? url($this->video_demo) : null,
                    'images' => $this->images->map(function ($image) {
                        return [
                            'id'    => (string)$image->id,
                            'title' => $image->title,
                            'url'   => $image->path ? url($image->path) : null,
                        ];
                    }),
                    
                    'related_courses' => \App\Models\RelatedCourse::where('targetable_id', $this->id)
                        ->where('targetable_type', 'App\Models\Product')
                        ->get()
                        ->map(function ($rc) {
                            $course = \App\Models\Webinar::find($rc->course_id);
                            if ($course and $course->status == 'active') {
                                return [
                                    'title' => $course->title,
                                    // 'url' => $course->getUrl(),
                                ];
                            }
                        })
                        ->filter()
                        ->values(),
                    
                    'selectable_specifications' => $this->dde(),
                    'selected_specifications'   => $this->getPrettySpecification(),
                    'description'               => $this->description,
                    'faqs'                      => FaqResource::collection($this->faqs),
                    'seller'                    => new UserResource($this->creator),
                    $this->mergeWhen($this->checkUserHasBought(apiAuth()), function () {
                        return [
                            'files' => ProductFileResource::collection($this->files),
                        ];
                    }),
                    'reviews'  => ReviewResource::collection($this->reviews),
                    'comments' => CommentResource::collection(
                        $this->comments
                            ->whereNull('reply_id')
                            ->where('status', 'active')
                            ->sortByDesc('created_at')
                            ->values()
                    ),
                ];
            }),
        ];
    }
}