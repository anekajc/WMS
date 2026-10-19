@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">

  <!-- <div id="qrcode"></div> -->
  <div class="row">
    <div class="col-6 text-left">
      <h1>Put Away Gudang</h1>
    </div>
    <div class="col-6 text-right">
      <button type="button" class="btn btn-primary btn-lg " style="height: 60px; " onclick="buttonAdd()"  >Add Put Away Gudang</button>
    </div>
  </div>
<!-- <button onclick="loadAll()">tes</button> -->
</div>


<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid">
  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />
  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
          <div class="row mt-4">
              <!-- <div class="col-12 text-right">
                  <button type="button" class="btn btn-primary btn-lg " style="height: 60px; " onclick="buttonAdd()"  >Add Koreksi Stock Gudang</button>
              </div> -->
          </div>
          <div class="row mt-3">
            <div class="col-12" style="overflow:auto;">
              <div class="">

                    <table id="tabel" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">No. Bukti</th>
                          <th scope="col">Tanggal</th>
                          <th scope="col">Gdg</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>


                      <tbody id="tabel_data" class="text-right" >
                        @for ($i = 0; $i < count($penerimaanArray); $i++)
                        <tr >

                          <td>{{ $penerimaanArray[$i][0]->NOBUKTI}}</td>
                          <td>{!! date("Y/m/d", strtotime($penerimaanArray[$i][0]->tanggal)) !!}</td>
                          <td>{{ $penerimaanArray[$i][0]->NAMAGUDANG}}</td>
                            <td class="text-center">
                              <button class="btn btn-warning btn-sm" type="button" onclick="buttonDetailKoreksi('{{ $penerimaanArray[$i][0]->NOBUKTI}}')" ><i class="bi bi-info-lg"></i></button>
                              <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('{{ $penerimaanArray[$i][0]->NOBUKTI}}')"><i class="bi bi-pen"></i></button>
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
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 600px"  role="document">
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
                  <label class="text-left">Barcode Barang</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="input_add_kode1" placeholder="Barcode Barang" onkeypress="enterScannerKode1(event)">
                </div>
              </div>

              <div class="col-2 text-right">
                <div class="form-group">
              <button onclick="resetScannerKode1()" class="btn btn-success btn-sm text-right"><i class="bi bi-arrow-clockwise"></i></button>
              </div>
              </div>

            </div>

          <div class="row">
            <div class="col-4">
              <div class="form-group">
                <label>barcode Lokasi</label>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_kode2" placeholder="Barcode Lokasi" onkeypress="enterScannerKode2(event)">
              </div>
            </div>

            <div class="col-2 text-right">
              <div class="form-group">
            <button onclick="resetScannerKode2()" class="btn btn-success btn-sm text-right"><i class="bi bi-arrow-clockwise"></i></button>
            </div>
            </div>







          </div>


        <!-- <div class="container-fluid">
          <div class="row ">
            <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" onclick="buttonAddItem()" class="btn btn-secondary"  >Add Item</button>
        </div>

        </div>



        </div> -->

    </div>
  </div>
</div>
</div>
</div>
<!-- End modal add-->


<!-- start modal detail koreksi -->
<div class="modal fade" id="formDetailKoreksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Koreksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <label>No Bukti</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detailKoreksi_nopen" value="TES/TES/00001"  disabled>
              </div>
            </div>
            <!-- <div class="col-">
            </div> -->
            <div class="col-2">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="date" class="form-control" id="input_detailKoreksi_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>


          </div>




        <!-- <div class="container-fluid">
          <div class="row ">
            <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" onclick="buttonAddItem()" class="btn btn-secondary"  >Add Item</button>
        </div>

        </div>



        </div> -->
        <div class="container-fluid mt-4" style="overflow-x: auto;">

              <table id="detailKoreksiTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <th scope="col">Kode Brg</th>
                    <th scope="col">Nama Brg</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Satuan</th>
                    <!-- <th scope="col">Actions</th> -->
                  </tr>
                </thead>


                <tbody id="detailKoreksiTableData" class="text-right" >
                  <tr >
                    <td colspan="3">Text</td>
                </tr>

                </tbody>


              </table>
        </div>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
        <!-- <button type="button" class="btn btn-primary" onclick="submitAdd()">Submit</button> -->
      </div>
    </div>
  </div>
