<div class="modal fade" id="detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          Detail Pesanan
        </h4>
      </div>
      <div class="modal-body" style="padding: 0px;">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#detail-order" data-toggle="tab">Pemesanan</a></li>
          <li><a href="#detail-product" data-toggle="tab">Produk</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade in active" id="detail-order">
            <table class="table table-responsive">
              <tr>
                <th>No Invoice</th>
                <td>: {{$data['detail'][0]->order->no_invoice}}</td>
              </tr>
              <tr>
                <th>Pemesan</th>
                <td>: {{$data['detail'][0]->order->order_name}}</td>
              </tr>
              <tr>
                <th>No.Telepon</th>
                <td>: {{$data['detail'][0]->order->order_phone}}</td>
              </tr>
              <tr>
                <th>Tujuan Pengiriman</th>
                <td>: {{$data['detail'][0]->order->order_address}}, {{$data['detail'][0]->order->district->name}}, {{$data['detail'][0]->order->city->nama}}, {{$data['detail'][0]->order->province->name}}</td>
              </tr>
              <tr>
                <th>Tanggal Pemesanan</th>
                <td>: {{ date_format(date_create($data['detail'][0]->order->order_date), "d M Y")}}</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>: {{$data['detail'][0]->order->order_status}}</td>
              </tr>
              <tr>
                <th>Subtotal</th>
                <td>: Rp. {{ number_format($data['detail'][0]->order->total_price + $data['detail'][0]->order->total_discount - $data['detail'][0]->order->shipping_price, 0, ",", ".") }}</td>
              </tr>
              <tr>
                <th>Kupon</th>
                <td>: {{$data['detail'][0]->order->discount_code}}</td>
              </tr>
              <tr>
                <th>Total Diskon</th>
                <td>: {{$data['detail'][0]->order->total_discount}}</td>
              </tr>
              <tr>
                <th>Ongkos Kirim</th>
                <td>: Rp. {{ number_format($data['detail'][0]->order->shipping_price, 0, ",", ".") }}</td>
              </tr>
              <tr>
                <th>Total Biaya</th>
                <td>: Rp. {{ number_format($data['detail'][0]->order->total_price, 0, ",", ".") }}</td>
              </tr>
            </table>
          </div>
          <div class="tab-pane fade" id="detail-product">
            <table class="table table-responsive" style="text-align: center;">
              <thead>
                <tr>
                  <th style="text-align: center;">Produk</th>
                  <th style="text-align: center;">Attribut</th>
                  <th style="text-align: center;">Jumlah</th>
                  <th style="text-align: center;">Total Harga</th>
                  <th style="text-align: center;">Total Berat</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data['detail'] as $order)
                <tr>
                  <td>{{$order->product->name}}</td>
                  <td>
                  <?php $properties = unserialize($order->properties)?>
                  @foreach( $properties as $value)
                      {{$value}}&nbsp;
                  @endforeach
                  </td>
                  <td>{{$order->quantity}}</td>
                  <td>Rp. {{ number_format($order->total_price, 0, ",", ".") }}</td>
                  <td>{{number_format($order->total_weight, 0, ",", ".")}} g</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
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
});
</script>