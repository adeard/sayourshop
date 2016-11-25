<section class="user-dashboard">
	<div class="container" style="padding: 0px;">
		<div class="row">
	
			@if(session('add'))
				<div class="alert alert-success">
					{{session('add')}}
				</div>
			@endif

			@if(session('fail'))
				<div class="alert alert-danger">
					{{session('fail')}}
				</div>
			@endif

			<?php // ================== User sub description ================== ?>
			<div class="span3">
				<div class="box" style="min-height:250px">
				
					@if(session('completed'))
						<div class="alert alert-success">
			  				{{session('completed')}}
						</div>
					@endif
	
					
					@if(session('failed'))
						<div class="alert alert-danger">
			  				{{session('failed')}}
						</div>
					@endif

					
					@if($data['user']->image == "")
						<div class="text-center">
							<img src="assets/image/user-image.png" class="user-image">
						</div>
					@else
						<div class="text-center">
							<img src="{{url('photo_profile/'.$data['user']->image)}}" class="user-image">
						</div>
					@endif

					<div class="text-center">
						<p class="user-name">{{ucwords($data['user']->fullname) }}</p>
						<p class="user-email"> {{$data['user']->email}} </p>
						<p>
				        	
				        	{!! Form::open(array('url'=>'upload_photopic','method'=>'POST', 'files'=>true)) !!}
				        		<div class="control-group">
				        			<div class="controls">
				        				{!! Form::file('image') !!}
										{!! Form::submit('Submit', array('class'=>'send-btn')) !!}
				        			</div>
				        		</div>		
				      		{!! Form::close() !!}

						</p>
					</div>
				</div>
			</div>
			<?php // ================== User Information ================== ?>
			<div class="span9">
				<ul class="nav nav-tabs" role="tablist">
	    			<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profil</a></li>
	    			<li role="presentation"><a href="#order" aria-controls="order" role="tab" data-toggle="tab">Daftar Pesanan</a></li>
	    			<li role="presentation"><a href="#wishlist" aria-controls="order" role="tab" data-toggle="tab">Wish List</a></li>
	    		</ul>
	    		<div class="tab-content" style="background:white;color:black;padding-bottom: 20px;">
	    						
	    			@if(session('success'))
						<div class="alert alert-success">
			  				{{session('success')}}
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger">
			  				{{session('error')}}
						</div>
					@endif

					<div role="tabpanel" class="tab-pane fade in active" id="profile">
						<input type="hidden" id="user_id" value="{{$data['user']->id}}">
						<form action="{{url('update')}}" method="POST">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
					        <label class="col-sm-2 control-label" style="margin: 10px;">Nama</label>
					        <div class="col-sm-8" style="padding: 5px;">
					        	<input type="text" class="form-control" id="fullname_input" name="fullname_input" value="{{$data['user']->fullname}}" required>
					        </div>
					        <label class="col-sm-2 control-label" style="margin: 10px;">No.Telepon</label>
					        <div class="col-sm-8" style="padding: 5px;">
					        	<input type="number" class="form-control" id="phone_input" name="phone_input" value="{{$data['user']->phone}}" required>
					        </div>
					        <label class="col-sm-2 control-label" style="margin: 10px;">&nbsp;</label>
					        <div class="col-sm-8" style="padding: 5px;">
					        	<button type="submit" class="btn btn-small btn-primary" >Simpan</button>
						    	<a href="{{ url('form_ubah_pass')}}"> 
						          	<button type="button" class="btn btn-small btn-peterriver">
						          		Ubah Password
						          	</button>
						      	</a>
					      	</div>
					    </form>
					    <div class="col-sm-11" style=" margin-bottom: 20px;">
							<div class="panel panel-success" style="margin: 20px 35px 0 10px;padding: 0px; ">
						    	<div class="panel-heading" style="margin: 0px;"><b>Alamat</b></div>
						    	<div class="panel-body">
						    		<button type="button" class="btn btn-mini btn-primary" data-toggle="modal" data-target="#add_address">Tambah</button>
						    		<br><br>
						    		<table class="table table-responsive">
						    			<thead>
						        			<tr>
						        				<th>Nama</th>
						        				<th>No Telepon</th>
						        				<th>Alamat</th>
						        				<th>Opsi</th>
						        			</tr>
						    			</thead>
						    			<tbody>
						    		
						    			@if ($data['address']) 
						    				<?php $query = unserialize($data['address']->meta_value); ?>
						        			
						        			@foreach($query as $address => $value)
						        				<tr>
						        					
						        					@foreach($value as $ind => $key)
						        						@if($ind != 'provinsi' and $ind != 'kota' and $ind != 'kecamatan')
						        							<td>{{$key}}</td>
						        						@endif
						        					@endforeach

						        					<td><a href='{{url('hapus_alamat/'.$address)}}' ><button class="btn btn-mini btn-alizarin" id="alamat_{{$address}}" name="alamat_{{$address}}">hapus</button></a></td>
						        				</tr>
						        			@endforeach

						    			@endif

						    			</tbody>
						    		</table>
						    	</div>
						    	<div class="panel-footer"></div>
						    </div>
						</div>
					    <!-- <div class="col-sm-11" style=" margin-bottom: 20px;">
							<div class="panel panel-success" style="margin: 20px 35px 0 10px;padding: 0px; ">
						    	<div class="panel-heading" style="margin: 0px;"><b>Rekening Bank</b></div>
						    	<div class="panel-body">
						    		<button type="button" class="btn btn-mini btn-primary" data-toggle="modal" data-target="#myModal">Tambah</button>
						    		<br><br>
						    		<table class="table table-responsive">
						    			<thead>
						        			<tr>
						        				<th>Bank</th>
						        				<th>No.Rekening</th>
						        				<th>Atas Nama</th>
						        				<th>Opsi</th>
						        			</tr>
						    			</thead>
						    			<tbody>
						    			<?php
						    			if ($data['rekening']) {
						    				$query = unserialize($data['rekening']->meta_value);
						    				$total_rek =count($query);
						    				for ($i=0; $i < $total_rek ; $i++) { 
						    			?>


						    				<tr>
						    					<td>{{$query[$i]['bank']}}</td>
						    					<td>{{$query[$i]['nomor_rekening']}}</td>
						    					<td>{{$query[$i]['atas_nama']}}</td>
						    					<td><a href='{{url('hapus_rek/'.$i)}}' ><button class="btn btn-mini btn-alizarin" id="no_rek_{{$i}} " name="no_rek_{{$i}} ">hapus</button></a></td>
						    				</tr>

						    			<?php
						    				}
						    			}
						    			?>
						    			</tbody>
						    		</table>
						    	</div>
						    	<div class="panel-footer"></div>
						    </div>
						</div> -->
						<!-- Modal -->
						<div id="add_address" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<form action="{{url('tambah_alamat')}}" method="POST">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <div class="modal-content">
								      	<div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal">&times;</button>
									        <h4 class="modal-title">Alamat</h4>
								      	</div>
								      	<div class="modal-body">
									      	<div class="form-group">
								                <label class="control-label">Nama</label>
								                <input type="text" class="form-control" id="name" name="name" required>
								          	</div>
								          	<div class="form-group">
								                <label class="control-label">No Telepon</label>
								                <input type="text" class="form-control" id="phone" name="phone" required>
								          	</div>
								          	<div class="form-group">
								                <label class="control-label">Provinsi</label>
								                <select name="province" id="province" class="form-control" >
		                		                    <option value="">-- Silahkan Pilih --</option>
		                		                    
		                		                    @foreach($data['province'] as $province)
		                		                        <option value="{{$province->id}}">{{$province->nama_province}}
		                		                    @endforeach

		                		                </select>
								          	</div>
								          	<div class="form-group">
			            		                <label class="control-label">Kota/Kabupaten</label>
			            		            	<select name="city" id="city" class="form-control"></select>
			            		            </div>
			            		            <div class="form-group">
			            		                <label class="control-label">Kecamatan</label>
			            		                <select name="district" id="district" class="form-control" ></select>
			            		            </div>
								          	<div class="form-group">
								                <label class="control-label">Detail Alamat</label>
								                <input type="text" class="form-control" id="address" name="address" required>
								          	</div>
								      	</div>
								      	<div class="modal-footer">
								      		<button type="submit" class="btn btn-primary">Tambah</button>
								        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								      	</div>
								    </div>
								</form>
							</div>
						</div>

						<!-- Modal -->
						<div id="myModal" class="modal fade" role="dialog">
							<div class="modal-dialog">
							    <!-- Modal content-->
							    <form action="{{url('tambah_rek')}}" method="GET">
								    <div class="modal-content">
								      	<div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal">&times;</button>
									        <h4 class="modal-title">Modal Header</h4>
								      	</div>
								      	<div class="modal-body">
									      	<div class="form-group">
								                <label class="control-label">Bank</label>
								                <input type="text" class="form-control" id="bank" name="bank" required>
							              	</div>

							              	<div class="form-group">
								                <label class="control-label">Nomor Rekening</label>
								                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
							              	</div>
							              	<div class="form-group">
								                <label class="control-label">Atas Nama</label>
								                <input type="text" class="form-control" id="account_name" name="account_name" required>
							              	</div>
								      	</div>
								      	<div class="modal-footer">
								      		<button type="submit" class="btn btn-primary">Tambah</button>
								        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								      	</div>
								    </div>
							    </form>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="order">
						<table class="table table-responsive" id="order_tbl">
							<thead>
								<tr>
									<th>Invoice</th>
									<th>Tanggal Pemesanan</th>
									<th>Detail</th>
					                <th>Total Harga</th>
					                <th>Status</th>
					                <th>No. Resi</th>
					                <th>Opsi</th>
								</tr>
							</thead>
					        <tbody>
						        <form action="{{url('konfirmasi_pembayaran')}}">
						        
						        @foreach($data['order'] as $value)
							        <tr>
							        	<td id="inv_{{$value->id}}" name="inv_{{$value->id}}">{{$value->no_invoice}}</td>
							        	<td>{{ date_format(date_create($value->order_date), "d M Y")}}</td>
							        	<td><button type="button" id="detail_{{$value->id}}" name="detail_{{$value->id}}" class="btn btn-mini btn-belizehole detail">Detail</button> </td>
							        	<td name="total_price" id="total_price">Rp. {{ number_format($value->total_price, 0, ",", ".") }}</td>
							        	<td>{{$value->order_status}}</td>
							        	<td>{{$value->no_resi}}</td>
							        	
							        	@if ($value->order_status == 'Menunggu Pembayaran')
							            	<td>
							            		<button type="submit" id="payment" name="payment" class="btn btn-mini btn-greensea" value="{{$value->no_invoice}}">Pembayaran</button>
							            	</td>
							            @elseif($value->order_status == 'Dikirim')
							            	<td>
							            		<button type="button" class="btn btn-mini btn-greensea receive" id="review" name="{{$value->id}}" value="{{$value->no_resi}}">Diterima</button>
							            	</td>
							        	@else
							            	<td>
							            		<button type="button" disabled="true" class="btn btn-mini btn-greensea">Pembayaran</button>
							            	</td>
							        	@endif

							        </tr>
						        @endforeach

						        </form>
					        </tbody>
						</table>
						<!-- Modal -->
						<div id="modaldetail"></div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="wishlist">
						
						@foreach($data['wishlist'] as $wish)
							<?php $image = unserialize($wish->image); ?>
							<div class="text-center pull-left col-sm-3" style="padding-top:10px; ">
								<img src=" {{url('photo_product/'.$image[0])}}" class="product" style="width: 150px;"><br> <br>
								<a href="{{url('produk/'.$wish->category->slug.'/'.$wish->subcategory->slug.'/'.$wish->id)}}"><button type="button" class="btn btn-mini btn-primary">{{$wish->name}}</button></a>
								<button type="button" class="btn btn-mini btn-alizarin del_wish" id="{{$wish->id}}">Hapus</button>
							</div>
						@endforeach

					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function(){
		$('.del_wish').click(function(){
			var id = this.id;

			$.ajax({
				url: "{!! url('del_wishlist') !!}",
				data: {product_id: id},
                method:'POST',
			}).done(function(data){
				alert("Produk telah dihapus dari wishlist");
				location.reload();
			});
		});

		$('.detail').click(function(){
			var id = this.id.substr(7);

			$.ajax({
				url: "{!! url('order_detail') !!}",
				data: {orderid: id},
                method:'GET',
			}).done(function(data){
				$('#modaldetail').html(data);
			});
		});

		$('#province').change(function(){
            var id = $('#province').val();

            $.ajax({
                url: "{!! url('konten_kota') !!}",
                data: {id: id},
                method:'GET',
            }).done(function(data){
                $('#city').html(data);
            });
        });

        $('#city').change(function(){
            var id = $('#city').val();

            $.ajax({
                url: "{!! url('konten_kecamatan') !!}",
                data: {id: id},
                method:'GET',
            }).done(function(data){
                $('#district').html(data);
            });
        });

        $('#review').click(function(){
        	var resi = this.value;
        	var order_id = this.name;

        	$.ajax({
                url: "{!! url('modal_review') !!}",
                data: {
                	resi: resi,
                	order_id: order_id
                },
                method:'POST',
            }).done(function(data){
                $('#modaldetail').html(data);
            });
        });

        $('#modaldetail').on('hidden.bs.modal', function (){
			var user_id = $('#user_id').val();

			$.ajax({
				url: "{!! url('update_order') !!}",
				data: { user_id:user_id },
				method:'GET',
			}).done(function(data){
				$('#order').html(data);
			});
		});
	});
</script>