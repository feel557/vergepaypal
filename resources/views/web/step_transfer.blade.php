<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Verge Purchase Progress - VergePal</title>
    <!-- Favicons-->
    <link rel="icon" href="/_vergepal/images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="/_vergepal/images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->
    <!-- CORE CSS-->
    <link href="/_vergepal/css/themes/overlay-menu/materialize.css" type="text/css" rel="stylesheet">
    <link href="/_vergepal/css/themes/overlay-menu/style.css" type="text/css" rel="stylesheet">
    <!-- CSS for Overlay Menu (Layout Full Screen)-->
    <link href="/_vergepal/css/layouts/style-fullscreen.css" type="text/css" rel="stylesheet">
    <!-- Custome CSS-->
    <link href="/_vergepal/css/custom/custom.css" type="text/css" rel="stylesheet">
    <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    <link href="/_vergepal/vendors/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet">
    <link href="/_vergepal/vendors/flag-icon/css/flag-icon.min.css" type="text/css" rel="stylesheet">
  </head>
  <body>
    
    <!-- START HEADER -->
    <header id="header" class="page-topbar">
      <div class="navbar-fixed">
        <nav class="navbar-color">
          <div class="nav-wrapper">
            <ul class="left">
              <li>
                <h1 class="logo-wrapper">
                  <a href="/" class="brand-logo darken-1">
                    <img src="/_vergepal/images/logo/logo.png" alt="VergePal Logo">
                  </a>
                </h1>
              </li>
            </ul>
            
          </div>
        </nav>
      </div>
    </header>
    <!-- END HEADER -->
    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <!-- START MAIN -->
    <div id="main">
      <!-- START WRAPPER -->
      <div class="wrapper">
        
        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- START CONTENT -->
        <section id="content">
          <!--start container-->
          <div class="container">
            <div id="profile-page" class="section">
              <!-- profile-page-header -->
              <div id="profile-page-header" class="card">
                <div class="card-content">
                  <div class="row pt-2">
                    <div class="col s12 m6">
                      <h4 class="card-title grey-text text-darken-4"><a href="https://verge-blockchain.info/address/<?= $order_data->verge_address ?>" target="_blank"><?= $order_data->verge_address ?></a></h4>
					  <div style="font-size:10px;">Click on the wallet to view transactions on blockchain</div>
                      <p class="medium-small grey-text">Wallet Address</p>
                    </div>
                    
                    <div class="col s12 m3 center-align">
                      <h4 class="card-title grey-text text-darken-4"><?= $wallet_total_trx_count ?></h4>
                      <p class="medium-small grey-text">Transactions</p>
                    </div>
                    
                  </div>
                </div>
              </div>
              <!--/ profile-page-header -->
              <!-- profile-page-content -->
              <div id="profile-page-content" class="row">
                <!-- profile-page-sidebar-->
                <div id="profile-page-sidebar" class="col s12 m4">
                  <!-- Profile feed  -->
                  <ul id="profile-page-about-feed" class="collection z-depth-1">
                    <li class="collection-item avatar">
                      <i class="material-icons circle teal accent-4">warning</i>
                      <span class="title">Project Title</span>
                      <p>Task assigned to new changes.
                        <br>
                        <span class="ultra-small">Second Line</span>
                      </p>
                    </li>
                    <li class="collection-item avatar">
                      <i class="material-icons circle teal accent-4">warning</i>
                      <span class="title">Project Title</span>
                      <p>Task assigned to new changes.
                        <br>
                        <span class="ultra-small">Second Line</span>
                      </p>
                    </li>
                   <li class="collection-item avatar">
                      <i class="material-icons circle teal accent-4">warning</i>
                      <span class="title">Project Title</span>
                      <p>Task assigned to new changes.
                        <br>
                        <span class="ultra-small">Second Line</span>
                      </p>
                    </li>
                    <li class="collection-item avatar">
                      <i class="material-icons circle teal accent-4">warning</i>
                      <span class="title">Project Title</span>
                      <p>Task assigned to new changes.
                        <br>
                        <span class="ultra-small">Second Line</span>
                      </p>
                    </li>
                  </ul>
                  <!-- Profile feed  -->
                  
                </div>
                <!-- profile-page-sidebar-->
                <!-- profile-page-wall -->
                <div id="profile-page-wall" class="col s12 m8">
                  <!-- profile-page-wall-share -->
                  <div id="profile-page-wall-share" class="row">
                    <div class="col s12">
                  <!-- profile-page-wall-posts -->
                  <div id="profile-page-wall-posts" class="row">
                    <div class="col s12">
                      <div id="current_transaction_block" class="card">
                        <div class="card-profile-title">
                          <div class="row">
                            <div class="col s6">
                            
                              <span class="grey-text text-darken-1 ultra-small">Initiated - <?= date("d F Y H:m", strtotime($order_data->date)); ?> </span>
                            </div>
                            <div class="col s6 right-align">
                              <button class="btn waves-effect waves-light " type="submit" name="action">Confirm Receipt
								  <i class="material-icons left">warning</i>
								</button>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col s12">
                              <div class="card-content">
								  <div class="row pt-2">
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4"><?= $order_data->product_qty ?></h4>
									  <p class="medium-small grey-text">XVG Sent</p>
									</div>
									
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4">$<?= $order_data->total_amount ?></h4>
									  <p class="medium-small grey-text">Deposited</p>
									</div>
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4"><span id="blocks-count">0</span>/30</h4>
									  <p class="medium-small grey-text">Confirmations</p>
									</div>
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4"><span id="status-spinner" style="padding-right:10px;"><img src="/_vergepal/images/curve-bars-loading-indicator.gif" width="30" height="30"></span><span id="status-text">Waiting Authorization</span></h4>
									  <p class="medium-small grey-text">Status</p>
									</div>
								  </div>
								</div>
                            </div>
                          </div>
                        </div>
                       
                        <div class="card-action row">
                          <div id="verge-block-link"></div>
                          <div class="input-field col s12 margin">
                             <div class="progress">
								  <div class="determinate" id="percent_progress" style="width:0%;"></div>
							  </div>
                          </div>
                        </div>
                      </div>
                      
					  
					  
					  
					  
					  
                      <?
					  
					  if(isset($previous_order_data) && count($previous_order_data)>0){
					  
					  ?>
                      
                      <div id="profile-page-wall-post" class="card" style="background:#eee">
                        <div class="card-profile-title">
                          <div class="row">
                            <div class="col s6">
                            
                              <span class="grey-text text-darken-1 ultra-small">Initiated - <?= date("d F Y H:m", strtotime($previous_order_data[0]->date)); ?></span>
                            </div>
                            <div class="col s6 right-align">
                               <button class="btn waves-effect waves-light disabled" type="submit" name="action">Received
								  <i class="material-icons left">check</i>
								</button>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col s12">
                              <div class="card-content">
								  <div class="row pt-2">
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4"><?= $previous_order_data[0]->product_qty ?></h4>
									  <p class="medium-small grey-text">XVG Sent</p>
									</div>
									
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4">$<?= $previous_order_data[0]->total_amount ?></h4>
									  <p class="medium-small grey-text">Deposited</p>
									</div>
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4">more than 30</h4>
									  <p class="medium-small grey-text">Confirmations</p>
									</div>
									<div class="col s12 m3 center-align">
									  <h4 class="card-title grey-text text-darken-4">Complete</h4>
									  <p class="medium-small grey-text">Status</p>
									</div>
								  </div>
								</div>
                            </div>
                          </div>
                        </div>
                       
                        <div class="card-action row">
                          
                          <div class="input-field col s12 margin">
                             <div class="progress">
								  <div class="determinate" style="width: 100%"></div>
							  </div>
                          </div>
                        </div>
                      </div>
					  
					  <? } ?>
					  
                    </div>
                  </div>
                  <!--/ profile-page-wall-posts -->
                </div>
                <!--/ profile-page-wall -->

				
              </div>
            </div>
            </div>
      </div>
      <!--end container-->
      </section>
      <!-- END CONTENT -->
      
    </div>
    <!-- END WRAPPER -->
    </div>
    <!-- END MAIN -->
    <!-- //////////////////////////////////////////////////////////////////////////// -->
    <!-- START FOOTER -->
    <footer class="page-footer">
      <div class="footer-copyright">
        <div class="container">
          <span>Copyright ©
            <script type="text/javascript">
              document.write(new Date().getFullYear());
            </script> <a class="grey-text text-lighten-4" href="https://peakk.com" target="_blank">PEAKK</a> All rights reserved.</span>
         
        </div>
      </div>
    </footer>
    <!-- END FOOTER -->
    <!-- ================================================
    Scripts
    ================================================ -->
    <!-- jQuery Library -->
    <script type="text/javascript" src="/_vergepal/vendors/jquery-3.2.1.min.js"></script>
    <!--materialize js-->
    <script type="text/javascript" src="/_vergepal/js/materialize.min.js"></script>
    <!--scrollbar-->
    <script type="text/javascript" src="/_vergepal/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <!-- sparkline -->
    <script type="text/javascript" src="/_vergepal/vendors/sparkline/jquery.sparkline.min.js"></script>
    <!-- google map api -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAZnaZBXLqNBRXjd-82km_NO7GUItyKek"></script>
    <!--google map-->
    <script type="text/javascript" src="/_vergepal/js/scripts/google-map-script.js"></script>
    <!--profile page js-->
    <script type="text/javascript" src="/_vergepal/js/scripts/page-profile.js"></script>
    <!--plugins.js - Some Specific JS codes for Plugin Settings-->
    <script type="text/javascript" src="/_vergepal/js/plugins.js"></script>
    <!--custom-script.js - Add your own theme custom JS-->
    <script type="text/javascript" src="/_vergepal/js/custom-script.js"></script>
	
	<script>
	
	var getTrxDetails = 0;
	var trxId = "";
	
	var timerId = setInterval(getWDetails, 15000);
	
	var timerIdBlocks = setInterval(function(){
		
		if(getTrxDetails == 1){
			
			getBlocks();
			
		}
		
	}, 10000);
	
	function getWDetails(){
	
		console.log("getwdetails");
		console.log(trxId);
	
	// ajax
			$.ajax({
				type: "GET",
				url: "/orders/withdrawal-details",
				data: {
					"t_id": "<?= $order_data->withdrawal_external_id ?>"
				},
				cache: false,
				success: function(response){
					
					if(response == 0){
						
						//alert("Thank you! Please send payment");
						
					}else{
						
						if(response == 3){
							
							
						}else{
							
							clearInterval(timerId);
							getTrxDetails = 1;
							trxId = response;
							
							$("#verge-block-link").html("<a href='https://verge-blockchain.info/tx/"+trxId+"' target='_blank'>Link to blockchain</a>");
							
						}
						
					}
					
				}
			});
	
	}
	
	function getBlocks(){
	
		console.log("getblokss");
	
		//ajax
			$.ajax({
				type: "GET",
				url: "/orders/trx-blocks",
				data: {
					"trx_id": trxId
				},
				cache: false,
				success: function(response){
					
					response = parseFloat(response);
					
					$("#blocks-count").html(response);
					
					if(response >= 30){
						
						clearInterval(timerIdBlocks);
						$("#status-text").html("Completed");
						$("#percent_progress").css("width", "100%");
						$("#status-spinner").hide();
						
					}else{
						
						var oneblockSize = 30/100;
						var widthTotal = oneblockSize*response;
						
						$("#status-text").html("In Progress");
						$("#percent_progress").css("width", widthTotal+"%");
						
					}
					
				}
			});
	
	}
	
	
	</script>
	
	
	
	
	
	
	
	
	
  </body>
</html>