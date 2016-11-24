<table id="productlist" class="table table-bordered table-hover">
<thead>
  <tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Nama Produk</th>
    <th>Kategori</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Opsi</th>
  </tr>
</thead>
<tbody>
  @if($data['product'])
    <?php $i=1; foreach($data['product'] as $product): ?> 
    <tr>
      <td><?= $i ?></td>
      <td><?= date_format(date_create($product->created_at), "d M Y") ?></td>
      <td><?= $product->name ?></td>
      <td><?= $product->category->name ?></td>
      <td>Rp. <?= number_format($product->price, 0, ",", ".") ?></td>
      <td><div id="update_qty_{{$product->id}}"><?= $product->quantity ?></div></td>
      <td>
        @if($product->status != 'publish')
          <button type="button" class="btn btn-success btn-xs active_product" id="<?= $product->id ?>" name="<?= $product->name ?>">Aktifkan</button>
        @else
          <button class="btn btn-danger btn-xs active_product" id="<?= $product->id ?>">Non-Aktifkan</button>
        @endif
        <a href="#" id="detail_{{$product->id}}" name="detail_{{$product->id}}" class="detail"><i class="fa fa-eye"></i></a>
        <a href="#" id="delete" value="<?=$product->id?>" method="post"><font color="red"><i class="fa fa-remove"></i></font></a>
        <input type="hidden" class="product_status" id="status_{{$product->id}}" value="{{$product->status}}">
      </td>
    </tr>
    <?php $i++; endforeach; ?>
  @else
    <tr>
      <td colspan="7">No Data Found</td>
    </tr>
  @endif
</tbody>
</table>

