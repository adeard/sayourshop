<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{!! $data['title'] !!}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php // ============ CSS ============ ?>

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    @foreach( $data['css_assets'] as $key => $assets ) 
      {!! Html::style($assets) !!}
    @endforeach

    @yield('style')

    <?php // ============ END CSS ======== ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition fixed skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('/master') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>S</b>LTE</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Sayour</b>SHOP</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            {!! Html::image('assets/img/user2-160x160.jpg','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            {!! Html::image('assets/img/user3-128x128.jpg','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            {!! Html::image('assets/img/user4-128x128.jpg','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            Developers
                            <small><i class="fa fa-clock-o"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            {!! Html::image('assets/img/user3-128x128.jpg','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            {!! Html::image('assets/img/user4-128x128.jpg','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Create a nice theme
                            <small class="pull-right">40%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">40% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Some task I need to do
                            <small class="pull-right">60%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">60% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Make beautiful transitions
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img>
                  <span class="hidden-xs">{{ucwords(Sentinel::getUser()->first_name)}} {{ucwords(Sentinel::getUser()->last_name)}}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    {!! Html::image('assets/img/user2-160x160.jpg','user image', array('class' => 'img-circle')) !!}
                    <p>
                      Alexander Pierce - Web Developer
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="#" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              {!! Html::image('assets/img/user2-160x160.jpg','user image', array('class' => 'img-circle')) !!}
            </div>
            <div class="pull-left info">
              <p>{{ucwords(Sentinel::getUser()->first_name)}} {{ucwords(Sentinel::getUser()->last_name)}}</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          
          <?php //=================================== NAVIGATION ==================================== ?>

          <ul class="sidebar-menu">
            <li class="header">MENU NAVIGATION</li>
            <li class="<?php if(Request::segment(1) == 'master' and Request::segment(2) == NULL){echo 'active';} ?> treeview">
              <a href="{{ url('/master') }}">
                <i class="fa fa-home"></i> <span>Home</span>
              </a>
            </li>
            <li class="{{ Request::segment(2) === 'setting' ? 'active' : null }} treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Setting</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/setting/bank_account') }}"><i class="fa fa-circle-o text-yellow"></i> Bank Account </a></li>
                <li><a href="{{ url('/master/setting/category/list') }}"><i class="fa fa-circle-o text-yellow"></i> Category </a></li>
                <li><a href="{{ url('/master/setting/subcategory/list') }}"><i class="fa fa-circle-o text-yellow"></i> Subcategory </a></li>
              </ul>
            </li>
            <li class="{{ Request::segment(2) === 'transaction' ? 'active' : null }} treeview">
            <li>
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Transaction</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/transaction/order') }}"><i class="fa fa-circle-o text-yellow"></i> Order </a></li>
                <li><a href="{{ url('/master/transaction/pembayaran') }}"><i class="fa fa-circle-o text-yellow"></i> Konfirmasi Pembayaran </a></li>
                <li><a href="{{ url('/master/user/create') }}"><i class="fa fa-circle-o text-aqua"></i> Create </a></li>
              </ul>
            </li>
            <li class="{{ Request::segment(2) === 'order' ? 'active' : null }} treeview">
              <a href="{{ url('/master') }}">
                <i class="fa fa-shopping-cart"></i>
                <span>List Order</span>
                <span class="label label-warning pull-right">4 New</span>
              </a>
            </li>
            <li class="{{ Request::segment(2) === 'user' ? 'active' : null }} treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>User</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/user/list') }}"><i class="fa fa-circle-o text-yellow"></i> List </a></li>
                <li><a href="{{ url('/master/user/create') }}"><i class="fa fa-circle-o text-aqua"></i> Create </a></li>
              </ul>
            </li>
            <li class="{{ Request::segment(2) === 'produk' ? 'active' : null }} treeview">
              <a href="#">
                <i class="fa fa-shopping-basket"></i>
                <span>Product</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/produk/list') }}"><i class="fa fa-circle-o text-yellow"></i> List </a></li>
                <li><a href="{{ url('/master/produk/create') }}"><i class="fa fa-circle-o text-aqua"></i> Create </a></li>
              </ul>
            </li>
            
            <?php /*
            <li><a href="{{ url('/master/setting/category/list') }}"><i class="fa fa-circle-o text-yellow"></i> Category </a></li>
            <li><a href="{{ url('/master/setting/subcategory/list') }}"><i class="fa fa-circle-o text-yellow"></i> Subcategory </a></li>
            */ ?>
          </ul>

          <?php //================================ END NAVIGATION ==================================== ?>

        </section>
        <!-- /.sidebar -->
      </aside>

      <?php //=====================  Content Wrapper. Contains page content ============================== ?>
      <div class="content-wrapper">

          {!! $content !!}

      </div>
      <?php //=================================  /.content-wrapper ======================================= ?>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <?= date('Y') ?> <a href="http:/sayourshop.com">SayourShop</a>.</strong> All rights reserved.
      </footer>

    </div><!-- ./wrapper -->

    <?php // ========================= JS ==========================?>

    

    @foreach( $data['js_assets'] as $key => $assets ) 
      {!! Html::script($assets) !!}
    @endforeach
    
    <!-- jQuery 2.1.4 -->
    <?php // <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script> ?>
    <!-- jQuery UI 1.11.4 -->
    <!-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->

    <!-- Morris Chart -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->

    <!-- DateRangePicker -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script> -->

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>

    @yield('script')

    <?php /*
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    */ ?>

  </body>
</html>
