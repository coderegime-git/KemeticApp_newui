<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Checkout...</title>
</head>
<body>
    <form id="checkout-form" action="/panel/financial/recurringPay-subscribes?auto_redirect=1" method="POST">
        @csrf
        <input type="hidden" name="amount" value="{{ $checkoutData['amount'] }}">
        <input type="hidden" name="id" value="{{ $checkoutData['id'] }}">
    </form>

    <script>
        document.getElementById('checkout-form').submit();
    </script>
</body>
</html>
