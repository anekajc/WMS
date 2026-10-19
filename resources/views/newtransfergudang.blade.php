@extends('newmaster')
@section('buttons')

@endsection
@section('content')
<div class="container-fluid">
<h1>Transfer gudang</h1>
<!-- <button onclick="loadAll()">tes</button> -->
</div>

<div id="printContainer" style="display:none">

</div>

<div id="tempPrintContainer" style="display:none">

</div>

<div id="contentContainer" class="container-fluid">

  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />

  <input type="hidden" id="akses_istambah" value="{!! $akses->ISTAMBAH !!}" />
  <input type="hidden" id="akses_ishapus" value="{!! $akses->ISHAPUS!!}" />
  <input type="hidden" id="akses_iskoreksi" value="{!! $akses->ISKOREKSI !!}" />
  <input type="hidden" id="akses_iscetak" value="{!! $akses->ISCETAK !!}" />

  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
  <div class="card">
    <div class="card-header">
      <div class="row">
        <nav style="width: 100%;">
          <div class="nav nav-tabs col-12" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="nav-home" aria-selected="true" style="color: black;">Outstanding Transfer Gudang</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="nav-profile" aria-selected="false" style="color: black;">Transaksi Terima Transfer</a>
          </div>
        </nav>
      </div>
    </div>
    <div class="card-body" >
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-12" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabel" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Actions</th>
                          <th scope="col">No. TRFs</th>
                          <th scope="col">Tanggal</th>
                          <th scope="col">Gdg Asal</th>
                          <th scope="col">Gdg Tujuan</th>
                        </tr>
                      </thead>


                      <tbody id="tabel_data" class="text-left" >
                        @for ($i = 0; $i < count($outstandingArray); $i++)
                        <tr>
                          <td class="text-center"><button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetail('{{$outstandingArray[$i][0]->NOBUKTI}}')" ><i class="bi bi-info-lg"></i></button>
                            <button class="btn btn-success btn-sm" type="button" onclick="buttonAdd('{{ $outstandingArray[$i][0]->NOBUKTI }}')"><i class="bi bi-bag-plus-fill"></i></button></td>
                            <td>{{ $outstandingArray[$i][0]->NOBUKTI }}</td>
                            <td>{!! date("Y/m/d", strtotime($outstandingArray[$i][0]->TANGGAL)) !!}</td>
                            <td>{{ $outstandingArray[$i][0]->NamagdgAsal }}</td>
                            <td>{{ $outstandingArray[$i][0]->NamagdgTujuan }}</td>
                        </tr>
                        @endfor
                      </tbody>


                    </table>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <div class="row">
            <div class="col-12" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabelRetur" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Actions</th>
                          <th scope="col">No. TRT</th>
                          <th scope="col">Tanggal</th>
                          <th scope="col">No. TRF</th>
                          <th scope="col">Gudang Asal</th>
                          <th scope="col">Gudang Terima</th>
                        </tr>
                      </thead>


                      <tbody id="tabelRetur_data" class="text-left" >
                        @for ($i = 0; $i < count($penerimaanArray); $i++)
                        <tr>
                          <td class="text-center"><button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetailKoreksi('{{ $penerimaanArray[$i][0]->NOBUKTI }}')" ><i class="bi bi-info-lg"></i></button>
                            <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('{{ $penerimaanArray[$i][0]->NOBUKTI }}')"><i class="bi bi-pen"></i></button>
                            <button style="" class="btn btn-primary btn-sm" type="button"   onclick="buttonPrintPenerimaan('{{ $penerimaanArray[$i][0]->NOBUKTI }}')" ><i class="bi bi-printer"></i></button>
                          </td>
                          <td>{{ $penerimaanArray[$i][0]->NOBUKTI }}</td>
                          <td>{!! date("Y/m/d", strtotime($penerimaanArray[$i][0]->TANGGAL)) !!}</td>
                          <td>{{ $penerimaanArray[$i][0]->Notransfer }}</td>
                          <td>{{ $penerimaanArray[$i][0]->NamagdgAsal }}</td>
                          <td>{{ $penerimaanArray[$i][0]->NamagdgTujuan }}</td>
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


<!-- start modal add -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
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


            <div class="col-12">
              <div class="form-group">
                <label>No TRT</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_notrt" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No TRF</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_notrf" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg Asal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_gdgasal" placeholder="Gdg Asal" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_add_tanggal" value="{!! date('Y-m-d') !!}" >
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Gdg Tujuan</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_gdgtujuan" placeholder="Gdg Tujuan" disabled>
              </div>
            </div>


          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



      </div>
        <div class="container-fluid" style="overflow-x: auto;">

              <table id="addTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <th style="" scope="col">Terima</th>
                    <th scope="col">Kode Brg</th>
                    <th scope="col">Nama Brg</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Qty OS</th>
                    <th scope="col">Satuan</th>
                    <th scope="col">Qty Terima</th>

                  </tr>
                </thead>


                <tbody id="addTableData" class="text-right" >
                  <tr >

                      <td class="text-center"><input class="" type="checkbox" value="" id="flexCheckDefault"></td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>

                </tr>

                </tbody>


              </table>
        </div>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitAdd()">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal add-->

<!-- start modal Detail -->
<div class="modal fade" id="formDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
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
          <input type="hidden" name="noUrut" id="input_detail_noUrut" value="" />
          <div class="row">


            <div class="col-12">
              <div class="form-group">
                <label>Gdg Tujuan</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_gdgtujuan" placeholder="Gdg Tujuan" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No TRF</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_notrf" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg Asal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_gdgasal" placeholder="Gdg Tujuan" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_detail_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>




          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



      </div>
        <div class="container-fluid" style="overflow-x: auto;">

              <table id="detailTable" class="table table-bordered table-striped"  >
                <thead class="text-center">
                  <tr>
                    <th scope="col">Kode Brg</th>
                    <th scope="col">Nama Brg</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Qty OS</th>
                    <th scope="col">Satuan</th>

                  </tr>
                </thead>


                <tbody id="detailTableData" class="text-right" >
                  <tr >

                      <td>!</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>

                </tr>

                </tbody>


              </table>
        </div>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>

      </div>
    </div>
  </div>
