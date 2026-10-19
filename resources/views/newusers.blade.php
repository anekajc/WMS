@extends('newmaster')
@section('page-title', 'User')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">

  <!-- <div id="qrcode"></div> -->
  <div class="row">
    <div class="col-6 text-left">
      {{-- <h1>User</h1> - judul pindah ke header (page-title) --}}
    </div>
    <div class="col-6 text-right">
      <button data-wms-toolbar="tabel" type="button" class="btn btn-primary btn-sm " onclick="buttonAdd()"  >Add User</button>
      
    </div>
  </div>
<!-- <button onclick="loadAll()">tes</button> -->
</div>

<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid" style="max-width: 900px">
  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />


  <input type="hidden" id="akses_istambah" value="{!! $akses->ISTAMBAH !!}" />
  <input type="hidden" id="akses_ishapus" value="{!! $akses->ISHAPUS!!}" />
  <input type="hidden" id="akses_iskoreksi" value="{!! $akses->ISKOREKSI !!}" />
  <input type="hidden" id="akses_iscetak" value="{!! $akses->ISCETAK !!}" />
  <input type="hidden" id="akses_isotorisasi1" value="{!! $akses->IsOtorisasi1 !!}" />

  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
          <div class="row mt-4">
              <!-- <div class="col-12 text-right">
                  <button type="button" class="btn btn-primary btn-lg " style="height: 60px; " onclick="buttonAdd()"  >Add Koreksi Stock Gudang</button>
              </div> -->
          </div>
          <div class="row mt-3">
            <div class="col-12" style="overflow:auto;">
              <div class="card wms-kartu">

                    <table id="tabel" class="data-table"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Username</th>
                          <th scope="col">Name</th>
                          <th scope="col">Level</th>
                          <th scope="col">Actions</th>

                        </tr>
                      </thead>


                      <tbody id="tabel_data" class="text-left" >
                        @for ($i = 0; $i < count($listData); $i++)
                        <tr >

                          <td>{{ $listData[$i]->username }}</td>
                          <td>{{ $listData[$i]->name }}</td>
                          <td>{{ $listData[$i]->level }}</td>


                            <td class="text-center">
                              <!-- <button class="btn btn-warning btn-sm" type="button" onclick="" ><i class="bi bi-info-lg"></i></button> -->
                              <button class="btn btn-warning btn-sm" type="button" onclick="buttonDetail('{{ $listData[$i]->username }}')"><i class="bi bi-info"></i></button>
                              <button class="btn btn-success btn-sm" type="button" onclick="buttonEdit('{{ $listData[$i]->username }}')"><i class="bi bi-pen"></i></button>
                              <!-- <button data-toggle="tooltip" data-placement="top" title="Ganti Password" class="btn btn-primary btn-sm" type="button" onclick="buttonGantiPassword('{{ $listData[$i]->username }}')"><i class="bi bi-lock"></i></button> -->
                              <button data-toggle="tooltip" data-placement="top" title="FLMENU" class="btn btn-success btn-sm" type="button" onclick="editAkses('{{ $listData[$i]->id }}', '{{ $listData[$i]->username }}')"><i class="bi bi-card-checklist"></i></button>
                              <button data-toggle="tooltip" data-placement="top" title="FLMENUREPORT" class="btn btn-success btn-sm" type="button" onclick="editAksesReport('{{ $listData[$i]->id }}', '{{ $listData[$i]->username }}')"><i class="bi bi-card-checklist"></i></button>
                              <button class="btn btn-danger btn-sm" type="button" onclick="buttonDelete('{{ $listData[$i]->username }}')"><i class="bi bi-trash"></i></button>


                            </td>
                      </tr>
                      @endfor
                      </tbody>


                    </table>
              </div>
            </div>
          </div>


</div>


