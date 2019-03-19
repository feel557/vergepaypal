<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">

    <!--========== Page Ttile ==========-->
    <title>Success - Your e-Guide is Ready for Download</title>

    <!-- Google Font -->
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,300,500,600,700' rel='stylesheet' type='text/css'>

    <!-- CSS Plugin Files -->
    <link href="/_ironhome/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="/_ironhome/css/plugins/font-awesome.min.css" rel="stylesheet">
    <link href="/_ironhome/css/plugins/lineicons.css" rel="stylesheet">
    <link href="/_ironhome/vendors/magnific-popup/magnific-popup.css" rel="stylesheet">
    <link href="/_ironhome/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet">
    <link href="/_ironhome/vendors/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/_ironhome/vendors/owl-carousel/assets/owl.carousel.css" rel="stylesheet">
    <link href="/_ironhome/css/plugins/animate.css" rel="stylesheet">
	<!-- Flip - Countdown Timer CSS settings -->
	<link href="/_ironhome/css/plugins/fliptimer.css" rel="stylesheet">
    <!-- Preloader -->
    <link href="/_ironhome/css/plugins/preloader.css" rel="stylesheet">

    <!--========== Main Styles==========-->
    <link href="/_ironhome/css/style.css" rel="stylesheet">

    <!-- Theme CSS (For Available Color Options, See Documentation ) -->
    <link href="/_ironhome/css/themes/blue-orange.css" rel="stylesheet">

    <!--========== HTML5 shim and Respond.js (Required) ==========-->
    <!--[if lt IE 9]>
    <script src="/_ironhome/js/lib/html5shiv.min.js"></script>
    <script src="/_ironhome/js/lib/respond.min.js"></script>
    <![endif]-->
	<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '350312795478858');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=350312795478858&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115854389-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-115854389-1');
</script>



</head>

<body data-scroll-animation="false">
<script>
  fbq('track', 'Purchase');
