<?php

namespace App\Mixins\Cart;

class CartItemInfo
{
    public function getItemInfo($cart)
    {
        if (!empty($cart->webinar_id)) {
            $webinar = $cart->webinar;
            if (empty($webinar)) return null;
            return $this->getCourseInfo($cart, $webinar);

        } elseif (!empty($cart->bundle_id)) {
            $bundle = $cart->bundle;
            if (empty($bundle)) return null;
            return $this->getBundleInfo($cart, $bundle);

        } elseif (!empty($cart->productOrder) and !empty($cart->productOrder->product)) {
            $product = $cart->productOrder->product;
            return $this->getProductInfo($cart, $product);

        } elseif (!empty($cart->bookOrder) and !empty($cart->bookOrder->book)) {
            $book = $cart->bookOrder->book;
            return $this->getBookInfo($cart, $book);

        } elseif (!empty($cart->reserve_meeting_id)) {
            $creator = $cart->reserveMeeting->meeting->creator;
            if (empty($creator)) return null;
            return $this->getReserveMeetingInfo($cart, $creator);

        } elseif (!empty($cart->installment_payment_id)) {
            $installmentPayment = $cart->installmentPayment;
            if (empty($installmentPayment)) return null;
            return $this->getInstallmentOrderInfo($cart, $installmentPayment);
        }
    }

    private function getCourseInfo($cart, $webinar)
    {
        $info = [];

        $info['imgPath']      = $webinar->getImage();
        $info['itemUrl']      = $webinar->getUrl();
        $info['title']        = $webinar->title;
        $info['profileUrl']   = $webinar->teacher->getProfileUrl();
        $info['teacherName']  = $webinar->teacher->full_name;
        $info['rate']         = $webinar->getRate();
        $info['price']        = $webinar->price;
        $info['discountPrice']= $webinar->getDiscount($cart->ticket)
                                    ? ($webinar->price - $webinar->getDiscount($cart->ticket))
                                    : null;
        $info['type']         = 'Course';

        return $info;
    }

    private function getBundleInfo($cart, $bundle)
    {
        $info = [];

        $info['imgPath']      = $bundle->getImage();
        $info['itemUrl']      = $bundle->getUrl();
        $info['title']        = $bundle->title;
        $info['profileUrl']   = $bundle->teacher->getProfileUrl();
        $info['teacherName']  = $bundle->teacher->full_name;
        $info['rate']         = $bundle->getRate();
        $info['price']        = $bundle->price;
        $info['discountPrice']= $bundle->getDiscount($cart->ticket)
                                    ? ($bundle->price - $bundle->getDiscount($cart->ticket))
                                    : null;
        $info['type']         = 'Bundle';

        return $info;
    }

    private function getProductInfo($cart, $product)
    {
        $info = [];

        // ── CJ Dropship: read name & image from specs JSON ────────────────────
        // The proxy Product row stores CJ data in the ProductOrder specifications
        // column. For normal products the specs field is null/irrelevant so this
        // fallback is harmless.
        $specs        = [];
        $productOrder = $cart->productOrder;

        if (!empty($productOrder) && !empty($productOrder->specifications)) {
            $decoded = json_decode($productOrder->specifications, true);
            if (is_array($decoded) && ($decoded['source'] ?? '') === 'cj_dropship') {
                $specs = $decoded;
            }
        }

        // Title: use CJ name from specs when the translated title is empty
        $title = $product->title;
        if (empty(trim((string) $title)) && !empty($specs['cj_name'])) {
            $title = $specs['cj_name'];
        }

        // Thumbnail: use CJ image URL from specs when ProductMedia row is missing
        $imgPath = $product->thumbnail;
        if (empty($imgPath) && !empty($specs['cj_image'])) {
            $imgPath = $specs['cj_image'];
        }

        $info['isProduct']    = true;
        $info['imgPath']      = $imgPath;
        $info['itemUrl']      = $product->getUrl();
        $info['title']        = $title;
        $info['profileUrl']   = $product->creator->getProfileUrl();
        $info['teacherName']  = $product->creator->full_name;
        $info['rate']         = $product->getRate();
        $info['quantity']     = $productOrder ? $productOrder->quantity : 1;
        $info['price']        = !empty($specs) ? (float)($specs['cj_price'] ?? $product->price) : $product->price;
        $info['discountPrice']= (!empty($specs))
                                    ? null   // CJ products don't use local discount system
                                    : (($product->getPriceWithActiveDiscountPrice() < $product->price)
                                        ? $product->getPriceWithActiveDiscountPrice()
                                        : null);
        $info['type']         = !empty($specs) ? 'CJ Product' : 'Product';

        return $info;
    }

