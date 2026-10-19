@extends('newmaster')
@section('page-title', 'Ganti Password')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">
{{-- <h1>Ganti Password</h1> - judul pindah ke header (page-title) --}}
<!-- <button onclick="loadAll()">tes</button> -->
</div>

<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid">

  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />


  <div class="row mt-6">
              <!-- <div class="col-12 text-right">
                  <button type="button" class="btn btn-primary btn-lg " style="height: 60px; " onclick="buttonAdd()"  >Add Koreksi Stock Gudang</button>
              </div> -->
          </div>
          <div class="row text-left justify-content-center">
            <div class="card wms-form-kartu bg-light mb-3" >
            <div class="card-header">Ganti Password</div>
            <div class="card-body">




              <div class="row">
                <div class="col-12 text-left">
                  <div class="form-group text-left">
                    <label class="text-left">Password</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <input type="password" class="form-control" id="input_gantipassword_password" placeholder="Password">
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-12 text-left">
                  <div class="form-group text-left">
                    <label class="text-left">Repeat Password</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <input type="password" class="form-control" id="input_gantipassword_password2" placeholder="Repeat Password">
                  </div>
                </div>

              </div>


              <div class="row text-right mt-4">
                <div class="col-12">
                  <a href="#" onclick="submitGantiPassword()" class="btn text-right btn-primary">Simpan</a>
                </div>
              </div>
            </div>
          </div>


          </div>

</div>










@endsection

@section('js')
<script type="text/javascript">




  function submitGantiPassword () {

    let _token = $("#_token").val();
    // let username = $("#input_gantipassword_username").val();
    let password = $("#input_gantipassword_password").val();
    let password2 = $("#input_gantipassword_password2").val();
    // let isagen = 0

    // console.log('password', password)
    // console.log('password2', password2)

    if (!password) {
      alertify.warning('password harus diisi')
      return
    }

    if (password != password2) {
      alertify.warning('password harus sama')
      return
    }



    // if (document.getElementById("input_add_isagen").checked) {
    //   isagen= 1
    // }
    // if (!kode) {
    //   alertify.warning("Kode harus diisi");
    //   return
    // }
    //
    // if (!nama) {
    //   alertify.warning("Nama harus diisi");
    //   return
    // }



    // return

    // return
    // return
    $.ajax({
      url: "{!! url('gantipassworduser') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        // username,
        password,
      },
      success: function(res) {

        if (res != 1) {
          alertify.warning(res);
        }  else {
          console.log(res ,'!')
          // $("#formEdit").modal('toggle')
          alertify.success("User telah ganti password");
          // loadAll()
          // $("#formGantiPassword").modal('toggle')
          document.getElementById("input_gantipassword_password").value = ''
          document.getElementById("input_gantipassword_password2").value = ''
        }

      },
      error: function (err) {
        // xhr, ajaxOptions, thrownError
        // alert(xhr.status);
        // alert(thrownError);
        alertify.warning('error')
        console.log(err)
      }})

    // console.log(kodearea, namaarea)
  }




</script>




@endsection
