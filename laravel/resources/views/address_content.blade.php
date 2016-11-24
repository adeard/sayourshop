<div class="span6">
    <h5><strong>{{ucwords($data['address']['nama'])}}</strong></h5>
    {{$data['address']['alamat']}}<br>
    Kecamatan {{$data['district']->name}}, {{$data['city']->nama}}<br>
    {{$data['province']->name}}<br>
    {{$data['address']['telepon']}}<br><br><br>
    
    @if(Cart::count())
        <button class="btn btn-mini btn-greensea address" id="no_address" name="no_address"  type="submit" disabled>Checkout</button></a><br><br>
    @endif

</div>

<div class="span2 box pull-left">
    <table class="table">
        <?php $result = unserialize($data['cost_data'])?>
        <tr>
            <th style="text-align: center;">Paket Kurir</th>
        </tr>
       
        @foreach ($result as $key => $value)
            @if($result[$key]->service == 'OKE' || $result[$key]->service == 'CTCOKE')
                <tr>
                    <td>
                        <div class="radio">
                            <input class="radiobtn" type="radio" name="optradio" id="{{$result[$key]->service}}" value="{{$result[$key]->cost[0]->value * ceil($data['weight']/1000)}}">
                        </div>OKE (Rp. {{ number_format($result[$key]->cost[0]->value * ceil($data['weight']/1000), 0, ",", ".") }})
                    </td>
                </tr>
            @elseif($result[$key]->service == 'REG' || $result[$key]->service == 'CTC')
                <tr>
                    <td>
                        <div class="radio">
                            <input class="radiobtn" type="radio" name="optradio" id="{{$result[$key]->service}}" value="{{$result[$key]->cost[0]->value * ceil($data['weight']/1000)}}">
                        </div>REG (Rp. {{ number_format($result[$key]->cost[0]->value * ceil($data['weight']/1000), 0, ",", ".") }})
                    </td>
                </tr>
            @elseif($result[$key]->service == 'YES' || $result[$key]->service == 'CTCYES')
                <tr>
                    <td>
                        <div class="radio">
                            <input class="radiobtn" type="radio" name="optradio" id="{{$result[$key]->service}}" value="{{$result[$key]->cost[0]->value * ceil($data['weight']/1000)}}">
                        </div>YES (Rp. {{ number_format($result[$key]->cost[0]->value * ceil($data['weight']/1000), 0, ",", ".") }})
                    </td>
                </tr>
            @endif
        @endforeach

    </table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
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

        $('.radiobtn').click(function(){
            var id = this.id;
            var value = $('#'+id).val();
            var shipping = addCommas(value);
            var cart = $('#cart_total').val();
            var discount = $('#discount').val();
            if (discount == '') {
                var total = parseInt(cart)+parseInt(value);
            }else{
                var total = (parseInt(cart)+parseInt(value))-parseInt(discount);
            };
            var result = addCommas(total);
            $('#courier_check').val('JNE-'+id);
            $('#no_address').attr('disabled',false);
            $('#shipping_price').val(value);
            $('#shipping').html("Biaya Kirim: <strong>Rp. "+shipping+"</strong>");
            $('#total').html("Total: <strong>Rp. "+result+"</strong>");
        });
    });
</script>

