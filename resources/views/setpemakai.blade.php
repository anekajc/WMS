@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">
<h1>Set Pemakai</h1>
<!-- <button onclick="loadAll()">tes</button> -->
</div>

<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid">
  <input type="hidden" id="akses_istambah" value="{!! $akses->ISTAMBAH !!}" />
  <input type="hidden" id="akses_ishapus" value="{!! $akses->ISHAPUS!!}" />
  <input type="hidden" id="akses_iskoreksi" value="{!! $akses->ISKOREKSI !!}" />
  <input type="hidden" id="akses_iscetak" value="{!! $akses->ISCETAK !!}" />


  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
  <!-- <div class="card">
    <div class="card-header">
      <div class="row">
        <nav style="width: 100%;">
          <div class="nav nav-tabs col-12" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="nav-home" aria-selected="true" style="color: black;">Set Pemakai</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="nav-profile" aria-selected="false" style="color: black;">Temp</a>
          </div>
        </nav>
      </div>
    </div>
    <div class="card-body" >
      <div class="tab-content" id="myTabContent"> -->
        <!-- <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> -->
          <div class="row mt-4">
            <div class="col-12" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabel" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Username</th>
                          <th scope="col">Nama</th>
                          <th scope="col">Level</th>
                          <th scope="col">Status</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>


                      <tbody id="tabel_data" class="text-right" >
                        @for ($i = 0; $i < count($users); $i++)
                        <tr>
                          <td>{{ $users[$i]->username }}</td>
                          <td>{{ $users[$i]->name }}</td>
                          <td>{{ $users[$i]->level }}</td>
                          <td>{{ $users[$i]->status }}</td>
                          <td class="text-center">
                            
                            <!-- <button style="" class="btn btn-warning btn-sm" type="button"   onclick="" ><i class="bi bi-info-lg"></i></button> -->
                            <button data-toggle="tooltip" data-placement="top" title="FLMENU" class="btn btn-success btn-sm" type="button" onclick="editAkses('{{ $users[$i]->id }}', '{{ $users[$i]->username }}')"><i class="bi bi-card-checklist"></i></button>
                          </td>
                        </tr>
                        @endfor
                      </tbody>


                    </table>
              </div>
            </div>
          </div>
        <!-- </div> -->
        <!-- <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="row">
            <div class="col-12" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabelRetur" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Temp 1</th>
                          <th scope="col">Temp 2</th>
                          <th scope="col">Temp 3</th>
                          <th scope="col">Temp 4</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>


                      <tbody id="tabelRetur_data" class="text-right" >

                      </tbody>
                    </table>
              </div>
            </div>
          </div>
        </div> -->
      <!-- </div>
    </div>
  </div> -->
</div>



<!-- start modal akses menu -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Akses Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" class="form-control" id="input_add_userid" disabled>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <input type="hidden" name="noUrut" id="input_add_noUrut" value="" />
          <div class="row">


            <div class="col-2">
              <div class="form-group">
                <label>Username</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_username" placeholder="Username" disabled>
              </div>
            </div>
            <!-- <div class="col-2">
              <div class="form-group">
                <label>No RPB</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_norpb" placeholder="No PBG" disabled>
              </div>
            </div> -->
          </div>

          <!-- <div class="row">
            <div class="col-2">
              <div class="form-group">
                <label>Gdg</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_gdg" placeholder="Gdg Asal" required disabled>
              </div>
            </div>
            <div class="col-2">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="date" class="form-control" id="input_add_tanggal" value="{!! date('Y-m-d') !!}" >
              </div>
            </div>

            <div class="col-2">
              <div class="form-group">
                <label>Nama Cust</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_namacust" placeholder="cust supp" disabled>
              </div>
            </div>


          </div> -->
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



      </div>
        <!-- <div class="container-fluid" style="overflow-x: auto; transform: rotateX(180deg)">
          <div style="transform: rotateX(180deg)"> -->
        <div class="container-fluid doublescroll" style="overflow-x: auto;">
          <div style="">

              <table id="addTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <!-- <th style="" scope="col">Kode Menu</th> -->
                    <th scope="col">Ket</th>
                    <th scope="col">ACCESS</th>
                    <th scope="col">TAMBAH</th>
                    <th scope="col">KOREKSI</th>
                    <th scope="col">HAPUS</th>
                    <th scope="col">CETAK</th>
                    <th scope="col">EXPORT</th>
                    <th scope="col">OTO1</th>
                    <th scope="col">OTO2</th>
                    <th scope="col">OTO3</th>
                    <th scope="col">OTO4</th>
                    <th scope="col">OTO5</th>
                    <th scope="col">BATAL</th>
                    <th scope="col">Pemb</th>
                  </tr>
                </thead>


                <tbody id="addTableData" class="text-left" style="" >
                  <tr >

                      <td class="text-center"><input class="" type="checkbox" value="" id="flexCheckDefault"></td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                </tr>

                </tbody>


              </table>
            </div>
        </div>



      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitAkses()">Submit</button>
      </div> -->
    </div>
  </div>