<script>
      $(document).ready(function () {
        $("#productlist").DataTable();
        // $("#productlist").DataTable({
        //     processing: true,
        //     serverSide: true,
        //     bDestroy:true,
        //     pagingType:"full_numbers",
        //     pageLength: 10,
        //     responsive: true,
        //     ajax: { url:'{!! url("list_order") !!}', data:{order_status: order_status}},
        //     columns: [
        //         { data: 'id'},
        //         { data: 'no_invoice', name: 'no_invoice'},
        //         { data: 'order_status', name: 'order_status'},
        //         { data: 'detail'},
        //         { data: 'opt'},
        //     ]
        // });
        $("#color").focus(function(){
          $('#alert_variant').hide('slow'); 
        });
      });

      $("#productlist").on("click", ".active_product", function(){
        var id = this.id;
          var status = $('#status_'+id).val();
          var category_id = $('#category').val();
          $('#alert_variant').hide(); 
          $('#varian_total').empty();
          if (status == 'unactive') {
            var name = this.name;
            $('#id').val(id);
            $('.modal-title').html('Varian produk ' + name);
            $.ajax({
              url: "{!! url('check_variant') !!}",
              data: { id:id },
              method:'POST',
            }).done(function(data){4
              if (data != '') {
                $('#varian_total').html("Varian produk saat ini terdapat <b>" + data['variant_count']+"("+ data['variant'] +")" + "</b> varian");
                $('#done').attr('disabled', false);
              }
            });
            $('#myModal').modal('show');
          }else{
            $.ajax({
              url: "{!! url('unactivated_product') !!}",
              data: { id:id },
              method:'POST',
            }).done(function(data){
              get_category_product(category_id);
            });
          }
      });

      $('a#delete').click(function(){
        r = confirm("Are You Sure Want to Remove This Item?");
        if (r == true) {
           window.location.href='{{url("/master/produk/delete")}}/'+$(this).attr("value");
        }
      });

      $('.detail').click(function(e){
        e.preventDefault();
        var id = this.id.substr(7);
        $.ajax({
          url: "{!! url('master/produk_detail') !!}",
          data: {product_id: id},
          method:'GET',
        }).done(function(data){
          $('#modaldetail').html(data);
        });
      });

          $('#automatic').click(function()
            {
              $('input[type=number]').attr( 'disabled',true);
                $('#s').prop( "checked",true);
                $('#s_qty').val(5);
                $('#s_qty_tmp').val(5);
                $('#m').prop( "checked",true);
                $('#m_qty').val(5);
                $('#m_qty_tmp').val(5);
                $('#l').prop( "checked",true);
                $('#l_qty').val(5);
                $('#l_qty_tmp').val(5);
                $('#xl').prop( "checked",true);
                $('#xl_qty').val(5);
                $('#s_qty_tmp').val(5);
                $('#allsize').prop( "checked",false);
                $('#size').val("s,m,l,xl,");
                $('#quantity_tmp').val(20);
                $('#quantity').html("20 pcs");
            });

            $('#costumize').click(function()
            {
              $('input[type=number]').attr( 'disabled',false);
              $('input[type=number]').val( 0);
            });

            function quantity(){
              var total = parseInt($('#s_qty').val())+ parseInt($('#m_qty').val()) + parseInt($('#l_qty').val()) + parseInt($('#xl_qty').val()) + parseInt($('#allsize_qty').val());
              $('#quantity').html(total + " pcs");
              $('#quantity_tmp').val(total);
              console.log($('#quantity_tmp').val());
            }

            $('#s').change(function()
            {
                var result = parseInt($('#quantity_tmp').val()) - parseInt($('#s_qty').val()) ;
                var show = result.toString();
                var size = $('#size').val().replace("allsize","");
                $('#automatic').prop("checked",false);
                $('#costumize').prop("checked",true);
                $('#allsize').prop("checked",false);
                $('#allsize_qty').attr('disabled',true);
                $('#allsize_qty').val(0);
                if ($('#s').prop( "checked" )) {
                  $('#s_qty').attr('disabled',false);

                  $(function() {
                  $('#s_qty').blur(function (){
                  quantity();
                  $('#size').val(size.concat("s,"));
                  });
                  console.log(s_qty);
                });
                }else{ 
                  $('#s_qty').attr('disabled',true);
                  $('#s_qty').val(0);
                  if (result = '') {
                    $('#quantity').html("0 pcs");   
                    $('#quantity_tmp').val(0); 
                    $('#size').val(size.replace("s,",""));
                  }else{
                    $('#quantity').html(show + " pcs");
                    $('#quantity_tmp').val(show);
                    $('#size').val(size.replace("s,",""));
                  }
                }
            });
            
            $('#m').change(function()
            {
                var result = parseInt($('#quantity_tmp').val()) - parseInt($('#m_qty').val()) ;
                var show = result.toString();
                var size = $('#size').val().replace("allsize","");;
                $('#automatic').prop("checked",false);
                $('#costumize').prop("checked",true);
                $('#allsize').prop("checked",false);
                $('#allsize_qty').attr('disabled',true);
                $('#allsize_qty').val(0);
                if ($('#m').prop( "checked" )) {
                  $('#m_qty').attr('disabled',false);
                  $(function() {
              $('#m_qty').blur(function (){
              quantity();
              $('#size').val(size.concat("m,"));
               });
            });
                }else{ 
                  $('#m_qty').attr('disabled',true);
                  $('#m_qty').val(0);
                  if (result = '') {
                    $('#quantity').html("0 pcs"); 
                    $('#quantity_tmp').val(0);
                    $('#size').val(size.replace("m,",""));   
                  }else{
                    $('#quantity').html(show + " pcs");
                    $('#quantity_tmp').val(show);
                    $('#size').val(size.replace("m,","")); 
                  }
                }
            });

            $('#l').change(function()
            {
                var result = parseInt($('#quantity_tmp').val()) - parseInt($('#l_qty').val()) ;
                var show = result.toString();
                var size = $('#size').val().replace("allsize","");;
                $('#automatic').prop("checked",false);
                $('#costumize').prop("checked",true);
                $('#allsize').prop("checked",false);
                $('#allsize_qty').attr('disabled',true);
                $('#allsize_qty').val(0);
                if ($('#l').prop( "checked" )) {
                  $('#l_qty').attr('disabled',false);
                  $(function() {
              $('#l_qty').blur(function (){
              quantity();
              $('#size').val(size.concat("l,"));
               });
            });
                }else{ 
                  $('#l_qty').attr('disabled',true);
                  $('#l_qty').val(0);
                  if (result = '') {
                    $('#quantity').html("0 pcs");  
                    $('#quantity_tmp').val(0);
                    $('#size').val(size.replace("l,","")); 
                  }else{
                    $('#quantity').html(show + " pcs");
                    $('#quantity_tmp').val(show);
                    $('#size').val(size.replace("l,","")); 
                  }
                }
            });

            $('#xl').change(function()
            {
                var result = parseInt($('#quantity_tmp').val()) - parseInt($('#xl_qty').val()) ;
                var show = result.toString();
                var size = $('#size').val().replace("allsize","");;
                $('#automatic').prop("checked",false);
                $('#costumize').prop("checked",true);
                $('#allsize').prop("checked",false);
                $('#allsize_qty').attr('disabled',true);
                $('#allsize_qty').val(0);
                if ($('#xl').prop( "checked" )) {
                  $('#xl_qty').attr('disabled',false);
                  $(function() {
              $('#xl_qty').blur(function (){
              quantity();
              $('#size').val(size.concat("xl,"));
               });
            });
                }else{ 
                  $('#xl_qty').attr('disabled',true);
                  $('#xl_qty').val(0);
                  if (result = '') {
                    $('#quantity').html("0 pcs"); 
                    $('#quantity_tmp').val(0);
                    $('#size').val(size.replace("xl,","")); 
                  }else{
                    $('#quantity').html(show + " pcs");
                    $('#quantity_tmp').val(show);
                    $('#size').val(size.replace("xl,","")); 
                  }
                }
            });

            $('#allsize').change(function()
            {
                var result = parseInt($('#quantity_tmp').val()) - parseInt($('#allsize_qty').val()) ;
                var show = result.toString();
                $('#automatic').prop("checked",false);
                $('#costumize').prop("checked",true);
                if ($('#allsize').prop( "checked" )) {
                  $('#allsize_qty').attr('disabled',false);
                  $('#s').prop("checked",false);
                  $('#s_qty').attr('disabled',true);
                  $('#s_qty').val(0);
                  $('#m').prop("checked",false);
                  $('#m_qty').attr('disabled',true);
                  $('#m_qty').val(0);
                  $('#l').prop("checked",false);
                  $('#l_qty').attr('disabled',true);
                  $('#l_qty').val(0);
                  $('#xl').prop("checked",false);
                  $('#xl_qty').attr('disabled',true);
                  $('#xl_qty').val(0);
                  $(function() {
              $('#allsize_qty').blur(function (){
              quantity();
              $('#size').val("allsize");
               });
            });
                }else{ 
                  $('#allsize_qty').attr('disabled',true);
                  $('#allsize_qty').val(0);
                  if (result = '') {
                    $('#quantity').html("0 pcs"); 
                    $('#quantity_tmp').val(0);
                    $('#size').val(size.replace("allsize","")); 
                  }else{
                    $('#quantity').html(show + " pcs");
                    $('#quantity_tmp').val(show);
                    $('#size').val(size.replace("allsize","")); 
                  }
                }
            });
    </script>