</div>
</div>
</div>
<!-- End modal detail koreksi-->


<!-- start modal detail koreksi -->
<div class="modal fade" id="formKoreksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Koreksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <div class="row">
            <div class="col-2">
              <div class="form-group">
                <label>No Bukti</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_nopen" value="TES/TES/00001"  disabled>
              </div>
            </div>
            <!-- <div class="col-">
            </div> -->
            <div class="col-2">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <input type="date" class="form-control" id="input_koreksi_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>


          </div>




        <!-- <div class="container-fluid">
          <div class="row ">
            <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" onclick="buttonAddItem()" class="btn btn-secondary"  >Add Item</button>
        </div>

        </div>



        </div> -->



        <div class="container-fluid mt-4" style="overflow-x: auto;">

              <table id="koreksiTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <th scope="col">Kode Brg</th>
                    <th scope="col">Nama Brg</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Satuan</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>


                <tbody id="koreksiTableData" class="text-right" >
                  <tr >
                    <td colspan="3">Text</td>
                </tr>

                </tbody>


              </table>
        </div>

        <!-- koreksi edit -->

        <div id="formKoreksiEdit" class="container-fluid showhide">
          <div class="line"></div>
          <div class="row">
            <div class="col-4">
              <h4>Edit Item</h4>
            </div>
          </div>

          <div class="row">
            <div class="col-2">
              <div class="form-group">
              <label>Kode Barang</label>
            </div>
            </div>
            <div class="col-6">
              <input id="koreksiEditKodeBrg" type="text" class="form-control" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <div class="form-group">
              <label>Nama Barang</label>
            </div>
            </div>
            <div class="col-6">
              <input id="koreksiEditNamaBrg" type="text" class="form-control" disabled>
            </div>
          </div>
          <div class="row">
            <div class="col-1">
              <div class="form-group">
              <label>Qty</label>
            </div>
            </div>
            <div class="col-3">
              <input id="koreksiEditInputQty" type="number" value=0.00 class="form-control">
            </div>
            <div class="col-1">
              <div class="form-group">
              <label>Satuan</label>
            </div>
            </div>
            <div class="col-3">

                <input type="text" id="koreksiEditSatuan" value="PCS" class="form-control" disabled>
            </div>
          </div>
          <!-- <div class="row">
            <div class="col-1">
              <div class="form-group">
              <label>Qty OS</label>
            </div>
            </div>
            <div class="col-3">
              <input id="koreksiEditQtyOS" type="number" value=0.00 class="form-control" disabled>
            </div>

          </div> -->
          <div class="row mt-2">
            <div class="col-md-12 text-right">
              <button type="button" class="btn btn-secondary" onclick="hideShowhide()" >Batal</button>
              <button type="button" onclick="submitEditKoreksi()" class="btn btn-primary" >Edit Item</button>
            </div>

          </div>
        </div>

        <!-- end  -->

      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitAdd()">Submit</button>
      </div> -->
    </div>
  </div>
</div>
</div>
</div>
<!-- End modal detail koreksi-->







@endsection

