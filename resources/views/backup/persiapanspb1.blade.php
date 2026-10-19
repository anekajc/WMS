@extends('newmaster')
@section('buttons')

@endsection
@section('content')




<div class="container-fluid">

<h1>Surat Jalan</h1>
<!-- <button onclick="loadAll()">tes</button> -->
</div>

<div id="printContainer" style="display:none">

</div>
<div id="contentContainer" class="container-fluid">

  <input type="hidden" id="periode_tahun" value="{!! $periode->tahun !!}" />
  <input type="hidden" id="periode_bulan" value="{!! $periode->bulan !!}" />

  <input type="hidden" id="akses_istambah" value="{!! $akses->ISTAMBAH !!}" />
  <input type="hidden" id="akses_ishapus" value="{!! $akses->ISHAPUS!!}" />
  <input type="hidden" id="akses_iskoreksi" value="{!! $akses->ISKOREKSI !!}" />
  <input type="hidden" id="akses_iscetak" value="{!! $akses->ISCETAK !!}" />
  <input type="hidden" id="akses_isotorisasi1" value="{!! $akses->IsOtorisasi1 !!}" />



  <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}" />
  <div class="card">

    <div class="card-header">
      <div class="row">
        <nav style="width: 100%;">
          <div class="nav nav-tabs col-12" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="nav-home" aria-selected="true" style="color: black;">Outstanding Dispatch</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="nav-profile" aria-selected="false" style="color: black;">Transaksi Spb</a>
          </div>
        </nav>
      </div>
    </div>




    <div class="card-body" >
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="row">
            <div class="col-20" style="overflow:auto;">
              <div class="container-fluid">

                    <table id="tabel" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Actions</th>
                          <th scope="col">No. SO</th>
                          <th scope="col">No. PL</th>
                          <th scope="col">Tanggal</th>
                          <th style="min-width: 200px;">Nama Customer</th>
                          <th style="min-width: 200px;">Nama Barang</th>
                          <th scope="col">PO Customer</th>
                          <th scope="col">Qnt. Persiapan</th>
                          <th scope="col">Qnt OS</th>
                          <!-- <th scope="col">Stock</th>
                          <th scope="col">Stock G01</th>
                          <th scope="col">Stock G02</th>
                          <th scope="col">Stock G03</th> -->
                          <th scope="col">Sat</th>
                          <th scope="col">Catatan</th>

                          <th scope="col">Lokasi Penerima</th>
                          <th scope="col">Alamat Kirim</th>
                          <th scope="col">Due Date</th>
                          <th scope="col">Reff PR</th>

                          <th scope="col">Part Number</th>
                          <th scope="col">Merk</th>
                          <th scope="col">User ID</th>

                        </tr>
                      </thead>


                      <tbody id="tabel_data" class="text-left" >
                        @for ($i = 0; $i < count($outstandingArray); $i++)
                        <tr>
                          <td class="text-center">
                            <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetail('{{$outstandingArray[$i][0]->NOSO}}')" ><i class="bi bi-info-lg"></i></button>
                            <button class="btn btn-success btn-sm" type="button" onclick="buttonAdd('{{$outstandingArray[$i][0]->NOSO}}')"><i class="bi bi-bag-plus-fill"></i></button>
                          </td>
                          <td>{{ $outstandingArray[$i][0]->NOSO }}</td>
                          <td>{{ $outstandingArray[$i][0]->NOBUKTI }}</td>
                          <td>{!! date("Y/m/d", strtotime($outstandingArray[$i][0]->Tanggal)) !!}</td>
                          <td>{{ $outstandingArray[$i][0]->NamaCustSupp }}</td>
                          <td>{{ $outstandingArray[$i][0]->NamaBrgx }}</td>
                          <td>{{ $outstandingArray[$i][0]->Nopesanan }}</td>
                          <td>{{ $outstandingArray[$i][0]->Qnt }}</td>

                          <td>{{ $outstandingArray[$i][0]->QntOut }}</td>

                          <!-- <td>{{ $outstandingArray[$i][0]->SaldoQnt }}</td>
                          <td>{{ $outstandingArray[$i][0]->saldoqntg01 }}</td>
                          <td>{{ $outstandingArray[$i][0]->saldoqntg02 }}</td>
                          <td>{{ $outstandingArray[$i][0]->saldoqntg03 }}</td> -->

                          <td>{{ $outstandingArray[$i][0]->satuan }}</td>

                          <td>{{ $outstandingArray[$i][0]->catatan }}</td>
                          <td>{{ $outstandingArray[$i][0]->xckebun }}</td>


                          <td>{{ $outstandingArray[$i][0]->alamatlokasi }}</td>
                          <td>{!! date("Y/m/d", strtotime($outstandingArray[$i][0]->duedate)) !!}</td>

                          <td>{{ $outstandingArray[$i][0]->refpr }}</td>
                          <td>{{ $outstandingArray[$i][0]->partnumber }}</td>

                          <td>{{ $outstandingArray[$i][0]->namamerk }}</td>
                          <td>{{ $outstandingArray[$i][0]->userid }}</td>

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

                    <table id="tabelPersiapanspb" class="table table-bordered table-striped"  >
                      <thead class="text-center">
                        <tr>
                          <th scope="col">Actions</th>
                          <th scope="col">No. SPB</th>
                          <th scope="col">Tanggal</th>
                          <th scope="col">Nama Cust</th>
                          <th scope="col">PO. Cust</th>
                          <th scope="col">User ID</th>

                          <th scope="col">Tgl. Kirim</th>
                          <th scope="col">Tgl. Terima</th>

                          <th scope="col">Oto 1</th>
                          <th scope="col">User oto1</th>
                          <th scope="col">Tgl oto1</th>

                          <th scope="col">Lokasi Terima</th>
                        </tr>
                      </thead>


                      <tbody id="tabelpersiapanspb_data" class="text-left" >
                        @for ($i = 0; $i < count($penerimaanArray); $i++)
                        <tr>
                          <td class="text-center">
                          <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetailKoreksi('{{ $penerimaanArray[$i][0]->nobukti }}')" ><i class="bi bi-info-lg"></i></button>
                          <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('{{ $penerimaanArray[$i][0]->nobukti }}')"><i class="bi bi-pen"></i></button>
                          @if ($penerimaanArray[$i][0]->oto1 == 1)
                          <button disabled data-toggle="tooltip" data-placement="top" title="Otorisasi" class="btn btn-primary btn-sm" type="button" onclick="buttonOtorisasi('{{ $penerimaanArray[$i][0]->nobukti }}' , '{{ $penerimaanArray[$i][0]->oto1 }}' )"><i class="bi bi-key"></i></button>
                          @else
                          <button data-toggle="tooltip" data-placement="top" title="Otorisasi" class="btn btn-primary btn-sm" type="button" onclick="buttonOtorisasi('{{ $penerimaanArray[$i][0]->nobukti }}' , '{{ $penerimaanArray[$i][0]->oto1 }}' )"><i class="bi bi-key"></i></button>
                          @endif
                          </td>
                          <td>{{ $penerimaanArray[$i][0]->nobukti }}</td>
                          <td>{!! date("Y/m/d", strtotime($penerimaanArray[$i][0]->tanggal)) !!}</td>
                          <td>{{ $penerimaanArray[$i][0]->kodecustsupp }}</td>
                          <td>{{ $penerimaanArray[$i][0]->nopesanan }}</td>
                          <td>{{ $penerimaanArray[$i][0]->iduser }}</td>

                          <td>{!! date("Y/m/d", strtotime($penerimaanArray[$i][0]->tglkirim)) !!}</td>
                          <td>{!! date("Y/m/d", strtotime($penerimaanArray[$i][0]->tglterima)) !!}</td>
                          @if ($penerimaanArray[$i][0]->oto1 == 0)
                          <td></td>
                          @else
                          <td>Approved</td>
                          @endif
                          <td>{{ $penerimaanArray[$i][0]->useroto1 }}</td>
                          <td>{!! $penerimaanArray[$i][0]->tgloto1 ? $penerimaanArray[$i][0]->tgloto1 : '' !!}</td>


                          <td>{{ $penerimaanArray[$i][0]->namakebun }}</td>
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
                <label>No SPB</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_norspb" placeholder="No TRT" disabled>
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
                <label>No Persiapan</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_nosj" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-12">
              <div class="form-group">
                <label>Nama Cust</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_namacust" placeholder="Gdg Tujuan" disabled>
              </div>
            </div>









            <div class="col-12">
              <div class="form-group">
                <label>Gudang</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_gdg" placeholder="Gdg Asal" required disabled>
              </div>
            </div>


            <div class="col-12">
              <div class="form-group">
                <label>Lokasi terima</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_lokasiterima" placeholder="Lokasi Terima" required disabled>
              </div>
            </div>


            <div class="col-12">
              <div class="form-group">
                <label>Alamat Kirim</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_alamatkirim" placeholder="Alamat kirim" required disabled>
              </div>
            </div>






            <div class="col-12">
              <div class="form-group">
                <label>No. Pol Kendaraan</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_add_nopolkendaraan" placeholder="No Pol Kendaraan" required enabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Expedisi</label>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
              <select id="input_add_expedisi" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                <option value="-" selected>-</option>
                @foreach ($expedisi as $exp)
                <option value="{{ $exp->KODECUSTSUPP }}">{{ $exp->KODECUSTSUPP }} {{ $exp->NAMACUSTSUPP }}</option>
                @endforeach
              </select>
            </div>
          </div>


          <div class="col-12">
            <div class="form-group">
              <label>Sopir</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <input type="text" class="form-control" id="input_add_sopir" placeholder="Sopir" required enabled>
            </div>
          </div>


          <div class="col-12">
            <div class="form-group">
              <label>Ref UKM</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <input type="text" class="form-control" id="input_add_refukm" placeholder="Ref UKM" required enabled>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <label>Catatan SO</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <input type="text" class="form-control" id="input_add_catatan" placeholder="Catatan" required enabled>
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
                    <th scope="col">Qty SPB</th>

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

