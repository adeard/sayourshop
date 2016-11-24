<div class="modal fade" id="detail">
  <div class="modal-dialog" style="width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">
          Review Produk
        </h5>

      </div>
      <div class="modal-body">
        <table class="table table-responsive">
          
          @foreach($data['order'] as $order)
            <?php
              $image = unserialize($order->product->image);
            ?>
            <tr>
              <th style="text-align: center;">
                <img src="{{url('photo_product/'.$image[0])}}" style="width: 100px;"><br>
                {{ucwords($order->product->name)}}
              </th>
              <td>
              
              @if($order->review == 'reviewed')
                <div class="alert alert-success" id="alert_{{$order->product->id}}">Terima kasih atas testimonial Anda</div>
              @else
                <div class="alert alert-success" hidden="true" id="alert_{{$order->product->id}}">Terima kasih atas testimonial Anda</div>
                <div id="comment_{{$order->product->id}}">
                  <textarea rows="5" placeholder="Berikan testimoni Anda" id="review_{{$order->product->id}}"></textarea>
                  <div class="col-sm-12">
                    <input id="input-id_{{$order->product->id}}" name="input-1" type="number" class="rating rating-loading" data-min=0 data-step="1" data-max=5 data-size="xs">
                  </div>
                  <button type="button" id="{{$order->product->id}}" name="{{$order->order_id}}" class=" btn btn-mini btn-primary submit_rev">Kirim Review</button>
                </div>
              @endif

              </td>
            </tr>
          @endforeach

        </table>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-mini" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function()
  {
    $('#detail').modal('show');
    $(".rating").rating();

    $('.submit_rev').click(function(){
      var product_id = this.id;
      var order_id = this.name;
      var rating = $('#input-id_' + product_id).val();
      var review = $('#review_' + product_id).val();
      
      if (rating >= 1) {
        $.ajax({
            url: "{!! url('add_review') !!}",
            data: {
              product_id: product_id,
              rating: rating,
              review: review,
              order_id: order_id
            },
            method:'POST',
        }).done(function(data){
            $('#comment_' + product_id).hide();
            $('#alert_' + product_id).show('slow');
        });
      }

    });
});
</script>