</script>

    <!--========== Success Content ==========-->
    <section class="row">
        <div class="container">

            <!-- Header Starts -->
            <main id="top" class="text-center m-t-100">
                <div class="container">
                    <!-- Startup Logo -->
                    <div class="logo">
                        <a href="#"><img src="/_ironhome/images/success-icon.png" alt="Success Icon"></a>
                    </div>
                    <!-- Hero Title -->
                    <h2 class="h2">Success!</h2>
					
					<? //var_dump(\Session::get('oneClickPurchase')); ?>
					
                    <!-- Sub Title -->
                    <p class="lead">Your e-Guide has been sent to your email.<br/>Looking for more protection resources?</p>
					
                </div>
            </main>
            <!-- //   Header Ends -->

			<? if(isset($_GET['tp_type']) && $_GET['tp_type'] == 1){ ?>
				
				<p class="lead" style="text-align:center;"><strong>Upgrade before the timer hits zero &amp; receive a discount!</strong></p>
				<div class="countdown"></div>
				
			<?
			
			$getProductData_2 = DB::table("_products")
				->where("id", "=", 2)
				->get();
			
			?>
			

				<div class="col-sm-12 col-sm-offset-0 col-md-4 col-md-offset-0 product-item" data-id="<?= $getProductData_2[0]->id ?>" style="margin:0 auto;float:none;">
                        <div class="pricing">
                            <div class="pricing__header"> 
								<img src="/_ironhome/images/harden-home-network.jpg" width="100%"/>
								<h3 class="pricing__title product-item-name"><?= $getProductData_2[0]->name ?></h3>
								<h4 class="pricing__price product-item-price">
								
								<strike style="color: #cdd0d4; font-weight: 200;">$<?= $getProductData_2[0]->extra_compare_price ?></strike> $<?= $getProductData_2[0]->extra_price ?>
								
								</h4>
								
								
								
                             </div>
                            <!-- // end .pricing__header -->
                            <div class="pricing__content product-item-desc">
                                <ul>
                                    <li>The Home Cybersecurity Checklist for home WiFi routers, connected devices test &amp; best practices.</li>
                                </ul>
                                <!-- // end  list unstyled -->
                            </div>
                            <!-- // end .pricing__content -->
                            <div class="pricing__cta"> 
                            <a href="#product-choose" data-id="<?= $getProductData_2[0]->id ?>" class="order-btn btn btn-sm btn-warning">Get Higher Security</a> 
                            </div>
                            <!-- // end .pricing__cta -->
                        </div>
                        <!-- // end .pricing -->
                    </div>			
				
				
			
				
				
				
			<? } ?>
			
			<? if(isset($_GET['tp_type']) && $_GET['tp_type'] == 2){ ?>
			
				<p class="lead" style="text-align:center;"><strong>Upgrade before the timer hits zero &amp; receive a discount!</strong></p>
				<div class="countdown"></div>
				
			<?
			
			$getProductData_3 = DB::table("_products")
				->where("id", "=", 3)
				->get();
			
			?>
				
				<div class="col-sm-12 col-sm-offset-0 col-md-4 col-md-offset-0 product-item" data-id="<?= $getProductData_3[0]->id ?>" style="margin:0 auto;float:none;">
					<div class="pricing">
						<div class="pricing__header">
							<img src="/_ironhome/images/maximum-home-cybersecurity.jpg" width="100%"/>
						 <h3 class="pricing__title product-item-name"><?= $getProductData_3[0]->name ?></h3>
						 <h4 class="pricing__price product-item-price"><strike style="color: #cdd0d4; font-weight: 200;">$<?= $getProductData_3[0]->extra_compare_price ?></strike> $<?= $getProductData_3[0]->extra_price ?></h4>
						 
						  </div>
						<!-- // end .pricing__header -->
						<div class="pricing__content product-item-desc">
							<ul>
								<li>Created by the world's top cyber security professionals, buying this guide will significantly reduce the probability of a home cyber invasion.</li>
							</ul>
							<!-- // end  list unstyled -->
						</div>
						<!-- // end .pricing__content -->
						<div class="pricing__cta">
						<form action="/orders/order-processing" id="order-upsell-type-2" method="post">
							{{ csrf_field() }}
							
							<input type="hidden" name="redirect" id="redirect" value="/lp/success?a=1">
							<input type="hidden" name="paypal_payment" id="paypal_payment-2" value="0">
							<input type="hidden" name="product_id" id="product_id" value="<?= $getProductData_3[0]->id ?>">
							<input type="hidden" value="1" name="product_qty">
							<?
							
							$sessionOneClickPurchase = \Session::get('oneClickPurchase');
							
							if(isset($sessionOneClickPurchase) && $sessionOneClickPurchase == 1){ ?>
								
								<button type="submit" class="btn btn-rounded js-preorder-btn btn-block"><span>Upgrade Now</span></button><br/>(or)<br/><br/>
								
							<? } ?>
							
							<div id="paypal-btn"> <a href="javascript:void(0);" id="paypal_processing-2" style="text-align: center;"><img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Check out with PayPal" /></a></div>
						</form>
						
						 </div>
						<!-- // end .pricing__cta -->
					</div>
				</div>
				
			
				
			<? } ?>
			
            
			
			
			
			
			
			
			
			
            <p class="text-center"> <a href="/lp/page?a=<?= $_GET['a'] ?>" class="btn btn-outline"> ← Back to Home</a> </p>

            <!-- Container -->
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center m-t-20">IronHome.org &copy; <?= date("Y", time()) ?>. All Rights Reserved. </p>
                    </div>
                </div>
                <!-- // End Client Logos -->

            </div>
            <!-- // Container Ends -->

        </div>
    </section>

	
	
	
	
	<!-- ============================================================
                            PRODUCT POPUP
         ============================================================ -->

    <div class="row product-box mfp-hide" id="product-choose">
        <button class="mfp-close custom_close" title="Close (Esc)" type="button">&#215;</button>
        <div class="product-img-gallery">
            <div id="product-imgs" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#product-imgs" data-slide-to="0" class="active"></li>
                </ol>

                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="/_ironhome/images/harden-home-network.jpg" class="product-image" width="100%"/>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="product-desc">

            <form action="/orders/order-processing" id="preorderform" class="choose-form row m0" method="post" style="min-height: 620px;">
			
				<input type="hidden" name="paypal_payment" id="paypal_payment" value="0">
				<input type="hidden" name="product_id" id="product_id" value="">
				<input type="hidden" name="redirect" id="redirect" value="/lp/success?a=1">
				
				
				{{ csrf_field() }}
            
                <div id="js-product-info" class="product-info">
                    <h4 class="name">Cybersecurity</h4>
                    <h2 class="edition product-item-name">Maximum Home Protection</h2>
                    
                    <h2 class="price product-item-price"><del>$99</del>$49 <span class="label label-default">early bird offer</span></h2>
                    <div class="row m0 description product-item-desc">
                        <p>The Home Cybersecurity Checklist for home WiFi routers, connected devices test &amp; best practices.</p>
                    </div>

                    <div class="form-group col-sm-4 choose-options row m0" id="js-choose-color">
                       
                        <div class="option">
                            <h4 class="form-label">Qty</h4>
                            <input type="text" value="1" name="product_qty" class="quanity">
                        </div>
                        
                    </div>
                    <div class="form-group col-sm-8 submit-area row m0">
                        <a href="javascript:void(0);" class="btn btn-rounded btn-block" id="next-personal">Order Now</a>
                    </div>
					<div class="form-group col-sm-12" style="margin-top: 20px; text-align: center;">
						<img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png"/>
					</div>
                </div>
                <!-- // End Product Info -->

                <div class="personal-info" id="js-personal-info">

                    <div class="form-group text-center"><a href="javascript:void(0);" class="btn" id="prev-product-info">Go Back</a></div>
					
					<? if(isset($_GET['tp_type']) && $_GET['tp_type'] == 2){}else{ ?>

					<?
					
					$sessionCustomerId = \Session::get('customerId');
					
					$customerFirstName = "";
					$customerLastName = "";
					$customerEmail = "";
					
					if(isset($sessionCustomerId)){
						
						$getProductData_3 = DB::table("users")
							->where("id", "=", $sessionCustomerId)
							->get();
							
						$customerFirstName = $getProductData_3[0]->first_name;
						$customerLastName = $getProductData_3[0]->last_name;
						$customerEmail = $getProductData_3[0]->email;
						
					}
					
					?>
					<div class="row m0 description product-item-desc" style="padding-bottom: 10px;">
					<div class="row">
                        <div class="form-group col-sm-6">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?= $customerFirstName ?>" required>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?= $customerLastName ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" value="<?= $customerEmail ?>" required>
                        </div>
                    </div>
					</div>
					<p>&nbsp;</p>
					<fieldset id="payment_fieldset ">
						<div class="row">
                    <div class="form-group col-sm-8">
                        <input type="text" name="card_number" class="form-control" placeholder="Credit Card Number" required>
                    </div>
                        <div class="form-group col-sm-4">
                            <input type="text" name="cvc" class="form-control" placeholder="CVV" required>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="text" name="exp_month" class="form-control" placeholder="MM" required>
                        </div>
						<div class="form-group col-sm-4">
                            <input type="text" name="exp_year" class="form-control" placeholder="YY" required>
                        </div>
						<div class="form-group col-sm-4" id="form_zip_code">
                            <input type="text" name="zip" class="form-control" placeholder="Zip Code" required>
                        </div>
						</div>
                    
					</fieldset>

					<? } ?>
                    
					<div class="submit-area-2 row form-group m0">
                        <button type="submit" class="btn btn-rounded js-preorder-btn btn-block"><i class="li_lock"></i> <span id="order-submit-button-text">Checkout Securely</span></button>
						
                    </div>
					<div class="form-group col-sm-12" style="margin-top: 20px; text-align: center;">
						<div id="paypal-btn">(or) <a href="javascript:void(0);" id="paypal_processing" style="text-align: center;"><img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Check out with PayPal" /></a></div>
					</div>

                </div>
                <!-- // end #js-personal-info.personal-info -->

            </form>

        </div>
    </div>

    <!-- // End Product Popup Section -->
	
	
	
	
	
	
    <!--========== Javascript Files ==========-->

    <!-- jQuery Latest -->
    <script src="/_ironhome/js/lib/jquery-2.2.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="/_ironhome/js/lib/bootstrap.min.js"></script>
    <script src="/_ironhome/vendors/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/_ironhome/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/_ironhome/js/plugins/validate.js"></script>

    <!-- Main JS -->
    <script src="/_ironhome/js/main.js"></script>
	
	
	<script>
	
