<section class="main">
    <section class="product">
        <!-- Product info -->
        <section class="product-info">
            <div class="container" style="padding-left: 0px;padding-right: 0px;">
           
            @if(session('success'))
                <div class="alert alert-success">
                    {{session('success')}} <a href="{{url('keranjang')}}"><button type="button" class="btn btn-mini btn-primary">Lihat Keranjang</button></a>
                </div>
            @elseif(session('failed'))
                <div class="alert alert-danger">
                    {{session('failed')}} 
                </div>
            @endif

                <div class="row">
                    <div class="span4">
                        <div class="product-images">
                            <div class="box">

                                <?php
                                    $image = unserialize($data['product']->image);
                                ?>

                                <div class="primary">
                                    <img src="{{url('photo_product/'.$image[0])}}" data-zoom-image="{{url('photo_product/'.$image[0])}}" alt="Chaser Overalls" style="max-width:100%;position:relative!IMPORTANT;" />
                                </div>
                                <div class="thumbs" id="gallery" align="center">
                                    <ul class="thumbs-list">

                                        <?php if($image == null): else: ?>
                                        @foreach($image as $images)
                                            <li>
                                                <a class="active" href="#" data-image="{{url('photo_product/'.$images)}}" title="{{$data['product']->name}}" data-zoom-image="{{url('photo_product/'.$images)}}">
                                                    <img style="max-width: 100%;" src="{{url('photo_product/'.$images)}}" alt="{{$data['product']->name}}" />
                                                </a>
                                            </li>
                                        @endforeach
                                        <?php endif; ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span8">
                        <!-- Product content -->
                        <div class="product-content">
                            <div class="box">
                                <!-- Tab panels' navigation -->
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#product" data-toggle="tab">
                                            <i class="icon-reorder icon-large"></i>
                                            <span class="hidden-phone">Produk</span>
                                        </a>
                                    </li>
                                    <li>
                                         <a href="#ratings" data-toggle="tab">
                                            <i class="icon-heart icon-large"></i>
                                            <span class="hidden-phone">Testimonial ({{count($data['count'])}})</span>
                                        </a>
                                    </li>
                                    <li>
                                         <a href="#ask" data-toggle="tab">
                                            <i class="icon-heart icon-large"></i>
                                            <span class="hidden-phone">Tanya Produk</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- End Tab panels' navigation -->
                                <!-- Tab panels container -->
                                <div class="tab-content">
                                    <!-- Product tab -->
                                    <div class="tab-pane active" id="product">
                                        <form enctype="multipart/form-data" action="{{url('order')}}" method="post" />
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <input type="hidden" name="name" value="{{ucwords($data['product']->name)}}">
                                            <input type="hidden" name="price" value="{{$data['product']->price}}">
                                            <input type="hidden" name="id" value="{{$data['product']->id}}">
                                            <div class="details">
                                                <h1>{{ucwords($data['product']->name)}}</h1>
                                                <div class="prices">
                                                    <span class="price">Rp. {{ number_format($data['product']->price, 0, ",", ".") }}</span>
                                                    <div class="rating" style="color: #1abc9c;">
                                                        
                                                        @if($data['product']->rating > 0)
                                                            <?php 
                                                                $stars = $data['product']->rating/count($data['product']->reviews);
                                                                $half = $data['product']->rating % count($data['product']->reviews);
                                                                for ($i=0; $i < floor($stars); $i++) { 
                                                            ?>
                                                                    <i class="fa fa-star fa-lg"></i>
                                                            <?php
                                                                }
                                                            ?>
                                                            @if($half > 0)
                                                                <i class="fa fa-star-half-o fa-lg"></i>
                                                            @endif
                                                            {{number_format($stars,1)}} / 5
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <div class="sku">
                                                        <i class="icon-pencil"></i>
                                                        <span rel="tooltip" title="Terjual">Terjual</span>
                                                    </div>
                                                    <div class="categories">
                                                        <span><i class="icon-tags"></i><a href="#" title="Dresses">{{$data['product']->sold}}</a></span>
                                                    </div>
                                                    
                                                    <div class="sku">
                                                        <i class="icon-pencil"></i>
                                                        <span rel="tooltip" title="Berat barang">Berat</span>
                                                    </div>
                                                    <div class="categories">
                                                        <span id="weight" name="weight"><i class="icon-tags"></i><a href="#" title="Dresses">{{$data['product']->weight}} g</a></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="short-description">
                                                <textarea class="form-control" rows="15" readonly="true" style="border:none;background-color:white;">{{$data['product']->desc}}</textarea>
                                            </div>
                                            <div class="options">
                                                <div class="row-fluid">
                                                    <div class="span6">
                                                        <?php  $properties = unserialize($data['product']->properties);?>
                                                        <div class="control-group">
                                                            <label for="product_options" class="control-label">Warna</label>
                                                            <div class="controls">
                                                                <select id="warna" name="warna" class="form-control">
                                                                    
                                                                    @foreach($properties as $key => $value)
                                                                        <option name="{{$key}}" value="{{$key}}" />{{$key}}
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="size_product"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="add-to-cart">
                                                <button class="btn btn-primary btn-small" type="submit" id="add-to-crot">
                                                    <i class="icon-plus"></i> Beli
                                                </button>
                                                <button class="btn btn-primary btn-small" type="button" id="wishlist">
                                                    <i class="icon-plus"></i> Jadikan Favorit
                                                </button>
                                            </div>
                                        </form>                     
                                    </div>
                                    <!-- End id="product" -->
                                    <!-- Ratings tab -->
                                    <div class="tab-pane" id="ratings">
                                    </div>
                                    <!-- End id="ratings" -->
                                    <!-- Ratings tab -->
                                    <div class="tab-pane" id="ask">
                                        <div class="alert alert-success" id="alert_message" hidden="true"></div>
                                        <div class="details">
                                            <article class="rating-item">
                                                <div class="row-fluid">
                                                    <div class="span6">
                                                        <label>Email</label>
                                                        
                                                        @if(Sentinel::check())
                                                            <input class="form-control" type="text" id="email" value="{{Sentinel::getUser()->email}}"></input>
                                                        @else
                                                            <input class="form-control" type="text" id="email"></input>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="row-fluid">
                                                    <div class="span12">
                                                        <label>Pesan</label>
                                                        <textarea rows="5" id="message"></textarea>
                                                    </div>
                                                </div>
                                            </article>
                                            <hr />
                                            <div class="add-to-cart">
                                                <button class="btn btn-primary btn-large" type="submit" id="send_message">
                                                    <i class="icon-plus"></i> Kirim
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End id="ratings" -->          
                                </div>                                            
                                <!-- End tab panels container -->            
                            </div>
                        </div>                                    
                        <!-- End class="product-content" -->
                    </div>
                </div>
            </div>
        </section>
        <!-- End class="product-info" -->

        <section>
            <div class="container">
                <div class="col-lg-12">
                    <div id="disqus_thread"></div>
                    <script>
                    /**
                    * RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                    * LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                    */
                    /*
                    var disqus_config = function () {
                    this.page.url = PAGE_URL; // Replace PAGE_URL with your page's canonical URL variable
                    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                    };
                    */
                    (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');

                    s.src = '//sayourshopcom.disqus.com/embed.js';

                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                </div>
            </div>
        </section>
        <!-- Related products -->
        <section class="product-related">
            <div class="container" style="padding: 0px;">
                <div class="span12" style="margin-left: 0px;">
                    <h5>Produk kami lainnya&hellip;</h5>
                    <div class="product-list isotope">
                        
                        @foreach($data['related'] as $related)
                            <?php
                                $image = unserialize($related->image);
                            ?>
                            <li class="standard" data-price="160">
                                <a href="{{url('produk/'.$related->category->slug.'/'.$related->subcategory->slug.'/'.$related->id)}}" title="{{$related->name}}">
                                    <div class="image">
                                        <img class="primary" src="{{url('photo_product/2_'.$image[0])}}" alt="{{$related->name}}" />
                                    </div>
                                    <div class="title">
                                        <div class="prices"><span class="price">Rp. {{ number_format($related->price, 0, ",", ".") }}</span></div>
                                        <h3>{{$related->name}}</h3>
                                    </div>
                                </a>
                            </li>
                        @endforeach

                    </div>
                </div>
            </div>  
        </section>                    
        <!-- End class="products-related" -->
        <!-- Added to cart modal window -->
        <div name="added" id="added" class="modal hide fade" tabindex="-1">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="hgroup title">
                    <h3>You're one step closer to owning this product!</h3>
                    <h5>"Chaser Overalls" has been added to your cart</h5>
                </div>
            </div>
            <div class="modal-footer">  
                <div class="pull-right">                
                    <a href="cart.html" class="btn btn-primary btn-small">
                        Go to cart &nbsp; <i class="icon-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End id="added" -->
    </section>
</section>

@section('script')
    <script type="text/javascript">
    
        <?php if($data['product']->status == 'unactive'): ?>
            $("#add-to-crot").prop('disabled', true);
        <?php endif; ?>

        function size_product(product_id, color){

            $.ajax({
                url: "{!! url('size_product') !!}",
                data: {
                    id: product_id,
                    color: color
                },
                method:'POST',
                success: function(data) {
                        $('#size_product').html(data);
                    }
            });
        }

        function review(product_id){

            $.ajax({
                url: "{!! url('review_content') !!}",
                data: {
                    product_id: product_id
                },
                method:'POST',
            }).done(function(data){
                $('#ratings').html(data);  
            });
        }

        $('#send_message').click(function(){
            var email = $('#email').val();
            var message = $('#message').val();
            var product_id = $('input[name=id]').val();
            
            $.ajax({
                url: "{!! url('send_message') !!}",
                data: {
                    email: email,
                    message: message,
                    product_id: product_id
                },
                method: 'POST'
            }).done(function(result){
                $('#alert_message').html("Pesan berhasil dikirim");
                $('#alert_message').show('slow');
            });
            
        });

        $(document).ready(function(){
            var color = $('#warna option:selected').val();
            var product_id = $('input[name=id]').val();

            $('#wishlist').click(function(){
                var product_id = $('input[name=id]').val();

                $.ajax({
                url: "{!! url('wishlist') !!}",
                data: {
                    product_id: product_id
                },
                method:'POST',
                success: function(data) {
                        alert('Produk berhasil ditambahkan ke wishlist');
                    }
                });

            });

            size_product(product_id, color);
            review(product_id);

            $("#quantity").on("keydown", function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
            
    	    $("#zoom-image").elevateZoom({
                cursor: "pointer", 
                galleryActiveClass: "active", 
                scrollZoom : true , 
                imageCrossfade: true, 
                loadingIcon: "http://www.elevateweb.co.uk/spinner.gif"
            });

            $('#warna').click(function(){
                var color = $('#warna option:selected').val();
                var product_id = $('input[name=id]').val();

                $.ajax({
                    url: "{!! url('size_product') !!}",
                    data: {
                        id: product_id,
                        color: color
                    },
                    method:'POST',
                    success: function(data) {
                        $('#size_product').html(data);
                    }
                });

            });

        });
    </script>
    <script id="dsq-count-scr" src="//sayourshop.disqus.com/count.js" async></script>
@stop