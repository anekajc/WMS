@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">

  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />

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
        <div class="col-lg-3">
          <div class="form-group">
          <label>Tgl Awal</label>
        </div>
        </div>
        <div class="col-lg-9">
          <div class="form-group input-group">
              <input type="date" class="form-control" id="input_tglawal" value="{!! date('Y-m-d') !!}" >
            </div>

        </div>

      </div>

    </div>
    <div class="col-lg-2">
      <div class="row">
        <div class="col-lg-3">
          <div class="form-group">
          <label>Tgl Akhir</label>
        </div>
        </div>
        <div class="col-lg-9">
          <div class="form-group input-group">
              <input type="date" class="form-control" id="input_tglakhir" value="{!! date('Y-m-d') !!}" >
            </div>

        </div>

      </div>

    </div>
    <div class="col-lg-3">
      <div class="row">
        <div class="col-lg-2">
          <div class="form-group">
          <label>Gudang</label>
        </div>
        </div>
        <div class="col-lg-10">
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

      </div>

    </div>

    <div class="col-lg-2">
      <div class="row text-left">
        <button class="btn btn-primary btn-sm text-right" id="buttonSubmit" onclick="buttonSubmit()"><i class="bi bi-search"></i></button>

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
                  <h2 style="color: #2372fa" id="input_targetaudit">{{  $res[0]->targetaudit }}</h2>
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
                      <h2 style="color: #f7af43" id="input_terhitung">{{  $res[0]->terhitung  }}</h2>
                    </div>

                  </div>
                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12">
                      <h4>Item</h4>

                    </div>

                  </div>

                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12 text-right">
                      <h4 style="color: #f7af43" id="input_terhitungpersen">{{  $res[0]->terhitungpersen  }}%</h4>

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
                          <h2 style="color: #fc2121" id="input_belumterhitung">{{  $res[0]->belumterhitung  }}</h2>
                        </div>

                      </div>
                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12">
                          <h4>Item</h4>

                        </div>

                      </div>

                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12 text-right">
                          <h4 style="color: #f7af43" id="input_belumterhitungpersen">{{  $res[0]->belumterhitungpersen  }}%</h4>

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
                                <h2 style="color: #fc2121" id="input_pencapaianaudit">{{  $res[0]->pencapaianaudit  }}%</h2>
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
                  <h2 style="color: #2372fa" id="input_sesuai">{{  $res[0]->sesuai  }}</h2>
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
                      <h2 style="color: #f7af43" id="input_selisihkurang">{{  $res[0]->selisihkurang  }}</h2>
                    </div>

                  </div>
                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12">
                      <h4>Item</h4>

                    </div>

                  </div>

                  <div class="row" style="margin-top: -20px">
                    <div class="col-lg-12 text-right">
                      <h4 style="color: #f7af43" id="input_selisihkurangpersen">{{  $res[0]->selisihkurangpersen  }}%</h4>

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
                          <h2 style="color: #fc2121" id="input_sesuailebih">{{  $res[0]->sesuailebih  }}</h2>
                        </div>

                      </div>
                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12">
                          <h4>Item</h4>

                        </div>

                      </div>

                      <div class="row" style="margin-top: -20px">
                        <div class="col-lg-12 text-right">
                          <h4 style="color: #f7af43" id="input_sesuailebihpersen">{{  $res[0]->sesuailebihpersen  }}%</h4>

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
                                <h2 style="color: #fc2121" id="input_firstdifferent">{{  $res[0]->firstdifferent  }}%</h2>
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



});



function buttonSubmit () {
    let gudang = $("#input_gudang").val();
    let tglawal = $("#input_tglawal").val();
    let tglakhir = $("#input_tglakhir").val();
    let _token = $("#_token").val();

    $.ajax({
      url: "{!! url('dashboardopnamehariansp') !!}",
      type: "post",
      async: false,
      data: {
        _token,
        tglawal, tglakhir, gudang
      },
      success: function(res) {
      console.log(res)

      if (res.length) {
        document.getElementById("input_targetaudit").innerHTML = res[0].targetaudit
        document.getElementById("input_terhitung").innerHTML = res[0].terhitung
        document.getElementById("input_terhitungpersen").innerHTML = res[0].terhitungpersen + '%'
        document.getElementById("input_belumterhitung").innerHTML = res[0].belumterhitung
        document.getElementById("input_belumterhitungpersen").innerHTML = res[0].belumterhitungpersen + '%'
        document.getElementById("input_pencapaianaudit").innerHTML = res[0].pencapaianaudit

        document.getElementById("input_sesuai").innerHTML = res[0].sesuai
        document.getElementById("input_selisihkurang").innerHTML = res[0].selisihkurang
        document.getElementById("input_selisihkurangpersen").innerHTML = res[0].selisihkurangpersen + '%'
        document.getElementById("input_sesuailebih").innerHTML = res[0].sesuailebih
        document.getElementById("input_sesuailebihpersen").innerHTML = res[0].sesuailebihpersen + '%'
        document.getElementById("input_firstdifferent").innerHTML = res[0].firstdifferent
      } else {
        document.getElementById("input_targetaudit").innerHTML = '0'
        document.getElementById("input_terhitung").innerHTML = '0'
        document.getElementById("input_terhitungpersen").innerHTML = '0' + '%'
        document.getElementById("input_belumterhitung").innerHTML = '0'
        document.getElementById("input_belumterhitungpersen").innerHTML = '0' + '%'
        document.getElementById("input_pencapaianaudit").innerHTML = '0'

        document.getElementById("input_sesuai").innerHTML = '0'
        document.getElementById("input_selisihkurang").innerHTML = '0'
        document.getElementById("input_selisihkurangpersen").innerHTML = '0' + '%'
        document.getElementById("input_sesuailebih").innerHTML = '0'
        document.getElementById("input_sesuailebihpersen").innerHTML = '0' + '%'
        document.getElementById("input_firstdifferent").innerHTML = '0'
      }


    }});



}



</script>




@endsection
