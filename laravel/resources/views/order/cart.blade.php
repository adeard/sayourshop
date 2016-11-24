 <!-- Checkout / Billing Address -->
<section class="checkout" style="margin-top:20px">
    <div class="container" style="padding: 0px;">
        <div class="row">
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif

            @if(session('fail'))
                <div class="alert alert-danger">
                    {{session('fail')}}
                </div>
            @endif

            <div class="span9">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box" style="padding-top: 0px;">
                    <!-- Checkout content -->
                    <div id="tab-content">
                        <div class="tab-pane active" id="cart" role="presentation">
                            <form enctype="multipart/form-data" action="{{ url('update_order') }}" method="get" />
                                <div class="box-header" style="background-color: #1abc9c;padding: 10px;">
                                    <h3 style="color: white;">Keranjang Belanja</h3>
                                    <h5 style="color: white">Anda memiliki <strong>{{ Cart::count() }}</strong> barang di keranjang</h5>
                                </div>
                                <div class="box-content">
                                    <div class="cart-items table-responsive">
                                        <table class="styled-table">
                                            <thead>
                                                <tr>
                                                    <th class="col_product text-left">Produk</th>
                                                    <th class="col_properties text-left">Properti</th>
                                                    <th class="col_remove text-right">&nbsp;</th>
                                                    <th class="col_qty text-right">Jumlah</th>
                                                    <th class="col_single text-right">Harga</th>
                                                    <th class="col_total text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach($data['cart'] as $product)                                 
                                                    <tr>
                                                        <td class="col_product text-left">
                                                            <h5>
                                                                <a href="{{ url('detail/'.$product->id) }}">{{$product->name}}</a>
                                                            </h5>
                                                        </td>
                                                        <td class="col_properties text-left">
                                                            
                                                            @foreach( $product->options as $value)
                                                                {{ucwords($value)}}&nbsp;
                                                            @endforeach

                                                        </td>
                                                        <td class="col_remove text-right">
                                                            <a href="javascript:void(0)" class="delete" name="{{$product->rowid}}">
                                                                <span rel="tooltip" title="Hapus"><i class="fa fa-trash icon-large"></i></span>
                                                                <input type="hidden" name="row_id" value="{{$product->rowid}}" />
                                                            </a>
                                                        </td>
                                                        <td class="col_qty text-right">
                                                            <input type="text" id="quantity_{{$product->rowid}}" value="{{$product->qty}}" />
                                                            <span rel="tooltip" title="Perbaharui"><button class="btn btn-mini quantity_update" type="button" id="{{$product->rowid}}">
                                                                <i class="fa fa-undo"></i>
                                                            </button></span>
                                                        </td>
                                                        <td class="col_single text-right">
                                                            <span class="single-price">Rp. {{ number_format($product->price, 0, ",", ".") }}</span>
                                                        </td>
                                                        <td class="col_total text-right">
                                                            <span class="total-price" id="subtotal_{{$product->rowid}}">Rp. {{ number_format($product->subtotal, 0, ",", ".") }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>                       
                    </div>  
                    <!-- End id="checkout-content" -->
                    <!-- Checkout content -->
                    <div id="checkout-content">
                        <form action="{{url('checkout')}}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="box-header" style="background-color: #1abc9c;padding: 10px;">
                                <h3 style="color: white">Alamat</h3>
                                <h5 style="color: white">Alamat tujuan pengiriman</h5>
                            </div>
                            <div class="box-content">
                                
                                @if($data['address'])
                                    <?php $query = unserialize($data['address']->meta_value); ?>
                                    <select class="form-control" id="address_check" name="address_check">
                                        <option value="">--Silahkan Pilih--</option>
                                        
                                        @foreach($query as $new => $value)
                                            <option value="{{$new}}">{{$value['nama']}} | {{$value['alamat']}} | {{$value['telepon']}}</option>
                                        @endforeach

                                    </select>
                                    <br>
                                    <div id="result_address" name="result_address"></div>
                                @endif

                                <table class="table table-responsive">
                                        
                                        @if ($data['address']) 
                                            <?php $query = unserialize($data['address']->meta_value); ?>
                                            <!-- mengambil properti produk -->
                                            @foreach($data['cart'] as $product)
                                                <?php $properties = array();?>
                                                
                                                @foreach( $product->options as $key => $value)
                                                    <?php $properties[$key] = $value;?>
                                                @endforeach

                                                <input type="hidden" id="properties_{{$product->rowid}}" name="properties_{{$product->rowid}}" value="{{serialize($properties)}}"></input>
                                            @endforeach

                                            <input type="hidden" class="form-control" id="coupon_code" name="coupon_code" value="{{session('coupon')}}">
                                            <input type="hidden" class="form-control" value="{{session('discount')}}" id="discount" name="discount">
                                            <input type="hidden" class="form-control" id="shipping_price" name="shipping_price">
                                            <input type="hidden" class="form-control" value="{{Cart::total()}}" id="cart_total" name="cart_total">
                                            <input type="hidden" class="form-control" value="" id="courier_check" name="courier_check">
                                            <input type="hidden" class="form-control" value="{{$data['weight']}}" id="weight" name="weight">
                                            <tr>
                                                <td><button class="btn btn-mini btn-greensea" id="alamat_baru" name="alamat_baru" type="button">Alamat Baru</button></td>
                                            </tr>
                                        @endif

                                </table>
                            </div>
                        </form>
                        <form id="checkout" name="checkout" action="{{url('checkout')}}" role="get">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="box-content" hidden="true" id="form_new_address">
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label for="first_name" class="control-label">Nama Lengkap</label>
                                            <div class="controls">
                                                <input class="span12" type="text" name="name" id="name" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="city" class="control-label">Provinsi</label>
                                            <div class="controls">
                                                <select class="span12" name="province" id="province" >
                                                    <option value="">-- Silahkan Pilih --</option>
                                                    
                                                    @foreach($data['provinces'] as $province)
                                                        <option value="{{$province->id}}">{{$province->nama_province}}
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="city" class="control-label">Kota/Kabupaten</label>
                                            <div class="controls" id="city_content">
                                                <select class="span12" name="city" id="city"></select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="city" class="control-label">Kecamatan</label>
                                            <div class="controls">
                                                <select class="span12" name="district" id="district" ></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label for="phone" class="control-label">No.Telp</label>
                                                    <div class="controls">
                                                        <input class="span12" type="text" value="" name="phone" id="phone" />
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label for="city" class="control-label">Kurir Pengiriman</label>
                                                    <div class="controls">
                                                        <select class="span12 courier_new" name="courier" id="courier">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="address" class="control-label">Detail Alamat</label>
                                            <div class="controls">
                                                <textarea class="span12" name="address" id="address" rows="4" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{url('/')}}" class="btn btn-small">
                                        <i class="icon-chevron-left"></i>lanjut belanja
                                    </a>
                                </div>
                                
                                @if(Cart::count())
                                    
                                    @foreach($data['cart'] as $product)
                                        <?php $properties = array();?>
                                        
                                        @foreach( $product->options as $key => $value)
                                            <?php $properties[$key] = $value;?>
                                        @endforeach

                                        <input type="hidden" id="properties_{{$product->rowid}}" name="properties_{{$product->rowid}}" value="{{serialize($properties)}}"></input>
                                    @endforeach

                                    <input type="hidden" class="form-control" id="coupon_code" name="coupon_code" value="{{session('coupon')}}">
                                    <input type="hidden" class="form-control" value="{{session('discount')}}" id="discount" name="discount">
                                    <input type="hidden" class="form-control" id="shipping_price_new" name="shipping_price_new">
                                    <input type="hidden" class="form-control" value="{{Cart::total()}}" id="cart_total_new" name="cart_total_new">
                                    <input type="hidden" class="form-control" value="" id="courier_check_new" name="courier_check_new">
                                    <input type="hidden" class="form-control" value="{{$data['weight']}}" id="weight_new" name="weight_new">
                                    <div class="pull-right" hidden="true" id="btn_new_checkout" name="btn_new_checkout">
                                        <button type="submit" class="btn btn-greensea">
                                            Checkout <i class="icon-chevron-right"></i>
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </form>                  
                    </div>  
                    <!-- End id="checkout-content" -->

                </div>
            </div>
            <div class="span3">                                    
                <!-- Cart details -->
                <div class="cart-details">
                    <div class="box">
                        <div class="hgroup title">
                            <h3>Total Pemesanan</h3>
                        </div>
                        <div class="price-list">
                            <li>Subtotal: <strong>Rp. {{ number_format(Cart::total(), 0, ",", ".") }}</strong></li>
                            
                            @if(session('discount'))
                                <li>Diskon: <strong>- Rp. {{ number_format(session('discount'), 0, ",", ".") }}</strong></li>
                               <!--  <li class="important">Total: <strong>Rp. {{ number_format(Cart::total()-session('discount'), 0, ",", ".") }}</strong></li> -->
                            @else
                                <li>Diskon: <strong>Rp. {{ number_format(0, 0, ",", ".") }}</strong></li>
                            @endif

                                <li>
                                    <div id="shipping" name="shipping">
                                        Biaya Kirim: <strong>Rp. -</strong>
                                    </div>
                                </li>
                                <li class="important">
                                    <div id="total" name="total">
                                        Total: <strong>Rp. {{ number_format(Cart::total()-session('discount'), 0, ",", ".") }}</strong>
                                    </div>
                                </li>
                            
                        </div>
                    </div>
                </div>
                <!-- End class="cart-details" -->
                <!-- Coupon -->
                <div class="coupon">
                    <div class="box">
                        <div class="hgroup title">
                            <h3>Kode Voucher</h3>
                            <h5>Masukan kode voucher disini</h5>
                        </div>
                        <form class="form-horizontal" enctype="multipart/form-data" action="{{url('discount')}}" method="get">
                            <label for="coupon_code">Kode Voucher</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon" name="coupon" placeholder="" >
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" style="height:34px"><i class="fa fa-check"></i></button>
                                </span>
                            </div>
                        </form>     
                    </div>
                </div>
            <!-- End class="coupon" -->
            <!-- Coupon -->
                <div class="coupon">
                    <div class="box">
                        <div class="hgroup title">
                            <h3>Estimasi Biaya Pengiriman</h3>
                            <h5>Cek biaya pengiriman Anda disini</h5>
                        </div>
                        <label for="coupon_code">Provinsi</label>
                            <select class="form-control" name="province_check" id="province_check" >
                                <option value="">-- Silahkan Pilih --</option>
                                
                                @foreach($data['provinces'] as $province)
                                    <option value="{{$province->id}}">{{$province->nama_province}}
                                @endforeach

                            </select>
                        <label for="coupon_code">Kota/Kabupaten</label>
                        <select class="form-control" name="city_check" id="city_check"></select>
                        <div id='result_check'></div>
                    </div>
                </div>
            <!-- End class="coupon" -->               
            </div>

        </div>
    </div>  
