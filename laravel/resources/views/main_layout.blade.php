@extends('components.layout')
@section('title', $data['title'])
@section('content')

<div class="header">
    <!-- Logo & Search bar -->
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-lg-offset-0 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
                    <div class="logo">
                        <a href="{{url ('/') }}" title="&larr; Back home">
                            <img src="{{asset('assets/image/logo.png')}}" style="max-width: 100%;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
                    <div class="row-fluid">
                        <div class="col-lg-12">
                            <!-- Search -->
                            <div class="search">
                                <div class="qs_s">
                                    <form method="get" action="{{url('cari')}}" />
                                        <input type="text" name="search" id="query" placeholder="Masukkan Nama Barang&hellip;" autocomplete="off" value="" />
                                    </form>
                                </div>
                            </div>
                            <!-- End class="search"-->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5 col-sm-5 col-xs-12" align="center">
                    <h1>
                        <a href="{{url('keranjang')}}"><i class="fa fa-shopping-cart"></i> <span>{{Cart::count()}}</span></a>
                        
                        @if(Sentinel::check())
                            <a href="{{url('dashboard') }}" style="min-width:150px"><i class="fa fa-user"></i> {{ucwords(Sentinel::getUser()->fullname)}}</a>
                            <a href="{{url('logout')}}" style="min-width:150px"><i class="glyphicon glyphicon-log-out"></i> Logout</a>
                        @else
                            <a href="{{url('login_form')}}" style="min-width:150px"><i class="fa fa-user"></i> Login</a> <a href="{{url('daftar')}}" style="min-width:150px">Daftar</a>
                        @endif

                    </h1>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!-- End class="bottom" -->
</div>
<!-- End class="header" -->

<!-- Navigation -->
<nav class="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hidden-xs">
                    <!-- Main menu (desktop) -->
                    <div class="main-menu">
                        <li>
                            <a href="{{ url('/') }}" title="Home" class="title">Home</a>
                        </li>
                        <?php
                            $total_app = count($data['category']);

                            for ($i=0; $i < $total_app ; $i++) {
                        ?>
                            <li>
                                <a href="{{ url('produk/'.$data['category'][$i]->slug) }}">
                                    {{$data['category'][$i]->name}}
                                </a>
                                
                                @if($data['category'][$i]->subcategories == "1")
                                <?php $sub=$data['category'][$i]->subcategory; $category=$data['category'][$i]->slug;?>
                                    <ul class="dropdown-menu">
                                        
                                        @foreach($sub as $key)
                                        <li>
                                            <a href="{{ url('produk/'.$category.'/'.$key->slug)}}" title={{ucwords($key->subname)}}>
                                                   {{ucwords($key->subname)}}
                                            </a>
                                        </li>
                                        @endforeach

                                    </ul>
                                @endif

                            </li>
                        <?php
                            }
                        ?>
                        <li>
                            <a href="{{url('cek_order_form')}}">Cek Order</a>
                        </li>
                        <li>
                            <a href="{{url('konfirmasi_pembayaran')}}">Konfirmasi Pembayaran</a>
                        </li>
                        
                    </div>
                    <!-- End class="main-menu" -->
                </div>
                <div class=" col-sm-12 visible-xs-* hidden-lg hidden-sm hidden-md">
                    <!-- Main menu (mobile) -->
                    <select class="form-control" onchange="location = this.value;">
                        <option value="" selected="selected" />Go to&hellip;
                        <option value="{{url('/')}}" />Home
                        @foreach($data['category'] as $category)
                            <option value="{{url('produk/'.$category->slug)}}" />{{ucwords($category->name)}}
                        @endforeach
                        <option value="{{url('check_order')}}" />Cek Order
                        <option value="{{url('contact')}}" />Kontak Kami
                    </select>
                </div>
            </div>
            <div class="col-lg-3 visible-desktop"></div>
        </div>
    </div>
</nav>
<!-- End class="navigation" -->

{!! $content !!}

