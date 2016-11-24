<div class="span10 posts" style="padding-top: 10px;">
    <!-- Products list -->
    <div class="product-list">
        
        @foreach($data['product'] as $product)
            <?php
                $image = unserialize($product->image);
            ?>
            <li class="standard" data-price="28" style="width: 220px;">
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
        @endforeach

    </div>
    <?php $products = $data['product']?>
    <div align="center">
        {!! $products->render() !!}    
    </div>
</div>
<script type="text/javascript">
    $('.pagination a').click(function(e){

        $('#content').css('display', 'none');

        e.preventDefault();

        var url = $(this).attr('href');
        var category_id = $('#slug_category').val();
        var subcategory_id = $('#slug_subcategory').val();
        var sortby = $('#sort').val();

        if (sortby == undefined) {
            var sortby = 'name';
        };
        
        $.ajax({
            url: url ,
            data: (subcategory_id == undefined ) ? {
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

</script>