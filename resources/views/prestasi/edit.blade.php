<!-- resources/views/prestasi/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-white p-6">
    <div class="h-fit md:ml-40 md:justify-around md:items-start mt-0 mb-10 gap-2">
        <h2>Edit Prestasi</h2>

        <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="nama_prestasi">Nama Prestasi:</label>
            <input type="text" id="nama_prestasi" name="nama_prestasi" value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}" required>
            <br>

            <label for="deskripsi_prestasi">Deskripsi Prestasi:</label>
            <textarea id="deskripsi_prestasi" name="deskripsi_prestasi" required>{{ old('deskripsi_prestasi', $prestasi->deskripsi_prestasi) }}</textarea>
            <br>

            <label for="jenis_prestasi">Jenis Prestasi:</label>
            <select id="jenis_prestasi" name="jenis_prestasi" required>
                <option value="Akademik" {{ $prestasi->jenis_prestasi == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                <option value="Non-Akademik" {{ $prestasi->jenis_prestasi == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
            </select>
            <br>

            <label for="tingkatan_prestasi">Tingkatan Prestasi:</label>
            <select id="tingkatan_prestasi" name="tingkatan_prestasi" required>
                <option value="Lokal" {{ $prestasi->tingkatan_prestasi == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                <option value="Nasional" {{ $prestasi->tingkatan_prestasi == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                <option value="Internasional" {{ $prestasi->tingkatan_prestasi == 'Internasional' ? 'selected' : '' }}>Internasional</option>
            </select>
            <br>

            <label for="file_prestasi">File Prestasi:</label>
            <input type="file" id="file_prestasi" name="file_prestasi">
            @if ($prestasi->file_prestasi)
            <p>File saat ini: <a href="{{ asset('storage/' . $prestasi->file_prestasi) }}" target="_blank">Lihat File</a></p>
            @endif
            <br>

            <button type="submit">Update Prestasi</button>
        </form>
    </div>
</div>

@endsection