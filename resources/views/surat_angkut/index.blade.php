@extends('layouts.master')

@section('title')
    Daftar sa
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar sa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('sa.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>

            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                           
                            <th width="5%">No</th>
                            <th>Kode Tanda Penerima</th>
                            <th>Nomor SA</th>
                            <th>nama customer</th>
                            <th>alamat customer</th>
                            <th>telepon customer</th>
                            <th>nama barang</th>
                            <th>Jumlah Barang</th>
                            <th>Berat Barang</th>
                            <th>Nama Penerima</th>
                            <th>Alamat Penerima</th>
                            <th>Telepon Penerima</th>
                            <th>Supir</th>
                            <th>Nomor Mobil</th>
                            <th>Keterangan</th>
                            <th>harga</th>
                            <th>Tanggal Pengambilan</th>
                            <th>Tanggal Dikirim</th>
                            <th>Tanggal Diterima</th>
                            <th>Tanggal Dibuat</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('surat_angkut.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('sa.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nomor_sa'},
                {data: 'kode_tanda_penerima'},
                {data: 'nama_customer'},
                {data: 'alamat_customer'},
                {data: 'telepon_customer'},
                {data: 'nama_barang'},
                {data: 'jumlah_barang'},
                {data: 'berat_barang'},
                {data: 'nama_penerima'},
                {data: 'alamat_penerima'},
                {data: 'telepon_penerima'},
                {data: 'supir'},
                {data: 'no_mobil'},
                {data: 'keterangan'},
                {data: 'harga'},
                {data: 'tanggal_pengambilan'},
                {data: 'tanggal_kirim'},
                {data: 'tanggal_terima'},
                {data: 'created_at'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=kode_tanda_penerima]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=kode_tanda_penerima]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nomor_sa]').val(response.nomor_sa);
                $('#modal-form [name=kode_tanda_penerima]').val(response.kode_tanda_penerima);
                $('#modal-form [name=nama_customer]').val(response.nama_customer);
                $('#modal-form [name=alamat_customer]').val(response.alamat_customer);
                $('#modal-form [name=telepon_customer]').val(response.telepon_customer);
                $('#modal-form [name=nama_barang]').val(response.nama_barang);
                $('#modal-form [name=jumlah_barang]').val(response.jumlah_barang);
                $('#modal-form [name=berat_barang]').val(response.berat_barang);
                $('#modal-form [name=nama_penerima]').val(response.nama_penerima);
                $('#modal-form [name=alamat_penerima]').val(response.alamat_penerima);
                $('#modal-form [name=telepon_penerima]').val(response.telepon_penerima);
                $('#modal-form [name=supir]').val(response.supir);
                $('#modal-form [name=no_mobil]').val(response.no_mobil);
                $('#modal-form [name=keterangan]').val(response.keterangan);
                $('#modal-form [name=tanggal_kirim]').val(response.tanggal_kirim);
                $('#modal-form [name=tanggal_pengambilan]').val(response.tanggal_pengambilan);
                $('#modal-form [name=tanggal_terima]').val(response.tanggal_terima);
                $('#modal-form [name=harga]').val(response.harga);
                $('#modal-form [name=created_at]').val(response.created_at);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    // function deleteSelected(url) {
    //     if ($('input:checked').length > 1) {
    //         if (confirm('Yakin ingin menghapus data terpilih?')) {
    //             $.post(url, $('.form-produk').serialize())
    //                 .done((response) => {
    //                     table.ajax.reload();
    //                 })
    //                 .fail((errors) => {
    //                     alert('Tidak dapat menghapus data');
    //                     return;
    //                 });
    //         }
    //     } else {
    //         alert('Pilih data yang akan dihapus');
    //         return;
    //     }
    // }

    // function cetakBarcode(url) {
    //     if ($('input:checked').length < 1) {
    //         alert('Pilih data yang akan dicetak');
    //         return;
    //     } else if ($('input:checked').length < 3) {
    //         alert('Pilih minimal 3 data untuk dicetak');
    //         return;
    //     } else {
    //         $('.form-produk')
    //             .attr('target', '_blank')
    //             .attr('action', url)
    //             .submit();
    //     }
    // }
</script>
@endpush