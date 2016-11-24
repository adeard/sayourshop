		<section class="content-header">
          <h1>
            User
            <small>List</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{url('/master')}}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-users"></i> User</a></li>
            <li><a href="{{url('/master/user/list')}}"></i> List</a></li>
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
              	  <table id="userlist_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Last Login</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Last Login</th>
                      </tr>
                    </tfoot>
                  </table>
                  <?php // ======================== END Data Table ================================ ?>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

        </section><!-- /.content -->

@section('script')
	<script>
      $(document).ready(function () {
        $('#userlist_table').DataTable({
            processing: true,
            serverSide: true,
            bDestroy:true,
            pagingType:"full_numbers",
            pageLength: 10,
            responsive: true,
            ajax: { url:'{!! url("get_list_user") !!}'},
            columns: [
                { data: 'email', name: 'email'},
                { data: 'first_name', name: 'first_name'},
                { data: 'last_name', name: 'last_name'},
                { data: 'phone', name: 'phone'},
                { data: 'status', name: 'status'},
                { data: 'last_login', name: 'last_login'},
            ]
          });
      });
    </script>
@stop