<!-- start modal detail -->
<div class="modal fade" id="formDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <div class="row">



            <div class="col-12">
              <div class="form-group">
                <label>Nama Cust</label>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_namacust" placeholder="Gdg Tujuan" disabled>
              </div>
            </div>





            <div class="col-12">
              <div class="form-group">
                <label>No Persiapan</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_nosj" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-12">
              <div class="form-group">
                <label>Gudang</label>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_detail_gdg" placeholder="Gdg Asal" required disabled>
              </div>
            </div>




            <div class="col-12">
              <div class="form-group">
                <label>TanggalX</label>
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



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Batal</button>

      </div>
    </div>
  </div>
</div>
<!-- End modal detail-->

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
                <label>No SPB</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_norspb" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No SO</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_nosj" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gdg</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_gdg" placeholder="Kode Cust" required disabled>
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
                <label>Cust Supp</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksi_custsupp" placeholder="Nama Cust" disabled>
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
            <!-- <button type="button" class="btn btn-primary" onclick="buttonKoreksiAdd()" class="btn btn-secondary"  >Add Item</button> -->
        </div>

        <div class="container-fluid">
          <!-- koreksi add -->
          <div id="formKoreksiAdd" class="container-fluid showhide">
            <div class="line"></div>
            <div class="row">
              <div class="col-4">
                <h4>Add Item</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <label>Pilih Barang</label>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <select onchange="changeSelectKoreksiAdd()" id="koreksiAddSelect" class="form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                <label>Kode Barang</label>
              </div>
              </div>
              <div class="col-6">
                <input id="koreksiAddKodeBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                <label>Nama Barang</label>
              </div>
              </div>
              <div class="col-6">
                <input id="koreksiAddNamaBrg" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                <label>Qty</label>
              </div>
              </div>
              <div class="col-3">
                <input id="koreksiAddInputQty" type="number" value=0.00 class="form-control">
              </div>
              <div class="col-1">
                <div class="form-group">
                <label>Satuan</label>
              </div>
              </div>
              <div class="col-3">

                  <input type="text" id="koreksiAddSatuan" value="PCS" class="form-control" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-1">
                <div class="form-group">
                <label>Qty OS</label>
              </div>
              </div>
              <div class="col-3">
                <input id="koreksiAddQtyOS" type="number" value=0.00 class="form-control" disabled>
              </div>
              <div class="col-1">
                <div class="form-group">
                <label>Qty PO</label>
              </div>
              </div>
              <div class="col-3">

                  <input id="koreksiAddQtyPO" type="number" value=0.00 class="form-control" disabled>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary" onclick="" >Batal</button>
                <button type="button" onclick="submitAddKoreksi()" class="btn btn-primary" >Add Item</button>
              </div>

            </div>
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

          <!-- end  -->
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

                      <th scope="col">Actions</th>
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




      </div>





    </div>
  </div>