<!-- start modal add -->
<div class="modal fade"  id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered"  role="document" style="max-width: 500px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <input type="hidden" name="noUrut" id="input_add_noUrut" value="" />

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Username</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_add_username" placeholder="Username">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Nama</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_add_nama" placeholder="Nama">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Password</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="password" class="form-control" id="input_add_password" placeholder="Password">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Repeat Password</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="password" class="form-control" id="input_add_password2" placeholder="Repeat Password">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Level</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <!-- <input type="text" class="form-control" id="input_add_namaarea" placeholder="Nama Area"> -->
                  <select id="input_add_level" class="form-control" aria-label="Default select example">
                    <option disabled selected value="">Pilih Level</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4">

              </div>






    </div>


    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
    <button type="button" class="btn btn-primary" onclick="submitAdd()">Submit</button>
  </div>
</div>
</div>
</div>
<!-- End modal add-->


<!-- start modal add -->
<div class="modal fade"  id="formGantiPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered"  role="document" style="max-width: 500px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <!-- <input type="hidden" name="noUrut" id="input_add_noUrut" value="" /> -->

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Username</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_gantipassword_username" readonly placeholder="Username">
                </div>
              </div>

            </div>


            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Password</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="password" class="form-control" id="input_gantipassword_password" placeholder="Password">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Repeat Password</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="password" class="form-control" id="input_gantipassword_password2" placeholder="Repeat Password">
                </div>
              </div>

            </div>


            <div class="row">
              <div class="col-4">

              </div>






    </div>


    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
    <button type="button" class="btn btn-primary" onclick="submitGantiPassword()">Submit</button>
  </div>
</div>
</div>
</div>
<!-- End modal add-->


<!-- start modal add -->
<div class="modal fade"  id="formDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered"  role="document" style="max-width: 500px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <!-- <input type="hidden" name="noUrut" id="input_add_noUrut" value="" /> -->

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Username</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_detail_username" placeholder="Username" readonly>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Nama</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" readonly id="input_detail_nama" placeholder="Nama">
                </div>
              </div>

            </div>


            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Level</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <!-- <input type="text" class="form-control" id="input_detail_namaarea" placeholder="Nama Area"> -->
                  <input type="text" class="form-control" readonly id="input_detail_level" placeholder="Level">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4">

              </div>






    </div>


    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
    <!-- <button type="button" class="btn btn-primary" onclick="submitAdd()">Submit</button> -->
  </div>
</div>
</div>
</div>
<!-- End modal add-->



<!-- start modal akses menu -->
<div class="modal fade" id="formAkses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Akses Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" class="form-control" id="input_akses_userid" disabled>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <input type="hidden" name="noUrut" id="input_akses_noUrut" value="" />
          <div class="row">


            <div class="col-4">
              <div class="form-group">
                <label>Username</label>
              </div>
            </div>
            <div class="col-8">
              <div class="form-group">
                <input type="text" class="form-control" id="input_akses_username" placeholder="Username" disabled>
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

              <table id="aksesTable" class="table table-bordered table-striped"  >
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


                <tbody id="aksesTableData" class="text-left" style="" >
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


<!-- start modal akses menu -->
<div class="modal fade" id="formAksesReport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Akses Menu Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" class="form-control" id="input_aksesreport_userid" disabled>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <input type="hidden" name="noUrut" id="input_aksesreport_noUrut" value="" />
          <div class="row">


            <div class="col-4">
              <div class="form-group">
                <label>Username</label>
              </div>
            </div>
            <div class="col-8">
              <div class="form-group">
                <input type="text" class="form-control" id="input_aksesreport_username" placeholder="Username" disabled>
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

              <table id="aksesReportTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <!-- <th style="" scope="col">Kode Menu</th> -->
                    <th scope="col">Ket</th>
                    <th scope="col">ACCESS</th>
                    <th scope="col">DESIGN</th>
                    <th scope="col">EXPORT</th>
                  </tr>
                </thead>


                <tbody id="aksesReportTableData" class="text-left" style="" >
                  <tr >

                      <td class="text-center"><input class="" type="checkbox" value="" id="flexCheckDefault"></td>
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



