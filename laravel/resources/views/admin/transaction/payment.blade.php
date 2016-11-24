		<section class="content-header">
          <h1>
            Order
            <small>List</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{url('/master')}}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-file"></i> SubCategory</a></li>
            <li><a href="{{url('/master/subcategory/list')}}"></i> List</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          
          <!-- Default box -->
          <div class="box box-warning">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
            	<?php // ======================== Data Table ================================ ?>
              	  <form action="{{url('master/transaction/payment')}}" method="post" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <table id="order_table" class="table table-bordered table-hover">
                    
                    <thead>
                      <tr>
                        <th>No Invoice</th>
                        <th>Detail</th>
                        <th>Nama</th>
                        <th>Tujuan</th>
                        <th>Total Transfer</th>
                        <th>Tanggal Transfer</th>
                        <th>Status</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($data['payment'] as $payment)
                          <tr>
                              <td>{{$payment->no_invoice}}</td>
                              <td><button type="button" class="btn btn-xs btn-success detail" value="{{$payment->order_id}}" id="payment" name="payment">Detail</button></td>
                              <td>{{$payment->account_name}}</td>
                              <td>{{$payment->admin_account}}</td>
                              <td>Rp. {{ number_format($payment->total_transfer, 0, ",", ".") }}</td>
                              <td>{{$payment->transfer_date}}</td>
                              <td>{{$payment->order['order_status']}}</td>
                              
                              @if($payment->order['order_status'] == 'Lunas')
                                <td><button type="button" class="btn btn-xs btn-primary disabled">Lunas</button></td>
                              @elseif($payment->order['order_status'] == 'Telah Dibayar')
                                <td><button type="submit" class="btn btn-xs btn-primary" value="{{$payment->order_id}}" id="payment" name="payment">Konfirmasi</button></td>
                              @else
                                <td><button type="submit" class="btn btn-xs btn-primary" value="{{$payment->order_id}}" id="payment" name="payment" disabled="true">Selesai</button></td>
                              @endif

                          </tr>
                        @endforeach
                        
                    </tbody>
                   
                  </table>
                   </form>
                  <?php // ======================== END Data Table ================================ ?>
                  <div id="modaldetail"></div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

        </section><!-- /.content -->

@section('script')
	<script>
      $(function () {
        $("#order_table").DataTable();
      });

      $('.detail').click(function(){
      var id = this.value;
      $.ajax({
        url: "{!! url('order_detail') !!}",
        data: {orderid: id},
                method:'GET',
      }).done(function(data){
        $('#modaldetail').html(data);
      });
      });
    </script>
@stop