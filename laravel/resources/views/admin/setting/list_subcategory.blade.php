<section class="content-header">
    <h1>
        SubCategory
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

            <form action="{{url('master/setting/subcategory/create')}}" role="GET">
                <select name="category" id="category">
                    
                    @foreach($data['category'] as $category)
                      <option value="{{$category->id}}">{{$category->name}}
                    @endforeach

                </select>
                <input type="text" id="subcategory" name="subcategory"></input>
                <button type="submit" class="btn btn-xs btn-primary">Tambah</button>
            </form>
        </div>
        <div class="box-body">
            <?php // ======================== Data Table ================================ ?>
            <table id="subcategorylist_table" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Nama Subkategori</th>
                        <th>Slug</th>
                        <th>Properties</th>
                        <th>Total Produk</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $i=1; foreach($data['subcategory'] as $subcategory): ?> 
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $subcategory->category->name ?></td>
                        <td><?= $subcategory->subname ?></td>
                        <td><?= $subcategory->slug ?></td>
                        <td><?= $subcategory->properties ?></td>
                        <td><?= $subcategory->total_product ?></td>
                        <td>
                            <a href="{{url('/master/subcategory/view')}}/<?=$subcategory->id?>"><i class="fa fa-eye"></i></a>
                            <a href="{{url('/master/subcategory/edit')}}/<?=$subcategory->id?>"><font color="orange"><i class="fa fa-pencil"></i></font></a>
                            <a href="#" id="delete" value="<?=$subcategory->id?>" method="post"><font color="red"><i class="fa fa-remove"></i></font></a>
                        </td>
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
            $("#subcategorylist_table").DataTable();
        });

        $('a#delete').click(function(){
            var r = confirm("Are You Sure Want to Remove This Item?");

            if (r == true) {
               window.location.href='{{url("/master/subcategory/delete")}}/'+$(this).attr("value");
            }

        });
    </script>
@stop