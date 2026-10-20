@extends('newmaster')
@section('page-title', 'User')
@section('buttons')

@endsection

@section('css')
{{-- Diubah 2026-10-20: tampilan menu User disamakan dengan newSML berkas/newsetpemakai.blade.php
     (kartu ringkasan, toolbar, tombol aksi soft, badge level, modal akses & form bs-form).
     Logika (route users* / setpemakai*, cek hak akses, isi data) tetap milik ajcwms. --}}
<link rel="stylesheet" href="{!! URL::asset('public/css/master-list.css') !!}?v={{ @filemtime(base_path('public/css/master-list.css')) ?: '1' }}">
<style>
  /* Kartu ringkasan di atas tabel - nilai disalin dari newSML newsetpemakai. */
  .sp-stats-row { display: flex; gap: 16px; margin-bottom: 14px; flex-wrap: wrap; }
  .sp-stat-card {
    background: #fff;
    border: 1px solid #e7e9ee;
    border-radius: 10px;
    box-shadow: 0 1px 2px rgba(20,20,43,.04), 0 1px 1px rgba(20,20,43,.03);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 200px;
    flex: 1 1 200px;
  }
  .sp-stat-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex: none;
  }
  .sp-stat-icon.is-purple { background: #efe9ff; color: #6f42f3; }
  .sp-stat-icon.is-green  { background: #e7f7ed; color: #16a34a; }
  .sp-stat-icon.is-blue   { background: #e8edff; color: #2563eb; }
  .sp-stat-label { font-size: 13px; color: #6b7280; margin-bottom: 2px; }
  .sp-stat-value { font-size: 22px; font-weight: 700; line-height: 1.1; color: #1f2430; }
  .sp-stat-sub   { font-size: 12px; color: #6b7280; margin-top: 2px; }

  /* Tabel hak akses di modal Akses Menu / Akses Report. */
  .akses-user { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: 13.5px; color: #6b7280; }
  .akses-user b { color: #1f2430; font-size: 15px; }
  .akses-wrap { max-height: 62vh; overflow: auto; border: 1px solid #e7e9ee; border-radius: 8px; }
  .akses-tabel { width: 100%; margin: 0; border-collapse: separate; border-spacing: 0; font-size: 13px; }
  .akses-tabel thead th {
    position: sticky; top: 0; z-index: 2;
    background: #f7f8fa; color: #6b7280; font-weight: 700; font-size: 11.5px;
    text-transform: uppercase; letter-spacing: .03em; text-align: center;
    padding: 8px 6px; border-bottom: 1px solid #e7e9ee; white-space: nowrap;
  }
  .akses-tabel tbody td { padding: 5px 8px; border-bottom: 1px solid #f0f1f4; vertical-align: middle; white-space: nowrap; }
  .akses-tabel tbody td.cek { text-align: center; }
  .akses-tabel tbody tr:hover td { background: #f7f9ff; }
  .akses-tabel tbody tr.akses-induk td { background: #fff8e1; font-weight: 600; }
  .akses-tabel input[type="checkbox"] { width: 15px; height: 15px; cursor: pointer; accent-color: #2563eb; vertical-align: middle; transform: none; }
  .akses-kosong { text-align: center; color: #9ca3af; padding: 18px !important; }

  /* Tombol Tambah di toolbar tabel - ukuran sama dengan newSML (.btn-dpp-utama). */
  #content-blade .wms-toolbar .po-toolbar-act .btn.btn-dpp-utama { height: 36px !important; padding: 0 16px; font-size: 0.78rem; }
</style>
@endsection

@section('content')

<div id="printContainer" style="display:none">

</div>

<div id="contentContainer" class="container-fluid po-list-page">
  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />

  <input type="hidden" id="akses_istambah" value="{!! $akses->ISTAMBAH !!}" />
  <input type="hidden" id="akses_ishapus" value="{!! $akses->ISHAPUS!!}" />
  <input type="hidden" id="akses_iskoreksi" value="{!! $akses->ISKOREKSI !!}" />
  <input type="hidden" id="akses_iscetak" value="{!! $akses->ISCETAK !!}" />
  <input type="hidden" id="akses_isotorisasi1" value="{!! $akses->IsOtorisasi1 !!}" />

  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />

  <!-- Kartu ringkasan - angkanya diisi isiRingkasan() -->
  <div class="sp-stats-row">
    <div class="sp-stat-card">
      <div class="sp-stat-icon is-purple"><i class="bi bi-people"></i></div>
      <div>
        <div class="sp-stat-label">Total User</div>
        <div class="sp-stat-value" id="stat_total">-</div>
      </div>
    </div>
    <div class="sp-stat-card">
      <div class="sp-stat-icon is-green"><i class="bi bi-check-circle"></i></div>
      <div>
        <div class="sp-stat-label">User Online</div>
        <div class="sp-stat-value" id="stat_aktif">-</div>
        <div class="sp-stat-sub"><span id="stat_nonaktif">-</span> Offline</div>
      </div>
    </div>
    <div class="sp-stat-card">
      <div class="sp-stat-icon is-blue"><i class="bi bi-shield-check"></i></div>
      <div>
        <div class="sp-stat-label">User Level 2</div>
        <div class="sp-stat-value" id="stat_admin">-</div>
      </div>
    </div>
  </div>

  <div class="card wms-kartu">
    <div class="card-body" style="padding:0;">

      {{-- Toolbar Cari + Tampilkan dibuat otomatis (public/js/wms-tampilan-baru.js); tombol ini dipindah ke kanannya. --}}
      <button data-wms-toolbar="tabel" id="AddVisibility" class="btn btn-dpp-utama" type="button" onclick="buttonAdd()">Tambah</button>

      <table id="tabel" class="data-table po-aksi-hover">
        <thead>
          <tr>
            <th style="padding: 4px 12px;" scope="col">Actions</th>
            <th style="padding: 4px 12px;" scope="col">User ID</th>
            <th style="padding: 4px 12px;" scope="col">Nama</th>
            <th style="padding: 4px 12px;" scope="col">Level</th>
          </tr>
        </thead>
        <tbody id="tabel_data" class="text-left">
          @for ($i = 0; $i < count($listData); $i++)
          <tr>
            <td style="white-space:nowrap;" class="text-center">
              <div class="action-buttons-wrap">
                <button title="Detail User" class="btn-action-sm btn-action-warning" type="button" onclick="buttonDetail('{{ $listData[$i]->username }}')"><i class="bi bi-info-lg"></i></button>
                <button title="Edit User" class="btn-action-sm btn-action-success" type="button" onclick="buttonEdit('{{ $listData[$i]->username }}')"><i class="bi bi-pen"></i></button>
                <button title="Akses Menu" class="btn-action-sm btn-action-primary" type="button" onclick="editAkses('{{ $listData[$i]->id }}', '{{ $listData[$i]->username }}')"><i class="bi bi-card-checklist"></i></button>
                <button title="Akses Report" class="btn-action-sm btn-action-primary" type="button" onclick="editAksesReport('{{ $listData[$i]->id }}', '{{ $listData[$i]->username }}')"><i class="bi bi-card-list"></i></button>
                <button title="Hapus User" class="btn-action-sm btn-action-danger" type="button" onclick="buttonDelete('{{ $listData[$i]->username }}')"><i class="bi bi-trash"></i></button>
              </div>
            </td>
            <td>{{ $listData[$i]->username }}</td>
            <td>{{ $listData[$i]->name }}</td>
            <td><span class="sp-badge {{ $listData[$i]->level == 2 ? 'is-admin' : ($listData[$i]->level == 1 ? 'is-supervisor' : 'is-user') }}">Level {{ $listData[$i]->level }}</span></td>
          </tr>
          @endfor
        </tbody>
      </table>

    </div>
  </div>

</div>


<!-- start modal add -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="judulAddUser" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 540px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulAddUser">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="formBsGrid">
        <div class="container-fluid">
          <input type="hidden" name="noUrut" id="input_add_noUrut" value="" />
          <div class="bs-form bs-form-1">
            <label for="input_add_username">User</label>
            <input type="text" class="form-control" id="input_add_username" placeholder="Username" autocomplete="off">

            <label for="input_add_nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="input_add_nama" placeholder="Nama">

            <label for="input_add_password">Password</label>
            <input type="password" class="form-control" id="input_add_password" placeholder="Password" autocomplete="new-password">

            <label for="input_add_password2">Konfirmasi PW</label>
            <input type="password" class="form-control" id="input_add_password2" placeholder="Ulangi password" autocomplete="new-password">

            <label for="input_add_level">Level</label>
            <select id="input_add_level" class="form-control">
              <option disabled selected value="">Pilih Level</option>
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-chip-biru" onclick="submitAdd()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal add-->


<!-- start modal ganti password -->
<div class="modal fade" id="formGantiPassword" tabindex="-1" role="dialog" aria-labelledby="judulGantiPassword" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 540px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulGantiPassword">Ganti Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="formBsGrid">
        <div class="container-fluid">
          <div class="bs-form bs-form-1">
            <label for="input_gantipassword_username">User</label>
            <input type="text" class="form-control" id="input_gantipassword_username" readonly placeholder="Username">

            <label for="input_gantipassword_password">Password</label>
            <input type="password" class="form-control" id="input_gantipassword_password" placeholder="Password" autocomplete="new-password">

            <label for="input_gantipassword_password2">Konfirmasi PW</label>
            <input type="password" class="form-control" id="input_gantipassword_password2" placeholder="Ulangi password" autocomplete="new-password">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-chip-biru" onclick="submitGantiPassword()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal ganti password-->


<!-- start modal detail -->
<div class="modal fade" id="formDetail" tabindex="-1" role="dialog" aria-labelledby="judulDetailUser" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 540px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulDetailUser">Detail User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="formBsGrid">
        <div class="container-fluid">
          <div class="bs-form bs-form-1">
            <label for="input_detail_username">User</label>
            <input type="text" class="form-control" id="input_detail_username" placeholder="Username" readonly>

            <label for="input_detail_nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="input_detail_nama" placeholder="Nama" readonly>

            <label for="input_detail_level">Level</label>
            <input type="text" class="form-control" id="input_detail_level" placeholder="Level" readonly>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal detail-->


<!-- start modal akses menu -->
<div class="modal fade" id="formAkses" tabindex="-1" role="dialog" aria-labelledby="judulAksesMenu" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 95%; max-width:1400px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulAksesMenu">Akses Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="input_akses_userid">
      <div class="modal-body">
        <input type="hidden" name="noUrut" id="input_akses_noUrut" value="" />
        <input type="hidden" id="input_akses_username">
        <div class="akses-user">User <b id="label_akses_username">-</b> <span>· perubahan langsung tersimpan saat kotak dicentang</span></div>

        <div class="akses-wrap">
          <table id="aksesTable" class="akses-tabel">
            <thead>
              <tr>
                <th scope="col" style="text-align:left;">Kode Menu</th>
                <th scope="col" style="text-align:left;">Keterangan</th>
                <th scope="col">Akses</th>
                <th scope="col">Tambah</th>
                <th scope="col">Koreksi</th>
                <th scope="col">Hapus</th>
                <th scope="col">Cetak</th>
                <th scope="col">Export</th>
                <th scope="col">Oto1</th>
                <th scope="col">Oto2</th>
                <th scope="col">Oto3</th>
                <th scope="col">Oto4</th>
                <th scope="col">Oto5</th>
                <th scope="col">Batal</th>
                <th scope="col">Pemb</th>
              </tr>
            </thead>
            <tbody id="aksesTableData">
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal akses menu-->


<!-- start modal akses menu report -->
<div class="modal fade" id="formAksesReport" tabindex="-1" role="dialog" aria-labelledby="judulAksesReport" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:900px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulAksesReport">Akses Menu Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="input_aksesreport_userid">
      <div class="modal-body">
        <input type="hidden" name="noUrut" id="input_aksesreport_noUrut" value="" />
        <input type="hidden" id="input_aksesreport_username">
        <div class="akses-user">User <b id="label_aksesreport_username">-</b> <span>· perubahan langsung tersimpan saat kotak dicentang</span></div>

        <div class="akses-wrap">
          <table id="aksesReportTable" class="akses-tabel">
            <thead>
              <tr>
                <th scope="col" style="text-align:left;">Kode Menu</th>
                <th scope="col" style="text-align:left;">Keterangan</th>
                <th scope="col">Akses</th>
                <th scope="col">Design</th>
                <th scope="col">Export</th>
              </tr>
            </thead>
            <tbody id="aksesReportTableData">
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal akses menu report-->


<!-- start modal edit -->
<div class="modal fade" id="formEdit" tabindex="-1" role="dialog" aria-labelledby="judulEditUser" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 540px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="judulEditUser">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="formBsGrid">
        <div class="container-fluid">
          <div class="bs-form bs-form-1">
            <label for="input_edit_username">User</label>
            <input type="text" class="form-control" id="input_edit_username" readonly placeholder="Username">

            <label for="input_edit_nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="input_edit_nama" placeholder="Nama">

            <label for="input_edit_level">Level</label>
            <select id="input_edit_level" class="form-control">
              <option disabled selected value="">Pilih Level</option>
              <option value=0>0</option>
              <option value=1>1</option>
              <option value=2>2</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-batal-add" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-sm btn-chip-biru" onclick="submitEdit()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal edit-->

@endsection

@section('js')
<script type="text/javascript">

let dataRefresh = []

// Ditambahkan 2026-10-20 (tampilan newSML): badge level & kartu ringkasan di atas tabel.
function badgeLevel (level) {
  let kelas = Number(level) === 2 ? 'is-admin' : (Number(level) === 1 ? 'is-supervisor' : 'is-user')
  return `<span class="sp-badge ${kelas}">Level ${level}</span>`
}

function isiRingkasan (list) {
  let online = list.filter(u => Number(u.status) === 1).length
  document.getElementById('stat_total').textContent = list.length
  document.getElementById('stat_aktif').textContent = online
  document.getElementById('stat_nonaktif').textContent = list.length - online
  document.getElementById('stat_admin').textContent = list.filter(u => Number(u.level) === 2).length
}

$(function () {
  isiRingkasan(@json($listData))
})

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
      rowTable += `<tr class="akses-induk">

      <td>${item.KODEMENU}</td>
      <td>${item.Keterangan}</td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","hasaccess")' class="" type="checkbox" value="" id="akses_checkbox_hasaccess${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","istambah")' class="" type="checkbox" value="" id="akses_checkbox_istambah${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isKoreksi")' class="" type="checkbox" value="" id="akses_checkbox_isKoreksi${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isHapus")' class="" type="checkbox" value="" id="akses_checkbox_isHapus${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isCetak")' class="" type="checkbox" value="" id="akses_checkbox_isCetak${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isExport")' class="" type="checkbox" value="" id="akses_checkbox_isExport${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi1")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi2")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi3")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi4")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isOtorisasi5")' class="" disabled type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","isBatal")' class="" type="checkbox" value="" id="akses_checkbox_isBatal${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuHeader("${i}" ,"${item.KODEMENU}","pembatalan")' class="" type="checkbox" value="" id="akses_checkbox_pembatalan${i}"></td>
      </tr>`
    } else {
      rowTable += `<tr>

      <td>${item.KODEMENU}</td>
      <td>${item.Keterangan}</td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_hasaccess${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_istambah${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isKoreksi${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isHapus${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isCetak${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isExport${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 1)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi1${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 2 )' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi2${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}", 3)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi3${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 4)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi4${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}" , 5)' class="" type="checkbox" value="" id="akses_checkbox_isOtorisasi5${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_isBatal${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenu("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="akses_checkbox_pembatalan${i}"></td>
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
  document.getElementById("label_akses_username").textContent = username // 2026-10-20: nama user di judul modal (tampilan newSML)
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
      rowTable += `<tr class="akses-induk">

      <td>${item.KODEMENU}</td>
      <td>${item.Keterangan}</td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","hasaccess")' class="" type="checkbox" value="" id="aksesreport_checkbox_hasaccess${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","isdesign")' class="" type="checkbox" value="" id="aksesreport_checkbox_isdesign${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReportHeader("${i}" ,"${item.KODEMENU}","isexport")' class="" type="checkbox" value="" id="aksesreport_checkbox_isexport${i}"></td>
      </tr>`
    } else {
      rowTable += `<tr>

      <td>${item.KODEMENU}</td>
      <td>${item.Keterangan}</td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_hasaccess${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_isdesign${i}"></td>
      <td class="cek"><input onclick='clickUpdateAksesMenuReport("${i}","${item.KODEMENU}")' class="" type="checkbox" value="" id="aksesreport_checkbox_isexport${i}"></td>
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
  document.getElementById("label_aksesreport_username").textContent = username // 2026-10-20: nama user di judul modal (tampilan newSML)
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

  // Diubah 2026-10-20 (tampilan newSML): kolom Actions di depan, tombol aksi soft, level berupa badge.
  rowTable += `<tr>
  <td style="white-space:nowrap;" class="text-center">
    <div class="action-buttons-wrap">
      <button title="Detail User" class="btn-action-sm btn-action-warning" type="button" onclick="buttonDetail('${item.username}')"><i class="bi bi-info-lg"></i></button>
      <button title="Edit User" class="btn-action-sm btn-action-success" type="button" onclick="buttonEdit('${item.username}')"><i class="bi bi-pen"></i></button>
      <button title="Akses Menu" class="btn-action-sm btn-action-primary" type="button" onclick="editAkses('${item.id}', '${item.username}')"><i class="bi bi-card-checklist"></i></button>
      <button title="Akses Report" class="btn-action-sm btn-action-primary" type="button" onclick="editAksesReport('${item.id}', '${item.username}')"><i class="bi bi-card-list"></i></button>
      <button title="Hapus User" class="btn-action-sm btn-action-danger" type="button" onclick="buttonDelete('${item.username}')"><i class="bi bi-trash"></i></button>
    </div>
  </td>
  <td>${item.username}</td>
  <td>${item.name}</td>
  <td>${badgeLevel(item.level)}</td>
  </tr>`
});

// <button data-toggle="tooltip" data-placement="top" title="Ganti Password" class="btn btn-primary btn-sm" type="button" onclick="buttonGantiPassword('${item.username}')"><i class="bi bi-lock"></i></button>




document.getElementById("tabel_data").innerHTML = rowTable
isiRingkasan(dataRefresh)
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
