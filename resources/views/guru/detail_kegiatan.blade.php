@extends('guru.layouts.app')

@section('title', 'Detail Kegiatan')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($kegiatan)
<div class="row bg-light rounded align-items-center mx-0">
<div class="col-md-6 p-3">
    <table>
        <tr>
            <td width="100">Nama Siswa</td>
            <td width="15">:</td>
            <td>{{ $kegiatan->KegiatanSiswa->nama_siswa }}</td>
        </tr>
    </table>
</div>
</div>
<br>
@endif
<div class="row g-4">
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">Detail Kegiatan</h6>
                <div class="mb-3">
                    <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan</label>
                    <input type="text" class="form-control" value="{{ old('tanggal_kegiatan', $kegiatan->tanggal_kegiatan) }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                    <input type="text" class="form-control" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="ringkasan_kegiatan" class="form-label">Ringkasan Kegiatan</label>
                   <textarea name="ringkasan_kegiatan" id="ringkasa_kegiatan" rows="10" class="form-control"></textarea>
                </div>
            <div class="mb-3">
                <label for="foto" class="form-lable">Foto</label>
                <div class="text-center">
                    <img src="{{ asset('storage/'  . $kegiatan->foto) }}" alt="" height="200">
                </div>
            </div>
                <a href="{{ route('guru.pembimbing.siswa.kegiatan', ['id' => $id, 'id_siswa' => $kegiatan->id_siswa]) }}"
                    class="btn btn-primary">Kembali</a>
            </form>
        </div>
    </div>
</div>

@endsection