<!-- start modal add -->
<div class="modal fade"  id="formEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered"  role="document" style="max-width: 500px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <!-- <input type="hidden" name="noUrut" id="input_add_noUrut" value="" /> -->

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Username</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_edit_username" readonly placeholder="Username">
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Nama</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_edit_nama" placeholder="Nama">
                </div>
              </div>

            </div>



            <div class="row">
              <div class="col-4 text-left">
                <div class="form-group text-left">
                  <label class="text-left">Level</label>
                </div>
              </div>
              <div class="col-8">
                <div class="form-group">
                  <!-- <input type="text" class="form-control" id="input_edit_namaarea" placeholder="Nama Area"> -->
                  <select id="input_edit_level" class="form-control" aria-label="Default select example">
                    <option disabled selected value="">Pilih Level</option>
                    <option value=0>0</option>
                    <option value=1>1</option>
                    <option value=2>2</option>
                  </select>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-4">

              </div>






    </div>


    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
    <button type="button" class="btn btn-primary" onclick="submitEdit()">Submit</button>
  </div>
</div>
</div>
</div>
<!-- End modal add-->







@endsection

@section('js')
<script type="text/javascript">

let dataRefresh = []

$(document).ready(function(){
      $("#tabel").DataTable({
        "lengthChange": false,
          "paging": false ,
        //    "columnDefs": [
        // { "type": "date", "targets": [1] },
        // {  "className": "text-center", "targets": [3] },
      // ]
    });
});

function buttonAdd () {

  let akses = $("#akses_istambah").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }

  document.getElementById("input_add_username").value = ''
  document.getElementById("input_add_level").value = ""
  document.getElementById("input_add_nama").value = ""
  document.getElementById("input_add_password").value = ""
  document.getElementById("input_add_password2").value = ""

  $("#form").modal('toggle')

}

