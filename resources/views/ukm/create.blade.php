@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6 bg-gray-100 rounded-lg shadow-md">
    <!-- Title Section -->
    <!-- Title Section -->
    <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">
        Buat UKM atau HMPS untuk Anda: {{ Auth::user()->getRoleNames()->first() }}
    </h2>

    <!-- Form Start -->
    <form action="{{ route('ukm.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nama UKM/HMPS -->
        <div class="mb-6">
            <label for="nama" class="block text-sm font-bold mb-2">Nama UKM/HMPS</label>

            @if(Auth::user()->hasRole('admin'))
            <!-- Input teks untuk admin (editable) -->
            <input type="text" name="nama" id="nama"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Masukkan nama UKM atau HMPS">
            @else
            <!-- Input teks untuk prodi (readonly) -->
            <input type="text" name="nama" id="nama"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-gray-200"
                value="HIMPUNAN MAHASISWA PROGRAM STUDI {{ Auth::user()->getRoleNames()->first() }}" readonly>
            @endif
        </div>

        <!-- Cari Mahasiswa -->
        <div class="mb-6">
            <label for="searchMahasiswa" class="block text-sm font-bold mb-2">Cari Mahasiswa</label>
            <input type="text" id="searchMahasiswa"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Cari mahasiswa berdasarkan nama/NIM">
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
            <!-- Selected mahasiswa will be appended here -->
        </div>

        <!-- Upload File Prestasi -->
        <div class="mb-6">
            <label for="file_prestasi" class="block text-sm font-bold mb-2">Unggah File Prestasi (opsional, maksimal 2MB
                per file)</label>
            <input type="file" name="file_prestasi[]" id="file_prestasi"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                accept="image/*,.pdf" multiple>
            <span class="text-xs text-red-500" id="fileError" style="display: none;">File size exceeds 2MB.</span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-green-500 text-white w-full py-3 rounded-lg hover:bg-green-600">Simpan</button>
    </form>

</div>

<!-- JavaScript for handling mahasiswa search and selection -->
<script>
    document.getElementById('searchMahasiswa').addEventListener('keyup', function() {
        const search = this.value;
        fetch(`/search-mahasiswa?search=${search}`)
            .then(response => response.json())
            .then(data => {
                let mahasiswaTable = document.getElementById('mahasiswaTable');
                mahasiswaTable.innerHTML = '';

                data.forEach(mahasiswa => {
                    mahasiswaTable.innerHTML += `
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-3">${mahasiswa.nim}</td>
                            <td class="px-6 py-3">${mahasiswa.nama}</td>
                            <td class="px-6 py-3">${mahasiswa.prodi}</td>
                            <td class="px-6 py-3">
                                <button type="button" class="bg-indigo-500 text-white px-4 py-1 rounded-lg hover:bg-indigo-600 select-mahasiswa" data-id="${mahasiswa.id}" data-nama="${mahasiswa.nama}">Pilih</button>
                            </td>
                        </tr>
                    `;
                });
            });
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('select-mahasiswa')) {
            const mahasiswaId = event.target.dataset.id;
            const mahasiswaNama = event.target.dataset.nama;
            const selectedMahasiswa = document.getElementById('selectedMahasiswa');

            if (document.querySelector(`input[value="${mahasiswaId}"]`)) {
                alert('Mahasiswa sudah dipilih.');
                return;
            }

            selectedMahasiswa.innerHTML += `
                <div class="mb-3">
                    <label for="jabatan_${mahasiswaId}" class="block text-sm font-bold mb-2">${mahasiswaNama}</label>
                    <input type="hidden" name="mahasiswa_ids[]" value="${mahasiswaId}">
                    <input type="text" name="jabatan[]" id="jabatan_${mahasiswaId}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Ketua, Wakil Ketua">
                </div>
            `;
        }
    });

    document.getElementById('file_prestasi').addEventListener('change', function() {
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
</script>
@endsection
