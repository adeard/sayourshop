<?php //Css for this page  ?>
    <style>
        .thumb{display:inline-block;vertical-align:baseline;overflow:hidden;padding-top:64px;height:0;width:64px;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;background-position:0 0;background-repeat:no-repeat;text-decoration:none;color:inherit}

        #port {
            margin: 1.5em 0px;
            overflow: hidden;
            position: relative;
            /*width: 700px;*/
            height: 168px;
            padding: 24px 64px;
        }

        .thumbs_index {
            padding: 0 12px;
            /* initial position */
            left: 0;
            /* Put all he thumbs on one line. */
            white-space: nowrap;
        }
        
        .thumbs_index > li {
            display: inline;
            margin-right: 12px;
        }
        
        .img_thumb {
          padding-top: 120px;
          width: 192px;

          -webkit-box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
             -moz-box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
                  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
        }
        .index{list-style:none;margin:0;padding:0}
        
    </style>

<!-- Promos -->
<section class="new-product" style="margin:0px;">
    <div class="container">
        <div class="row" style="text-align: center;">
        <!-- Slider -->
            <div class="flexslider" style="margin:10px;">
                <ul class="slides col-lg-12">
                    
                    <?php $banner = unserialize($data['banner']->meta_value);?>
                    @foreach($banner['slider1'] as $slide1)
                        <li>
                            <img src="{{url('photo_banner/'.$slide1)}}">
                        </li>
                    @endforeach

                </ul>
            </div>
            <!-- End class="flexslider" -->  
            
            <?php // ================ TOP PRODUCT SLIDER ==================  ?>
                @foreach($banner['slider2'] as $slide2)
                    <div class="col-lg-3">
                        <img src="{{url('photo_banner/'.$slide2)}}" style="max-width: 100%;" height="266">
                    </div>
                @endforeach
            <?php // ================ PRODUCT MO READY 2 ==================  ?>

        </div>
    </div>
</section>

<?php // ============================ New Product ================================== ?>
<section class="new-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12 box border-top">
                    <h4 >Produk Terlaris</h4>
                    <div class="product-list isotope">
                        
                        @foreach($data['sold'] as $product)
                            @if($product->status != '')
                                <?php
                                    $image = unserialize($product->image);
                                ?>
                                <li class="standard" data-price="28" style="width: 198px;">
                                    <a href="{{url('produk/'.$product->category->slug.'/'.$product->subcategory->slug.'/'.$product->id)}}" title="{{ucwords($product->name)}}">
                                        <div class="image img-responsive">
                                            <img src="{{url('photo_product/2_'.$image[0])}}" class="primary">
                                        </div>
                                        <div class="title">
                                            <div class="prices">
                                                <span class="price">Rp. {{ number_format($product->price, 0, ",", ".") }}</span>
                                            </div>

                                            <?php if (strlen($product->name) > 30) { ?>
                                                <h3>{{ucwords(substr($product->name, 0, 30))}}...</h3>
                                            <?php }else if(strlen($product->name) < 16) { ?>
                                                <h3>{{ucwords($product->name)}}<br>&nbsp;</h3> 
                                            <?php }else{ ?>
                                                <h3>{{ucwords($product->name)}}</h3>
                                            <?php } ?>

                                        </div>
                                    </a>
                                </li>
                            @endif

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php // ============================ Promo ================================== ?>
<section class="new-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="margin-bottom: 10px;" >
                
                @if($banner['banner1'] != '')
                    <img src="{{url('photo_banner/'.$banner['banner1'])}}" style="max-width: 100%; height: auto;">
                @endif

            </div>
        </div>
    </div>
</section>

<section class="new-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="box border-top" style="margin-bottom:10px;min-height: 210px;">
                    <h4 class="widget-title widget-title ">Produk Terbaru</h4>
                    <div class="product-list isotope">
                        
                        @foreach($data['product'] as $product)
                            @if($product->status == 'publish')
                                <?php
                                    $image = unserialize($product->image);
                                ?>
                                <li class="standard" data-price="28" style="width: 198px;">
                                    <a href="{{url('produk/'.$product->category->slug.'/'.$product->subcategory->slug.'/'.$product->id)}}" title="{{ucwords($product->name)}}">
                                        <div class="image img-responsive">
                                            <img  src="{{url('photo_product/2_'.$image[0])}}" class="primary">
                                        </div>
                                        <div class="title">
                                            <div class="prices">
                                                <span class="price">Rp. {{ number_format($product->price, 0, ",", ".") }}</span>
                                            </div>
                                            <?php if (strlen($product->name) > 30) { ?>
                                                <h3>{{ucwords(substr($product->name, 0, 30))}}...</h3>
                                            <?php }else if(strlen($product->name) < 16) { ?>
                                                <h3>{{ucwords($product->name)}}<br>&nbsp;</h3> 
                                            <?php }else{ ?>
                                                <h3>{{ucwords($product->name)}}</h3>
                                            <?php } ?>
                                        </div>
                                    </a>
                                </li>
                            @endif

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php // ============================ Section 3 ================================== ?>


<section class="new-product" style="margin:0px;">
    <div class="container">
        <div class="row" style="text-align: center;">
        <!-- Slider -->
            <!-- End class="flexslider" --> 
            <div class="col-lg-6" >
                
                    @if($banner['banner2'] != '')
                        <img src="{{url('photo_banner/'.$banner['banner2'])}}" style="max-width: 100%;">
                    @endif

            </div>
            
            <?php // ================ PRODUCT MO READY 2 ==================  ?>
            <div class="col-lg-6" >
                    
                    @if($banner['banner3'] != '')
                        <img src="{{url('photo_banner/'.$banner['banner3'])}}" style="max-width: 100%;">
                    @endif

            </div>
            <?php // ================ READY STOCK ==================  ?>
        </div>
    </div>
</section> 
<!-- End class="home" -->

<?php //Java script for this page  ?>
@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            // Declare parallax on layers
            jQuery('.parallax-layer').parallax({
                mouseport: jQuery("#port"),
                yparallax: false
            });
        });
    </script>
@stop