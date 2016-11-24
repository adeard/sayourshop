<!-- Checkout / Billing Address -->
<section class="checkout" style="margin-top:20px">
    <div class="container" style="padding: 0px;">
        <div class="row">
            <div class="span2"></div>
            <div class="span8">
                <div class="box" style="padding-top: 0px;">
                     <!-- Checkout content -->
                    <div id="checkout-content">
                        <div class="box-header" style="background-color: #1abc9c;padding: 10px;">
                            <h3 style="color: white;">Nomor Invoice Anda : <u>{{$data['order']->no_invoice}}</u> <div class="pull-right">Status : {{$data['order']->order_status}}</div></h3>
                        </div>
                        <div class="box-content">
                            <table class="table table-responsive">
                                <tr>
                                    <th>Nama</th>
                                    <td><span>{{$data['order']->order_name}}</span></td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td><span>{{$data['order']->order_phone}}</span></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><span>{{$data['order']->order_address}}, Kecamatan {{$data['order']->district->name}}, {{$data['order']->city->nama}},  {{$data['order']->province->nama_province}}</span></td>
                                </tr>
                                <tr>
                                    <th>Kurir</th>
                                    <td><span>{{$data['order']->courier}}</span></td>
                                </tr>
                            </table>
                        </div>   
                    </div>  
                    <!-- End id="checkout-content" -->
                    <!-- Checkout content -->
                    <div id="tab-content">
                        <div class="tab-pane active" id="cart" role="presentation">
                            <form enctype="multipart/form-data" action="{{ url('update_order') }}" method="get" />
                                <div class="box-header" style="background-color: #1abc9c;padding: 10px;">
                                    <h3 style="color: white">Produk</h3>
                                </div>
                                <div class="box-content">
                                    <div class="cart-items table-responsive">
                                        <table class="styled-table">
                                            <thead>
                                                <tr>
                                                    <th class="col_product">Produk</th>
                                                    <th class="col_properties ">Properti</th>
                                                    <th class="col_qty ">Jumlah</th>
                                                    <th class="col_single ">Harga</th>
                                                    <th class="col_single ">Total Berat</th>
                                                    <th class="col_total ">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['orderdetail'] as $order)                                 
                                                    <tr>
                                                        <td class="col_product">
                                                            <h5>
                                                                {{ucwords($order->product->name)}}
                                                            </h5>
                                                        </td>
                                                        <td class="col_product">
                                                            <h5>
                                                                @foreach( $properties = unserialize($order->properties) as $value)
                                                                    {{ucwords($value)}}&nbsp;
                                                                @endforeach
                                                            </h5>
                                                        </td>
                                                        <td>
                                                            <span>{{$order->quantity}}</span>
                                                        </td>
                                                        <td>
                                                            <span>Rp. {{ number_format($order->product->price, 0, ",", ".") }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{number_format($order->product->weight*$order->quantity, 0, ",", ".")}} g</span>
                                                        </td>
                                                        <td>
                                                            <span>Rp. {{ number_format($order->product->price*$order->quantity, 0, ",", ".") }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td>Subtotal</td>
                                                    <td colspan="3">Rp. {{ number_format($data['order']->total_discount+$data['order']->total_price-$data['order']->shipping_price, 0, ",", ".") }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td>Diskon</td>
                                                    <td>- Rp. {{ number_format($data['order']->total_discount, 0, ",", ".") }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td>Biaya Kirim</td>
                                                    <td colspan="3">Rp. {{ number_format($data['order']->shipping_price, 0, ",", ".") }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                    <td>Total Biaya</td>
                                                    <td colspan="3"><b>Rp. {{ number_format($data['order']->total_price, 0, ",", ".") }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>                       
                    </div>  
                    <!-- End id="checkout-content" -->  
                </div>
            </div>
        </div>
    </div>  
</section>
<!-- End class="checkout" -->
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#alamat_baru').click(function()
        {
            $('#form_new_address').show('slow');
            $('#btn_new_checkout').show('slow');
        });
    });

</script>