<!-- Twitter bar -->
<!-- End class="twitter-bar" -->
<!-- Footer -->
<div class="footer" style="margin-top: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <!-- Support -->
                <div class="support" style="min-height:200px">
                    <h6>Dukungan</h6>
                    <div class="list-chevron links">
                        <li>
                            <a href="{{url('contact')}}" title="Kontak Kami" class="title">Kontak Kami</a>
                        </li>
                        <li>
                            <a href="{{url('cek_order_form')}}" title="Cek Order" class="title">Cek Order</a>
                        </li>
                        <li>
                            <a href="{{url('konfirmasi_pembayaran')}}" title="Konfirmasi Pembayaran" class="title">Konfirmasi Pembayaran</a>
                        </li>
                        <li>
                            <a href="{{url('terms_conditions')}}">Syarat & Ketentuan</a>
                        </li>
                        <li>
                            <a href="{{url('privacy_policy')}}">Kebijakan Privasi</a>
                        </li>
                    </div>
                </div>
                <!-- End class="support" -->
            </div>
            <div class="col-lg-3">
                <!-- Categories -->
                <div class="categories" style="min-height:200px">
                    <h6>Kategori</h6>

                    <div class="list-chevron links">
                        <?php
                            $total_app = count($data['category']);

                            for ($i=0; $i < $total_app ; $i++) {
                        ?>
                            <li>
                                <a href="{{ url('produk/'.$data['category'][$i]->slug) }}">
                                    {{ucwords($data['category'][$i]->name)}}
                                </a>
                                
                                @if($data['category'][$i]->subcategories == "1")
                                    <?php $tes=$data['category'][$i]->subcategory; $category=$data['category'][$i]->slug;?>
                                    <ul class="dropdown-menu">
                                        
                                        @foreach($tes as $key)
                                        <li>
                                            <a href="{{ url('produk/'.$category.'/'.$key->slug)}}" title="{{ucwords($key->subname)}}">
                                                {{ucwords($key->subname)}}
                                            </a>
                                        </li>
                                        @endforeach

                                    </ul>
                                @endif

                            </li>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <!-- End class="categories" -->
                <!-- Pay with confidence -->
                <div class="confidence" hidden="true">
                    <h6>Pay with confidence</h6>

                    {!! Html::image('assets/image/payment_image/bca500.png', '', array('style' => 'width:150px')) !!}
                    {!! Html::image('assets/image/payment_image/bni300x200.png', '', array('style' => 'width:150px')) !!}
                </div>
                <!-- End class="confidence" -->
            </div>
            <div class="col-lg-3">
                <!-- Newsletter subscription -->
                <div style="min-height:200px">
                    <div class="newsletter">
                        <h6>Subscribe</h6>
                    </div>
                    <form class="form-horizontal" enctype="multipart/form-data" method="post">
                        <div id="alert_subscribe" hidden="true"></div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="email_subscribe" placeholder="Email..." >
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" style="height:34px" id="submit_subscribe" name="submit_subscribe"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                            </span>
                        </div>
                    </form>
                    <div class="newsletter">
                        <p style="margin-top:20px">Daftarkan email kamu untuk mendapatkan berita terbaru kami langsung</p>
                    </div>
                </div>
                <!-- End class="newsletter" -->
            </div>
            <!-- Social icons -->
            <div class="social col-md-3">
                <h6>Hubungi kami</h6>
                <div class="social-icons">
                    <li>
                        <a class="twitter" href="#" title="Twitter">Twitter</a>
                    </li>
                    <li>
                        <a class="facebook" href="#" title="Facebook">Facebook</a>
                    </li>
                    <li>
                        <a class="googleplus" href="#" title="Google+">Google+</a>
                    </li>
                    <li>
                        <a class="instagram" href="#" title="Instagram">Instagram</a>
                    </li>
                </div>
            </div>
            <!-- End class="social" -->
        </div>
    </div>
</div>
<!-- End id="footer" -->
<!-- Credits bar -->
<div class="credits">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p>&copy; <?= date('Y')?> <a href="http://themeforest.net/item/la-boutique-responsive-ecommerce-template/5573130?ref=Tfingi" title="La Boutique">La Boutique</a> &middot; <a href="#" title="Terms &amp; Conditions">Terms &amp; Conditions</a> &middot; <a href="#" title="Privacy policy">Privacy policy</a> &middot; All Rights Reserved. </p>
            </div>
        </div>
    </div>
</div>
<!-- End class="credits" -->
<script type="text/javascript">
    $('#submit_subscribe').click(function(){
        var email_subscribe = $('#email_subscribe').val();
        
        $('#alert_subscribe').hide('slow');
        
        $.ajax({
            url: "{!! url('subscribe') !!}",
            data: {
                email: email_subscribe
            },
            method:'POST',
        }).done(function(data){
            if (data == 'success') {
                $('#alert_subscribe').html('<div class="alert alert-success">Email berhasil didaftarkan</div>');
            } else {
                $('#alert_subscribe').html('<div class="alert alert-danger">Email gagal didaftarkan</div>');
            }
            
            $('#alert_subscribe').show('slow');
            $('#email_subscribe').val('');
        });
    });
</script>
@stop