</div>
<!-- End modal akses menu-->







@endsection

@section('js')
<script type="text/javascript">


// addEventListener("beforeunload", (event) => {
//   window.location.href = "{{ url('logout')}}";
// });

  let listAkses = []
  let listAksesReport = []
  let listCOA = []
  let listAksesCOA = []


  function checkAll ( index , kodemenu , field) {
    console.log(index, kodemenu, field)

    // console.log(checkBox)
    let checkBox = document.getElementById(`akses_checkbox_${field}${index}`).checked
    let kodeLength = kodemenu.length
    for (let i = Number(index) + 1 ; i < listAkses.length; i++) {
      // console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodeLen)
      console.log(kodeLength)
      console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodemenu)
      if (listAkses[i].KODEMENU.slice(0,kodeLength) !== kodemenu || listAkses[i].L0 == 0 ) {
        break
      }
      document.getElementById(`akses_checkbox_${field}${i}`).checked = checkBox
      console.log(listAkses[i].KODEMENU)
    }
  }













  function editAkses (userid, username) {

    let akses = $("#akses_iskoreksi").val();

    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    console.log(userid, username)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('setpemakailistakses') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        userid: userid
      },
      success: function(res) {
        // console.log(res)
        // res.forEach((item, i) => {
        //   console.log(item)
        // });
        listAkses = res

      }
    })

    let rowTable = ""
    listAkses.forEach((item, i) => {
      // <td>${item.KODEMENU}</td>
      if (item.L0 == 0) {
        rowTable += `<tr style="background-color: #FDFD96">

        <td>${item.Keterangan}</td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","hasaccess")' class="" type="checkbox" value="" id="akses_checkbox_hasaccess${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","istambah")' class="" type="checkbox" value="" id="akses_checkbox_istambah${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isKoreksi")' class="" type="checkbox" value="" id="akses_checkbox_isKoreksi${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isHapus")' class="" type="checkbox" value="" id="akses_checkbox_isHapus${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isCetak")' class="" type="checkbox" value="" id="akses_checkbox_isCetak${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isExport")' class="" type="checkbox" value="" id="akses_checkbox_isExport${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi1")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi2")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi3")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi4")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi5")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isBatal")' class="" type="checkbox" value="" id="akses_checkbox_isBatal${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","pembatalan")' class="" type="checkbox" value="" id="akses_checkbox_pembatalan${i}"></td>
        </tr>`
      } else {
        rowTable += `<tr>

        <td>${item.Keterangan}</td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_hasaccess${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_istambah${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isKoreksi${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isHapus${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isCetak${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isExport${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isBatal${i}"></td>
        <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_pembatalan${i}"></td>
        </tr>`
      }

    });


    // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
    // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
    // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
    // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
    console.log(listAkses)
    console.log(listAkses[0])
    document.getElementById("input_add_username").value = username
    document.getElementById("input_add_userid").value = userid

    document.getElementById("addTableData").innerHTML = rowTable

    listAkses.forEach((item, i) => {
      if (item.HASACCESS == 1) {
        document.getElementById(`akses_checkbox_hasaccess${i}`).checked = true;
      }
      if (item.ISTAMBAH == 1) {
        document.getElementById(`akses_checkbox_istambah${i}`).checked = true;
      }
      if (item.ISKOREKSI == 1) {
        document.getElementById(`akses_checkbox_isKoreksi${i}`).checked = true;
      }
      if (item.IsOtorisasi1 == 1) {
        document.getElementById(`akses_checkbox_isOtorisasi1${i}`).checked = true;
      }
      if (item.IsOtorisasi2 == 1) {
        document.getElementById(`akses_checkbox_isOtorisasi2${i}`).checked = true;
      }
      if (item.IsOtorisasi3 == 1) {
        document.getElementById(`akses_checkbox_isOtorisasi3${i}`).checked = true;
      }
      if (item.IsOtorisasi4 == 1) {
        document.getElementById(`akses_checkbox_isOtorisasi4${i}`).checked = true;
      }
      if (item.IsOtorisasi5 == 1) {
        document.getElementById(`akses_checkbox_isOtorisasi5${i}`).checked = true;
      }
      if (item.ISHAPUS == 1) {
        document.getElementById(`akses_checkbox_isHapus${i}`).checked = true;
      }
      if (item.ISCETAK == 1) {
        document.getElementById(`akses_checkbox_isCetak${i}`).checked = true;
      }
      if (item.ISEXPORT == 1) {
        document.getElementById(`akses_checkbox_isExport${i}`).checked = true;
      }
      if (item.IsBatal == 1) {
        document.getElementById(`akses_checkbox_isBatal${i}`).checked = true;
      }
      if (item.pembatalan == 1) {
        document.getElementById(`akses_checkbox_pembatalan${i}`).checked = true;
      }
    });






    $("#form").modal('toggle')



  }


  function clickUpdateAksesMenuHeader (index, kodemenu, field) {
    console.log('clickUpdateAksesMenuHeader')
    console.log(index, kodemenu, field)

    let _token = $("#_token").val();
    let username = $("#input_add_username").val();
    let userid = $("#input_add_userid").val();
    let nilai = 0

    if (document.getElementById(`akses_checkbox_${field}${index}`).checked) {
      nilai = 1
    }

    let checkBox = document.getElementById(`akses_checkbox_${field}${index}`).checked
    let kodeLength = kodemenu.length
    for (let i = Number(index) + 1 ; i < listAkses.length; i++) {
      // console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodeLen)
      // console.log(kodeLength)
      // console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodemenu)
      if (listAkses[i].KODEMENU.slice(0,kodeLength) !== kodemenu || listAkses[i].L0 == 0 ) {
        break
      }
      document.getElementById(`akses_checkbox_${field}${i}`).checked = checkBox
      // console.log(listAkses[i].KODEMENU)
    }


    console.log(kodemenu , nilai , username , field)

    // newsetpemakaispupdateaksesheader
    // return
    $.ajax({
      url: "{!! url('setpemakaiupdateaksesheader') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        userid  : userid,
        nilai,
        field,
        headermenu: kodemenu
      },
      success: function(res) {
        console.log(res)


      },
      error: function (err) {
        console.log(err)
        alertify.warning('Terjadi kesalahan silahkan refresh ulang browser')
      }
    })
  }

  function clickUpdateAksesMenu ( index , kodemenu) {

    console.log('clickUpdateAksesMenu')
    console.log(index, kodemenu)
    let _token = $("#_token").val();
    let akses = []
    let aksesObj = {}
    let username = $("#input_add_username").val();
    let userid = $("#input_add_userid").val();

    let tempData = {
      KODEMENU: listAkses[index].KODEMENU,
      Keterangan: listAkses[index].Keterangan,
      HASACCESS: 0,
      ISTAMBAH: 0,
      ISKOREKSI: 0,
      ISHAPUS: 0,
      ISCETAK: 0,
      ISBATAL: 0,
      ISEXPORT: 0,
      ISOTO1: 0,
      ISOTO2: 0,
      ISOTO3: 0,
      ISOTO4: 0,
      ISOTO5: 0,
      pembatalan: 0

    }
    if (document.getElementById(`akses_checkbox_hasaccess${index}`).checked) {
      tempData.HASACCESS = 1
    }
    if (document.getElementById(`akses_checkbox_istambah${index}`).checked) {
      tempData.ISTAMBAH = 1
    }
    if (document.getElementById(`akses_checkbox_isKoreksi${index}`).checked) {
      tempData.ISKOREKSI = 1
    }
    if (document.getElementById(`akses_checkbox_isHapus${index}`).checked) {
      tempData.ISHAPUS = 1
    }
    if (document.getElementById(`akses_checkbox_isCetak${index}`).checked) {
      tempData.ISCETAK = 1
    }
    if (document.getElementById(`akses_checkbox_isBatal${index}`).checked) {
      tempData.ISBATAL = 1
    }
    if (document.getElementById(`akses_checkbox_isExport${index}`).checked) {
      tempData.ISEXPORT = 1
    }
    if (document.getElementById(`akses_checkbox_isOtorisasi1${index}`).checked) {
      tempData.ISOTO1 = 1
    }
    if (document.getElementById(`akses_checkbox_isOtorisasi2${index}`).checked) {
      tempData.ISOTO2 = 1
    }
    if (document.getElementById(`akses_checkbox_isOtorisasi3${index}`).checked) {
      tempData.ISOTO3 = 1
    }
    if (document.getElementById(`akses_checkbox_isOtorisasi4${index}`).checked) {
      tempData.ISOTO4 = 1
    }
    if (document.getElementById(`akses_checkbox_isOtorisasi5${index}`).checked) {
      tempData.ISOTO5 = 1
    }
    if (document.getElementById(`akses_checkbox_pembatalan${index}`).checked) {
      tempData.pembatalan = 1
    }
    console.log(username)
    console.log(tempData)
    // return
    $.ajax({
      url: "{!! url('setpemakaiupdateakses') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        userid: userid,
        tempData: tempData
      },
      success: function(res) {
        console.log(res)


      },
      error: function (err) {
        console.log(err)
        alertify.warning('Terjadi kesalahan silahkan refresh ulang browser')
      }
    })


  }






</script>




@endsection
