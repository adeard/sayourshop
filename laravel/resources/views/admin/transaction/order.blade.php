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
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="order_status active" id="waiting_payment"><a href="#waiting_payment" aria-controls="waiting_payment" role="tab" data-toggle="tab">Menunggu Pembayaran</a></li>
                <li role="presentation" class="order_status" id="Dibayar"><a href="#dibayar" aria-controls="dibayar" role="tab" data-toggle="tab">Dibayar</a></li>
                <li role="presentation" class="order_status" id="Lunas"><a href="#lunas" aria-controls="lunas" role="tab" data-toggle="tab">Lunas</a></li>
                <li role="presentation" class="order_status" id="Dikirim"><a href="#dikirim" aria-controls="dikirim" role="tab" data-toggle="tab">Dikirim</a></li>
                <li role="presentation" class="order_status" id="Diterima"><a href="#selesai" aria-controls="selesai" role="tab" data-toggle="tab">Selesai</a></li>
            </ul>
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
            </table>
            <?php // ======================== END Data Table ================================ ?>
            <div id="modaldetail"></div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</section><!-- /.content -->

@section('script')
	<script>
        $(document).ready(function(){
            var order_status="Menunggu Pembayaran";

            $('#order_table').DataTable({
                processing: true,
                serverSide: true,
                bDestroy:true,
                pagingType:"full_numbers",
                pageLength: 10,
                responsive: true,
                ajax: { url:'{!! url("list_order") !!}', data:{order_status: order_status}},
                columns: [
                    { data: 'id'},
                    { data: 'no_invoice', name: 'no_invoice'},
                    { data: 'order_status', name: 'order_status'},
                    { data: 'detail'},
                    { data: 'opt'},
                ]
            });
        });

        $("#order_table").on("click", ".detail", function(){
            var id = this.id;
          
            $.ajax({
                url: "{!! url('order_detail') !!}",
                data: {orderid: id},
                method:'GET',
            }).done(function(data){
                $('#modaldetail').html(data);
            });
        });

        $("#order_table").on("change", ".resi", function(){
            var id = this.id;
            var resi = $('#'+id).val();
            
            $.ajax({
                url: "{!! url('insert_shipping') !!}",
                data: {
                    resi: resi,
                    orderid : id
                },
                method:'POST',
            }).done(function(data){
                $('#alert').html('<div class="alert alert-success">No resi berhasil disimpan </div>');
                $('#alert').show('slow');
            });
        });

        $("#order_table").on("click", ".send", function(){
            var id = this.id.substr(5);

            if ($('.resi').val() != '') {

                $.ajax({
                    url: "{!! url('send') !!}",
                    data: {orderid : id},
                    method:'POST',
                }).done(function(data){
                    $('#send_'+ id).attr('disabled', true);
                    $('#send_'+ id).html('Selesai');
                    $('input.resi#'+id).attr('disabled', true);
                });

                $('#alert').hide('slow');
            } else {
                $('#alert').html('<div class="alert alert-danger">Silahkan isi no resi terlebih dahulu</div>');
                $('#alert').show('slow');
            }
        });

        $('.order_status').click(function(){
            var order_status= this.id;

            if (order_status == "waiting_payment") {
                order_status = "Menunggu Pembayaran";
            }

            $('#order_table').DataTable({
                processing: true,
                serverSide: true,
                bDestroy:true,
                pagingType:"full_numbers",
                pageLength: 10,
                responsive: true,
                ajax: { url:'{!! url("list_order") !!}', data:{order_status: order_status}},
                columns: [
                    { data: 'id'},
                    { data: 'no_invoice', name: 'no_invoice'},
                    { data: 'order_status', name: 'order_status'},
                    { data: 'detail'},
                    { data: 'opt'},
                ]
            });
        });
    </script>
@stop