    private function getBookInfo($cart, $book)
    {
        $info = [];

        $info['isBook']       = true;
        $info['imgPath']      = $book->getImage();
        $info['itemUrl']      = $book->getUrl();
        $info['title']        = $book->title;
        $info['rate']         = null;
        $info['profileUrl']   = $book->creator ? $book->creator->getProfileUrl() : null;
        $info['teacherName']  = $book->creator ? $book->creator->full_name : null;
        $info['quantity']     = $cart->bookOrder ? $cart->bookOrder->quantity : 1;
        $info['price']        = $book->price ?? 0;
        $info['discountPrice']= null;
        $info['type']         = 'Book';

        return $info;
    }

    private function getReserveMeetingInfo($cart, $creator)
    {
        $info = [];

        $info['imgPath']     = $creator->getAvatar(150);
        $info['itemUrl']     = null;
        $info['title']       = trans('meeting.reservation_appointment')
                               . ' '
                               . ((!empty($cart->reserveMeeting->student_count)
                                   and $cart->reserveMeeting->student_count > 1)
                                  ? '(' . trans('update.reservation_appointment_student_count',
                                        ['count' => $cart->reserveMeeting->student_count]) . ')'
                                  : '');
        $info['profileUrl']  = $creator->getProfileUrl();
        $info['teacherName'] = $creator->full_name;
        $info['rate']        = $creator->rates();
        $info['price']       = $cart->reserveMeeting->paid_amount;
        $info['type']        = 'Reserve Meeting';

        return $info;
    }

    private function getSubscribeInfo($cart, $subscribe)
    {
        $info = [];

        $info['imgPath']      = $subscribe->icon;
        $info['itemUrl']      = null;
        $info['title']        = $subscribe->title;
        $info['profileUrl']   = null;
        $info['teacherName']  = null;
        $info['extraHint']    = trans('public.subscribe');
        $info['rate']         = null;
        $info['quantity']     = null;
        $info['price']        = $subscribe->price;
        $info['discountPrice']= null;
        $info['type']         = 'Subscribe';

        return $info;
    }

    private function getRegistrationPackageInfo($cart, $registrationPackage)
    {
        $info = [];

        $info['imgPath']      = $registrationPackage->icon;
        $info['itemUrl']      = null;
        $info['title']        = $registrationPackage->title;
        $info['profileUrl']   = null;
        $info['teacherName']  = null;
        $info['extraHint']    = trans('update.registration_package');
        $info['rate']         = null;
        $info['quantity']     = null;
        $info['price']        = $registrationPackage->price;
        $info['discountPrice']= null;
        $info['type']         = 'Registration Package';

        return $info;
    }

    private function getInstallmentOrderInfo($cart, $installmentPayment)
    {
        $info = [];

        $installmentOrder = $installmentPayment->installmentOrder;

        if (!empty($installmentOrder)) {

            if (!empty($installmentOrder->webinar_id)) {
                $info = $this->getCourseInfo($cart, $installmentOrder->webinar);

            } elseif (!empty($installmentOrder->bundle_id)) {
                $info = $this->getBundleInfo($cart, $installmentOrder->bundle);

            } elseif (!empty($installmentOrder->product_id)) {
                $info = $this->getProductInfo($cart, $installmentOrder->product);

            } elseif (!empty($installmentOrder->subscribe_id)) {
                $info = $this->getSubscribeInfo($cart, $installmentOrder->subscribe);

            } elseif (!empty($installmentOrder->registration_package_id)) {
                $info = $this->getRegistrationPackageInfo($cart, $installmentOrder->registrationPackage);
            }

            $info['price']         = $installmentPayment->amount;
            $info['discountPrice'] = 0;
            $info['extraPriceHint']= ($installmentPayment->type == 'upfront')
                                        ? trans('update.installment_upfront')
                                        : trans('update.installment');
        }

        return $info;
    }
}