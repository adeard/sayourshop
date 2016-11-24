		<section class="content-header">
          <h1>
            Distributor
            <small>List</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{url('/master')}}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-user"></i> Distributor</a></li>
            <li><a href="{{url('/master/distributor/list')}}"></i> List</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          
          <!-- Default box -->
          <div class="box box-warning">
            <div class="box-header with-border">
              @if(session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
              @endif
              @if(session('failed'))
                  <div class="alert alert-danger">
                      {{session('failed')}}
                  </div>
              @endif
            </div>
            <div class="box-body">
            	<?php // ======================== Data Table ================================ ?>
              	  <table id="distributorlist_table" class="table table-bordered table-hover">

                    <thead>
                      <tr>
                        <th>Nama Distributor</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Date Created</th>
                        <th></th>
                      </tr>
                    </thead>
                  </table>
                  <?php // ======================== END Data Table ================================ ?>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

        </section><!-- /.content -->

@section('script')
	  <script>
      $(function () {
        $('#distributorlist_table').DataTable({
            processing: true,
            serverSide: true,
            bDestroy:true,
            pagingType:"full_numbers",
            pageLength: 10,
            responsive: true,
            ajax: { url:'{!! url("get_list_distributor") !!}'},
            columns: [
                { data: 'name'},
                { data: 'email', name: 'email'},
                { data: 'address', name: 'address'},
                { data: 'phone'},
                { data: 'created_at'},
                { data: 'opt'},
            ]
        });
      });

      $("#distributorlist_table").on("click", "a#delete", function(){
         r = confirm("Hapus Distributor ?");

        if (r == true) {
           window.location.href='{{url("/master/distributor/delete")}}/'+$(this).attr("value");
        }
      });

      // $('a#delete').click(function(){
      //   r = confirm("Hapus Distributor ?");

      //   if (r == true) {
      //      window.location.href='{{url("/master/distributor/delete")}}/'+$(this).attr("value");
      //   }

      // });
    </script>
@stop