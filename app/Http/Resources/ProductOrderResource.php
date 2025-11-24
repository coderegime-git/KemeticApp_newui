<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class ProductOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $productData = Product::find($this->product_id);

        $productImages = $productData && $productData->images
            ? $productData->images->map(function ($image) {
                return [
                    'id' => (string)$image->id,
                    'title' => $image->title,
                    'url' => $image->path ? url($image->path) : null
                ];
            })
            : [];

        return [
            'id' => (string) $this->id,
            'quantity' => (string) $this->quantity,
            'buyer' => $this->buyer ? [
                'id' => (string) $this->buyer->id,
                'full_name' => $this->buyer->full_name,
                'email' => $this->buyer->email,
                'avator' => $this->buyer->getAvatar(),
            ] : [],
            'seller' => $this->seller ? [
                'id' => (string) $this->seller->id,
                'full_name' => $this->seller->full_name,
                'email' => $this->seller->email,
                'avator' => $this->seller->getAvatar(),
            ] : [],
            'price' => (string)(float)convertPriceToUserCurrency($this->sale->amount),
            'discount' => (string)(float)convertPriceToUserCurrency($this->sale->discount),
            'total_amount' => (string)(float)$this->sale->total_amount,
            'income' => (string)(float)convertPriceToUserCurrency($this->sale->getIncomeItem()),
            'tax' => (string)convertPriceToUserCurrency($this->sale->tax) ?? 0,
            'product_delivery_fee' => (string)convertPriceToUserCurrency($this->sale->product_delivery_fee) ?? 0,
            'product_type' => $this->product->type ?? '',
            'status' => ($this->status == 'waiting_delivery') ? 'waitingdelivery' : $this->status,
            'created_at' => $this->created_at,
            'product_images' => $productImages,
            'product_name' => $productData->title ?? 'Unknown',
            'product_id' => $productData->id ?? 'N/A',
            'product_rating' => $productData ? strval($productData->getRate()) : strval(0),
            'salesInvoiceUrl' => url('/getSalesInvocie/' . $this->sale_id . '/productOrder/' . $this->id . '/invoice/' . apiAuth()->id),
            'purchaseInvoiceUrl' => url('/getPurchaseInvocie/' . $this->sale_id . '/productOrder/' . $this->id . '/invoice/' . apiAuth()->id),
            'pdfPath' => !empty($productData?->files[0]) ? url('/downloadPDF/' . $productData->files[0]->id) : '',
            'viewPdf' => !empty($productData?->files[0]) && ($productData->files[0]->online_viewer == 1) ? url($productData->files[0]->path) : '',
        ];
    }
}
