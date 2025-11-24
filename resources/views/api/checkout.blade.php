<?php
// die($discount_id);
?>
<form action="/cart/checkout" method="post" id="cartForm">
    {{ csrf_field() }}
    <input type="hidden" name="discount_id" value="10">
    <input type="hidden" name="zip_code" value="10">
    <input type="hidden" name="house_no" value="10">
    <input type="hidden" name="address" value="test">
    <input type="hidden" name="country_id" value="10">
    <input type="hidden" name="message_to_seller" value="test">
    {{-- <button type="button" onclick="submitForm()">Submit</button> --}}
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function submitForm() {
        // alert('6354546')
        document.getElementById("cartForm").submit();
    }
// alert('dfgdzfgd')
    submitForm();
    // Ensure the form is submitted only once
  
    // let isSubmitted = false;

    // function submitForm() {
    //     if (!isSubmitted) {
    //         isSubmitted = true; // Prevent multiple submissions
    //         console.log(isSubmitted)
    //         document.getElementById("cartForm").submit();
    //     }
    // }

    // // Call submitForm when the page is ready
    // window.onload = function() {
    //     submitForm();
    // };
</script>
