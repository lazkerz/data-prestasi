@extends('layouts.app')

@section('content')
<div class="bg-gray-50 p-6">
    <div class="h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-center mx-auto text-purple-400 mb-5">Tambah Prestasi Mahasiswa: {{
            $mahasiswa->nama }}</h2>

        <!-- Form untuk submit -->
        <div class="flex justify-center mx-auto w-[450px]">
            <form action="{{ route('prestasi.store', $mahasiswa->id) }}" method="POST" enctype="multipart/form-data"
                id="prestasiForm" class="w-full gap-3 p-5 bg-white rounded-lg shadow-lg">
                @csrf
                <div id="prestasi-forms">
                    <!-- Form Prestasi 1 -->
                    <div class="prestasi-form">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Prestasi 1</h3>

                        <div class="mb-4">
                            <label for="nama_prestasi" class="block text-sm font-medium text-gray-700">Judul
                                Prestasi:</label>
                            <input type="text" name="nama_prestasi[]" required
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi_prestasi"
                                class="block text-sm font-medium text-gray-700">Deskripsi:</label>
                            <textarea name="deskripsi_prestasi[]" required
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_prestasi" class="block text-sm font-medium text-gray-700">Jenis
                                Prestasi:</label>
                            <select name="jenis_prestasi[]" required
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Akademik">Akademik</option>
                                <option value="Non-Akademik">Non Akademik</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tingkatan_prestasi" class="block text-sm font-medium text-gray-700">Tingkatan
                                Prestasi:</label>
                            <select name="tingkatan_prestasi[]" required
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Lokal">Lokal</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                            </select>
                        </div>
                        <!-- Date Field -->


                        <div class="mb-4">
                            <label for="file_prestasi" class="block text-sm font-medium text-gray-700">Upload File (PDF
                                atau Gambar):</label>
                            <input type="file" name="file_prestasi[]" accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="tahun_prestasi" class="block text-sm font-medium text-gray-700">Tanggal:</label>
                            <input type="date" name="tahun_prestasi" id="tanggal"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Tombol untuk menambah form prestasi baru -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="addPrestasiButton"
                        class="border border-purple-500 text-purple-500 py-2 px-4 rounded-md hover:bg-purple-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Tambah Prestasi
                    </button>
                    <button type="submit"
                        class="bg-purple-400 text-white py-2 px-4 rounded-md hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Simpan Semua Prestasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let formCounter = 1;

    document.getElementById('addPrestasiButton').addEventListener('click', function() {
        formCounter++;

        const formContainer = document.createElement('div');
        formContainer.classList.add('prestasi-form', 'mt-5');
        formContainer.innerHTML = `
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Prestasi ${formCounter}</h3>
            <div class="mb-4">
                <label for="nama_prestasi" class="block text-sm font-medium text-gray-700">Judul Prestasi:</label>
                <input type="text" name="nama_prestasi[]" required
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="deskripsi_prestasi" class="block text-sm font-medium text-gray-700">Deskripsi:</label>
                <textarea name="deskripsi_prestasi[]" required
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="jenis_prestasi" class="block text-sm font-medium text-gray-700">Jenis Prestasi:</label>
                <select name="jenis_prestasi[]" required
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="Akademik">Akademik</option>
                    <option value="Non-Akademik">Non Akademik</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="tingkatan_prestasi" class="block text-sm font-medium text-gray-700">Tingkatan Prestasi:</label>
                <select name="tingkatan_prestasi[]" required
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="Lokal">Lokal</option>
                    <option value="Nasional">Nasional</option>
                    <option value="Internasional">Internasional</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="file_prestasi" class="block text-sm font-medium text-gray-700">Upload File (PDF atau Gambar):</label>
                <input type="file" name="file_prestasi[]" accept=".pdf,.jpg,.jpeg,.png"
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        `;

        document.getElementById('prestasi-forms').appendChild(formContainer);
    });
</script>
@endsection
