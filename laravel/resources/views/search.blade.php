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
                    <div class="box">
                        <input type="hidden" id="search_category" value=""></input>
                        <p>
                            <h6> 
                                Hasil Pencarian "<b>{{$data['name']}}</b>"
                                <input type="hidden" id="pencarian" value="{{$data['name']}}"></input>
                                <div class="pull-right">
                                    <select class="form-control" id="sort">
                                        <option value="name">Urut Berdasarkan</option>
                                        <option value="name">Nama</option>
                                        <option value="price">Termurah</option>
                                        <option value="pricedesc">Termahal</option>
                                        <option value="rating">Rating</option>
                                    </select>
                                </div>
                            </h6>
                        </p>
                    </div>
                </div>
                <div class="span2">
                    <aside class="sidebar">
                        <div class="children">
                            <div class="box border-top">
                                <div class="hgroup title">
                                    <h3>
                                        <a href="#" title="Kategori">Kategori</a>
                                    </h3>
                                </div>
                                <div class="category-list secondary">
                                    @foreach($data['category'] as $category)
                                        <li>
                                            <a href="javascript:void(0)" title="{{ucwords($category->name)}}" class="category" id="{{$category->id}}">
                                                {{ucwords($category->name)}}            
                                            </a>
                                        </li>
                                    @endforeach
                                </div>
                            </div>
                        </div>           
                    </aside>
                </div>
                <div class="span10" id="search_content"></div>
            </div>
        </div>
    </section>
</section>

@section('script')
<script type="text/javascript">
    jQuery(document).ready(function(){
        var search = $('#pencarian').val();

        jQuery('.parallax-layer').parallax({
          mouseport: jQuery("#port"),
          yparallax: false
        });

        $.ajax({
            url: "{!! url('ajax_cari') !!}",
            data: {
                search: search
            },
            method:'GET',
        }).done(function(data){
            $('#search_content').html(data);
        });

        $('a.category').click(function(){
            var category = this.id;

            $('#search_category').val(category); 

            $.ajax({
                url: "{!! url('ajax_category_search') !!}",
                data: {
                    search: search,
                    category_id: category
                },
                method:'GET',
            }).done(function(data){
                $('#search_content').html(data);
            });
            
        });

        $('#sort').change(function(){
            var sortby = this.value;
            var category_id = $('#search_category').val();
            var search = $('#pencarian').val();

            $.ajax({
                url: "{!! url('sort_search') !!}",
                data: {
                    sortby: sortby,
                    search: search,
                    category_id: category_id
                },
                method:'GET',
            }).done(function(data){
                $('#search_content').html(data);
            });

        });
    });
</script>
@stop