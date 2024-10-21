@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6 bg-gray-100 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Edit UKM/HMPS: {{ $ukm->nama }}</h2>

    <form action="{{ route('ukm.update', $ukm->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Use PUT method for update -->

        <div class="mb-6">
            <label for="nama" class="block text-sm font-bold mb-2">Nama UKM/HMPS</label>

            @if(Auth::user()->hasRole('admin')) <!-- Cek jika user adalah admin -->
                <input type="text" name="nama" id="nama"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    value="{{ $ukm->nama }}">
            @else
                <input type="text" name="nama" id="nama"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-gray-200"
                    value="{{ $ukm->nama }}" readonly>
            @endif
        </div>

        <!-- Tabel Mahasiswa -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full bg-white shadow-md rounded-lg w-full">
                <thead class="bg-indigo-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">NIM</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Prodi</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody id="mahasiswaTable" class="bg-white text-gray-700 divide-y divide-gray-200">
                    @foreach($mahasiswas as $mahasiswa)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-3">{{ $mahasiswa->nim }}</td>
                        <td class="px-6 py-3">{{ $mahasiswa->nama }}</td>
                        <td class="px-6 py-3">{{ $mahasiswa->prodi }}</td>
                        <td class="px-6 py-3">
                            <button type="button"
                                class="bg-indigo-500 text-white px-4 py-1 rounded-lg hover:bg-indigo-600 select-mahasiswa"
                                data-id="{{ $mahasiswa->id }}" data-nama="{{ $mahasiswa->nama }}">Pilih</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Daftar Mahasiswa yang Dipilih -->
        <div id="selectedMahasiswa" class="mb-6">
            @foreach($ukm->mahasiswas as $mahasiswa)
            <div class="mb-3 flex justify-between items-center">
                <label for="jabatan_{{ $mahasiswa->id }}" class="block text-sm font-bold mb-2">{{ $mahasiswa->nama }}</label>
                <input type="hidden" name="mahasiswa_ids[]" value="{{ $mahasiswa->id }}">
                <input type="text" name="jabatan[]" id="jabatan_{{ $mahasiswa->id }}"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    value="{{ $mahasiswa->pivot->jabatan }}" placeholder="Contoh: Ketua, Wakil Ketua">
                <button type="button" class="text-red-500 ml-2 remove-mahasiswa"
                    data-id="{{ $mahasiswa->id }}">Hapus</button>
            </div>
            @endforeach
        </div>

        <!-- Upload File Prestasi -->
        <div class="mb-6">
            <label for="file_prestasi" class="block text-sm font-bold mb-2">Unggah File Prestasi (opsional, maksimal 2MB per file)</label>
            <input type="file" name="file_prestasi[]" id="file_prestasi"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                accept="image/*,.pdf" multiple>
            <span class="text-xs text-red-500" id="fileError" style="display: none;">File size exceeds 2MB.</span>
        </div>

        <!-- File Prestasi Sebelumnya -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold">File Prestasi Sebelumnya:</h3>
            <ul class="list-disc pl-5">
                @foreach(is_array($ukm->file_prestasi) ? $ukm->file_prestasi : json_decode($ukm->file_prestasi) as $file)
                <li class="flex justify-between items-center">
                    <a href="{{ asset('storage/' . $file) }}" class="text-blue-500" target="_blank">{{ basename($file) }}</a>
                    <button type="button" class="text-red-500 ml-2 remove-file" data-file="{{ basename($file) }}">Hapus</button>
                    <input type="hidden" name="removed_files[]" value="" class="removed-file-input">
                </li>
                @endforeach
            </ul>
        </div>


        <!-- Submit Button -->
        <button type="submit" class="bg-green-500 text-white w-full py-3 rounded-lg hover:bg-green-600">Simpan</button>
    </form>
</div>

<script>
    // File Prestasi - Validasi ukuran file
    document.getElementById('file_prestasi').addEventListener('change', function () {
        const fileError = document.getElementById('fileError');
        let exceedsLimit = false;

        Array.from(this.files).forEach(file => {
            const fileSize = file.size / 1024 / 1024;
            if (fileSize > 2) {
                exceedsLimit = true;
            }
        });

        if (exceedsLimit) {
            fileError.style.display = 'block';
            this.value = '';
        } else {
            fileError.style.display = 'none';
        }
    });

    // Menghapus file yang ada
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-file')) {
            const fileName = event.target.dataset.file;
            const input = event.target.nextElementSibling; // Hidden input for the file

            if (confirm(`Apakah Anda yakin ingin menghapus file: ${fileName}?`)) {
                input.value = fileName; // Set file name in hidden input to send to controller
                event.target.closest('li').remove(); // Remove the file from the DOM
            }
        }
    });

    // Menambahkan mahasiswa yang dipilih ke daftar mahasiswa terpilih
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('select-mahasiswa')) {
            const mahasiswaId = event.target.dataset.id;
            const mahasiswaNama = event.target.dataset.nama;
            const selectedMahasiswa = document.getElementById('selectedMahasiswa');

            if (document.querySelector(`input[value="${mahasiswaId}"]`)) {
                alert('Mahasiswa sudah dipilih.');
                return;
            }

            // Tambahkan mahasiswa ke daftar terpilih
            selectedMahasiswa.innerHTML += `
                <div class="mb-3 flex justify-between items-center">
                    <label for="jabatan_${mahasiswaId}" class="block text-sm font-bold mb-2">${mahasiswaNama}</label>
                    <input type="hidden" name="mahasiswa_ids[]" value="${mahasiswaId}">
                    <input type="text" name="jabatan[]" id="jabatan_${mahasiswaId}"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Contoh: Ketua, Wakil Ketua">
                    <button type="button" class="text-red-500 ml-2 remove-mahasiswa" data-id="${mahasiswaId}">Hapus</button>
                </div>
            `;
        }

        // Menghapus mahasiswa dari daftar terpilih
        if (event.target.classList.contains('remove-mahasiswa')) {
            event.target.closest('div.mb-3').remove();
        }
    });
</script>
@endsection