function clickUpdateAksesMenuHeader (index, kodemenu, field) {
  console.log('clickUpdateAksesMenuHeader')
  console.log(index, kodemenu, field)

  let _token = $("#_token").val();
  let username = $("#input_akses_username").val();
  let userid = $("#input_akses_userid").val();
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

function clickUpdateAksesMenuReportHeader (index, kodemenu, field) {
  console.log('clickUpdateAksesMenuHeader')
  console.log(index, kodemenu, field)

  let _token = $("#_token").val();
  let username = $("#input_aksesreport_username").val();
  let userid = $("#input_aksesreport_userid").val();
  let nilai = 0

  if (document.getElementById(`aksesreport_checkbox_${field}${index}`).checked) {
    nilai = 1
  }

  let checkBox = document.getElementById(`aksesreport_checkbox_${field}${index}`).checked
  let kodeLength = kodemenu.length
  for (let i = Number(index) + 1 ; i < listAksesReport.length; i++) {
    // console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodeLen)
    // console.log(kodeLength)
    // console.log(listAkses[i].KODEMENU.slice(0,kodeLength) , kodemenu)
    if (listAksesReport[i].KODEMENU.slice(0,kodeLength) !== kodemenu || listAksesReport[i].L0 == 0 ) {
      break
    }
    document.getElementById(`aksesreport_checkbox_${field}${i}`).checked = checkBox
    // console.log(listAkses[i].KODEMENU)
  }


  console.log(kodemenu , nilai , username , field)

  // newsetpemakaispupdateaksesheader
  // return
  $.ajax({
    url: "{!! url('setpemakaiupdateaksesreportheader') !!}",
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

function clickUpdateAksesMenu ( index , kodemenu , isoto = 0) {

  console.log('clickUpdateAksesMenu')
  console.log(index, kodemenu, isoto)
  let _token = $("#_token").val();
  let akses = []
  let aksesObj = {}
  let username = $("#input_akses_username").val();
  let userid = $("#input_akses_userid").val();
  if (isoto) {
    if (document.getElementById(`akses_checkbox_isOtorisasi${isoto}${index}`).checked) {
      document.getElementById(`akses_checkbox_isOtorisasi1${index}`).checked =false
      document.getElementById(`akses_checkbox_isOtorisasi2${index}`).checked =false
      document.getElementById(`akses_checkbox_isOtorisasi3${index}`).checked =false
      document.getElementById(`akses_checkbox_isOtorisasi4${index}`).checked =false
      document.getElementById(`akses_checkbox_isOtorisasi5${index}`).checked =false

      document.getElementById(`akses_checkbox_isOtorisasi${isoto}${index}`).checked =true
    }


  }

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



function clickUpdateAksesMenuReport ( index , kodemenu) {

  console.log('clickUpdateAksesMenu')
  console.log(index, kodemenu)
  let _token = $("#_token").val();
  let akses = []
  let aksesObj = {}
  let username = $("#input_aksesreport_username").val();
  let userid = $("#input_aksesreport_userid").val();

  let tempData = {
    KODEMENU: listAksesReport[index].KODEMENU,
    Keterangan: listAksesReport[index].Keterangan,
    HASACCESS: 0,
    ISDESIGN: 0,
    ISEXPORT: 0

  }
  if (document.getElementById(`aksesreport_checkbox_hasaccess${index}`).checked) {
    tempData.HASACCESS = 1
  }
  if (document.getElementById(`aksesreport_checkbox_isdesign${index}`).checked) {
    tempData.ISDESIGN = 1
  }
  if (document.getElementById(`aksesreport_checkbox_isexport${index}`).checked) {
    tempData.ISEXPORT = 1
  }
  console.log(username)
  console.log(tempData)
  // return
  $.ajax({
    url: "{!! url('setpemakaiupdateaksesreport') !!}",
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

function submitEdit () {

    let _token = $("#_token").val();
    let username = $("#input_edit_username").val();
    let nama = $("#input_edit_nama").val();
    let level = $("#input_edit_level").val();
    // let isagen = 0

    if (!username) {
      alertify.warning('username harus diisi')
      return
    }

    if (!nama) {
      alertify.warning('nama harus diisi')
      return
    }

    if (!level) {
      alertify.warning('level harus diisi')
      return
    }


    console.log('username', username)
    console.log('nama', nama)
    console.log('level' , level)


      $.ajax({
        url: "{!! url('usersedit') !!}",
        type: "post",
        async: false,
        data: {
          _token : _token,
          username,
          nama,
          level
        },
        success: function(res) {

          if (res != 1) {
            alertify.warning(res);
          }  else {
            console.log(res ,'!')
            // $("#formEdit").modal('toggle')
            alertify.success("User telah diedit");
            loadAll()
            $("#formEdit").modal('toggle')
          }

        },
        error: function (err) {
          // xhr, ajaxOptions, thrownError
          // alert(xhr.status);
          // alert(thrownError);
          alertify.warning('error')
          console.log(err)
        }})


}


function buttonEdit (username) {
  let akses = $("#akses_iskoreksi").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }
  console.log(username)

  let _token = $("#_token").val();

  $.ajax({
    url: "{!! url('usersdetail') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      username,
    },
    success: function(res) {

      console.log(res)
      document.getElementById("input_edit_username").value = res[0].username
      document.getElementById("input_edit_level").value = res[0].level
      document.getElementById("input_edit_nama").value = res[0].name

      $("#formEdit").modal('toggle')
      // console.log(res)

    },
    error: function (err) {
      alertify.warning('error')
      console.log(err)
    }})



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
      <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi1")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi2")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi3")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi4")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi5")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
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
      <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 1)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 2 )' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}", 3)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 4)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 5)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
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
  document.getElementById("input_akses_username").value = username
  document.getElementById("input_akses_userid").value = userid

  document.getElementById("aksesTableData").innerHTML = rowTable

  listAkses.forEach((item, i) => {
    console.log(item)
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
      if (item.L0 == 0 ) {
        document.getElementById(`akses_checkbox_isOtorisasi1${i}`).checked = false;
      }
    }
    if (item.IsOtorisasi2 == 1) {
      document.getElementById(`akses_checkbox_isOtorisasi2${i}`).checked = true;
      if (item.L0 == 0 ) {
        document.getElementById(`akses_checkbox_isOtorisasi2${i}`).checked = false;
      }
    }
    if (item.IsOtorisasi3 == 1) {
      document.getElementById(`akses_checkbox_isOtorisasi3${i}`).checked = true;
      if (item.L0 == 0 ) {
        document.getElementById(`akses_checkbox_isOtorisasi3${i}`).checked = false;
      }
    }
    if (item.IsOtorisasi4 == 1) {
      document.getElementById(`akses_checkbox_isOtorisasi4${i}`).checked = true;
      if (item.L0 == 0 ) {
        document.getElementById(`akses_checkbox_isOtorisasi4${i}`).checked = false;
      }
    }
    if (item.IsOtorisasi5 == 1) {
      document.getElementById(`akses_checkbox_isOtorisasi5${i}`).checked = true;
      if (item.L0 == 0 ) {
        document.getElementById(`akses_checkbox_isOtorisasi5${i}`).checked = false;
      }
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






  $("#formAkses").modal('toggle')



}



function editAksesReport (userid, username) {

  let akses = $("#akses_iskoreksi").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }
  console.log(userid, username)
  let _token = $("#_token").val();
  $.ajax({
    url: "{!! url('setpemakailistaksesreport') !!}",
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
      listAksesReport = res

    }
  })

  let rowTable = ""
  listAksesReport.forEach((item, i) => {
    // <td>${item.KODEMENU}</td>
    if (item.L0 == 0) {
      rowTable += `<tr style="background-color: #FDFD96">

      <td>${item.Keterangan}</td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","hasaccess")' class="" type="checkbox" value="" id="aksesreport_checkbox_hasaccess${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","isdesign")' class="" type="checkbox" value="" id="aksesreport_checkbox_isdesign${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","isexport")' class="" type="checkbox" value="" id="aksesreport_checkbox_isexport${i}"></td
      </tr>`
    } else {
      rowTable += `<tr>

      <td>${item.Keterangan}</td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_hasaccess${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_isdesign${i}"></td>
      <td class="text-center"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_isexport${i}"></td>
      </tr>`
    }

  });


  // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
  // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
  // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
  // <td class="text-center"><input class="" type="checkbox" value="" id="akses_checkbox_${i}"></td>
  console.log(listAksesReport)
  console.log(listAksesReport[0])
  document.getElementById("input_aksesreport_username").value = username
  document.getElementById("input_aksesreport_userid").value = userid

  document.getElementById("aksesReportTableData").innerHTML = rowTable

  listAksesReport.forEach((item, i) => {
    if (item.HASACCESS == 1) {
      document.getElementById(`aksesreport_checkbox_hasaccess${i}`).checked = true;
    }
    if (item.ISDESIGN == 1) {
      document.getElementById(`aksesreport_checkbox_isdesign${i}`).checked = true;
    }
    if (item.ISEXPORT == 1) {
      document.getElementById(`aksesreport_checkbox_isexport${i}`).checked = true;
    }
  });






  $("#formAksesReport").modal('toggle')



}



function buttonDetail(username) {
  console.log(username)
  let _token = $("#_token").val();

  $.ajax({
    url: "{!! url('usersdetail') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      username,
    },
    success: function(res) {


      console.log(res)
      document.getElementById("input_detail_username").value = res[0].username
      document.getElementById("input_detail_level").value = res[0].level
      document.getElementById("input_detail_nama").value = res[0].name

      $("#formDetail").modal('toggle')

    },
    error: function (err) {
      alertify.warning('error')
      console.log(err)
    }})
}

function buttonGantiPassword (username) {
  let akses = $("#akses_isotorisasi1").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }


  console.log(username)

  let _token = $("#_token").val();

  $.ajax({
    url: "{!! url('usersdetail') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      username,
    },
    success: function(res) {


      console.log(res)
      document.getElementById("input_gantipassword_username").value = res[0].username

      $("#formGantiPassword").modal('toggle')

    },
    error: function (err) {
      alertify.warning('error')
      console.log(err)
    }})


}


