<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="{{ asset('/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('/admin/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('/admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('/admin/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('/admin/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('/admin/build/css/custom_2.min.css') }}" rel="stylesheet">
	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.css" rel="stylesheet">
	<!-- Switchery -->
    <link href="{{ asset('/admin/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
	
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/" class="site_title"><span>Dashboard</span></a>
            </div>

            <div class="clearfix"></div>

           

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
				
                  <!--<li><a href="/admin/reports"><i class="fa fa-home"></i> Reports </a></li>-->
				
				
				
				<? if(Auth::user()->user_type == 2){ ?>
				
				  <li><a><i class="fa fa-flag"></i> Property <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
					  <li><a href="/manager/property-list">Property List</a></li>
                    </ul>
                  </li>
				  
				  <li><a><i class="fa fa-flag"></i> Documents <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/manager/documents-categories">Documents Categories</a></li>
					  <li><a href="/manager/documents-list">Documents List</a></li>
                    </ul>
                  </li>
				
				<? } ?>
				
				
				
				<? if(Auth::user()->user_type == 21){ ?>
				
				  <li><a href="/admin/property-list"><i class="fa fa-list"></i>Property List</a></li>
				  
                  <li><a href="/admin/transaction-list"><i class="fa fa-money"></i>Transactions</a></li>
				  
                  <li><a href="/admin/transaction-sale-list"><i class="fa fa-tags"></i>Resell Requests</a></li>
				  
                  <li><a href="/admin/blog-pages"><i class="fa fa-clone"></i> Web pages</a></li>
				  
				  <li><a href="/admin/bank-data-list"><i class="fa fa-calculator"></i> Bank Accounts</a></li>
				  
				   <li><a><i class="fa fa-folder"></i> Documents <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/admin/documents-categories">Documents Categories</a></li>
					  <li><a href="/admin/documents-list">Documents List</a></li>
                    </ul>
                  </li>
				  
				  <? } ?>
				  
				  <li><a href="/common/user-list"><i class="fa fa-users"></i> Users </a></li>
				  
				  
                </ul>
              </div>
              <div class="menu_section">
                <h3>Additional</h3>
                <ul class="nav side-menu">
					
					
					
                <li><a><i class="fa fa-gear"></i> Settings <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
						<li><a href="/common/settings/">Profile</a></li>
						<!--<li><a href="/admin/api-settings">API</a></li>-->
                    </ul>
                </li>
				
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->
<?/*
            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
			*/?>
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?= Auth::user()->email ?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="/auth/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

		
		
        <!-- page content -->
        <div class="right_col" role="main">
		
		
@yield('content')
		
		
		
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Peakk Development
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('/admin/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('/admin/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('/admin/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('/admin/vendors/nprogress/nprogress.js') }}"></script>
	<!-- Dropzone.js -->
    <script src="{{ asset('/admin/vendors/dropzone/dist/min/dropzone.min.js') }}"></script>
	<!-- Chart.js') }} -->
    <script src="{{ asset('/admin/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <!-- gauge.js') }} -->
    <script src="{{ asset('/admin/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ asset('/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('/admin/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Skycons -->
    <script src="{{ asset('/admin/vendors/skycons/skycons.js') }}"></script>
    <!-- Flot -->
    <script src="{{ asset('/admin/vendors/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('/admin/vendors/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('/admin/vendors/Flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('/admin/vendors/Flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('/admin/vendors/Flot/jquery.flot.resize.js') }}"></script>
    <!-- Flot plugins -->
    <script src="{{ asset('/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
    <script src="{{ asset('/admin/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('/admin/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <!-- DateJS -->
    <script src="{{ asset('/admin/vendors/DateJS/build/date.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('/admin/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ asset('/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('/admin/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/admin/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<!-- Switchery -->
    <script src="{{ asset('/admin/vendors/switchery/dist/switchery.min.js') }}"></script>
	
    <!-- Custom Theme Scripts -->
    <script src="{{ asset('/admin/build/js/custom.min.js') }}"></script>
	
<script>

$(document).ready(function(){
	$(".right_col").css("min-height",$("body").outerHeight());
})

</script>

@yield('footer')

  </body>
</html>