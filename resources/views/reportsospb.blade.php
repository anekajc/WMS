@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">
  <div class="row">

 <div class="col-12 text-left">
    <div class="row">

    <h3>Report So - SPB</h3>  <button data-placement="top" title="Filter Date" style="height: 40px; margin-left: 10px" class="btn btn-secondary btn-sm" type="button" onclick="buttonOpenFilter()"  ><i class="bi bi-funnel"></i></button>
     <!-- <button  class="btn btn-primary" style="height: 40px; margin-left: 10px" onclick="buttonOpenFilter()">Filter</button> -->
  </div>
  </div>


  </div>
<!-- <button onclick="loadAll()">tes</button> -->
<!-- <svg id="barcode"></svg> -->
<!--<button onclick="submitFilter()">tes</button>
<button onclick="loadAll1()">tes1</button>
<button onclick="loadAll2()">tes2</button> -->
</div>

<!-- <div class="container"> -->


<div id="printContainer" style="display:none">

</div>

<div id="tempPrintContainer" style="display:none">

</div>

<div id="contentContainer" class="container-fluid">
  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />


  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />


  <div class="card mt-6">
    <div class="card-header">
      <div class="row">
        <nav style="width: 100%;">
          <div class="nav nav-tabs col-12" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="nav-home" aria-selected="true" style="color: black;">Report SO - SPB</a>

          </div>
        </nav>
      </div>

    </div>
    <div class="card-body" >
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-13" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabel" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">No So</th>
                          <th scope="col">Tgl So</th>
                          <th scope="col">Kode Barang</th>
                          <th scope="col">Nama Barang</th>
                          <th scope="col">Qnt SO</th>
                          <th scope="col">No PL</th>
                          <th scope="col">Qnt PList</th>
                          <th scope="col">SQnt Picking</th>
                          <th scope="col">Qnt Reject</th>

                          <th scope="col">No Spb</th>
                          <th scope="col">Qnt SPB</th>
                          <th scope="col">No Rspb</th>
                          <th scope="col">Qnt RSPB</th>

                        </tr>
                      </thead>
                      <!-- nobukti , tanggal, nama cust, nama barang, pocust, qntso, qty, stock , sat, catatan, lokasipenerima, alamat kirim, duedate, pr, part number, merk, userid -->

                      <tbody id="tabel_data" class="text-left" >
                        @for ($i = 0; $i < count($listData); $i++)
                        <tr>
                          <td>{{ $listData[$i]->noso }}</td>
                          <td>{{ $listData[$i]->tglso }}</td>
                          <td>{{ $listData[$i]->kodebrg }}</td>
                          <td>{{ $listData[$i]->namabrg }}</td>
                          <td>{{ (int)$listData[$i]->qntso ? $listData[$i]->qntso : '0.00' }}</td>
                          <td>{{ $listData[$i]->nopl }}</td>
                          <td>{{ (int)$listData[$i]->qntpckinglist ? $listData[$i]->qntpckinglist : '0.00' }}</td>
                          <td>{{ (int)$listData[$i]->qntp ? $listData[$i]->qntp : '0.00' }}</td>
                          <td>{{ (int)$listData[$i]->qntplreject ? $listData[$i]->qntplreject : '0.00' }}</td>
                          <td>{{ $listData[$i]->nospb }}</td>
                          <td>{{ (int)$listData[$i]->qntspb ? $listData[$i]->qntspb : '0.00' }}</td>

                          <td>{{ $listData[$i]->norspb }}</td>
                          <td>{{ (int)$listData[$i]->qntspb ? $listData[$i]->qntrspb : '0.00' }}</td>
                        </tr>
                        @endfor
                      </tbody>


                    </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- <div> -->


<!-- start modal add -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" style=" " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filter Date</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <h1>Tes Modal</h1> -->

        <div class="container-fluid">
          <div class="row">


                <div class="container-fluid">
                <div style=" " class="rounded">
                  <h5>Bulan</h5>
                  <select class="form-control" aria-label="Default select example" id="input_date_month" >
                    <option value=0 selected disabled>Pilih Bulan</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                  <h5 style="margin-top:10px">Tahun</h5>
                  <input type="number" class="form-control" id="input_date_year" value="{!! $periode->tahun !!}">


              </div>








              </div>



          </div>

        </div>


      </div>




      <div class="modal-footer">
       <button type="button" class="btn btn-primary" onclick="submitFilter()">Submit</button>
      </div>


    </div>
  </div>
</div>
<!-- End modal add-->




@endsection

@section('js')


<script type="text/javascript">



$(document).ready(function(){

      $("#tabel").DataTable({
        // 'order': [[0, 'asc']],
       // "lengthChange": false,
         // "paging": false ,
           "columnDefs": [
        // { "type": "date", "targets": [2] },
        {  "className": "text-right", "targets": [5],  },
      ]});
});

function buttonOpenFilter () {
  // console.log(document.getElementById("filterContainer").style.display)
  // let display = document.getElementById("filterContainer").style.display
  // if (display == "block") {
  //   document.getElementById("filterContainer").style.display = "none";
  // } else {
  //
  //   document.getElementById("filterContainer").style.display = "block";
  // }
  let bulan = $("#input_date_month").val();
  console.log(bulan)
  if (!bulan) {

    document.getElementById("input_date_month").value = $("#periode_bulan").val();
  }

  $("#form").modal('toggle')


}

function submitFilter () {
  // console.log(tanggal)
  let _token = $("#_token").val();
  

  let bln = $("#input_date_month").val();
  let thn = $("#input_date_year").val();

  $.ajax({
    url: "{!! url('reportsospbspfilter') !!}",
    type: "post",
    async: false,
    data: {
      _token,
      bln,
      thn
    },
    success: function(res) {
    console.log(res)
    $('#tabel').DataTable().destroy();
    let rowTable = ""
    // tabel_data_add_list_lokasi
    res.forEach((item, i) => {

      rowTable += `
      <tr>
        <td>${item.noso}</td>
        <td>${item.tglso}</td>
        <td>${item.kodebrg}</td>
        <td>${item.namabrg}</td>
        <td>${Number(item.qntso) ? item.qntso : '0.00'}</td>
         <td>${item.nopl}</td>
         <td>${Number(item.qntpckinglist) ? item.qntpckinglist : '0.00'}</td>
         <td>${Number(item.qntp) ? item.qntp : '0.00'}</td>
         <td>${Number(item.qntplreject) ? item.qntplreject : '0.00'}</td>
         <td>${item.nospb}</td>
         <td>${Number(item.qntspb) ? item.qntspb : '0.00'}</td>
         <td>${item.norspb}</td>
         <td>${Number(item.qntrspb) ? item.qntspb : '0.00'}</td>
      </tr>`
    });




    document.getElementById("tabel_data").innerHTML = rowTable
    $("#tabel").DataTable({
      // 'order': [[0, 'asc']],
      //"lengthChange": false,
       // "paging": false ,
         "columnDefs": [
      // { "type": "date", "targets": [2] },
      {  "className": "text-right", "targets": [5],  },
    ]});
  }});



  $("#form").modal('toggle')
}


</script>


}


@endsection