function submitGantiPassword () {

  let _token = $("#_token").val();
  let username = $("#input_gantipassword_username").val();
  let password = $("#input_gantipassword_password").val();
  let password2 = $("#input_gantipassword_password2").val();
  // let isagen = 0

  if (!username) {
    alertify.warning('username harus diisi')
    return
  }

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

  console.log('username', username)
  console.log('password', password)
  console.log('password2', password2)

  // return

  // return
  // return
  $.ajax({
    url: "{!! url('usersgantipassword') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      username,
      password,
    },
    success: function(res) {

      if (res != 1) {
        alertify.warning(res);
      }  else {
        console.log(res ,'!')
        // $("#formEdit").modal('toggle')
        alertify.success("User telah ganti password");
        loadAll()
        $("#formGantiPassword").modal('toggle')
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


function buttonDelete (username) {
  let akses = $("#akses_ishapus").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }
  console.log(username)
  let _token = $("#_token").val();


  alertify.confirm('Hapus Kota', 'Apakah yakin ingin menghapus User ' + username + ' ?',
      function() {
        console.log('yes')

        $.ajax({
          url: "{!! url('usersdelete') !!}",
          type: "post",
          async: false,
          data: {
            _token : _token,
            username
          },
          success: function(res) {
            if (res == 2) {
              // alertify.warning(res);
              alertify.warning('User SA tidak bisa dihapus');
            } else if (res != 1) {
              alertify.warning(res);
            } else  {
              console.log(res)
              loadAll()
              alertify.success("User telah dihapus");




          }
          }})
      }
    ,function(){
      console.log('no')
    });


}




function loadAll () {
  $('#tabel').DataTable().destroy();

$.ajax({
  url: "{!! url('usersloadall') !!}",
  type: "get",
  async: false,
  data: {
    // _token : _token,
  },
  success: function(res) {
    console.log(res)
    dataRefresh = res
}})

let rowTable = ""
dataRefresh.forEach((item, i) => {
  let temp = ""

  rowTable += `<tr>
  <td>${item.username}</td>
  <td>${item.name}</td>
  <td>${item.level}</td>
  <td class="text-center">
  <button class="btn btn-warning btn-sm" type="button" onclick="buttonDetail('${item.username}')"><i class="bi bi-info"></i></button>
    <button class="btn btn-success btn-sm" type="button" onclick="buttonEdit('${item.username}')"><i class="bi bi-pen"></i></button>
    <button data-toggle="tooltip" data-placement="top" title="FLMENU" class="btn btn-success btn-sm" type="button" onclick="editAkses('${item.id}', '${item.username}')"><i class="bi bi-card-checklist"></i></button>
    <button data-toggle="tooltip" data-placement="top" title="FLMENUREPORT" class="btn btn-success btn-sm" type="button" onclick="editAksesReport('${item.id}', '${item.username}')"><i class="bi bi-card-checklist"></i></button>

    <button class="btn btn-danger btn-sm" type="button" onclick="buttonDelete('${item.username}')"><i class="bi bi-trash"></i></button>
  </td>
  </tr>`
});

// <button data-toggle="tooltip" data-placement="top" title="Ganti Password" class="btn btn-primary btn-sm" type="button" onclick="buttonGantiPassword('${item.username}')"><i class="bi bi-lock"></i></button>




document.getElementById("tabel_data").innerHTML = rowTable
$("#tabel").DataTable({
  "lengthChange": false,
    "paging": false ,
  });
}


function submitAdd () {

  let _token = $("#_token").val();
  let username = $("#input_add_username").val();
  let nama = $("#input_add_nama").val();
  let password = $("#input_add_password").val();
  let password2 = $("#input_add_password2").val();
  let level = $("#input_add_level").val();
  // let isagen = 0

  if (!username) {
    alertify.warning('username harus diisi')
    return
  }

  if (!nama) {
    alertify.warning('nama harus diisi')
    return
  }

  if (!password) {
    alertify.warning('password harus diisi')
    return
  }

  if (password != password2) {
    alertify.warning('password harus sama')
    return
  }

  if (!level) {
    alertify.warning('level harus diisi')
    return
  }
  // return

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

  console.log('username', username)
  console.log('nama', nama)
  console.log('password', password)
  console.log('password2', password2)
  console.log('level' , level)

  // return

  // return

  $.ajax({
    url: "{!! url('usersadd') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      username,
      fullname : nama,
      password,
      level
    },
    success: function(res) {

      if (res != 1) {
        alertify.warning(res);
      }  else {
        console.log(res ,'!')
        // $("#formEdit").modal('toggle')
        alertify.success("User telah ditambah");
        loadAll()
        $("#form").modal('toggle')
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