$(function() {

    "use strict";

    // Go to second Step
    $('#next-personal').on('click', function() {
        $('#js-product-info').addClass('slide-out-left');
        $('#js-personal-info').addClass('slide-in-right');
    });
    // back to first Step
    $('#prev-product-info').on('click', function() {
        $('#js-personal-info').removeClass('slide-in-right');
        $('#js-product-info').removeClass('slide-out-left');
    });


    /* ================================================
   jQuery Validate - Reset Defaults
   ================================================ */

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorPlacement: function(error, element) {}
    });

    /* 
    VALIDATE
    -------- */

	$("#paypal_processing-2").click(function(){
		
		$("#paypal_payment-2").val(1);
		
		var product_id = $("#product_id").val();
		
		if(product_id == 1){
			
			//INSERT FACEBOOK CODE HERE LIKE:
			fbq('track', 'Lead');
		
		}else{
			
			//INSERT FACEBOOK CODE HERE LIKE: for paid products
			fbq('track', 'AddPaymentInfo');
			
		}
		
		$("#order-upsell-type-2").submit();
	
	});
	
	$("#order-upsell-type-2").submit(function(e) {
        e.preventDefault();
    }).validate({
        rules: {},
        submitHandler: function(form) {

			var product_id = $("#product_id").val();
			
			if(product_id == 1){
				
				//INSERT FACEBOOK CODE HERE LIKE:
				fbq('track', 'Lead');
			
			}else{
				
				//INSERT FACEBOOK CODE HERE LIKE: for paid products
				fbq('track', 'AddPaymentInfo');
				
			}
			
			form.submit();

        }
    });


	$("#paypal_processing").click(function(){
		
		$("#paypal_payment").val(1);
		
		var product_id = $("#product_id").val();
		
		if(product_id == 1){
			
		//INSERT FACEBOOK CODE HERE LIKE:
			fbq('track', 'Lead');
		
		}else{
			
			//INSERT FACEBOOK CODE HERE LIKE: for paid products
		fbq('track', 'AddPaymentInfo');
			
		}
		
		$("#order-upsell-type-2").submit();
	
	});
	
	
    $("#preorderform").submit(function(e) {
        e.preventDefault();
    }).validate({
        rules: {},
        submitHandler: function(form) {

           
			var product_id = $("#product_id").val();
			
			if(product_id == 1){
				
				//INSERT FACEBOOK CODE HERE LIKE:
				fbq('track', 'Lead');
			
			}else{
				
				//INSERT FACEBOOK CODE HERE LIKE: for paid products
				fbq('track', 'AddPaymentInfo');
				
			}
			
			form.submit();

           

        }
    });

})


	
	$(document).ready(function(){
		
		$("a[href='#product-choose']").magnificPopup({
				//src: '#product-choose',
				type: 'inline',
				mainClass: 'mfp-fade',
				midClick: true, // mouse middle button click
				callbacks: {
					beforeOpen: function(item) {
						
						var currentObj = this.st.el;
						
						var productId = currentObj.attr("data-id");
						var productImg = currentObj.attr("data-img");
						var productTxt = currentObj.attr("data-txt");
						
						$("#product_id").val(productId);
						
						if(productId == 1){
							
							//it is free product
							
							//hide payment fields
							$("#payment_fieldset").hide();
							$("#form_zip_code").hide();
							$("#paypal-btn").hide();
							$("#order-submit-button-text").text("Download Now");
							
						}else{
							
							//show payment fields
							$("#payment_fieldset").show();
							$("#form_zip_code").show();
							$("#paypal-btn").show();
							$("#order-submit-button-text").text("Checkout Now");
							
							//INSERT FACEBOOK CODE HERE LIKE: for paid products
							fbq('track', 'AddToCart');
							
						}
						
						// 2.
						var productName =  $(".product-item[data-id='"+productId+"']").find(".product-item-name").html();
						var productPrice =  $(".product-item[data-id='"+productId+"']").find(".product-item-price").html();
						var productDesc =  $(".product-item[data-id='"+productId+"']").find(".product-item-desc").text();
						
						$("#js-product-info").find(".product-item-name").html(productName);
						$("#js-product-info").find(".product-item-price").html(productPrice);
						$("#js-product-info").find(".product-item-desc").html("<p>"+productDesc+"</p>");
						$("#product-imgs").find(".product-image").attr("src",productImg);
						
						console.log('choosing product finished');
						console.log('Start of popup initialization');
						console.log('product:' + this.st.el.attr("data-id"));
						
					}
				}
			});
			
	});
	
	
	$(document).ready(function(){
		/*
		$(".order-btn").click(function(){
			
			var productId = $(this).attr("data-id");
			$("#product_id").val(productId);
			
			if(productId == 1){
				
				//hide payment fields
				$("#payment_fieldset").hide();
				$("#form_zip_code").hide();
				
			}else{
				
				//show payment fields
				$("#payment_fieldset").show();
				$("#form_zip_code").show();
				
			}
			
			// 2.
			var productName = $(".product-item[data-id='"+productId+"']").find(".product-item-name").html();
			var productPrice = $(".product-item[data-id='"+productId+"']").find(".product-item-price").html();
			var productDesc = $(".product-item[data-id='"+productId+"']").find(".product-item-desc").text();
			
			$("#js-product-info").find(".product-item-name").html(productName);
			$("#js-product-info").find(".product-item-price").html(productPrice);
			$("#js-product-info").find(".product-item-desc").html("<p>"+productDesc+"</p>");
			
		})
		*/
		
		
	})
	
	</script>
	
<script src="/_ironhome/js/plugins/jquery.fliptimer.js"></script>
		<script>
			var api;
			
			$(document).ready(function() {
				
				
				//Countdown timer
				api = $(".countdown").flipTimer({
					date:"<?= date("Y/m/d H:i:s", time()+60*5); ?>",
					//timeZone:-5,	//Time zone of New York
					//past:true,
					showDay:false,
					borderRadius:2,
					bgColor:"#ff6622",
					dividerColor:"#fff",
					digitColor:"#fff",
					textColor:"#fff",
					boxShadow:false
				});
				
				
				
				
			});
		</script>
	
	
	
	
</body>

</html>