</section>
<!-- End class="checkout" -->
<script type="text/javascript">
    $(document).ready(function(){

        $('a.delete').click(function(){
            var rowid = this.name;
            
            $.ajax({
                url: "{!! url('delete_order') !!}",
                data: {rowid: rowid},
                method:'POST',
            }).done(function(data){
                location.reload();
            });
        });

        function addCommas(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';

            var rgx = /(\d+)(\d{3})/;

            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        $(".quantity_update").click(function(){
            var rowid = this.id;
            var quantity = $('#quantity_'+rowid).val();

            if (quantity < 1) {
                quantity = 1;
                $('#quantity_'+rowid).val(1);
            }

            $.ajax({
                url: "{!! url('update_order_quantity') !!}",
                data: {rowid: rowid, quantity: quantity},
                method:'POST',
            }).done(function(data){
               location.reload();
            });

        });

        $('#alamat_baru').click(function(){
            if ($('#discount').val() != '') {
                var discount = $('#discount').val();
                var cart = $('#cart_total').val() - discount;
                var reset = addCommas(cart);
            }else{
                var cart = $('#cart_total').val();
                var reset = addCommas(cart);
            }
            
            $('#form_new_address').show('slow');
            $('#btn_new_checkout').show('slow');
            $('#total').html("Total: <strong>Rp. "+reset+"</strong>");
            $('#result_address').hide('slow');
            $('#address_check').val('');

        });

        $('#province').change(function(){
            var id = $('#province').val();
            
            $.ajax({
                url: "{!! url('konten_kota') !!}",
                data: {id: id},
                method:'GET',
            }).done(function(data){
                $('#city').html(data);
            });
        });

        $('#city').change(function(){
            var id = $('#city').val();
            var weight = $('#weight_new').val();
            
            $.ajax({
                url: "{!! url('konten_kecamatan') !!}",
                data: {id: id},
                method:'GET',
            }).done(function(data){
                $('#district').html(data);
                $('#courier').html('');
            });

            $.ajax({
                url: "{!! url('shipping_new_address') !!}",
                data: {id: id, weight:weight},
                method:'POST',
            }).done(function(data){
                $('#courier').html(data);

            });
        });

        $('#province_check').change(function(){
            var id = $('#province_check').val();
            var cart = $('#cart_total').val();
            var value = $('#courier').val();
            
            $.ajax({
                url: "{!! url('konten_kota') !!}",
                data: {id: id},
                method:'GET',
            }).done(function(data){
                $('#city_check').html(data);
            });
        });

        $('#city_check').change(function(){
            var id = $('#city_check').val();
            
            $.ajax({
                url: "{!! url('cek_ongkir') !!}",
                data: {id: id},
                method:'POST',
            }).done(function(data){
                $('#result_check').html(data);
            });
        });

        $('#address_check').change(function(){
            var id = $('#address_check').val();
            var weight = $('#weight').val();
            
            $('#result_address').hide('slow');
            $('#form_new_address').hide('slow');
            $('#btn_new_checkout').hide('slow');
            
            $.ajax({
                url: "{!! url('konten_alamat') !!}",
                data: {id: id,weight: weight},
                method:'GET',
            }).done(function(data){
                $('#result_address').html(data);
                $('#result_address').show('slow');
            });
        });

        $('#courier').change(function(){
            var courier = $(this).children(":selected").attr("id");
            var value = $(this).children(":selected").val();
            var cart = $('#cart_total_new').val();
            var discount = $('#discount').val();
            var cost = addCommas(value);
            
            if (discount == '') {
                var total = parseInt(cart)+parseInt(value);
            }else{
                var total = (parseInt(cart)+parseInt(value))-parseInt(discount);
            };
            
            var result = addCommas(total);
            
            $('#courier_check_new').val("JNE-"+courier);
            $('#shipping').html("Biaya Kirim: <strong>Rp. "+cost+"</strong>");
            $('#total').html("Total: <strong>Rp. "+result+"</strong>");
            $('#shipping_price_new').val(value);
        });
    });

</script>