</div>
<!-- End modal Detail-->

<!-- start modal koreksi -->
<div class="modal fade" id="formKoreksi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Koreksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">


            <div class="col-12">
              <div class="form-group">
                <label>No TRT</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_notrt" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No TRF</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_notrf" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg Asal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_gdgasal" placeholder="Kode Cust" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_koreksi_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Gdg Terima</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_gdgtujuan" placeholder="Nama Cust" disabled>
              </div>
            </div>


          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>

        <div class="container-fluid">
          <div class="row ">
            <div class="col-md-12 text-right">
            <button type="button" class="btn btn-primary" onclick="buttonKoreksiAdd()" class="btn btn-secondary"  >Add Item</button>
        </div>

        <div class="container-fluid">
          <div id="formKoreksiAdd" class="container-fluid showhide">
            <div class="line"></div>
            <div class="row">
              <div class="col-12">
                <h4>Add Item</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label>Pilih Barang</label>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <select onchange="changeSelectKoreksiAdd()" id="koreksiAddSelect" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Kode Barang</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiAddKodeBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Nama Barang</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiAddNamaBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Qty</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiAddInputQty" type="number" value=0.00 class="form-control">
              </div>
              <div class="col-12">
                <div class="form-group">
                <label>Satuan</label>
              </div>
              </div>
              <div class="col-12">

                  <input type="text" id="koreksiAddSatuan" value="PCS" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Qty OS</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiAddQtyOS" type="number" value=0.00 class="form-control" disabled>
              </div>
              <div class="col-12">
                <div class="form-group">
                <label>Qty PO</label>
              </div>
              </div>
              <div class="col-12">

                  <input id="koreksiAddQtyPO" type="number" value=0.00 class="form-control" disabled>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary" onclick="buttonBatalShowHide()" >Batal</button>
                <button type="button" onclick="submitAddKoreksi()" class="btn btn-primary" >Add Item</button>
              </div>

            </div>
            <div class="line"></div>
          </div>


          <!-- koreksi edit -->

          <div id="formKoreksiEdit" class="container-fluid showhide">
            <div class="line"></div>
            <div class="row">
              <div class="col-12">
                <h4>Edit Item</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Kode Barang</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiEditKodeBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Nama Barang</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiEditNamaBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Qty</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiEditInputQty" type="number" value=0.00 class="form-control">
              </div>
              <div class="col-12">
                <div class="form-group">
                <label>Satuan</label>
              </div>
              </div>
              <div class="col-12">

                  <input type="text" id="koreksiEditSatuan" value="PCS" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                <label>Qty OS</label>
              </div>
              </div>
              <div class="col-12">
                <input id="koreksiEditQtyOS" type="number" value=0.00 class="form-control" disabled>
              </div>

            </div>
            <div class="row mt-2">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary" onclick="buttonBatalShowHide()" >Batal</button>
                <button type="button" onclick="submitEditKoreksi()" class="btn btn-primary" >Edit Item</button>
              </div>

            </div>
            <div class="line"></div>
          </div>
        </div>

          <div class="container-fluid mt-4" style="overflow-x: auto;">

                <table id="koreksiTable" class="table table-bordered table-striped"  >
                  <thead class="text-center">
                    <tr>
                      <th scope="col">Kode Brg</th>
                      <th scope="col">Nama Brg</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Qty OS</th>
                      <th scope="col">Satuan</th>
                      <th scope="col">Actionss</th>
                    </tr>
                  </thead>


                  <tbody id="koreksiTableData" class="text-right" >
                    <tr >

                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                  </tr>

                  </tbody>


                </table>
          </div>

          <!-- koreksi add -->


      </div>





    </div>
  </div>
</div>
</div>
</div>
<!-- End modal koreksi-->

<!-- start modal detail koreksi -->
<div class="modal fade" id="formKoreksiDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Koreksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">


            <div class="col-12">
              <div class="form-group">
                <label>No TRT</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_detail_notrt" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No TRF</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_detail_notrf" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg Asal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_detail_gdgasal" placeholder="Kode Cust" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_koreksi_detail_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Gdg Terima</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_detail_gdgtujuan" placeholder="Nama Cust" disabled>
              </div>
            </div>


          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



          <div class="container-fluid mt-4" style="overflow-x: auto;">

                <table id="koreksiDetailTable" class="table table-bordered table-striped"  >
                  <thead class="text-center">
                    <tr>
                      <th scope="col">Kode Brg</th>
                      <th scope="col">Nama Brg</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Qty OS</th>
                      <th scope="col">Satuan</th>
                    </tr>
                  </thead>


                  <tbody id="koreksiDetailTableData" class="text-right" >
                    <tr >

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





    </div>
  </div>
</div>
</div>
</div>
<!-- End modal detail koreksi-->


<!-- start modal detail koreksi -->
<div class="modal fade" id="formPrint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Print</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">


            <div class="col-12">
              <div class="form-group">
                <label>No TRT</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_print_notrt" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No TRF</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_print_notrf" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg Asal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_print_gdgasal" placeholder="Kode Cust" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_print_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Gdg Terima</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_print_gdgtujuan" placeholder="Nama Cust" disabled>
              </div>
            </div>


          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



          <div class="container-fluid mt-4" style="overflow-x: auto;">

                <table id="printTable" class="table table-bordered table-striped"  >
                  <thead class="text-center">
                    <tr>
                      <th scope="col">v</th>
                      <th scope="col">Kode Brg</th>
                      <th scope="col">Nama Brg</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Qty OS</th>
                      <th scope="col">Satuan</th>
                    </tr>
                  </thead>


                  <tbody id="printTableData" class="text-right" >
                    <tr >

                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                  </tr>

                  </tbody>


                </table>
          </div>
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 text-right">
                <label>Jumlah Print</label>

                <input type="number" value=1 style="max-width: 50px" class="text-right" id="input_print_jumlahprint" >
              </div>
              <!-- <div class="col-1">
              </div> -->

            </div>

          </div>



        <div class="modal-footer mt-4">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>
          <button type="button" class="btn btn-primary" onclick="submitPrint()">Print</button>
        </div>




      </div>





    </div>
  </div>
