@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">


  <div class="row">
    <div class="col-12 text-left">
      <h1>Dashboard Opname Harian</h1>
    </div>
  </div>

</div>

<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid">


    <div class="row">
      <div class="col-lg-2">
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
            <label>Tipe</label>
          </div>
          </div>
          <div class="col-lg-8">
            <div class="form-group input-group">
              <select id="input_tipe" onchange="onchangeTipe()" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                <option value=0 selected>Bulanan</option>
                <option value=1>Tahunan</option>
                <option value=2>Semester</option>


              </select>
              </div>

          </div>

        </div>

      </div>
      <div class="col-lg-4">
        <div class="row">
          <div class="col-lg-2">
            <div class="form-group">
            <label>Gudang</label>
          </div>
          </div>
          <div class="col-lg-8">
            <div class="form-group input-group">
              <select id="input_gudang" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                <!-- <option value='BKK' selected>BKK</option>
                <option value='BKM' >BKM</option> -->
                @for ($i = 0; $i < count($gudang); $i++)
                  <option value='{{ $gudang[$i]->kodegdg }}' >{{ $gudang[$i]->kodegdg }} - {{ $gudang[$i]->nama }} </option>

                  @endfor

              </select>
              </div>

          </div>

          <div class="col-lg-2">
            <div class="row text-right">
              <button class="btn btn-primary btn-sm text-right" id="buttonSubmit" onclick="buttonSubmit()"><i class="bi bi-search"></i></button>

            </div>
          </div>

        </div>

      </div>


    </div>

    <div class="row">

    <div id="divbulan" class="col-lg-2" class="showhide showhidebulan" >
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
          <label class="">Bulan</label>
        </div>
        </div>
        <div class="col-lg-8">
          <div class="form-group input-group">
            <select id="input_bulan" class=" form-control form-select-lg mb-3" aria-label=".form-select-lg example"  >
              <option value=1>Januari</option>
              <option value=2>Februari</option>
              <option value=3>Maret</option>
              <option value=4>April</option>
              <option value=5>Mei</option>
              <option value=6>Juni</option>
              <option value=7>Juli</option>
              <option value=8>Agustus</option>
              <option value=9>September</option>
              <option value=10>Oktober</option>
              <option value=11>November</option>
              <option value=12>Desember</option>


            </select>
            </div>

        </div>

      </div>

    </div>
    <div id="divtahun" class="col-lg-2" class="showhide showhidetahun">
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
          <label>Tahun</label>
        </div>
        </div>
        <div class="col-lg-8">
          <div class="form-group input-group">
              <input type="number" class="form-control text-right" id="input_tahun" value="{!! date('Y') !!}" >
            </div>

        </div>

      </div>

    </div>


    <div id="divsemester" class="col-lg-2" class="showhide showhidesemester">
      <div class="row">
        <div class="col-lg-4">
          <div class="form-group">
          <label>Semester</label>
        </div>
        </div>
        <div class="col-lg-8">
          <div class="form-group input-group">
            <select id="input_semester" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example"  >
              <option value=1>Semester 1</option>
              <option value=2>Semester 2</option>


            </select>
            </div>

        </div>

      </div>

    </div>




</div>

<div class="row">
  <div class="col-lg-12">
    <h3>Monitoring Audit Harian</h3>
  </div>
