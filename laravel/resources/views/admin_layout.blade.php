<?php 
  use App\Http\Models\Ask;
  use App\Http\Models\Order;
  
  $data['mailCount'] = Ask::where('status', 0)->count();
  $data['mail'] = Ask::where('status',0)->orderBy('id', 'DESC')->get();
  $data['paymentCount'] = Order::where('order_status', 'Telah Dibayar')->count();
  $data['readyCount'] = Order::where('no_resi', '')->where('order_status', 'Lunas')->count();
  $data['notifCount'] = $data['paymentCount'] + $data['readyCount'];
  $now = Date("Y-m-d H:i:s");
  $now = new DateTime($now);

 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <title>{!! $data['title'] !!}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <?php // ============ CSS ============ ?>

    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->

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
          <span class="logo-mini">ADM</span>
          <!-- logo for regular state and mobile devices -->
          <!-- <span class="logo-lg"><b>Sayour</b>SHOP</span> -->
          <span class="logo-lg"><b>Admin Panel</b></span>
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
                  <span class="label label-success"><?php if ($data['mailCount'] > 0){echo $data['mailCount'];}else{echo "";} ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have {{ $data['mailCount'] }} new messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <?php $i=0;foreach($data['mail'] as $mail): 
                        if($i == 6 ){
                          break;
                        }
                      ?>
                      <li style="<?= $mail->status == 1 ? "background-color:rgba(0,0,0,0.05);" : "" ?>"><!-- start message -->
                        <a href="{{ url('/master/message/view') }}/{{ $mail->id }}">
                          <div class="pull-left">
                            {!! Html::image('assets/image/user-image.png','user image', array('class' => 'img-circle')) !!}
                          </div>
                          <h4>
                            <?php // ==================== Type ======================= ?>
                            {{ $mail->type }}
                            <small>
                            <i class="fa fa-clock-o"></i> 
                              <?php // ==================== Penghitungan waktu selisih ======================= ?>
                              <?php 
                                $date = strtotime($mail->created_at);
                                $date = date("Y-m-d H:i:s", $date);
                                $date = new DateTime($date); 
                                $diff  = $date->diff($now); 
                                if($diff->d == 0){
                                  if($diff->h == 0){
                                    if($diff->i == 0){
                                      echo 'Less than a minute ago';
                                    }else{
                                      echo $diff->i . ' minutes ago';
                                    }                                   
                                  }else{
                                    echo $diff->h . ' hours ago';
                                  }
                                }else{
                                  echo $diff->d . ' days ago';
                                }
                              ?>
                              <?php // ==================== END Penghitungan waktu selisih ======================= ?>
                            </small>
                          </h4>
                          <?php // ==================== isi pesan small ======================= ?>
                          <p>
                          <?php 
                            if (strlen($mail->ask) <= 20)
                                echo $mail->ask;
                            else
                                echo substr($mail->ask, 0, 20) . '...';
                          ?>
                          </p>
                        </a>
                      </li><!-- end message -->
                      <?php $i++;endforeach; ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="{{ url('/master/message/list') }}">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  @if($data['notifCount'] >= 1)
                  <span class="label label-danger">{{$data['notifCount']}}</span>
                  @endif
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have {{$data['notifCount']}} notification(s)</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="{{ url('/master/transaction/pembayaran') }}">
                          <i class="fa fa-money text-green"></i> {{$data['paymentCount']}} Konfirmasi Pembayaran Baru
                        </a>
                      </li>
                      <li>
                        <a href="{{ url('/master/transaction/order') }}">
                          <i class="fa fa-truck text-aqua"></i> {{$data['readyCount']}} Pesanan Siap Kirim
                        </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  @if(Sentinel::getUser()->image != '')
                    <img src="{{url('photo_profile/'.Sentinel::getUser()->image)}}" class="user-image">
                  @else
                    {!! Html::image('assets/image/user-image.png','user image', array('class' => 'user-image')) !!}
                  @endif
                  <span class="hidden-xs">{{ucwords(Sentinel::getUser()->first_name)}} {{ucwords(Sentinel::getUser()->last_name)}}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                   @if(Sentinel::getUser()->image != '')
                    <img src="{{url('photo_profile/'.Sentinel::getUser()->image)}}" class="img-circle">
                  @else
                    {!! Html::image('assets/image/user-image.png','user image', array('class' => 'img-circle')) !!}
                  @endif
                    <p>
                      {{ucwords(Sentinel::getUser()->first_name)}} {{ucwords(Sentinel::getUser()->last_name)}}
                      <small>Admin</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div align="center">
                      <a href="{{ url('logout') }}" class="btn btn-default btn-flat">Sign out</a>
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
              @if(Sentinel::getUser()->image != '')
                <img src="{{url('photo_profile/'.Sentinel::getUser()->image)}}" class="img-circle">
              @else
                {!! Html::image('assets/image/user-image.png','user image', array('class' => 'img-circle')) !!}
              @endif
            </div>
            <div class="pull-left info">
              <p>{{ucwords(Sentinel::getUser()->first_name)}} {{ucwords(Sentinel::getUser()->last_name)}}</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
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
                <i class="fa fa-gears"></i>
                <span>Setting</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('banner_list') }}"><i class="fa fa-circle-o text-yellow"></i> Banner </a></li>
                <li><a href="{{ url('/master/setting/bank_account') }}"><i class="fa fa-circle-o text-yellow"></i> Bank Account </a></li>
                <li><a href="{{ url('/master/setting/coupon') }}"><i class="fa fa-circle-o text-yellow"></i> Coupon </a></li>
                <li><a href="{{ url('/master/setting/category/list') }}"><i class="fa fa-circle-o text-yellow"></i> Category </a></li>
                <li><a href="{{ url('/master/setting/subcategory/list') }}"><i class="fa fa-circle-o text-yellow"></i> Subcategory </a></li>
              </ul>
            </li>
            <li class="{{ Request::segment(2) === 'transaction' ? 'active' : null }} treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>Transaction</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/transaction/order') }}"><i class="fa fa-circle-o text-yellow"></i> Order </a></li>
                <li><a href="{{ url('/master/transaction/pembayaran') }}"><i class="fa fa-circle-o text-yellow"></i> Konfirmasi Pembayaran </a></li>
              </ul>
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
            <li class="{{ Request::segment(2) === 'distributor' ? 'active' : null }} treeview">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>Distributor</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ url('/master/distributor/list') }}"><i class="fa fa-circle-o text-yellow"></i> List </a></li>
                <li><a href="{{ url('/master/distributor/create') }}"><i class="fa fa-circle-o text-aqua"></i> Create </a></li>
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

    @yield('js')
    

    @foreach( $data['js_assets'] as $key => $assets ) 
      {!! HTML::script($assets) !!}
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
    <script type="text/javascript">
      $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    </script>

    @yield('script')

  </body>
</html>
