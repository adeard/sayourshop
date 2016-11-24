<section class="register">
	<div class="container">
		<div class="row">

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

			<div class="col-lg-12" align="center">
				<div class="box" style="min-height:280px">
					<div class="col-lg-12">
						<h4> Form Pendaftaran</h4>
						<form class="form-horizontal" action="{{ url('register') }}" method="POST" role="POST">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
				            <div class="form-group">
				            	<label class="col-sm-3 control-label">Email<font color="red">*</font></label>
				            	<div class="col-sm-6">
				            		<input type="email" class="form-control" id="email_input" name="email_input" placeholder="Example@email.com" required="true">
				            	</div>
				            </div>
				            <div class="form-group">
				            	<label for="inputPassword" class="col-sm-3 control-label">Password<font color="red">*</font></label>
				            	<div class="col-sm-6">
				            	  <input type="password" class="form-control" id="pass_input" name="pass_input" placeholder="Minimal 6 Digit" required="true" minlength="6">
				            	</div>
				            </div>
				            <div class="form-group">
				            	<label for="inputPassword" class="col-sm-3 control-label">Ulangi Password<font color="red">*</font></label>
				            	<div class="col-sm-6">
				            		<input type="password" class="form-control" id="re_pass_input" name="re_pass_input" placeholder="Minimal 6 Digit" required="true" minlength="6">
				            	</div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-3 control-label">Nama Lengkap<font color="red">*</font></label>
				            	<div class="col-sm-6">
				                	<input type="text" class="form-control" id="fullname_input" name="fullname_input" placeholder="Johny van Hawk" required="true">
				              	</div>
				            </div>
				            <div class="form-group">
				            	<label class="col-sm-3 control-label">No.Telepon<font color="red">*</font></label>
				            	<div class="col-sm-6">
				            	  	<input type="text" class="form-control" id="phone_input" name="phone_input" placeholder="0812345789" required="true">
				            	</div>
				            </div>
				            <div class="col-sm-3"></div>
				            <p class="help-block text-left col-sm-6" style="color:red;font-size: 11px;"><i>*) wajib diisi</i></p>
				            <div class="col-lg-12">
					            <div class="checkbox">
								    <label>
								    	<input type="checkbox" id="accept" required="true"> Saya setuju dengan <a href="{{url('terms_conditions')}}" style="color:blue">Syarat dan Ketentuan</a> & <a href="{{url('terms_conditions')}}" style="color:blue">Kebijakan Privasi</a> yang berlaku
								    </label>
							  	</div>
							</div>
							<br/><br/>
				            <div class="form-group">
				            	<div class="col-lg-12">
				            	<p class="text-right">
				            		<button type="submit" class="btn btn-small btn-belizehole pull-right" id="register_btn">Register</button>
				            	</div>
				            </div>
				        </form>
			        </div>
			        <div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</section>

@section('script')
	<script>
		$("#phone_input").on("keydown", function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
	</script>
@stop