</div>
<div class="row" >

  <div
        class="card col-lg-2 text-center"
        style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5; background-color: #edf0f5"
      >

          <div class="" style="margin-top: 20px">
            <h4 class="card-title">
                Target Audit
            </h4>
            <div class="container-fluid" style="margin: 0px" >
              <div class="row">
                <div class="col-lg-12">
                  <h2 style="color: #2372fa" id="input_targetaudit">{{ $res[0]->terhitung ? number_format($res[0]->targetaudit , 0 ,'.' , ',') : 0 }}</h2>
                </div>

              </div>
              <div class="row" style="margin-top: -20px">
                <div class="col-lg-12">
                  <h4>Item</h4>

                </div>

              </div>

              <div class="row" style="margin-top: -20px">
                <div class="col-lg-12 text-right">
                  <h4></h4>

                </div>

              </div>

            </div>


          </div>
      </div>
      <div
            class="card col-lg-2 text-center"
            style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
          >

              <div class="" style="margin-top: 20px">
                <h4 class="card-title">
                    Terhitung
                </h4>
                <div class="container-fluid" style="margin: 0px" >
                  <div class="row">
                    <div class="col-lg-12">
                      <h2 style="color: #f7af43" id="input_terhitung">{{ $res[0]->terhitung ? number_format($res[0]->terhitung , 0 ,'.' , ',') : 0 }}</h2>


                    </div>

                  </div>
                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12">
                      <h4>Item</h4>

                    </div>

                  </div>

                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12 text-right">
                      <h4 style="color: #f7af43" id="input_terhitungpersen">{{ $res[0]->terhitung ? number_format($res[0]->terhitungpersen , 2 ,'.' , ',') : 0 }}%</h4>

                    </div>

                  </div>

                </div>


              </div>
          </div>
          <div
                class="card col-lg-2 text-center"
                style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
              >

                  <div class="" style="margin-top: 20px">
                    <h4 class="card-title">
                        Belum Terhitung
                    </h4>
                    <div class="container-fluid" style="margin: 0px" >
                      <div class="row">
                        <div class="col-lg-12">
                          <h2 style="color: #fc2121" id="input_belumterhitung">{{ $res[0]->terhitung ? number_format($res[0]->belumterhitung , 0 ,'.' , ',') : 0 }}</h2>
                        </div>

                      </div>
                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12">
                          <h4>Item</h4>

                        </div>

                      </div>

                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12 text-right">
                          <h4 style="color: #f7af43" id="input_belumterhitungpersen">{{ $res[0]->terhitung ? number_format($res[0]->belumterhitungpersen , 2 ,'.' , ',') : 0 }}%</h4>

                        </div>

                      </div>

                    </div>


                  </div>
              </div>
                <div
                      class="card col-lg-2 text-center"
                      style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
                    >

                        <div class="" style="margin-top: 20px">
                          <h4 class="card-title">
                              Pencapaian Audit
                          </h4>
                          <div class="container-fluid" style="margin: 0px" >
                            <div class="row">
                              <div class="col-lg-12">
                                <h2 style="color: #fc2121" id="input_pencapaianaudit">{{ $res[0]->terhitung ? number_format($res[0]->pencapaianaudit , 2 ,'.' , ',') : 0 }}%</h2>
                              </div>

                            </div>
                            <div class="row" style="margin-top: -20px">
                              <div class="col-lg-12">
                                <h4></h4>

                              </div>

                            </div>

                            <div class="row" style="margin-top: -20px">
                              <div class="col-lg-12 text-right">
                                <h4></h4>

                              </div>

                            </div>

                          </div>


                        </div>
                    </div>



</div>



<div class="row">
  <div class="col-lg-12">
    <h3>Monitoring Hasil Audit Harian</h3>
  </div>
</div>
<div class="row" >

  <div
        class="card col-lg-2 text-center"
        style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
      >

          <div class="" style="margin-top: 20px">
            <h4 class="card-title">
                Sesuai
            </h4>
            <div class="container-fluid" style="margin: 0px" >
              <div class="row">
                <div class="col-lg-12">
                  <h2 style="color: #2372fa" id="input_sesuai">{{ $res[0]->terhitung ? number_format($res[0]->sesuai , 0 ,'.' , ',') : 0 }}</h2>
                </div>

              </div>
              <div class="row" style="margin-top: -20px">
                <div class="col-lg-12">
                  <h4>Item</h4>

                </div>

              </div>

              <div class="row" style="margin-top: -20px">
                <div class="col-lg-12 text-right">
                  <h4></h4>

                </div>

              </div>

            </div>


          </div>
      </div>
      <div
            class="card col-lg-2 text-center"
            style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
          >

              <div class="" style="margin-top: 20px">
                <h4 class="card-title">
                    Selisih Kurang
                </h4>
                <div class="container-fluid" style="margin: 0px" >
                  <div class="row">
                    <div class="col-lg-12">
                      <h2 style="color: #f7af43" id="input_selisihkurang">{{ $res[0]->terhitung ? number_format($res[0]->selisihkurang , 0 ,'.' , ',') : 0 }}</h2>
                    </div>

                  </div>
                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12">
                      <h4>Item</h4>

                    </div>

                  </div>

                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12 text-right">
                      <h4 style="color: #f7af43" id="input_selisihkurangpersen">{{ $res[0]->terhitung ? number_format($res[0]->selisihkurangpersen , 2 ,'.' , ',') : 0 }}%</h4>

                    </div>

                  </div>

                </div>


              </div>
          </div>
          <div
                class="card col-lg-2 text-center"
                style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
              >

                  <div class="" style="margin-top: 20px">
                    <h4 class="card-title">
                        Sesuai Lebih
                    </h4>
                    <div class="container-fluid" style="margin: 0px" >
                      <div class="row">
                        <div class="col-lg-12">
                          <h2 style="color: #fc2121" id="input_sesuailebih">{{ $res[0]->terhitung ? number_format($res[0]->sesuailebih , 0 ,'.' , ',') : 0 }}</h2>
                        </div>

                      </div>
                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12">
                          <h4>Item</h4>

                        </div>

                      </div>

                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12 text-right">
                          <h4 style="color: #f7af43" id="input_sesuailebihpersen">{{ $res[0]->terhitung ? number_format($res[0]->sesuailebihpersen , 2 ,'.' , ',') : 0 }}%</h4>

                        </div>

                      </div>

                    </div>


                  </div>
              </div>
                <div
                      class="card col-lg-2 text-center"
                      style="margin:5px; height: 200px; max-width: 240px; background-color: #edf0f5"
                    >

                        <div class="" style="margin-top: 20px">
                          <h4 class="card-title">
                              First Different
                          </h4>
                          <div class="container-fluid" style="margin: 0px" >
                            <div class="row">
                              <div class="col-lg-12">
                                <h2 style="color: #fc2121" id="input_firstdifferent">{{ $res[0]->terhitung ? number_format($res[0]->firstdifferent , 2 ,'.' , ',') : 0 }}%</h2>
                              </div>

                            </div>
                            <div class="row" style="margin-top: -20px">
                              <div class="col-lg-12">
                                <h4></h4>

                              </div>

                            </div>

                            <div class="row" style="margin-top: -20px">
                              <div class="col-lg-12 text-right">
                                <h4></h4>

                              </div>

                            </div>

                          </div>


                        </div>
                    </div>