</div>
</div>
</div>
<!-- End modal detail koreksi-->


@endsection

@section('js')
<script type="text/javascript">


  // loadall
  let dataRefreshOutstanding = []
  let dataRefreshPenerimaan = []
  // add outstanding
  let addDataArray = []

  // penerimaan
  let koreksiDataTrt = {}
  let koreksiPenerimaanArray = []
  let koreksiDataAddList = []
  let koreksiDataEdit = {}
  let akses = {}

  $(document).ready(function(){
        $("#tabel").DataTable({ 'order': [[1, 'asc']],"lengthChange": false,
            "paging": false ,
             "columnDefs": [
          { "type": "date", "targets": [2] },
          {  "className": "text-center", "targets": [0],"orderable" : false },
        ]});

        $("#tabelRetur").DataTable({'order': [[1, 'asc']], "lengthChange": false,
            "paging": false ,
             "columnDefs": [
          { "type": "date", "targets": [2] },
          {  "className": "text-center", "targets": [0], "orderable" : false },
        ]});


  });


  function loadAll () {
    $.ajax({
      url: "{!! url('loadalltransfergudang') !!}",
      type: "get",
      async: false,
      success: function(res) {
        console.log(res)
        dataRefreshOutstanding = res.outstandingArray
        dataRefreshPenerimaan = res.penerimaanArray
        console.log(res.akses)
        // akses = res.akses
        document.getElementById("akses_istambah").value = res.akses.ISTAMBAH
        document.getElementById("akses_iskoreksi").value = res.akses.ISKOREKSI
        document.getElementById("akses_iscetak").value = res.akses.ISCETAK
        document.getElementById("akses_ishapus").value = res.akses.ISHAPUS
      }
    })
    // console.log('tes=======' , akses)
    console.log(dataRefreshOutstanding)
    console.log(dataRefreshPenerimaan)

    $('#tabel').DataTable().destroy();
    let rowTable = ""
    dataRefreshOutstanding.forEach((item, i) => {

      let date1 = ""
      if (item[0].TANGGAL) {
        let date = new Date(item[0].TANGGAL);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear() +"/"+month+"/"+day;
      }
      rowTable  += `<tr>
      <td class="text-center">
      <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetail('${item[0].NOBUKTI}')" ><i class="bi bi-info-lg"></i></button>
      <button class="btn btn-success btn-sm" type="button" onclick="buttonAdd('${item[0].NOBUKTI}')"><i class="bi bi-bag-plus-fill"></i></button>
      </td>
      <td>${item[0].NOBUKTI}</td>
      <td>${date1}</td>
      <td>${item[0].NamagdgAsal ? item[0].NamagdgAsal : ''}</td>
      <td>${item[0].NamagdgTujuan ? item[0].NamagdgTujuan : ''}</td>
      </tr>`
    })
    document.getElementById("tabel_data").innerHTML = rowTable
    $("#tabel").DataTable({ 'order': [[1, 'asc']], "lengthChange": false,
        "paging": false ,
         "columnDefs": [
      { "type": "date", "targets": [2] },
      {  "className": "text-center", "targets": [0], "orderable" : false },
    ]});



    $('#tabelRetur').DataTable().destroy();
    let rowTableRetur = ""
    dataRefreshPenerimaan.forEach((item, i) => {
      let date1 = ""
      // console.log(item[0].TANGGAL)
      // return
      if (item[0].TANGGAL) {
        let date = new Date(item[0].TANGGAL);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear() +"/"+month+"/"+day ;
      }

      rowTableRetur += `<tr>
      <td class="text-center">
      <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetailKoreksi('${item[0].NOBUKTI}')" ><i class="bi bi-info-lg"></i></button>
      <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('${item[0].NOBUKTI}' )"><i class="bi bi-pen"></i></button>
      <button class="btn btn-primary btn-sm" type="button" onclick="buttonPrintPenerimaan('${item[0].NOBUKTI}')"><i class="bi bi-printer"></i></button>
      </td>
      <td>${item[0].NOBUKTI}</td>
      <td>${date1}</td>
      <td>${item[0].Notransfer}</td>
      <td>${item[0].NamagdgAsal }</td>
      <td>${item[0].NamagdgTujuan}</td>
      </tr>`
    });

    document.getElementById("tabelRetur_data").innerHTML = rowTableRetur
    $("#tabelRetur").DataTable({'order': [[1, 'asc']], "lengthChange": false,
        "paging": false ,
         "columnDefs": [
      { "type": "date", "targets": [2] },
      {  "className": "text-center", "targets": [0], "orderable" : false },
    ]});

  }


  function submitAddKoreksi () {
    // console.log('submit add koreksi')
    // console.log(koreksiDataTrt)
    let check = document.getElementById("koreksiAddSelect").value;
    // console.log(check,'check')
    if (check === "") {
      // console.log('a')
      alertify.warning("Tidak ada item dipilih");
      return
    }
    let dataTrf = koreksiDataAddList[check]
    let qntTerima = $("#koreksiAddInputQty").val()

      // console.log(qntTerima , dataTrf.QntOS)
    if (Number(qntTerima) > Number(dataTrf.QntOS)) {
      alertify.warning("Qty tidak bisa lebih besar dari Qty OS");
      return
    }

    if (Number(qntTerima) <= 0) {
      alertify.warning("Qty tidak bisa 0 atau negatif");
      return
    }
    let qntTerima1 = 0
    let qntTerima2 = 0
    // console.log(dataTrf.ISI2 , ' <<iis2')
    if (dataTrf.NOSAT == 1) {
      qntTerima1 = qntTerima
      qntTerima2 = qntTerima / dataTrf.ISI2
    } else if (dataTrf.nosat == 2) {
      qntTerima1 = qntTerima * dataTrf.ISI2
      qntTerima2 = qntTerima
    }
    let _token = $("#_token").val();
    let choice = "I"
    let notrt = koreksiDataTrt.NOBUKTI
    // console.log(choice, notrt)
    let nourut = koreksiDataTrt.NOURUT
    // console.log(nourut)
    let tanggal = koreksiDataTrt.TANGGAL
    let note = ''
    let urut = 0

    let kodebrg = dataTrf.KODEBRG
    let gdgasal = koreksiDataTrt.GdgAsal
    let gdgtujuan = koreksiDataTrt.GdgTujuan
    let sat_1 = dataTrf.SAT_1
    let sat_2 = dataTrf.SAT_2

    // console.log(kodebrg,sat_1,sat_2 , ' <<<<<<')


    let nosat = dataTrf.NOSAT
    let isi = dataTrf.ISI
    // console.log(qntTerima, nosat , isi)
    let notrf = dataTrf.NOBUKTI
    let uruttrf = dataTrf.URUT
    // console.log(notrf, uruttrf)
    console.log('choice' , choice)
    console.log('notrt' , notrt)
    console.log('nourut', nourut)
    console.log('tanggal' , tanggal)
    console.log('note' , note)
    console.log('urut' , urut)
    console.log('kodebrg' , kodebrg)
    console.log('gdgasal' , gdgasal)
    console.log('gdgtujuan' , gdgtujuan)
    console.log('sat_1' , sat_1)
    console.log('sat_2' , sat_2)
    console.log('qntTerima' , qntTerima)
    console.log('qntTerima2' , qntTerima2)
    console.log('nosat' , nosat)
    console.log('isi' , isi)
    console.log('notrf' , notrf)
    console.log('uruttrf' , uruttrf)

    $.ajax({
      url: "{!! url('koreksiTransferGudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        choice,
        notrt,
        nourut,
        tanggal,
        note,
        urut,
        kodebrg,
        gdgasal,
        gdgtujuan,
        sat_1,
        sat_2,
        qntTerima,
        qntTerima2,
        nosat,
        isi,
        notrf,
        uruttrf
      },
      success: function(res) {
        console.log(res ,'succesasdas')
        refreshKoreksi(notrt)
        loadAll()
      }
    })


  }



  function submitEditKoreksi () {
    console.log('submit edit koreksi')
    console.log(koreksiDataEdit)
    let dataTrt = koreksiDataEdit
    let _token = $("#_token").val();
    let choice = "U"
    let qntTerima = $("#koreksiEditInputQty").val()
    if (Number(qntTerima) > Number(koreksiDataEdit.QntOS) + Number(koreksiDataEdit.QNT)) {
      alertify.warning("Qty melebihi Qty OS");
      return
    }
    if(Number(qntTerima) < 0 ) {
      alertify.warning("Qty tidak boleh negatif");
      return
    }
    let qntTerima1 = 0
    let qntTerima2 = 0
    if (koreksiDataEdit.NOSAT == 1) {
      qntTerima1 = qntTerima
      qntTerima2 = qntTerima / koreksiDataEdit.ISI2
    } else if (koreksiDataEdit.NOSAT == 2) {
      qntTerima1 = qntTerima * koreksiDataEdit.ISI2
      qntTerima2 = qntTerima
    }
    let notrt = koreksiDataEdit.NOBUKTI
    let nourut = koreksiDataEdit.NOURUT
    let tanggal = koreksiDataEdit.TANGGAL
    let note = ''
    let urut = koreksiDataEdit.URUT
    let kodebrg = koreksiDataEdit.KODEBRG
    let gdgasal = koreksiDataEdit.GdgAsal
    let gdgtujuan = koreksiDataEdit.GdgTujuan
    let sat_1 = koreksiDataEdit.SAT_1
    let sat_2 = koreksiDataEdit.SAT_2
    let nosat = koreksiDataEdit.NOSAT
    let isi = koreksiDataEdit.ISI
    let notrf = ''
    let uruttrf = 0


    console.log('choice' , choice)
    console.log('notrt' , notrt)
    console.log('nourut', nourut)
    console.log('tanggal' , tanggal)
    console.log('note' , note)
    console.log('urut' , urut)
    console.log('kodebrg' , kodebrg)
    console.log('gdgasal' , gdgasal)
    console.log('gdgtujuan' , gdgtujuan)
    console.log('sat_1' , sat_1)
    console.log('sat_2' , sat_2)
    console.log('qntTerima' , qntTerima)
    console.log('qntTerima2' , qntTerima2)
    console.log('nosat' , nosat)
    console.log('isi' , isi)
    console.log('notrf' , notrf)
    console.log('uruttrf' , uruttrf)


    $.ajax({
      url: "{!! url('koreksiTransferGudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        choice,
        notrt,
        nourut,
        tanggal,
        note,
        urut,
        kodebrg,
        gdgasal,
        gdgtujuan,
        sat_1,
        sat_2,
        qntTerima,
        qntTerima2,
        nosat,
        isi,
        notrf,
        uruttrf
      },
      success: function(res) {
        console.log(res ,'succes edit koreksi')
        refreshKoreksi(notrt)
        loadAll()
      }
    })

  }


  function buttonKoreksiAdd() {
    let akses = $("#akses_istambah").val();
    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    $('.showhide').hide();
    document.getElementById("koreksiAddKodeBrg").value = ""
    document.getElementById("koreksiAddNamaBrg").value = ""
    document.getElementById("koreksiAddQtyOS").value = 0.00
    document.getElementById("koreksiAddQtyPO").value = 0.00
    $('#formKoreksiAdd').show();
  }

  function changeSelectKoreksiAdd() {
    let indexBarang = document.getElementById("koreksiAddSelect").value;
    console.log(indexBarang)
    console.log(koreksiDataAddList[indexBarang])

    document.getElementById("koreksiAddKodeBrg").value = koreksiDataAddList[indexBarang].KODEBRG
    document.getElementById("koreksiAddNamaBrg").value = koreksiDataAddList[indexBarang].NAMABRG
    document.getElementById("koreksiAddQtyOS").value = koreksiDataAddList[indexBarang].QntOS
    document.getElementById("koreksiAddQtyPO").value = koreksiDataAddList[indexBarang].QNT

  }

  function refreshKoreksi (NOBUKTI, istambah = 0 , iskoreksi = 0, ishapus = 0) {
    console.log('REFRESH <<<<<<<<<<<')
    $('.showhide').hide();
    console.log(NOBUKTI)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaantransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        // console.log(res)
        koreksiPenerimaanArray = res
        koreksiDataTrt = res[0]
      }
    })
    console.log(koreksiPenerimaanArray.length)
    if (!koreksiPenerimaanArray.length) {
      console.log('item habis')
      $("#formKoreksi").modal('toggle')
      return
    }
    console.log(koreksiPenerimaanArray[0])
    console.log(koreksiPenerimaanArray[0].TANGGAL , "ASDADASADAD <<<<<")
    let date = new Date(koreksiPenerimaanArray[0].TANGGAL);
    let day = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    date1 = date.getFullYear()+"-"+(month)+"-"+(day);
    $('#input_koreksi_tanggal').val(date1)
    document.getElementById("input_koreksi_notrt").value = koreksiPenerimaanArray[0].NOBUKTI
    document.getElementById("input_koreksi_notrf").value = koreksiPenerimaanArray[0].Notransfer
    document.getElementById("input_koreksi_gdgasal").value = koreksiPenerimaanArray[0].NamagdgAsal
    document.getElementById("input_koreksi_gdgtujuan").value = koreksiPenerimaanArray[0].NamagdgTujuan

    let rowTable = ""
    koreksiPenerimaanArray.forEach((item, i) => {
      rowTable += `<tr>
      <td>${item.KODEBRG}</td>
      <td>${item.NAMABRG}</td>
      <td>${item.QNT}</td>
      <td>${Number(item.QntOS) ? item.QntOS : '0.00'}</td>
      <td>${item.Satuan}</td>

      <td class="text-center">
      <button class="btn btn-warning btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
      <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})" ><i class="bi bi-trash"></i></button></td>
      </td>
      </tr>`
    });
    document.getElementById("koreksiTableData").innerHTML = rowTable
    koreksiPenerimaanArray.forEach((item, i) => {
     // JsBarcode(`#barcodeKoreksi${i}`, item.NOBUKTI + item.KODEBRG ,{width: 1, height: 25,displayValue: false});
    });

    $.ajax({
      url: "{!! url('transfergudangkoreksiaddlist') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        notrt: koreksiPenerimaanArray[0].NOBUKTI,
        notrf: koreksiPenerimaanArray[0].Notransfer
      },
      success: function(res) {
        console.log(res , "addlistkoreksi !!!!!!")
        koreksiDataAddList = res
      }
    })
    let tempKoreksiAddList = ""
    if(koreksiDataAddList.length) {
      tempKoreksiAddList += `<option value="" selected disabled>-- Pilih Barang --</option>`
      koreksiDataAddList.forEach((item, i) => {
        tempKoreksiAddList += `<option value="${i}">${item.KODEBRG} - ${item.NAMABRG}</option>`
      });
    } else {
      tempKoreksiAddList += `<option value="" selected disabled>Tidak ada barang untuk ditambah</option>`
    }



    document.getElementById("koreksiAddSelect").innerHTML = tempKoreksiAddList

  }

  function buttonKoreksiEdit(index) {
    console.log('buttonKoreksiEdit')
    let akses = $("#akses_iskoreksi").val();
    console.log(akses)
    if (!Number(akses)) {
      alertify.warning('No Access')
      return
    }
    $('.showhide').hide();
    // console.log(koreksiPenerimaanArray[index])
    // console.log(index)
    koreksiDataEdit = koreksiPenerimaanArray[index]
    document.getElementById("koreksiEditKodeBrg").value = koreksiDataEdit.KODEBRG
    document.getElementById("koreksiEditNamaBrg").value = koreksiDataEdit.NAMABRG
    document.getElementById("koreksiEditQtyOS").value = koreksiDataEdit.QntOS
    document.getElementById("koreksiEditInputQty").value = koreksiDataEdit.QNT
    $('#formKoreksiEdit').show();
    document.getElementById('formKoreksiEdit').scrollIntoView();
  }

  function buttonKoreksiDelete(index) {
    let akses = $("#akses_ishapus").val();
    if (!Number(akses)) {
      alertify.warning('No Access')
      return
    }

    console.log(koreksiPenerimaanArray[index])
    let dataTrt = koreksiPenerimaanArray[index]
    alertify.confirm('Hapus Item', 'Apakah yakin ingin menghapus item ' + koreksiPenerimaanArray[index].KODEBRG + koreksiPenerimaanArray[index].NAMABRG + ' ?',
      function() {
        let choice = 'D'
        let _token = $("#_token").val();
        let qntTerima = 0
        let qntTerima1 = 0
        let qntTerima2 = 0
        let notrt = dataTrt.NOBUKTI
        let nourut = dataTrt.NOURUT
        let tanggal = dataTrt.TANGGAL
        let note = ''
        let urut = dataTrt.URUT
        let kodebrg = dataTrt.KODEBRG
        let gdgasal = dataTrt.GdgAsal
        let gdgtujuan = dataTrt.GdgTujuan
        let sat_1 = dataTrt.SAT_1
        let sat_2 = dataTrt.SAT_2
        let nosat = dataTrt.NOSAT
        let isi = dataTrt.ISI
        let notrf = ''
        let uruttrf = 0
        $.ajax({
          url: "{!! url('koreksiTransferGudang') !!}",
          type: "post",
          async: false,
          data: {
            _token : _token,
            choice,
            notrt,
            nourut,
            tanggal,
            note,
            urut,
            kodebrg,
            gdgasal,
            gdgtujuan,
            sat_1,
            sat_2,
            qntTerima,
            qntTerima2,
            nosat,
            isi,
            notrf,
            uruttrf
          },
          success: function(res) {
            console.log(res ,'succes delete koreksi')
            refreshKoreksi(notrt)
            loadAll()
          }
        })
      }
    ,function(){
      console.log('no')
    });

  }

  function submitPrint () {

    let printContent1 = ""
    let jumlahPrint = $(`#input_print_jumlahprint`).val();
    let z= 0
    koreksiPenerimaanArray.forEach((item, i) => {
      if (document.getElementById(`printChecklist${i}`).checked) {
        // qrcodeprint
        let date = new Date(item.TANGGAL);
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        let printContent = document.getElementById(`tempqrcodeprint${i}`).innerHTML;

        let printContent2 = document.getElementById(`tempqrcodeprintkodebrg${i}`).innerHTML;
        printContent1 += `<div style="margin: 0px; padding: 0px ; text-align: center">`
        for (let i=0; i < jumlahPrint ; i++ ) {

          if (z == 0) {
            printContent1 += `<div style="break-inside: avoid; width:7.5cm; height: 4.5cm; padding-right: 3px">`
          } else {
            printContent1 += `<div style="break-inside: avoid; width:7.5cm; height: 4.5cm; page-break-before: always; margin-top: 10px; padding-right: 3px">`
          }

          // printContent1 += `<div style="break-inside: avoid; width:7.5cm; height: 4.5cm; page-break-after: always">`
          printContent1 += `<div  style="display: flex; flex-direction: column; margin-top: -25px" >`
          printContent1 += `<div class="" style="display: flex; flex-direction: row; text-align: center,">`
          printContent1 += `<div style="width:70%; padding-left: 5px; text-align: left"><h6 style=""> ${item.NOBUKTI}</h6> </div>`
          // printContent1 += `<div style="width:40%" ></div>`
          printContent1 += `<div style=" width:30%; padding-right: 8px; margin-right: 10px" > <h6>${item.KODEBRG} </h6> </div>`
          printContent1 += `</div>`

          printContent1 += `<div class="" style="display: flex; flex-direction: row;margin-top: -20px">`
          printContent1 += `<div  style="width:70%; padding-left: 5px">` + printContent +`</div>`
          // printContent1 += `<div style="width:10%">` + `</div>`
          printContent1 += `<div style=" width:30%; padding-right: 18px">`+ printContent2 +`</div>`
          printContent1 += `</div>`

          printContent1 += `</div>`
          printContent1 += `<div style="width: 7.5cm; text-align: center ; margin-top: -15px">`
          printContent1 += `<h6 style="font-weight: bold; font-size: 10px;padding-right: 5px">${item.NAMABRG} ${ item.NAMAMERK ? ' / ' + item.NAMAMERK : '' }</h6>`
          printContent1 += `</div>`



          printContent1 += `<div style="margin-top: -25px; text-align: center">`
          printContent1 += `<h6>${date1} / ${item.QNT} ${item.Satuan}</h6>`
          printContent1 += `</div>`
          printContent1 += `</div>`
          // printContent1 += `<div style=' page-break-before: always'></div>`
          z++
        }
        printContent1 += '</div>'
      }
    });

    document.getElementById("printContainer").innerHTML = printContent1
    w=window.open(' ');
    // w.opener = null;
    // w.refferer = null;
    // w.rel="noopener , noreferrer"

    w.document.write($(`#printContainer`).html());
    console.log('a')
    w.print();
    console.log('b')
    w.close();
    console.log('c')


  }

  function buttonPrintPenerimaan (NOBUKTI) {
    let akses = $("#akses_iscetak").val();
    if (!Number(akses)) {
      alertify.warning('No Access')
      return
    }
    console.log('button print' , NOBUKTI)

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaantransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        // console.log(res)
        koreksiPenerimaanArray = res
        koreksiDataTrt = res[0]
        let date = new Date(res[0].TANGGAL);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        console.log(date1)
        console.log($("#input_print_tanggal").val())
        $('#input_print_tanggal').val(date1)
        console.log($("#input_print_tanggal").val())
        document.getElementById("input_print_notrt").value = res[0].NOBUKTI
        document.getElementById("input_print_notrf").value = res[0].Notransfer
        document.getElementById("input_print_gdgasal").value = res[0].NamagdgAsal
        document.getElementById("input_print_gdgtujuan").value = res[0].NamagdgTujuan


        let rowTable = ""
        let rowTableTemp = ""
        res.forEach((item, i) => {
          rowTable += `<tr>
          <td>
          <div class="form-check text-center">
              <input id="printChecklist${i}" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
              </div></td>
          <td>${item.KODEBRG}</td>
          <td>${item.NAMABRG}</td>
          <td>${item.QNT}</td>
          <td>${Number(item.QntOS) ? item.QntOS : '0.00'}</td>
          <td>${item.Satuan}</td>

          </tr>`

          rowTableTemp += `
          <div id="tempqrcodeprint${i}" style="width: 10px; height: 10px"></div>
          <div id="tempqrcodeprintkodebrg${i}" style="width: 10px; height: 10px"></div>
          `
        });
        document.getElementById("printTableData").innerHTML = rowTable
        document.getElementById("tempPrintContainer").innerHTML = rowTableTemp
        res.forEach((item, i) => {
          //JsBarcode(`#barcodeKoreksiDetail${i}`, item.NOBUKTI + item.KODEBRG ,{width: 1, height: 25, displayValue: false});
          new QRCode(document.getElementById(`tempqrcodeprint${i}`), {text: `${item.NOBUKTI}.${item.URUT}.${item.KODEBRG}` , width: 90, height: 90});
          new QRCode(document.getElementById(`tempqrcodeprintkodebrg${i}`), {text: `${item.KODEBRG}` , width: 90, height: 90});
        });

      }
    })



    $("#formPrint").modal('toggle')
  }

  function buttonBatalShowHide() {
    $('.showhide').hide();
  }

  function buttonDetailKoreksi (NOBUKTI) {
    console.log('button detail koreksi' , NOBUKTI)

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaantransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        // console.log(res)
        // koreksiPenerimaanArray = res
        // koreksiDataTrt = res[0]
        let date = new Date(res[0].TANGGAL);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        console.log(date1)
        console.log($("#input_koreksi_detail_tanggal").val())
        $('#input_koreksi_detail_tanggal').val(date1)
        console.log($("#input_koreksi_detail_tanggal").val())
        document.getElementById("input_koreksi_detail_notrt").value = res[0].NOBUKTI
        document.getElementById("input_koreksi_detail_notrf").value = res[0].Notransfer
        document.getElementById("input_koreksi_detail_gdgasal").value = res[0].NamagdgAsal
        document.getElementById("input_koreksi_detail_gdgtujuan").value = res[0].NamagdgTujuan


        let rowTable = ""
        res.forEach((item, i) => {
          rowTable += `<tr>
          <td>${item.KODEBRG}</td>
          <td>${item.NAMABRG}</td>
          <td>${item.QNT}</td>
          <td>${Number(item.QntOS) ? item.QntOS : '0.00'}</td>
          <td>${item.Satuan}</td>

          </tr>`
        });
        document.getElementById("koreksiDetailTableData").innerHTML = rowTable
        res.forEach((item, i) => {
          //JsBarcode(`#barcodeKoreksiDetail${i}`, item.NOBUKTI + item.KODEBRG ,{width: 1, height: 25, displayValue: false});
        });

      }
    })



    $("#formKoreksiDetail").modal('toggle')
  }

  function buttonKoreksi (NOBUKTI ) {
    $('.showhide').hide();
    console.log(NOBUKTI)
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpenerimaantransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        // console.log(res)
        koreksiPenerimaanArray = res
        koreksiDataTrt = res[0]
      }
    })
    console.log(koreksiDataTrt , 'DATA TRT')
    console.log(koreksiPenerimaanArray)
    console.log(koreksiPenerimaanArray[0])
    let date = new Date(koreksiPenerimaanArray[0].TANGGAL);
    let day = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
    console.log(date1)
    console.log($("#input_koreksi_tanggal").val())
    $('#input_koreksi_tanggal').val(date1)
    console.log($("#input_koreksi_tanggal").val())
    document.getElementById("input_koreksi_notrt").value = koreksiPenerimaanArray[0].NOBUKTI
    document.getElementById("input_koreksi_notrf").value = koreksiPenerimaanArray[0].Notransfer
    document.getElementById("input_koreksi_gdgasal").value = koreksiPenerimaanArray[0].NamagdgAsal
    document.getElementById("input_koreksi_gdgtujuan").value = koreksiPenerimaanArray[0].NamagdgTujuan


    let rowTable = ""
    koreksiPenerimaanArray.forEach((item, i) => {
      rowTable += `<tr>
      <td>${item.KODEBRG}</td>
      <td>${item.NAMABRG}</td>
      <td>${item.QNT}</td>
      <td>${Number(item.QntOS) ? item.QntOS : '0.00'}</td>
      <td>${item.Satuan}</td>

      <td class="text-center">
      <button class="btn btn-warning btn-sm" type="button" onclick="buttonKoreksiEdit(${i} )"><i class="bi bi-pen"></i></button>
      <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i} )" ><i class="bi bi-trash"></i></button></td>
      </td>
      </tr>`
    });
    document.getElementById("koreksiTableData").innerHTML = rowTable
    koreksiPenerimaanArray.forEach((item, i) => {
      //JsBarcode(`#barcodeKoreksi${i}`, item.NOBUKTI + item.KODEBRG ,{width: 1,  height: 25,displayValue: false});
    });
    // transfergudangkoreksiaddlist

    $.ajax({
      url: "{!! url('transfergudangkoreksiaddlist') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        notrt: koreksiPenerimaanArray[0].NOBUKTI,
        notrf: koreksiPenerimaanArray[0].Notransfer
      },
      success: function(res) {
        console.log(res , "addlistkoreksi !!!!!!")
        koreksiDataAddList = res
      }
    })
    console.log(koreksiDataAddList, "asdadasd")
    let tempKoreksiAddList = ""
    if(koreksiDataAddList.length) {
      tempKoreksiAddList += `<option value="" selected disabled>-- Pilih Barang --</option>`
      koreksiDataAddList.forEach((item, i) => {
        tempKoreksiAddList += `<option value="${i}">${item.KODEBRG} - ${item.NAMABRG}</option>`
      });
    } else {
      tempKoreksiAddList += `<option value="" selected disabled>Tidak ada barang untuk ditambah</option>`
    }



    document.getElementById("koreksiAddSelect").innerHTML = tempKoreksiAddList
    $("#formKoreksi").modal('toggle')
  }



  function buttonDetail (NOBUKTI) {
    console.log('masok detail')
    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailoutstandingtransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        document.getElementById("input_detail_notrf").value = res[0].NOBUKTI
        document.getElementById("input_detail_gdgasal").value = res[0].NamagdgAsal
        document.getElementById("input_detail_gdgtujuan").value = res[0].NamagdgTujuan

        let rowTable = ""
        res.forEach((item, i) => {
          rowTable += `<tr>
          <td class="text-left">${item.KODEBRG}</td>
          <td class="text-left">${item.NAMABRG}</td>
          <td>${item.QNT}</td>
          <td>${item.QntOS}</td>
          <td class="text-left">${item.Satuan}</td>

          </tr>`
        });
        // <td class="d-flex justify-content-center"><div id="qrcodeadd${i}"  style="width: 100px; height: 100px"></div></td>


        // <td><div id="qrcodeadd${i}"><h1>asd</h1></div></td>
        document.getElementById("detailTableData").innerHTML = rowTable
        // res.forEach((item, i) => {
        //   new QRCode(document.getElementById(`qrcodeadd${i}`), `${item.KODEBRG}`);
        // });


        res.forEach((item, i) => {
         // JsBarcode(`#barcodeDetail${i}`, item.KODEBRG ,{width: 2, height: 25,displayValue: false});
        });
      }
    })

    $("#formDetail").modal('toggle')
  }

  function setNewNoBukti () {
    $.ajax({
      url: "{!! url('getnobuktitransfergudang') !!}",
      type: "get",
      async: false,
      success: function(res) {
        console.log(res, 'NoBUkti')
        console.log(res[0].Nobukti , res[0].Nourut)
        document.getElementById("input_add_notrt").value = res[0].Nobukti
        document.getElementById("input_add_noUrut").value = res[0].Nourut
      }
    })
  }

  function buttonAdd (NOBUKTI) {
    // console.log('buttonAdd' , NOBUKTI , akses)
    let akses = $("#akses_istambah").val();

    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    console.log(NOBUKTI)
    setNewNoBukti()

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailoutstandingtransfergudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        addDataArray = res
      }
    })

    document.getElementById("input_add_notrf").value = addDataArray[0].NOBUKTI
    document.getElementById("input_add_gdgasal").value = addDataArray[0].NamagdgAsal
    document.getElementById("input_add_gdgtujuan").value = addDataArray[0].NamagdgTujuan

    let rowTable = ""
    addDataArray.forEach((item, i) => {
      rowTable += `<tr>
      <td class="text-center"><input class="" type="checkbox" value="" id="add_checkbox${i}"></td>
      <td>${item.KODEBRG}</td>
      <td>${item.NAMABRG}</td>
      <td>${item.QNT}</td>
      <td>${Number(item.QntOS) ? item.QntOS : '0.00'}</td>
      <td>${item.Satuan}</td>
      <td><input onchange="" id="input_add_qntTerima${i}" style="width: 100px;" class="text-right" type="number" min=0 value=${item.QntOS}></td>

      </tr>`
    });
    document.getElementById("addTableData").innerHTML = rowTable
    addDataArray.forEach((item, i) => {
      //JsBarcode(`#barcodeAdd${i}`, item.KODEBRG ,{width: 2, height: 25, displayValue: false});
    });
    $("#form").modal('toggle')
  }

  function printBarcode (idBarcode , idJumlah) {
    console.log(idBarcode , idJumlah)
    let printContent = document.getElementById(`${idBarcode}`).innerHTML;
    let jumlahPrint = $(`#${idJumlah}`).val();
    console.log(jumlahPrint)
    let printContent1 = ""
    for (let i=0; i < jumlahPrint ; i++ ) {
      printContent1 += `<div class="row">`
      printContent1 += printContent
      printContent1 += `</div>`
    }
    document.getElementById("printContainer").innerHTML = printContent1
    w=window.open(' ');

    w.document.write($(`#printContainer`).html());
    w.print();
    w.close();
  }

  function submitAdd () {
    console.log('Submit Add')
    let _token = $("#_token").val();
    let tempData = []

    addDataArray.forEach((item, i) => {
      if (document.getElementById(`add_checkbox${i}`).checked) {
        addDataArray[i].inputQntTerima = $(`#input_add_qntTerima${i}`).val();
        tempData.push(addDataArray[i])
      }
    });
    if (!tempData.length) {
      alertify.warning("Tidak ada item dipilih");
      return
    }

    // console.log(Number(qntTerima))
    let flag = false
    tempData.forEach((item, i) => {
      console.log(item)
      if (Number(item.inputQntTerima) > Number(item.QntOS)) {
        console.log('os')
        flag =true
      }
      if (Number(item.inputQntTerima) <= 0) {
        console.log('negatif')
        flag =true
      }
    });
    // console.log('lolos')
    // return
    if (flag) {
      alertify.warning("Qty tidak bisa negatif / melebihi OS");
      return
    }


    let inputDate = $("#input_add_tanggal").val();
    let notrt = $(`#input_add_notrt`).val();
    let notrf = $(`#input_add_notrf`).val();
    let nourut =  $(`#input_add_noUrut`).val();

    let checkDate = new Date(inputDate)

    let periode_bulan = document.getElementById("periode_bulan").value
    let periode_tahun = document.getElementById("periode_tahun").value

    if ( checkDate.getFullYear()  !== Number(periode_tahun)  || (checkDate.getMonth() +1) !== Number(periode_bulan) ) {

        alertify.warning("Tanggal tidak sesuai periode");
        return
    }

    console.log('notrf', notrf)
    console.log('notrt', notrt)
    console.log('nourut', nourut)
    console.log('inputDate', inputDate)
    console.log(tempData)
    $.ajax({
      url: "{!! url('addTransferGudang') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        notrt,
        notrf,
        nourut,
        tempData,
        inputDate
      },
      success: function(res) {
        console.log(res ,'!')
        if (res == 1) {
          loadAll()
          $("#form").modal('toggle')
          alertify.success('TRT telah ditambah');

        }
        if (res == 2) {
          setNewNoBukti()
          alertify.warning('Nobukti telah di refresh, silahkan submit ulang')
        }
      },
      error: function (err) {
        console.log(err)
        alertify.warning('Terjadi kesalahan silahkan refresh browser')
      }

    })

  }


</script>





@endsection
