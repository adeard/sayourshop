<!-- Content section -->		
<section class="main">
    <!-- Reset password -->
    <section class="reset_password">
        <div class="container">
            <div class="row">
                <div class="span6 offset3">
                    <form action="{{url('ubah_pass')}}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box">
                        
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

                            <div class="hgroup title">
                                <h3>Ubah Password</h3>
                            </div>
                            <div class="box-content">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label" for="email">Password Lama</label>
                                            <div class="controls">
                                                <input class="form-control" type="password" id="password" name="password" placeholder="Masukkan Password Lama"  />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="email">Password Baru</label>
                                            <div class="controls">
                                                <input class="form-control" type="password" id="new_pass" name="new_pass" placeholder="Masukkan Password Baru" />
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="email">Konfirmasi Password Baru</label>
                                            <div class="controls">
                                                <input class="form-control" type="password" id="re_new_pass" name="re_new_pass" placeholder="Masukkan Password Baru"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button class="btn btn-primary btn-small" type="submit">
                                    Ubah Password
                                </button>                                            
                            </div>
                        </div>
                    </form>		
                </div>
            </div>
        </div>	
    </section>                
    <!-- End Reset password -->
</section>
<!-- End class="main" -->