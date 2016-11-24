<!-- Content section -->		
<section class="main">
    <section class="check_order">
        <div class="container">
            <div class="row">
                <div class="span6 offset3">
                    @if(session('failed'))
                        <div class="alert alert-danger">
                            {{session('failed')}}
                        </div>
                    @endif
                    <form enctype="multipart/form-data" action="{{url('check_order')}}" method="get" />
                        <div class="box">
                            <div class="hgroup title">
                                <h3>Masukkan Nomor Invoice </h3>
                            </div>
                            <div class="box-content">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input class="span12" type="text" id="invoice" name="invoice" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <button class="btn btn-primary btn-small" type="submit">
                                    Cek Pesanan
                                </button>                                            
                            </div>
                        </div>
                    </form>		
                </div>
            </div>
        </div>	
    </section>           
</section>
<!-- End class="main" -->