</div>
</div>
</div>
<!-- End modal koreksi-->



<!-- start modal koreksi detail-->
<div class="modal fade" id="formOtorisasi1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="width: 90%; max-width:1500px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Otorisasi1</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">


            <div class="col-12">
              <div class="form-group">
                <label>No SPB</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_otorisasi1_norspb" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No SO</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_otorisasi1_nosj" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gudang</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_otorisasi1_gdg" placeholder="Kode Cust" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_otorisasi1_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Cust Supp</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_otorisasi1_custsupp" placeholder="Nama Cust" disabled>
              </div>
            </div>


          </div>
          <div class="row">


        </div>
        <div class="row">


        </div>

        </div>



          <div class="container-fluid mt-4" style="overflow-x: auto;">

                <table id="tableOtorisasi1" class="table table-bordered table-striped"  >
                  <thead class="text-center">
                    <tr>
                      <th scope="col">Kode Brg</th>
                      <th scope="col">Nama Brg</th>
                      <th scope="col">Qty</th>
                      <th scope="col">Qty OS</th>
                      <th scope="col">Satuan</th>

                    </tr>
                  </thead>


                  <tbody id="detailTableOtorisasi1" class="text-right" >
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
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="submitOtorisasi1()"  >Approve</button>

          </div>




      </div>





    </div>
  </div>
</div>
</div>
</div>
<!-- End modal koreksi detail-->

<!-- start modal koreksi detail-->
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
                <label>No SPB</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksidetail_norspb" placeholder="No TRT" disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>No SO</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksidetail_nosj" placeholder="No TRF" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Gudang</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksidetail_gdg" placeholder="Kode Cust" required disabled>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="date" class="form-control" id="input_koreksidetail_tanggal" value="{!! date('Y-m-d') !!}" disabled>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label>Cust Supp</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <input type="text" class="form-control" id="input_koreksidetail_custsupp" placeholder="Nama Cust" disabled>
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
<!-- End modal koreksi detail-->


@endsection

