		<section class="content-header">
          <h1>
            Category
            <small>List</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{url('/master')}}"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-folder"></i> Category</a></li>
            <li><a href="{{url('/master/category/list')}}"></i> List</a></li>
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
              	  <table id="category_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Total Product</th>
                        <th>Slug</th>
                        <th>Total Subkategori</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; foreach($data['category'] as $category): ?> 
                      <tr>
                        <td><?= $i ?></td>
                        <td><?= $category->name ?></td>
                        <td><?= $category->total_product ?></td>
                        <td><?= $category->slug ?></td>
                        <td><?= $category->subcategories ?></td>
                        <td>
                          <a href="{{url('/master/category/view')}}/<?=$category->id?>"><i class="fa fa-eye"></i></a>
                          <a href="{{url('/master/category/edit')}}/<?=$category->id?>"><font color="orange"><i class="fa fa-pencil"></i></font></a>
                          <a href="{{url('/master/category/delete')}}/<?=$category->id?>"><font color="red"><i class="fa fa-remove"></i></font></a>
                        </td>
                      </tr>
                      <?php $i++; endforeach; ?>
                    </tbody>
                  </table>
                  <?php // ======================== END Data Table ================================ ?>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

        </section><!-- /.content -->

@section('script')
	<script>
      $(function () {
        $("#category_table").DataTable();
      });
  </script>
@stop