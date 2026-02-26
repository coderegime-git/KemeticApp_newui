<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Book;
use App\User;

class BookOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $book = Book::with('translation')->find($this->book_id);

        return [
            'id' => (string) $this->id,

            'buyer' => $this->buyer ? [
                'id' => (string) $this->buyer->id,
                'full_name' => $this->buyer->full_name,
                'email' => $this->buyer->email,
                'avatar' => $this->buyer->getAvatar(),
            ] : null,

            'seller' => $this->seller ? [
                'id' => (string) $this->seller->id,
                'full_name' => $this->seller->full_name,
                'email' => $this->seller->email,
                'avatar' => $this->seller->getAvatar(),
            ] : null,

            'price' => (string) convertPriceToUserCurrency($this->sale->amount ?? 0),
            'discount' => (string) convertPriceToUserCurrency($this->sale->discount ?? 0),
            'tax' => (string) convertPriceToUserCurrency($this->sale->tax ?? 0),
            'total_amount' => (string) ($this->sale->total_amount ?? 0),
            'income' => (string) convertPriceToUserCurrency($this->sale->getIncomeItem() ?? 0),

            'status' => $this->status === 'waiting_delivery'
                ? 'waitingdelivery'
                : $this->status,

            'created_at' => $this->created_at,

            // 📘 Book Details
            'book' => $book ? [
                'id' => (string) $book->id,
                'title' => $book->title,
                'slug' => $book->slug,
                'type' => $book->type,
                'price' => (string) $book->price,
                'is_free' => (bool) $book->is_free,
                'image' => $book->getImage(),
                'page_count' => $book->page_count,
                // 'rating' => (string) $book->getRate(),
                'url' => $book->getUrl(),
            ] : null,

            // 📄 Invoice URLs
            'salesInvoiceUrl' => url('/getSalesInvocie/' . $this->sale_id . '/bookOrder/' . $this->id . '/invoice/' . apiAuth()->id),
            'purchaseInvoiceUrl' => url('/getPurchaseInvocie/' . $this->sale_id . '/bookOrder/' . $this->id . '/invoice/' . apiAuth()->id),

            // 📂 PDFs
            'cover_pdf' => $book?->url ? url($book->url) : '',
        ];
    }
}