@section('js')
<script type="text/javascript">


  // loadall
  let dataRefreshOutstanding = []
  let dataRefreshPenerimaan = []
  // add
  let addDataArray = []

  // koreksi
  let koreksiPenerimaanArray = []
  let koreksiDataEdit = {}
  let koreksiDataAddlist = []


  $(document).ready(function(){
        $("#tabel").DataTable({
          "lengthChange": false,
          "order": [[1, 'asc']],
            "paging": false ,
             "columnDefs": [
          { "type": "date", "targets": [3] },
          {  "className": "text-center", "targets": [0] },
        ]});
        $("#tabelPersiapanspb").DataTable({
          "lengthChange": false,
           "order": [[1, 'asc']],
            "paging": false ,
             "columnDefs": [
          { "type": "date", "targets": [2] },
          {  "className": "text-center", "targets": [0] },
        ]});


  });

  function submitOtorisasi1 () {

    let nobukti = $("#input_otorisasi1_norspb").val();
    let otorisasi = 1
    console.log(nobukti, otorisasi)
    let _token = $("#_token").val();


    $.ajax({
      url: "{!! url('persiapanspbspotorisasi') !!}",
      type: "post",
      async: false,
      data: {
        _token,
        nobukti,
        otorisasi
      },
      success: function(res) {
        if (res > 0) {
          loadAll()
          alertify.success('Berhasil update otorisasi')
          $("#formOtorisasi1").modal('toggle')
        }


    },
    error: function (err) {
      alertify.warning('Terjadi kesalahan pada server, silahkan refresh ulang')
    }
  })
  }


  function buttonOtorisasi  ( nobukti , isOtorisasi ) {
    console.log(nobukti , isOtorisasi )
    // isOtorisasi = 1

  let akses = $("#akses_isotorisasi1").val();

  if (!Number(akses)) {
    alertify.warning('No access')
    return
  }
  console.log('2')
  if(isOtorisasi > 0) {
    alertify.warning('Sudah diotorisasi')
    return
  }

  let _token = $("#_token").val();
  $.ajax({
    url: "{!! url('detailpersiapanspb') !!}",
    type: "post",
    async: false,
    data: {
      _token : _token,
      NOBUKTI: nobukti
    },
    success: function(res) {
      console.log(res,'==')
      // koreksiPenerimaanArray = res
      // koreksiDataEdit = res[0]
      console.log('=============!!==============')
      document.getElementById("input_otorisasi1_norspb").value = res[0].nobukti
      document.getElementById("input_otorisasi1_nosj").value = res[0].noso
      document.getElementById("input_otorisasi1_gdg").value = res[0].namagdg
      document.getElementById("input_otorisasi1_custsupp").value = res[0].namacustsupp
      nooutso = res[0].nooutso
      urutoutso = res[0].urutoutso

      let date = new Date(res[0].tanggal);
      let day = ("0" + date.getDate()).slice(-2);
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
      $('#input_otorisasi1_tanggal').val(date1)

      let rowTable = ""
      res.forEach((item, i) => {
        let qnt = 0.00
        let qntos = 0.00
        if(item.qnt) {
          qnt = parseFloat(item.qnt).toFixed(2)
        }
        if(item.qntos) {
          qntos = parseFloat(item.qntos).toFixed(2)
        }
        // console.log(item.qntos)
        // console.log(qntos)
        rowTable += `<tr class="text-left">
        <td>${item.kodebrg}</td>
        <td>${item.namabrg}</td>
        <td class="text-right">${qnt}</td>
        <td class="text-right">${qntos}</td>
        <td>${item.satuan}</td>

        </tr>`
      });
      document.getElementById("detailTableOtorisasi1").innerHTML = rowTable

      res.forEach((item, i) => {
        //JsBarcode(`#barcodeKoreksiDetail${i}`, item.kodebrg ,{width: 2, height: 25,});
      });

    }
  })
  console.log('1')
  $("#formOtorisasi1").modal('toggle')

  // let _token = $("#_token").val();

  // pembelianpermintaannonagenupdateotorisasi
  return
  // $.ajax({
  //   url: "{!! url('pembelianpermintaannonagenupdateotorisasi') !!}",
  //   type: "post",
  //   async: false,
  //   data: {
  //     _token,
  //     nobukti,
  //     otorisasi :1
  //   },
  //   success: function(res) {
  //     if (res > 0) {
  //       loadAll()
  //       alertify.success('Berhasil update otorisasi')
  //     }
  //
  //
  // }})
  }

  function submitAdd() {
    console.log('Submit Add')
    let _token = $("#_token").val();
    let tempData = []

    // console.log('TES ==========')
    // return
    let qtyCheck = false

    addDataArray.forEach((item, i) => {
      if (document.getElementById(`add_checkbox${i}`).checked) {
        addDataArray[i].inputQntTerima = $(`#input_add_qntTerima${i}`).val();
        if (Number(addDataArray[i].inputQntTerima) > Number(addDataArray[i].QntOut)) {
          qtyCheck = true
        }
        tempData.push(addDataArray[i])
      }
    });
    console.log('qwer')
    // console.log(tempData)
    // console.log(qtyCheck)
    if (qtyCheck) {
      // console.log(qtyCheck, 'true')
      // qtyCheck = false
      alertify.warning("Qty SPB tidak bisa lebih besar dari Qty OS");
      return
    }
    // return

    if (!tempData.length) {
      alertify.warning("Tidak ada item dipilih");
      return
    }
    let flag = false
    tempData.forEach((item, i) => {
      console.log(item , '==================')
      console.log(Number(item.inputQntTerima) ,Number(item.QntOut) )
      if (Number(item.inputQntTerima) > Number(item.QntOut)) {
        console.log('os')

        // return
        flag =true
      }
      if (Number(item.inputQntTerima) <= 0) {
        console.log('negatif')

        // return
        flag =true
      }
    });
    if (flag) {
      alertify.warning("Qty tidak bisa negatif / melebihi OS");
      return
    }
    let inputDate = $("#input_add_tanggal").val();
    let nosj = $(`#input_add_nosj`).val();
    let norspb = $(`#input_add_norspb`).val();
    let nourut =  $(`#input_add_noUrut`).val();
    let nopolkendaraan =  $(`#input_add_nopolkendaraan`).val();
    let expedisi = document.getElementById("input_add_expedisi").value;
    let lokasiterima =  $(`#input_add_lokasiterima`).val();


    let checkDate = new Date(inputDate)

    let periode_bulan = document.getElementById("periode_bulan").value
    let periode_tahun = document.getElementById("periode_tahun").value
    console.log('asd')

    if ( checkDate.getFullYear()  !== Number(periode_tahun)  || (checkDate.getMonth() +1) !== Number(periode_bulan) ) {

        alertify.warning("Tanggal tidak sesuai periode");
        return
    }




    console.log('nosj', nosj)
    console.log('norspb', norspb)
    console.log('nourut', nourut)
    console.log('inputDate', inputDate)
    console.log(tempData)
    console.log('nopolkendaraan', nopolkendaraan)
    console.log('expedisi', expedisi)
    // return

    $.ajax({
      url: "{!! url('addpersiapanspb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        nosj,
        norspb,
        nourut,
        tempData,
        inputDate,
        nopolkendaraan,
        expedisi
      },
      success: function(res) {
        console.log(res ,'!')
        // loadAll()
        if(res == 1) {

          $("#form").modal('toggle')
          alertify.success('SPB telah ditambah');
          console.log('before ===========')
          loadAll()
          console.log("after ===========")
        }

        if (res == 2) {
          setNewNoBukti()
          alertify.warning('Nobukti telah di refresh, silahkan submit ulang');
        }

      },
      error: function (err) {
        console.log(err)
        alertify.warning('Terjadi kesalahan silahkan refresh browser')
      }
    })
  }

  function buttonBatalShowHide() {
    $('.showhide').hide();
  }

  function buttonKoreksiEdit(index) {
    let akses = $("#akses_iskoreksi").val();

    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    $('.showhide').hide();
    // console.log(koreksiPenerimaanArray[index])
    // console.log(index)
    koreksiDataEdit = koreksiPenerimaanArray[index]
    console.log('edit data',koreksiDataEdit)
    let qnt = 0.00
    if (koreksiDataEdit.qnt) {

      qnt = parseFloat(koreksiDataEdit.qnt).toFixed(2)
    }
    document.getElementById("koreksiEditKodeBrg").value = koreksiDataEdit.kodebrg
    document.getElementById("koreksiEditNamaBrg").value = koreksiDataEdit.namabrg
    document.getElementById("koreksiEditQtyOS").value = koreksiDataEdit.qntos
    document.getElementById("koreksiEditInputQty").value = qnt
    $('#formKoreksiEdit').show();

    document.getElementById('formKoreksiEdit').scrollIntoView();
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
    document.getElementById("koreksiAddInputQty").value = "0.00"
    $('#formKoreksiAdd').show();
  }

  function buttonDetailKoreksi(NOBUKTI) {
    let _token = $("#_token").val();

    console.log(NOBUKTI,'==')
    //return
    $.ajax({
      url: "{!! url('detailpersiapanspb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res,'==')
        // koreksiPenerimaanArray = res
        // koreksiDataEdit = res[0]
        console.log('=============!!==============')
        document.getElementById("input_koreksidetail_norspb").value = res[0].nobukti
        document.getElementById("input_koreksidetail_nosj").value = res[0].noso
        document.getElementById("input_koreksidetail_gdg").value = res[0].namagdg
        document.getElementById("input_koreksidetail_custsupp").value = res[0].namacustsupp
        nooutso = res[0].nooutso
        urutoutso = res[0].urutoutso

        let date = new Date(res[0].tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#input_koreksidetail_tanggal').val(date1)

        let rowTable = ""
        res.forEach((item, i) => {
          let qnt = 0.00
          let qntos = 0.00
          if(item.qnt) {
            qnt = parseFloat(item.qnt).toFixed(2)
          }
          if(item.qntos) {
            qntos = parseFloat(item.qntos).toFixed(2)
          }
          // console.log(item.qntos)
          // console.log(qntos)
          rowTable += `<tr class="text-left">
          <td>${item.kodebrg}</td>
          <td>${item.namabrg}</td>
          <td class="text-right">${qnt}</td>
          <td class="text-right">${qntos}</td>
          <td>${item.satuan}</td>

          </tr>`
        });
        document.getElementById("koreksiDetailTableData").innerHTML = rowTable

        res.forEach((item, i) => {
          //JsBarcode(`#barcodeKoreksiDetail${i}`, item.kodebrg ,{width: 2, height: 25,});
        });

      }
    })


      $("#formKoreksiDetail").modal('toggle')
  }

  function buttonKoreksi (NOBUKTI) {
    console.log('button koreksi')
    console.log(NOBUKTI)
    $('.showhide').hide();

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailtransaksispb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        koreksiPenerimaanArray = res
        koreksiDataEdit = res[0]
        console.log('=============!!==============')
      }
    })

    document.getElementById("input_koreksi_norspb").value = koreksiPenerimaanArray[0].nobukti
    document.getElementById("input_koreksi_nosj").value = koreksiPenerimaanArray[0].noso
    document.getElementById("input_koreksi_gdg").value = koreksiPenerimaanArray[0].namagdg
    document.getElementById("input_koreksi_custsupp").value = koreksiPenerimaanArray[0].namacustsupp
    let date = new Date(koreksiPenerimaanArray[0].tanggal);
    let day = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#input_koreksi_tanggal').val(date1)
    let rowTable = ""
    koreksiPenerimaanArray.forEach((item, i) => {
      let qnt = 0.00
      let qntos = 0.00
      if(item.qnt) {
        qnt = parseFloat(item.qnt).toFixed(2)
      }
      if(item.qntos) {
        qntos = parseFloat(item.qntos).toFixed(2)
      }
      rowTable += `<tr class="text-left">
      <td>${item.kodebrg}</td>
      <td>${item.namabrg}</td>
      <td class="text-right">${qnt}</td>
      <td class="text-right">${qntos}</td>
      <td>${item.satuan}</td>

      <td class="text-center">
      <button class="btn btn-warning btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
      <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})" ><i class="bi bi-trash"></i></button></td>
      </td>
      </tr>`
    });
    document.getElementById("koreksiTableData").innerHTML = rowTable

    koreksiPenerimaanArray.forEach((item, i) => {
    //  JsBarcode(`#barcodeKoreksi${i}`, item.kodebrg ,{width: 2, height: 25,});
    });

    $.ajax({
      url: "{!! url('spbkoreksiaddlist') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        norspb: koreksiPenerimaanArray[0].NOBUKTI,
        nosj: koreksiPenerimaanArray[0].NoSPB
      },
      success: function(res) {
        console.log(res , "addlistkoreksi !!!!!!")
        koreksiDataAddList = res
      }
    })
    // console.log(koreksiDataAddList, "asdadasd")
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

  function changeSelectKoreksiAdd() {
    let indexBarang = document.getElementById("koreksiAddSelect").value;
    console.log(indexBarang)
    console.log(koreksiDataAddList[indexBarang])
    let qnt = 0.00
    let qntos = 0.00
    if(koreksiDataAddList[indexBarang].QNT) {
      qnt = parseFloat(koreksiDataAddList[indexBarang].QNT).toFixed(2)
    }
    if(koreksiDataAddList[indexBarang].QntOS) {
      qntos = parseFloat(koreksiDataAddList[indexBarang].QntOS).toFixed(2)
    }
    document.getElementById("koreksiAddKodeBrg").value = koreksiDataAddList[indexBarang].KODEBRG
    document.getElementById("koreksiAddNamaBrg").value = koreksiDataAddList[indexBarang].NAMABRG
    document.getElementById("koreksiAddQtyOS").value = qntos
    document.getElementById("koreksiAddQtyPO").value = qnt

  }

  function buttonKoreksiDelete (index) {
    let akses = $("#akses_ishapus").val();

    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    console.log(koreksiPenerimaanArray[index])
    let dataRSPB = koreksiPenerimaanArray[index]
    console.log(dataRSPB)
    alertify.confirm('Hapus Item', 'Apakah yakin ingin menghapus item ' + dataRSPB.kodebrg + dataRSPB.namabrg + ' ?',
      function() {
        let choice = 'D'
        let _token = $("#_token").val();
        let norspb = dataRSPB.nobukti
        let nourut = dataRSPB.NoUrut
        let inputDate = $("#input_koreksi_tanggal").val()
        let nosj = dataRSPB.NoSPB
        let kodecustsupp = dataRSPB.KodeCustSupp
        let nopolkend = ""
        let container = ""
        let nocontainer = ""
        let noseal = ""
        let catatan = ""
        let urut = dataRSPB.Urut
        let urutsj = 0
        let kodebrg = dataRSPB.KODEBRG
        let sat_1 = dataRSPB.SAT_1
        let sat_2 = dataRSPB.SAT_2
        let nosat = dataRSPB.NOSAT
        let isi = 0
        let netw = 0
        let grossw = 0
        let isempty = 1
        let namabrg = dataRSPB.NAMABRG
        let flagmenu = 1
        let flagtipe = 1
        let retursupp = 0
        let qntTerima = 0
        let qntTerima1 = 0
        let qntTerima2 = 0


        console.log('choice' , choice)
        console.log('norspb' , norspb)
        console.log('nourut', nourut)
        console.log('inputDate' , inputDate)
        console.log('nosj' , nosj)
        console.log('kodecustsupp' , kodecustsupp)
        console.log('nopolkend' , nopolkend)
        console.log('container' , container)
        console.log('nocontainer' , nocontainer)
        console.log('noseal' , noseal)
        console.log('catatan' , catatan)
        console.log('urut' , urut)
        console.log('urutsj' , urutsj)
        console.log('kodebrg' , kodebrg)
        console.log('qntTerima' , qntTerima)
        console.log('qntTerima2' , qntTerima2)
        console.log('sat_1' , sat_1)
        console.log('sat_2' , sat_2)
        console.log('nosat' , nosat)
        console.log('isi' , isi)
        console.log('netw' , netw)
        console.log('grossw' , grossw)
        console.log('isempty' , isempty)
        console.log('namabrg' , namabrg)
        console.log('flagmenu' , flagmenu)
        console.log('flagtipe' , flagtipe)
        console.log('retursupp' , retursupp)

        // return
        $.ajax({
          url: "{!! url('koreksiPersiapanSPB') !!}",
          type: "post",
          async: false,
          data: {
            _token : _token,
            choice,
            norspb,
            nourut,
            inputDate,
            nosj,
            kodecustsupp,
            nopolkend,
            container,
            nocontainer,
            noseal,
            catatan,
            urut,
            urutsj,
            kodebrg,
            qntTerima,
            qntTerima2,
            sat_1,
            sat_2,
            nosat,
            isi,
            netw,
            grossw,
            isempty,
            namabrg,
            flagmenu,
            flagtipe,
            retursupp,
          },
          success: function(res) {
            console.log(res ,'succes delete koreksi')
            refreshKoreksi(norspb)
            alertify.success('Item telah didelete');
            loadAll()
          }
        })
      }
    ,function(){
      console.log('no')
    });
  }



  function submitAddKoreksi () {
    // console.log('submit add koreksi')
    // console.log(koreksiDataTrt)
    let _token = $("#_token").val();
    let check = document.getElementById("koreksiAddSelect").value;
    // console.log(check,'check')
    if (check === "") {
      // console.log('a')
      alertify.warning("Tidak ada item dipilih");
      return
    }
    let dataSJ = koreksiDataAddList[check]
    let qntTerima = $("#koreksiAddInputQty").val()
    if (Number(qntTerima) > Number(dataSJ.QntOS)) {
      alertify.warning("Qty tidak bisa lebih besar dari Qty OS");
      return
    }
    if (Number(qntTerima) <= 0) {
      alertify.warning("Qty tidak bisa 0 atau negatif");
      return
    }

    let qntTerima1 = 0
    let qntTerima2 = 0
    if (dataSJ.NOSAT == 1) {
      qntTerima1 = qntTerima
      qntTerima2 = qntTerima / dataSJ.ISI2
    } else if (dataSJ.NOSAT == 2) {
      qntTerima1 = qntTerima * dataSJ.ISI2
      qntTerima2 = qntTerima
    }
    console.log(dataSJ , qntTerima)
    console.log(koreksiDataEdit)
    let dataRSPB = koreksiDataEdit
    let choice = "I"
    let norspb = dataRSPB.NOBUKTI
    let nourut = dataRSPB.NoUrut
    let inputDate = $("#input_koreksi_tanggal").val()
    let nosj = dataSJ.NOBUKTI
    let kodecustsupp = dataSJ.KODECUSTSUPP
    let nopolkend = ""
    let container = ""
    let nocontainer = ""
    let noseal = ""
    let catatan = ""
    let urut = 0
    let urutsj = dataSJ.URUT
    let kodebrg = dataSJ.KODEBRG
    let sat_1 = dataSJ.SAT1
    let sat_2 = dataSJ.SAT2
    let nosat = dataSJ.NOSAT
    let isi = dataSJ.ISI
    let netw = 0
    let grossw = 0
    let isempty = 1
    let namabrg = dataSJ.NAMABRG
    let flagmenu = 1
    let flagtipe = 1
    let retursupp = 0


    console.log('choice' , choice)
    console.log('norspb' , norspb)
    console.log('nourut', nourut)
    console.log('inputDate' , inputDate)
    console.log('nosj' , nosj)
    console.log('kodecustsupp' , kodecustsupp)
    console.log('nopolkend' , nopolkend)
    console.log('container' , container)
    console.log('nocontainer' , nocontainer)
    console.log('noseal' , noseal)
    console.log('catatan' , catatan)
    console.log('urut' , urut)
    console.log('urutsj' , urutsj)
    console.log('kodebrg' , kodebrg)
    console.log('qntTerima' , qntTerima)
    console.log('qntTerima2' , qntTerima2)
    console.log('sat_1' , sat_1)
    console.log('sat_2' , sat_2)
    console.log('nosat' , nosat)
    console.log('isi' , isi)
    console.log('netw' , netw)
    console.log('grossw' , grossw)
    console.log('isempty' , isempty)
    console.log('namabrg' , namabrg)
    console.log('flagmenu' , flagmenu)
    console.log('flagtipe' , flagtipe)
    console.log('retursupp' , retursupp)


    return
    $.ajax({
      url: "{!! url('koreksiSuratJalan') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        choice,
        norspb,
        nourut,
        inputDate,
        nosj,
        kodecustsupp,
        nopolkend,
        container,
        nocontainer,
        noseal,
        catatan,
        urut,
        urutsj,
        kodebrg,
        qntTerima,
        qntTerima2,
        sat_1,
        sat_2,
        nosat,
        isi,
        netw,
        grossw,
        isempty,
        namabrg,
        flagmenu,
        flagtipe,
        retursupp,
      },
      success: function(res) {
        console.log(res ,'succes add koreksi')
        refreshKoreksi(norspb)
        alertify.success('Item telah ditambah');
        loadAll()
      }
    })


  }

  function submitEditKoreksi () {
    console.log('submit edit koreksi')
    console.log(koreksiDataEdit)
    let dataRSPB = koreksiDataEdit
    let _token = $("#_token").val();
    let choice = "U"
    let qntTerima = $("#koreksiEditInputQty").val()
    if (Number(qntTerima) > Number(koreksiDataEdit.qntos) + Number(koreksiDataEdit.qnt)) {
      alertify.warning("Qty melebihi Qty OS");
      return
    }
    if(Number(qntTerima) < 0 ) {
      alertify.warning("Qty tidak boleh negatif");
      return
    }
    let qntTerima1 = 0
    let qntTerima2 = 0
    if (dataRSPB.NOSAT == 1) {
      qntTerima1 = qntTerima
      qntTerima2 = qntTerima / dataRSPB.ISI2
    } else if (dataRSPB.NOSAT == 2) {
      qntTerima1 = qntTerima * dataRSPB.ISI2
      qntTerima2 = qntTerima
    }
    let norspb = dataRSPB.nobukti
    let nourut = dataRSPB.NOURUT
    let inputDate = $("#input_koreksi_tanggal").val()
    let nosj = dataRSPB.NoSPB
    let kodecustsupp = dataRSPB.kodecustsupp
    let nopolkend = ""
    let container = ""
    let nocontainer = ""
    let noseal = ""
    let catatan = ""
    let urut = dataRSPB.Urut
    let urutsj = 0
    let kodebrg = dataRSPB.kodebrg
    let sat_1 = dataRSPB.SAT1
    let sat_2 = dataRSPB.SAT2
    let nosat = dataRSPB.NOSAT
    let isi = dataRSPB.ISI
    let netw = 0
    let grossw = 0
    let isempty = 1
    let namabrg = dataRSPB.namabrg
    let flagmenu = 1
    let flagtipe = 1
    let retursupp = 0

    console.log('choice' , choice)
    console.log('norspb' , norspb)
    console.log('nourut', nourut)
    console.log('inputDate' , inputDate)
    console.log('nosj' , nosj)
    console.log('kodecustsupp' , kodecustsupp)
    console.log('nopolkend' , nopolkend)
    console.log('container' , container)
    console.log('nocontainer' , nocontainer)
    console.log('noseal' , noseal)
    console.log('catatan' , catatan)
    console.log('urut' , urut)
    console.log('urutsj' , urutsj)
    console.log('kodebrg' , kodebrg)
    console.log('qntTerima' , qntTerima)
    console.log('qntTerima2' , qntTerima2)
    console.log('sat_1' , sat_1)
    console.log('sat_2' , sat_2)
    console.log('nosat' , nosat)
    console.log('isi' , isi)
    console.log('netw' , netw)
    console.log('grossw' , grossw)
    console.log('isempty' , isempty)
    console.log('namabrg' , namabrg)
    console.log('flagmenu' , flagmenu)
    console.log('flagtipe' , flagtipe)
    console.log('retursupp' , retursupp)

    // return

    $.ajax({
      url: "{!! url('koreksiPersiapanSPB') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        choice,
        norspb,
        nourut,
        inputDate,
        nosj,
        kodecustsupp,
        nopolkend,
        container,
        nocontainer,
        noseal,
        catatan,
        urut,
        urutsj,
        kodebrg,
        qntTerima,
        qntTerima2,
        sat_1,
        sat_2,
        nosat,
        isi,
        netw,
        grossw,
        isempty,
        namabrg,
        flagmenu,
        flagtipe,
        retursupp,
      },
      success: function(res) {
        console.log(res ,'succes edit koreksi')
        refreshKoreksi(norspb)
        alertify.success('Item telah diedit');
        loadAll()
      }
    })




  }

  function buttonDetail (NOBUKTI) {

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailoutpersiapanspb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        addDataArray = res
        console.log('===========================')
        document.getElementById("input_detail_nosj").value = res[0].NOBUKTI
        document.getElementById("input_detail_gdg").value = res[0].kodegdg
        document.getElementById("input_detail_namacust").value = res[0].NamaCustSupp
        // document.getElementById("input_detail_tanggal").value = res[0].Tanggal
        let date = new Date(res[0].Tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#input_detail_tanggal').val(date1)


        let rowTable = ""
        res.forEach((item, i) => {
          let qnt = 0.00
          let qntos = 0.00
          if(item.Qnt) {
            qnt = parseFloat(item.Qnt).toFixed(2)
          }
          if(item.QntOut) {
            qntos = parseFloat(item.QntOut).toFixed(2)
          }
          rowTable += `<tr class="text-left">
          <td>${item.KODEBRG}</td>
          <td>${item.NamaBrgx}</td>
          <td class="text-right">${qnt}</td>
          <td class="text-right">${qntos}</td>
          <td>${item.satuan}</td>
          </tr>`
        });
        document.getElementById("detailTableData").innerHTML = rowTable
        res.forEach((item, i) => {
        //  JsBarcode(`#barcodeDetail${i}`, item.KODEBRG ,{width: 2, height: 25,});
        });
      }
    })




    $("#formDetail").modal('toggle')

  }


  function setNewNoBukti () {
    $.ajax({
      url: "{!! url('getnobuktipersiapanspb') !!}",
      type: "get",
      async: false,
      success: function(res) {
        console.log(res, 'Nobukti')
        console.log(res[0].Nobukti , res[0].Nourut)
        console.log('===============')
        document.getElementById("input_add_norspb").value = res[0].Nobukti
        document.getElementById("input_add_noUrut").value = res[0].Nourut

      }
    })
  }


  function buttonAdd (NOBUKTI) {

    let akses = $("#akses_istambah").val();

    if (!Number(akses)) {
      alertify.warning('No access')
      return
    }
    console.log('button add')
    console.log(NOBUKTI)
    console.log('======!!=======')
    setNewNoBukti()


    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailoutstandingpersiapanspb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        addDataArray = res
        console.log('===========================')
      }
    })

    document.getElementById("input_add_nosj").value = addDataArray[0].NOBUKTI
    document.getElementById("input_add_gdg").value = addDataArray[0].kodegdg
    document.getElementById("input_add_namacust").value = addDataArray[0].NamaCustSupp
    document.getElementById("input_add_lokasiterima").value = addDataArray[0].ketkebun
    document.getElementById("input_add_alamatkirim").value = addDataArray[0].NamaCustSupp

    let rowTable = ""
    addDataArray.forEach((item, i) => {
      let qnt = 0.00
      let qntos = 0.00
      if(item.Qnt) {
        qnt = parseFloat(item.Qnt).toFixed(2)
      }
      if(item.QntOut) {
        qntos = parseFloat(item.QntOut).toFixed(2)
      }
      rowTable += `<tr class="text-left">
      <td class="text-center"><input class="" type="checkbox" value="" id="add_checkbox${i}"></td>
      <td>${item.KODEBRG}</td>
      <td>${item.NamaBrgx}</td>
      <td class="text-right">${qnt}</td>
      <td class="text-right">${qntos}</td>
      <td>${item.satuan}</td>
      <td class="text-center"><input onchange="" id="input_add_qntTerima${i}" style="width: 100px;" class="text-right" type="number" min=0 value=${qnt}></td>

      </tr>`
    });
    document.getElementById("addTableData").innerHTML = rowTable
    addDataArray.forEach((item, i) => {
    //  JsBarcode(`#barcodeAdd${i}`, item.KODEBRG ,{width: 2, height: 25,});
    });
    $("#form").modal('toggle')
  }

  function refreshKoreksi (NOBUKTI) {
    console.log('REFRESH START')
    console.log(NOBUKTI)
    $('.showhide').hide();

    let _token = $("#_token").val();
    $.ajax({
      url: "{!! url('detailpersiapanspb') !!}",
      type: "post",
      async: false,
      data: {
        _token : _token,
        NOBUKTI: NOBUKTI
      },
      success: function(res) {
        console.log(res)
        koreksiPenerimaanArray = res
        koreksiDataEdit = res[0]
        console.log('=============!!==============')
      }
    })

    if (!koreksiPenerimaanArray.length) {
      console.log('item habis')
      $("#formKoreksi").modal('toggle')
      return
    }

    document.getElementById("input_koreksi_norspb").value = koreksiPenerimaanArray[0].nobukti
    document.getElementById("input_koreksi_nosj").value = koreksiPenerimaanArray[0].noso
    document.getElementById("input_koreksi_gdg").value = koreksiPenerimaanArray[0].namagdg
    document.getElementById("input_koreksi_custsupp").value = koreksiPenerimaanArray[0].namacustsupp

    let rowTable = ""
    koreksiPenerimaanArray.forEach((item, i) => {
      let qnt = 0.00
      let qntos = 0.00
      if(item.qnt) {
        qnt = parseFloat(item.qnt).toFixed(2)
      }
      if(item.qntos) {
        qntos = parseFloat(item.qntos).toFixed(2)
      }
      rowTable += `<tr class="text-left">
      <td>${item.kodebrg}</td>
      <td>${item.namabrg}</td>
      <td class="text-right">${qnt}</td>
      <td class="text-right">${qntos}</td>
      <td>${item.satuan}</td>

      <td class="text-center">
      <button class="btn btn-warning btn-sm" type="button" onclick="buttonKoreksiEdit(${i})"><i class="bi bi-pen"></i></button>
      <button class="btn btn-danger btn-sm" type="button" onclick="buttonKoreksiDelete(${i})" ><i class="bi bi-trash"></i></button></td>
      </td>
      </tr>`
    });
    document.getElementById("koreksiTableData").innerHTML = rowTable


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

  function loadAll() {
    $.ajax({
      url: "{!! url('loadallpersiapanspb') !!}",
      type: "get",
      async: false,
      success: function(res) {
        console.log(res)
        dataRefreshOutstanding = res.outstandingArray
        dataRefreshPenerimaan = res.penerimaanArray
        document.getElementById("akses_istambah").value = res.akses.ISTAMBAH
        document.getElementById("akses_iskoreksi").value = res.akses.ISKOREKSI
        document.getElementById("akses_iscetak").value = res.akses.ISCETAK
        document.getElementById("akses_ishapus").value = res.akses.ISHAPUS
      }
    })
    // console.log(dataRefreshOutstanding, 'out')
    // console.log(dataRefreshPenerimaan, 'pen')

    $('#tabel').DataTable().destroy();
    let rowTable = ""
    dataRefreshOutstanding.forEach((item, i) => {
      // console.log(item,'==================')
      let date1 = ""
      if (item[0].Tanggal) {
        let date = new Date(item[0].Tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"/"+(month)+"/"+(day) ;
      }
      rowTable  += `<tr>
      <td class="text-center">
      <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetail('${item[0].NOSO}')" ><i class="bi bi-info-lg"></i></button>
      <button class="btn btn-success btn-sm" type="button" onclick="buttonAdd('${item[0].NOSO}')"><i class="bi bi-bag-plus-fill"></i></button>
      </td>
      <td>${item[0].NOSO}</td>
      <td>${item[0].NOBUKTI}</td>
      <td>${date1}</td>
      <td>${item[0].NamaCustSupp}</td>
      <td>${item[0].NamaBrgx}</td>
      <td>${item[0].Nopesanan}</td>
      <td>${item[0].Qnt}</td>
      <td>${item[0].QntOut}</td>

      <td>${item[0].satuan}</td>
      <td>${item[0].catatan}</td>

      <td>${item[0].ketkebun}</td>
      <td>${item[0].alamatlokasi}</td>
      <td>${item[0].duedate}</td>

      <td>${item[0].refpr}</td>
      <td>${item[0].partnumber}</td>
      <td>${item[0].namamerk}</td>
      <td>${item[0].userid}</td>
      </tr>`


    })


    document.getElementById("tabel_data").innerHTML = rowTable
    $("#tabel").DataTable({
      "lengthChange": false,
        "paging": false ,
        "order": [[1, 'asc']],
         "columnDefs": [
      { "type": "date", "targets": [3] },
      {  "className": "text-center", "targets": [0] },
    ]});


    $('#tabelPersiapanspb').DataTable().destroy();
    let rowTableper = ""

    dataRefreshPenerimaan.forEach((item, i) => {
      let date1 = ""
      // console.log(item[0].TANGGAL)
      // return
      if (item[0].tanggal) {
        let date = new Date(item[0].tanggal);
        let day = ("0" + date.getDate()).slice(-2);
        let month = ("0" + (date.getMonth() + 1)).slice(-2);
        date1 = date.getFullYear()+"/"+(month)+"/"+(day) ;
      }

      rowTableper  += `<tr>
        <td class="text-center">
        <button style="" class="btn btn-warning btn-sm" type="button"   onclick="buttonDetailKoreksi('${item[0].nobukti}')" ><i class="bi bi-info-lg"></i></button>
        <button class="btn btn-success btn-sm" type="button" onclick="buttonKoreksi('${item[0].nobukti}')"><i class="bi bi-pen"></i></button>
        `
        if ( Number(item[0].oto1) ) {
          rowTableper  += `<button disabled data-toggle="tooltip" data-placement="top" title="Otorisasi" class="btn btn-primary btn-sm" type="button" onclick="buttonOtorisasi('${item[0].nobukti}'' , '${item[0].oto1}' )"><i class="bi bi-key"></i></button>`
        } else {
          rowTableper  +=  `<button data-toggle="tooltip" data-placement="top" title="Otorisasi" class="btn btn-primary btn-sm" type="button" onclick="buttonOtorisasi('${item[0].nobukti}' , '${item[0].oto1}' )"><i class="bi bi-key"></i></button>`
        }

        console.log(item[0].tgloto1)
      rowTableper  +=  `</td>
        <td>${item[0].nobukti}</td>
        <td>${date1}</td>
        <td>${item[0].kodecustsupp}</td>
        <td>${item[0].nopesanan}</td>
        <td>${item[0].iduser}</td>
        <td>${item[0].tglkirim ? convertDate(item[0].tglkirim) : '' }</td>
        <td>${item[0].tglterima ? convertDate(item[0].tglterima) : ''}</td>
        <td>${Number(item[0].oto1) ? 'Approved' : '' }</td>

        <td>${item[0].useroto1}</td>
        <td>${item[0].tgloto1 ? item[0].tgloto1  : '' }</td>
        <td>${item[0].namakebun}</td>
      </tr>`
    });

    document.getElementById("tabelpersiapanspb_data").innerHTML = rowTableper

    $("#tabelPersiapanspb").DataTable({
      "lengthChange": false,
      "order": [[1, 'asc']],
        "paging": false ,
         "columnDefs": [
      { "type": "date", "targets": [2] },
      {  "className": "text-center", "targets": [0] },
    ]});
  }

  function convertDate (tanggal) {
    let date1 = ""
    if (tanggal) {
      let date = new Date(tanggal);
      let day = ("0" + date.getDate()).slice(-2);
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      date1 = date.getFullYear()+"/"+(month)+"/"+(day) ;
    }
    return date1
  }



</script>




@endsection