</div>




</div>













@endsection

@section('js')
<script type="text/javascript">

let noUrutTable = 1
let nomor = ''

$(document).ready(function(){
  document.getElementById('input_bulan').value = new Date().getMonth() + 1
  $("#divsemester").attr("style", "display:none");

});



function onchangeTipe () {
    let tipe = $("#input_tipe").val();
    // $(".showhide").removeAttr("style").hide();
    // $(".showhide").css("display","none")
    // $(".showhide").hide()
    $("#divbulan").attr("style", "display:none");
    $("#divtahun").attr("style", "display:none");
    $("#divsemester").attr("style", "display:none");

    if (tipe == 0) {
      $("#divbulan").attr("style", "display:block");
      $("#divtahun").attr("style", "display:block");

    } else if (tipe == 1) {
      $("#divtahun").attr("style", "display:block");

    } else {
      $("#divsemester").attr("style", "display:block");

    }
}

function buttonSubmit () {



    // return
    let gudang = $("#input_gudang").val();
    let tipe = $("#input_tipe").val();
    let bulan = $("#input_bulan").val();
    let tahun = $("#input_tahun").val();
    let semester = $("#input_semester").val();
    // let tglawal = $("#input_tglawal").val();
    // let tglakhir = $("#input_tglakhir").val();
    console.log(tipe,gudang)
    console.log(bulan, tahun, semester)
    $.ajax({
      url: "{!! url('dashboardopnamehariansp') !!}",
      type: "get",
      async: false,
      data: {
        tipe,gudang,bulan, tahun, semester
      },
      success: function(res) {
      console.log(res)

      if (res.length) {
        document.getElementById("input_targetaudit").innerHTML = formatAngkaDashboard(res[0].targetaudit)
        document.getElementById("input_terhitung").innerHTML = formatAngkaDashboard(res[0].terhitung)
        document.getElementById("input_terhitungpersen").innerHTML = formatAngkaDashboard(res[0].terhitungpersen , 1)
        document.getElementById("input_belumterhitung").innerHTML = formatAngkaDashboard(res[0].belumterhitung)
        document.getElementById("input_belumterhitungpersen").innerHTML = formatAngkaDashboard(res[0].belumterhitungpersen , 1)
        document.getElementById("input_pencapaianaudit").innerHTML = formatAngkaDashboard(res[0].pencapaianaudit , 1)

        document.getElementById("input_sesuai").innerHTML = formatAngkaDashboard(res[0].sesuai)
        document.getElementById("input_selisihkurang").innerHTML = formatAngkaDashboard(res[0].selisihkurang)
        document.getElementById("input_selisihkurangpersen").innerHTML = formatAngkaDashboard(res[0].selisihkurangpersen , 1)
        document.getElementById("input_sesuailebih").innerHTML = formatAngkaDashboard(res[0].sesuailebih)
        document.getElementById("input_sesuailebihpersen").innerHTML = formatAngkaDashboard(res[0].sesuailebihpersen, 1)
        document.getElementById("input_firstdifferent").innerHTML = formatAngkaDashboard(res[0].firstdifferent , 1)
      } else {
        document.getElementById("input_targetaudit").innerHTML = '0'
        document.getElementById("input_terhitung").innerHTML = '0'
        document.getElementById("input_terhitungpersen").innerHTML = '0' + '%'
        document.getElementById("input_belumterhitung").innerHTML = '0'
        document.getElementById("input_belumterhitungpersen").innerHTML = '0' + '%'
        document.getElementById("input_pencapaianaudit").innerHTML = '0' + '%'

        document.getElementById("input_sesuai").innerHTML = '0'
        document.getElementById("input_selisihkurang").innerHTML = '0'
        document.getElementById("input_selisihkurangpersen").innerHTML = '0' + '%'
        document.getElementById("input_sesuailebih").innerHTML = '0'
        document.getElementById("input_sesuailebihpersen").innerHTML = '0' + '%'
        document.getElementById("input_firstdifferent").innerHTML = '0' + '%'
      }


    }});



}

function formatAngkaDashboard (number , tipe = 0) {


  if (!number) {
    if (tipe == 0) {
      return '0'
    } else {
      return '0%'
    }
  } else {
    if (tipe == 0) {
      return parseFloat(number).toFixed(0)
    } else {
      return parseFloat(number).toFixed(2) + '%'
    }

  }




}



</script>




@endsection