@section('js')
<script type="text/javascript">

  let koreksiDataArray = []
  let koreksiDataEdit = {}

  let dataRefreshPenerimaan = []


  $(document).ready(function(){
        $("#tabel").DataTable({
          "lengthChange": false,
            "paging": false ,
             "columnDefs": [
          { "type": "date", "targets": [1] },
          {  "className": "text-center", "targets": [3] },
        ]});
        // new QRCode(document.getElementById("qrcode"), `asdasdadsadsaa`);



  });

  function buttonAdd () {


        $("#form").modal('toggle')
        $('#form').on('shown.bs.modal', function () {
          $('#input_add_kode1').trigger('focus')
        })
  }

  function loadAll () {
    $.ajax({
      url: "{!! url('loadallputaway') !!}",
      type: "get",
      async: false,
      success: function(res) {
        console.log(res)
        dataRefreshPenerimaan = res.penerimaanArray
      }
    })

    $('#tabel').DataTable().destroy();
    let rowTable = ""
    dataRefreshPenerimaan.forEach((item, i) => {
      // console.log(item,'==================')
      let date1 = ""
      if (item[0].tanngal) {
        let date = new Date(item[0].tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"/"+(month)+"/"+(day) ;
      }
      rowTable  += `<tr>
      <td>${item[0].NOBUKTI}</td>
      <td>${date1}</td>
      <td>${item[0].NAMAGUDANG}</td>
      <td class="text-center">
      <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetailKoreksi('${item[0].NOBUKTI}')" ><i class="bi bi-info-lg"></i></button>
      <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('${item[0].NOBUKTI}')"><i class="bi bi-bag-plus-fill"></i></button>
      </tr>`
    })



    document.getElementById("tabel_data").innerHTML = rowTable
    $("#tabel").DataTable({
      "lengthChange": false,
        "paging": false ,
         "columnDefs": [
      { "type": "date", "targets": [1] },
      {  "className": "text-center", "targets": [3] },
    ]});
  }



  function refreshKoreksi(NOBUKTI) {
    $('.showhide').hide();
    console.log(NOBUKTI)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaanputaway') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI,

      },
      success: function(res) {
        console.log(res ,'ress =============================')
        koreksiDataArray = res
        // let rowTable = ""
        // res.forEach((item, i) => {
        //
        //   rowTable += `<tr>
        //   <td>${item.KODEBRG}</td>
        //   <td>${item.NamaBrg}</td>
        //   <td>${item.QNT}</td>
        //   <td>${item.SATUAN}</td>
        //   <td class="text-center">
        //   <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
        //   <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})"><i class="bi bi-trash"></i></button>
        //   </td>
        //   </tr>`
        // });
        // console.log('tes2')
        // console.log(rowTable)
        // document.getElementById("koreksiTableData").innerHTML = rowTable
      }
    })

    if (!koreksiDataArray.length) {
      alertify.success('Item habis');
      $("#formKoreksi").modal('toggle')
      return
    }
    let rowTable = ""
    koreksiDataArray.forEach((item, i) => {

      rowTable += `<tr>
      <td>${item.KODEBRG}</td>
      <td>${item.NamaBrg}</td>
      <td>${item.QNT}</td>
      <td>${item.SATUAN}</td>
      <td class="text-center">
      <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
      <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})"><i class="bi bi-trash"></i></button>
      </td>
      </tr>`
    });
    console.log('tes2')
    // console.log(rowTable)
    document.getElementById("koreksiTableData").innerHTML = rowTable

  }

  function buttonKoreksi(NOBUKTI) {
    $('.showhide').hide();
    console.log(NOBUKTI)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaanputaway') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI,

      },
      success: function(res) {
        console.log(res ,'ress =============================')
        koreksiDataArray = res
        let rowTable = ""
        res.forEach((item, i) => {

          // console.log(item.kodebrg)
          rowTable += `<tr>
          <td>${item.KODEBRG}</td>
          <td>${item.NamaBrg}</td>
          <td>${item.QNT}</td>
          <td>${item.SATUAN}</td>
          <td class="text-center">
          <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
          <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})"><i class="bi bi-trash"></i></button>
          </td>
          </tr>`
        });
      //   <td>${item.KODEBRG}</td>
      //   <td>${item.NamaBrg}</td>
      //   <td>${item.QNT}</td>
      //   <td>${item.SATUAN}</td>
      //   <td><button class="btn btn-danger btn-sm" type="button" onclick=""><i class="bi bi-trash"></i></button></td>
      // </td>
        console.log('tes2')
        // console.log(rowTable)
        document.getElementById("koreksiTableData").innerHTML = rowTable


        document.getElementById("input_koreksi_nopen").value = res[0].NOBUKTI

        let date = new Date(res[0].tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#input_koreksi_tanggal').val(date1)
        // document.getElementById("input_koreksi_tanggal").value = res[0].tanggal
        // document.getElementById("koreksiEditInputQty").value = koreksiDataArray[index].QNT
      }
    })
    $("#formKoreksi").modal('toggle')
  }

  function buttonKoreksiEdit (index) {
    console.log(index)
    console.log(koreksiDataArray[index])
    koreksiDataEdit = koreksiDataArray[index]

    document.getElementById("koreksiEditKodeBrg").value = koreksiDataArray[index].KODEBRG
    document.getElementById("koreksiEditNamaBrg").value = koreksiDataArray[index].NamaBrg
    document.getElementById("koreksiEditInputQty").value = koreksiDataArray[index].QNT

    $('#formKoreksiEdit').show();
  }



  function buttonKoreksiDelete (index) {
    let data = koreksiDataArray[index]
    console.log(data)

    alertify.confirm('Hapus Item', 'Apakah yakin ingin menghapus item ' + data.KODEBRG + data.NamaBrg + ' ?',
        function() {
          let _token = $("#_token").val();

          // let _token = $("#_token").val();
          // let inputQnt = $('#koreksiEditInputQty').val()
          let namaBrg = data.NamaBrg
          let kodeBrg = data.KODEBRG
          let urut = data.URUT
          let choice = "D"
          let noBukti = data.NOBUKTI

          $.ajax({
            url: "{!! url('koreksiputaway') !!}",
            type: "post",
            async: false,
            data: {
              _token : _token,
              choice,
              inputQnt: 0,
              nobukti: noBukti,
              urut,
              kodeBrg

            },
            success: function(res) {
              console.log(res ,'succes delete koreksi')
              refreshKoreksi(noBukti)
              alertify.success('Item telah didelete');
              loadAll()
            }
          })


        }
      ,function(){
        console.log('no')
      });
  }

  function submitEditKoreksi () {
    let _token = $("#_token").val();
    let inputQnt = $('#koreksiEditInputQty').val()
    let namaBrg = koreksiDataEdit.NamaBrg
    let kodeBrg = koreksiDataEdit.KODEBRG
    let urut = koreksiDataEdit.URUT
    let choice = "U"
    let noBukti = koreksiDataEdit.NOBUKTI

    console.log(namaBrg, kodeBrg, noBukti)
    console.log(inputQnt, urut)


    $.ajax({
      url: "{!! url('koreksiputaway') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        choice,
        inputQnt,
        nobukti: noBukti,
        urut,
        kodeBrg

      },
      success: function(res) {
        console.log(res ,'ress =============================')
        refreshKoreksi(noBukti)
        alertify.success('Item telah diedit');
        loadAll()

      }
    })


  }

  function hideShowhide () {
    $('.showhide').hide();
  }

  function buttonDetailKoreksi(NOBUKTI) {
    console.log(NOBUKTI)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaanputaway') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI,

      },
      success: function(res) {
        console.log(res ,'ress =============================')
        let rowTable = ""
        res.forEach((item, i) => {

          // console.log(item.kodebrg)
          rowTable += `<tr>
          <td>${item.KODEBRG}</td>
          <td>${item.NamaBrg}</td>
          <td>${item.QNT}</td>
          <td>${item.SATUAN}</td>
          </tr>`
        });
      //   <td>${item.KODEBRG}</td>
      //   <td>${item.NamaBrg}</td>
      //   <td>${item.QNT}</td>
      //   <td>${item.SATUAN}</td>
      //   <td><button class="btn btn-danger btn-sm" type="button" onclick=""><i class="bi bi-trash"></i></button></td>
      // </td>
        console.log('tes2')
        // console.log(rowTable)
        document.getElementById("detailKoreksiTableData").innerHTML = rowTable

        document.getElementById("input_detailKoreksi_nopen").value = res[0].NOBUKTI

        let date = new Date(res[0].tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#input_detailKoreksi_tanggal').val(date1)
        // document.getElementById("input_detailKoreksi_tanggal").value = res[0].TANGGAL
      }
    })


    $("#formDetailKoreksi").modal('toggle')
  }

  function enterScannerKode1(e) {
    if (e.which === 13) {
      // SML/LPB/00035/101911209092300020
      // SML/LPB/00351/012320304161130061
      // let barcode = $("#input_koreksi_scanner").val();
      // console.log(barcode)
      // if (barcode.length === 8) {
      //   document.getElementById("input_koreksi_kodelokasi").value = barcode
      // } else if ( barcode.length === 14) {
      //   document.getElementById("input_koreksi_kodebarang").value = barcode
      // }
      // console.log(barcode)
      // let kodebrg = $("#input_koreksi_kodebarang").val();
      // let kodelokasi = $("#input_koreksi_kodelokasi").val();
      // if (kodebrg && kodelokasi) {
      // }

      let kode1 = document.getElementById("input_add_kode1").value

      if (!(kode1.length === 32 || kode1.length === 31 || kode1.length === 33)) {
        alertify.warning("kode tidak dikenali");
        document.getElementById("input_add_kode1").value = ''
        $('#input_add_kode1').focus();
        return
      }
      // console.log('tes1')
      // return
      // if (kode1.length !== 32) {
      //
      //   alertify.warning("kode tidak dikenali");
      //   document.getElementById("input_add_kode1").value = ''
      //   $('#input_add_kode1').focus();
      //   return
      //
      // }
      // let kode1 = document.getElementById("input_add_kode1").value
      let kode4 = ""
      let kode5 = ""

      if (kode1.length === 32) {
        kode4 = kode1.slice(0,18)
        kode5 = kode1.slice(18)
      }
      if (kode1.length === 31) {
        kode4 = kode1.slice(0,17)
        kode5 = kode1.slice(17)
      }
      if (kode1.length === 33) {
        kode4 = kode1.slice(0,19)
        kode5 = kode1.slice(19)
      }


      let _token = $("#_token").val();
      // let item = {}
        $.ajax({
          url: "{!! url('checkaddputaway') !!}",
          type: "post",
          async: false,
          data: {
            _token : _token,
            nobukti: kode4,
            nobarang: kode5,

          },
          success: function(res) {
            console.log(res ,'ress =============================')
            console.log(1)
            if (res.length == 0) {
              console.log(2)
              alertify.warning("periode / barang sudah diterima");
              document.getElementById("input_add_kode1").value = ''
              $('#input_add_kode1').focus();
              return
            }
            console.log(3)
            $('#input_add_kode2').focus();
            // console.log(res)
            // item = res[0]
            // document.getElementById("input_add_kode1").value = ''
            // document.getElementById("input_add_kode2").value = ''
            // $('#input_add_kode1').focus();
          }
        })

      // $('#input_add_kode2').focus();

      // document.getElementById("input_koreksi_scanner").value = ''

    }
  }

  function enterScannerKode2(e) {
    if (e.which === 13) {
      let kode1 = document.getElementById("input_add_kode1").value
      if (!(kode1.length === 32 || kode1.length === 31 || kode1.length === 33)) {

        alertify.warning("kode tidak dikenali");
        document.getElementById("input_add_kode1").value = ''
        $('#input_add_kode1').focus();
        return

      }
      // SML/LPB/00035/101911209092300020

      let kode4 = ""
      let kode5 = ""

      if (kode1.length === 32) {
        kode4 = kode1.slice(0,18)
        kode5 = kode1.slice(18)
      }
      if (kode1.length === 31) {
        kode4 = kode1.slice(0,17)
        kode5 = kode1.slice(17)
      }
      if (kode1.length === 33) {
        kode4 = kode1.slice(0,19)
        kode5 = kode1.slice(19)
      }


      // let kode4 = kode1.slice(0,18)
      // let kode5 = kode1.slice(18)
      let kode2 = document.getElementById("input_add_kode2").value
      // console.log(kode4,kode5,kode2)
      console.log('nobukti', kode4)
      console.log('nobarang' , kode5)
      console.log('kodelokasi', kode2)

      let _token = $("#_token").val();
      // let item = {}
        $.ajax({
          url: "{!! url('addputaway') !!}",
          type: "post",
          async: false,
          data: {
            _token : _token,
            nobukti: kode4,
            nobarang: kode5,
            barcodelokasi: kode2

          },
          success: function(res) {
            console.log(res ,'ress =============================')
            // console.log(res)
            // item = res[0]
            if (res === '1') {
              document.getElementById("input_add_kode1").value = ''
              document.getElementById("input_add_kode2").value = ''
              loadAll()
              $('#input_add_kode1').focus();
              alertify.success('Put Away telah ditambah')
            } else {
              alertify.warning('Terjadi kesalahan, silahkan input ulang')
            }

          }
        })
    }
  }

  function resetScannerKode1 () {
    document.getElementById("input_add_kode1").value = ''
    $('#input_add_kode1').focus();
  }

  function resetScannerKode2 () {
    document.getElementById("input_add_kode2").value = ''
    $('#input_add_kode2').focus();
  }
</script>




@endsection
