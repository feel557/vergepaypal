<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--========== Page Ttile ==========-->
    <title>@yield('pageTitle')</title>

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

    <!-- Preloader -->
    <link href="/_ironhome/css/plugins/preloader.css" rel="stylesheet">

    <!--========== Main Styles==========-->
    <link href="/_ironhome/css/style.css" rel="stylesheet">

    <!-- Theme CSS (For Available Color Options, See Documentation ) -->
    <link href="/_ironhome/css/themes/blue-orange.css" rel="stylesheet">

    <!--========== HTML5 shim and Respond.js (Required) ==========-->
    <!--[if lt IE 9]>
    <script src="js/lib/html5shiv.min.js"></script>
    <script src="js/lib/respond.min.js"></script>
    <![endif]-->

</head>

<body data-scroll-animation="false">

    <!--==========Preloader==========-->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <div class="object" id="object_four"></div>
                <div class="object" id="object_three"></div>
                <div class="object" id="object_two"></div>
                <div class="object" id="object_one"></div>
            </div>
        </div>
    </div>

    <!--==========Header==========-->
    <header class="row" id="header">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <!--========== Brand and toggle get grouped for better mobile display ==========-->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt=""></a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <!--========== Collect the nav links, forms, and other content for toggling ==========-->
                <div class="collapse navbar-collapse" id="main-navbar">

                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="index.html">product</a></li>
                        <li><a href="../#features">features</a></li>
                        <li><a href="../#reviews">reviews</a></li>
                        <li class="active"><a href="blog.html">blog</a></li>
                        <li><a href="../#contact">contact</a></li>
                    </ul>
                </div>
                <!--========== /.navbar-collapse ==========-->
            </div>
            <!--========== /.container-fluid ==========-->
        </nav>
    </header>

@yield('content')

 <!--==========Footer==========-->
    <footer class="row ">
        <div class="container ">
            <div class="row m0 social-links ">
                <ul class="nav ">
                    <li class="wow fadeInUp "><a href="# "><i class="fa fa-facebook "></i></a></li>
                    <li class="wow fadeInUp " data-wow-delay="0.1s "><a href="# "><i class="fa fa-twitter "></i></a></li>
                    <li class="wow fadeInUp " data-wow-delay="0.2s "><a href="# "><i class="fa fa-linkedin "></i></a></li>
                    <li class="wow fadeInUp " data-wow-delay="0.3s "><a href="# "><i class="fa fa-youtube "></i></a></li>
                    <li class="wow fadeInUp " data-wow-delay="0.4s "><a href="# "><i class="fa fa-google-plus "></i></a></li>
                    <li class="wow fadeInUp " data-wow-delay="0.5s "><a href="# "><i class="fa fa-pinterest "></i></a></li>
                </ul>
            </div>
            <div class="row m0 menu-rights ">
                <ul class="nav footer-menu ">
                    <li><a href="about.html">About</a></li>
                    <li><a href="terms-of-use.html">Terms of Use</a></li>
                    <li><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li><a href="# ">Mobile App</a></li>
                    <li><a href="presskit.zip ">PressKit</a></li>
                </ul>
                <p>Copyright © 2016. Proland.
                    <br class="small-divide "> All rights reserved</p>
            </div>
        </div>
    </footer>

    <!--========== Javascript Files ==========-->

    <!-- jQuery Latest -->
    <script src="/_ironhome/js/lib/jquery-2.2.0.min.js "></script>

    <!-- Bootstrap JS -->
    <script src="/_ironhome/js/lib/bootstrap.min.js "></script>

    <!-- Plugins -->
    <script src="/_ironhome/vendors/owl-carousel/owl.carousel.js "></script>
    <script src="/_ironhome/vendors/magnific-popup/jquery.magnific-popup.min.js "></script>
    <script src="/_ironhome/vendors/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js "></script>
    <script src="/_ironhome/vendors/bootstrap-select/js/bootstrap-select.min.js "></script>
    <script src="/_ironhome/js/plugins/wow.min.js "></script>

    <!-- Main JS -->
    <script src="/_ironhome/js/main.js"></script>
	@yield('footer')

</body>

</html>