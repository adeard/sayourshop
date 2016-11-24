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
            <div id="alert" hidden="true"></div>
            
            <div class="box-header with-border">

            </div>
            <div class="box-body">
            	<?php // ======================== Data Table ================================ ?>
              	  <table id="order_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No Resi</th>
                        <th>No Invoice</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['order'] as $order)
                        <tr>
                            @if($order->order_status == 'Lunas' && $order->no_resi == '')
                              <td><input type="text" class="col-sm-12 resi" id="{{$order->id}}" name="{{$order->id}}"></td>
                            @elseif($order->order_status == 'Dikirim')
                              <td><input type="text" class="col-sm-12 resi" id="{{$order->id}}" name="{{$order->id}}" value="{{$order->no_resi}}" disabled="true"></td>
                            @else
                              <td><input type="text" class="col-sm-12 resi" disabled="true"></input></td>
                            @endif
                            <td>{{$order->no_invoice}}</td>
                            <td>{{$order->order_status}}</td>
                            <td><button class="btn btn-primary btn-xs detail" id="{{$order->id}}">Lihat</button></td>
                            @if($order->order_status == 'Dikirim')
                              <td><button class="btn btn-info btn-xs" disabled="true">Selesai</button></td>
                            @else
                              <td><button class="btn btn-success btn-xs send" id="send_{{$order->id}}">Kirim</button></td>
                            @endif
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
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
        var id = this.id;
        $.ajax({
          url: "{!! url('order_detail') !!}",
          data: {orderid: id},
                  method:'GET',
        }).done(function(data){
          $('#modaldetail').html(data);
        });
      });

      $(".resi").focus(function(){
        $('#alert').hide('slow');
      });

      $('.resi').change(function(){
        var id = this.id;
        var resi = $('#'+id).val();
        $.ajax({
          url: "{!! url('insert_shipping') !!}",
          data: {resi: resi,
                  orderid : id},
                  method:'POST',
        }).done(function(data){
          $('#alert').html('<div class="alert alert-success">No resi berhasil disimpan </div>');
          $('#alert').show('slow');
        });
      });

      $('.send').click(function(){
        var id = this.id.substr(5);
        if ($('.resi').val() != '') {
          $.ajax({
            url: "{!! url('send') !!}",
            data: {orderid : id},
                    method:'POST',
          }).done(function(data){
            $('#send_'+ id).attr('disabled',true);
          });
          $('#alert').hide('slow');
        } else {
          $('#alert').html('<div class="alert alert-danger">Silahkan isi no resi terlebih dahulu</div>');
          $('#alert').show('slow');
        }
      });
    </script>
@stop