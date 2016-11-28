<?php 
use App\Http\Models\Order;
?>
    <section class="content-header">
        <h1>
            Home
            <small>it all starts here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{url('/master')}}"><i class="fa fa-home"></i> Home</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <h4 align="right">Total Users : {{count($data['users'])}}</h4>  
                </div>
                <div class="col-sm-6">
                    <h4>Total Visitors : {{$data['visitors']->meta_value}}</h4>
                </div>
            </div>
            <h3 align="center">Order By Status</h3>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{count($data['new'])}}<sup style="font-size: 12px">order(s)</sup></h3>
                        <p>Menunggu Pembayaran</p>
                    </div>
                    <div class="icon">
                        <i class="ion-ios-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer order_chart">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{count($data['has_paid'])}}<sup style="font-size: 12px">order(s)</sup></h3>
                        <p>Telah Dibayar</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer order_chart">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{count($data['paid'])}}<sup style="font-size: 12px">order(s)</sup></h3>
                        <p>Lunas</p>
                    </div>
                    <div class="icon">
                        <i class="ion-ios-paperplane"></i>
                    </div>
                    <a href="#" class="small-box-footer order_chart">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{count($data['send'])}}<sup style="font-size: 12px">order(s)</sup></h3>
                        <p>Dikirim</p>
                    </div>
                    <div class="icon">
                        <i class="ion-android-checkmark-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer order_chart">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#chart_view" aria-controls="chart_view" role="tab" data-toggle="tab">Chart</a></li>
                <li role="presentation"><a href="#table_view" aria-controls="table_view" role="tab" data-toggle="tab">Table</a></li>
            </ul>
            <div class="tab-content" style="background:white;color:black;padding-bottom: 20px;">
                <div role="tabpanel" class="tab-pane fade in active" id="chart_view">
                    <div id="chart_by_category"></div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="table_view">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <?php 
                            for ($i=1; $i < 13; $i++) { 
                                $timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
                                $count_month = Order::where('order_date','LIKE', '%-'.str_pad($i, 2, "0", STR_PAD_LEFT).'-%')->count();
                        ?>
                            <li role="presentation" class="count_month" id="month_{{$i}}"><a href="#month_{{$i}}" aria-controls="month_{{$i}}" role="tab" data-toggle="tab">{{date("F", $timestamp)}}<br>{{$count_month}}</a></li>
                        <?php
                            }
                        ?>
                    </ul>
                    <br><br><br>
                    <table class="table table-responsive table-bordered" id="table_content">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Nama Pemesan</th>
                                <th>Status</th>
                                <th>Harga</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="modaldetail"></div>
            </div>
        </div><!-- /.row -->
    </section><!-- /.content -->

@section('script')
	<script>
    $(function () {
        $('.count_month').click(function(){
            month = this.id.substr(6);

            $('#table_content').DataTable({
                processing: true,
                serverSide: true,
                bDestroy:true,
                pagingType:"full_numbers",
                pageLength: 10,
                responsive: true,
                ajax: { url:'{!! url("month_order") !!}', data:{month:month} },
                columns: [
                    { data: 'no_invoice', name: 'no_invoice'},
                    { data: 'order_name', name: 'order_name'},
                    { data: 'order_status', name: 'order_status'},
                    { data: 'total_price', name: 'total_price'},
                    { data: 'order_date', name: 'order_date'},
                    {
                      data: 'id',
                      className: "center",
                      mRender: function (data) {
                        $('#orderid').click(function(e){
                          e.preventDefault();
                          val = this.name;
                          $.ajax({
                            url: "{!! url('order_detail') !!}",
                            data: {orderid: val},
                            method:'GET',
                          }).done(function(data){
                            $('#modaldetail').html(data);
                          });
                        });
                        return '<a href="#" name="'+data+'" id="orderid"><button type="button" class="btn btn-primary btn-xs detail">Detail</button></a>';
                      },
                    }
                ]
            });
        });

        $('#chart_by_category').highcharts({
            title: {
                text: 'Total Order',
                x: -20 //center
            },
            xAxis: {
                categories: [
                
                <?php 
                    for ($i=1; $i < 13; $i++) { 
                        $timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
                ?>
                  "<?=date("M", $timestamp);?>",
                <?php
                    }
                ?>

                ]
            },
            yAxis: {
                title: {
                    text: 'Order'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Order',
                data: [

                <?php 
                    for ($i=1; $i <= 12 ; $i++) { 
                      $count = Order::where('order_date','LIKE', '%-'.str_pad($i, 2, "0", STR_PAD_LEFT).'-%')->count();
                      print_r($count.",");
                    }
                ?>

                ]
            }]
        });
    });
    </script>
@stop