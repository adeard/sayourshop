<section class="main">
    <section class="static_page_1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                            
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{session('success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{session('error')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <section class="static-page">
                        <div class="row-fluid">
                            <div class="col-lg-10">
                                <div class="content">
                                    <h3>Kontak Kami</h3>
                                    <hr />
                                    <div class="row-fluid">
                                        <div class="col-md-4">
                                            <h5><em class="icon-map-marker icon-larger"></em> Sayourshop</h5>
                                            <p>Jln. Narogong Megah 4 blok E15 no 5<br />Kota Bekasi, Bekasi Timur<br />17115</p>
                                        </div>
                                        <div class="col-md-4">
                                            <h5><em class="icon-phone icon-larger"></em> Hubungi Kami</h5>
                                            <p><strong>sayourshop.official@gmail.com</strong><br>
                                            <strong>+6212345678910</strong></p>
                                        </div>
                                    </div>
                                    <hr />
                                    <form id="form" enctype="multipart/form-data" action="{{url('contact')}}" role="POST" />
                                        <div class="row-fluid">
                                            <div class="col-lg-6">
                                                <div class="control-group">
                                                    <label for="name" class="control-label">Nama</label>
                                                    <div class="controls">
                                                        <input type="text" name="name" id="name" value="<?= Sentinel::check() ? ucwords(Sentinel::getUser()->fullname) : "" ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="control-group">
                                                    <label for="email" class="control-label">Email</label>
                                                    <div class="controls">
                                                        <input type="text" name="email" id="email" value="<?= Sentinel::check() ? Sentinel::getUser()->email : "" ?>" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-lg-6">
                                                <div class="control-group">
                                                    <label for="subject" class="control-label">Judul</label>
                                                    <div class="controls">
                                                        <input type="text" name="subject" id="subject" value="" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="control-group">
                                                    <label for="order_no" class="control-label">Tipe</label>
                                                    <div class="controls">
                                                        <select name="type" id="type" class="form-control">
                                                            <option value="saran">Saran</option>
                                                            <option value="pertanyaan">Pertanyaan</option>
                                                            <option value="keluhan">Keluhan</option>
                                                            <option value="lainnya">Lainnya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="text" name="any-else" id="any-else" value="" placeholder="lainnya..." class="form-control">
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-lg-12">
                                                <div class="control-group">
                                                    <label for="message" class="control-label">Pesan</label>
                                                    <div class="controls">
                                                        <textarea rows=5 name="message" id="message" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                                            </div>
                                        </div>
                                    </form>							
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>    
</section>

@section('script')
<script type='text/javascript'>
    $("#any-else").hide();
    $("#type").change(function(){
        if($(this).val()=='lainnya'){
            $("#any-else").show();
        }else{
            $("#any-else").hide();
            $("#any-else").val("");
        }
    });
</script>
@stop