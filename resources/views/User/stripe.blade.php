<!DOCTYPE html>
<html>
<head>
    <base href='/public'>
    <title>HAVANA PAYMENT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

     <!-- Additional CSS Files -->
     <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

<link rel="stylesheet" href="assets/css/shop.css">

<link rel="stylesheet" href="assets/css/owl-carousel.css">

<link rel="stylesheet" href="assets/css/lightbox.css">

<style>
        /* Tambahkan gaya sesuai kebutuhan Anda */
        .row {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .control-label {
            font-weight: bold;
        }

        .btn-primary {
            color: #fff;
        }

        .btn-primary:hover {
            color: #fff;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>

</head>
<body>


    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    @include('User.header')
    <!-- ***** Header Area End ***** -->
    <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <br></br>
                        <h2>PAY USING YOUR CART</h2>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                        <h3 class="panel-title" >Payment Details</h3>
                </div>
                <div class="panel-body">
    
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
    
                    <form 
                            role="form" 
                            action="{{ route('stripe.post') }}"
                            method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                            id="payment-form">
                        @csrf
    
                        <div class="row">
    <div class="col-xs-12 form-group required">
        <label class="control-label">Name on Card</label>
        <input class="form-control" size="4" type="text">
    </div>
</div>

<div class="row">
    <div class="col-xs-12 form-group card required">
        <label class="control-label">Card Number</label>
        <input autocomplete="off" class="form-control card-number" size="20" type="text">
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-4 form-group cvc required">
        <label class="control-label">CVC</label>
        <input autocomplete="off" class="form-control card-cvc" placeholder="ex. 311" size="4" type="text">
    </div>
    <div class="col-xs-12 col-md-4 form-group expiration required">
        <label class="control-label">Expiration Month</label>
        <input class="form-control card-expiry-month" placeholder="MM" size="2" type="text">
    </div>
    <div class="col-xs-12 col-md-4 form-group expiration required">
        <label class="control-label">Expiration Year</label>
        <input class="form-control card-expiry-year" placeholder="YYYY" size="4" type="text">
    </div>
</div>

<div class="row">
    <div class="col-md-12 error form-group hide">
        <div class="alert-danger alert">Please correct the errors and try again.</div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <button class="btn btn-primary btn-lg btn-block" type="submit" style="background-color: blue;">Pay Now</button>
    </div>
</div>

                            
                    </form>
                </div>
            </div>        
        </div>
    </div>
        
</div>

<!-- ***** Footer Start ***** -->
@include('User.footer')
    
</body>
    
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
<script type="text/javascript">
  
$(function() {
  
    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    
    var $form = $(".require-validation");
     
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('hide');
    
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
     
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
    
    });
      
    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
     
});
</script>

<!-- jQuery -->
<script src="assets/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/accordions.js"></script>
<script src="assets/js/datepicker.js"></script>
<script src="assets/js/scrollreveal.min.js"></script>
<script src="assets/js/waypoints.min.js"></script>
<script src="assets/js/jquery.counterup.min.js"></script>
<script src="assets/js/imgfix.min.js"></script> 
<script src="assets/js/slick.js"></script> 
<script src="assets/js/lightbox.js"></script> 
<script src="assets/js/isotope.js"></script> 

<!-- Global Init -->
<script src="assets/js/custom.js"></script>

</html>