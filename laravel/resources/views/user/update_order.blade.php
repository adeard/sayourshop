<table class="table table-responsive" id="order">
	<thead>
		<tr>
			<th>Invoice</th>
			<th>Tanggal Pemesanan</th>
			<th>Detail</th>
			<th>Total Harga</th>
			<th>Status</th>
			<th>No. Resi</th>
			<th>Opsi</th>
		</tr>
	</thead>
	<tbody>
	    <form action="{{url('konfirmasi_pembayaran')}}">

	    @foreach($data['order'] as $value)

	    <tr>
	    	<td id="inv_{{$value->id}}" name="inv_{{$value->id}}">{{$value->no_invoice}}</td>
			<td>{{ date_format(date_create($value->order_date), "d M Y")}}</td>
			<td><button type="button" id="detail_{{$value->id}}" name="detail_{{$value->id}}" class="btn btn-mini btn-belizehole detail">Detail</button> </td>
			<td name="total_price" id="total_price">Rp. {{ number_format($value->total_price, 0, ",", ".") }}</td>
			<td>{{$value->order_status}}</td>
			<td>{{$value->no_resi}}</td>

			@if ($value->order_status == 'Menunggu Pembayaran')
				<td>
					<button type="submit" id="payment" name="payment" class="btn btn-mini btn-greensea" value="{{$value->no_invoice}}">Pembayaran</button>
				</td>
			@elseif($value->order_status == 'Dikirim')
				<td>
					<button type="button" class="btn btn-mini btn-greensea receive" id="review" name="{{$value->id}}" value="{{$value->no_resi}}">Diterima</button>
				</td>
			@else
				<td>
					<button type="button" disabled="true" class="btn btn-mini btn-greensea">Pembayaran</button>
				</td>
			@endif

		</tr>

		@endforeach

		</form>
	</tbody>
</table>
<div id="modaldetail"></div>
<script type="text/javascript">
	$('.detail').click(function(){
			var id = this.id.substr(7);
			$.ajax({
				url: "{!! url('order_detail') !!}",
				data: {orderid: id},
                method:'GET',
			}).done(function(data){
				$('#modaldetail').html(data);
			});
		});

	$('#review').click(function(){
        	var resi = this.value;
        	var order_id = this.name;
        	$.ajax({
                url: "{!! url('modal_review') !!}",
                data: {
                	resi: resi,
                	order_id: order_id
                },
                method:'POST',
            }).done(function(data){
                $('#modaldetail').html(data);
            });
        });
</script>