@extends('siswa.layouts.app')

@section('title', 'Kegiatan')

@section('content')

@if ($errors->has('access'))
<div class="alert alert-danger">
    {{ $erorrs->first('access') }}
</div>
@endif
<div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">

            <h6 class="mb-4">Data Kegiatan</h6>
            <div class="table-responsive">
                <a href="{{ route('siswa.kegiatan.create') }}" class="btn btn-primary btn-sm">Tambah</a>
                <table class="table" id="kegiatan">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tanggal Kegiatan</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatans as $kegiatan)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $kegiatan->tanggal_kegiatan }}</td>
                            <td>{{ $kegiatan->nama_kegiatan }}</td>
                            <td>
                                <a href="{{ route('kegiatan.edit', $kegiatan->id_kegiatan) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('kegiatan.hapus', $kegiatan->id_kegiatan) }}" onclick="return confirm('yakin ingin dihapus')" class="btn btn-danger btn-sm">Hapus</a>
                                <a href="{{ route('kegiatan.detail', $kegiatan->id_kegiatan) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#kegiatan').DataTable();
    });
</script>

@endsection
