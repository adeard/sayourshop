<style>
        .thumb{display:inline-block;vertical-align:baseline;overflow:hidden;padding-top:64px;height:0;width:64px;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;background-position:0 0;background-repeat:no-repeat;text-decoration:none;color:inherit}
        #port {
            margin: 0.58em 0px;
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
<section class="main">
    <section class="category">
        <div class="container" style="padding: 0px;">
            <div class="row">
                <div class="span12">

                        @if($data['banner'])
                        <?php $banner = unserialize($data['banner']->meta_value);?>
                        <div class="col-lg-12" style="padding: 0px;margin: 0px;">
                            <img src="{{url('photo_banner/'.$banner['banner1'])}}" style="max-width: 100%; padding-bottom: 20px;">
                        </div>
                        @endif

                </div>
                <div class="span2">
                    <!-- Sidebar -->
                    <aside class="sidebar">
                        <div class="children">
                            <div class="box border-top">
                                <div class="hgroup title">
                                    <h3>
                                        <a href="{{url('produk/'.$data['slugcategory']->slug)}}" title="Ready Stock">{{ucwords($data['slugcategory']->name)}}</a>
                                    </h3>
                                </div>

                                @if($data['slugcategory']->subcategories == "1")
                                    <?php $sub=$data['slugcategory']->subcategory; ?>
                                    <div class="category-list secondary">

                                    @foreach($sub as $subcategory)
                                        <li>
                                            <a href="{{ url('produk/'.$data['slugcategory']->slug.'/'.$subcategory->slug) }}" title="Shoes">
                                                <span class="count">{{ $subcategory->is_publish }} </span>
                                                {{ ucwords($subcategory->subname) }}               
                                            </a>
                                        </li>
                                    @endforeach

                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- Price filter -->
                        <div class="price-filter">
                            <div class="box border-top">
                                <div class="hgroup title">
                                    <h3>Filter products</h3>
                                </div>
                                <div style="margin-top:8px">
                                    <h7>Sort By : </h7>
                                    <select class="form-control" id="sort" style="margin-top:8px">
                                        <option selected> - - - </option>
                                        <option value="name">Nama</option>
                                        <option value="price">Termurah</option>
                                        <option value="pricedesc">Termahal</option>
                                        <option value="rating">Rating</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- End class="price-filter" -->                     
                    </aside>
                     <!-- End sidebar -->
                </div>
                <input type="hidden" value="{{$data['slugcategory']->id}}" id="slug_category"></input>
                
                @if($data['slugsubcategory'] != '')
                    <input type="hidden" value="{{$data['slugsubcategory']->id}}" id="slug_subcategory"></input>
                @endif

                <div class="span10" style="text-align: center;">
                    <?php // ============================ Banner 1 ================================= ?>
                    <div class="col-lg-6" style="margin: 0px;padding: 5px;" >
                            <img src="{{url('photo_banner/'.$banner['banner2'])}}" style="max-width: 100%;">
                    </div>
                    <?php // ============================ Banner 2 ================================= ?>
                    <div class="col-lg-6" style="margin: 0px;padding: 5px;" >
                            <img src="{{url('photo_banner/'.$banner['banner3'])}}" style="max-width: 100%;">
                    </div>
                </div>
                <hr>
                <div id="content" style="display:none"></div>
            </div>
        </div>
    </section>
</section>
<?php //Java script for this page  ?>
@section('script')
<script type="text/javascript">
    jQuery(document).ready(function(){
        var category_id = $('#slug_category').val();
        var subcategory_id = $('#slug_subcategory').val();
        
        $.ajax({
            url: (subcategory_id == undefined) ? "{!! url('product_content') !!}" : "{!! url('subproduct_content') !!}" ,
            data: (subcategory_id == undefined) ? {
                category_id: category_id
            } : {
                category_id: category_id,
                subcategory_id: subcategory_id
            },
            method:'POST',
        }).done(function(data){
            $('#content').html(data);
            $('#content').fadeIn();
        });

        $('#sort').change(function(){
            $('#content').css('display', 'none');
            
            var sortby = this.value;
            var category_id = $('#slug_category').val();
            var subcategory_id = $('#slug_subcategory').val();
            
            $.ajax({
                url: "{!! url('sort_product') !!}",
                data: (subcategory_id == undefined) ? {
                    sortby: sortby,
                    category_id: category_id
                } : {
                    sortby: sortby,
                    category_id: category_id,
                    subcategory_id: subcategory_id
                },
                method:'POST',
            }).done(function(data){
                $('#content').html(data);
                $('#content').fadeIn();
            });
        });
    });  
</script>

@stop