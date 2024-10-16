@extends('layouts.app')

@section('content')
<div class="bg-gray-50 p-6">
    <div class="h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-center mx-auto text-purple-400 mb-5">Edit Mahasiswa</h2>
        <div class="flex justify-center mx-auto w-[450px]">
            <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" class="w-full gap-3 p-5 bg-white rounded-lg shadow-lg">
                @csrf
                @method('PUT')

                <!-- Nama Field -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama:</label>
                    <input type="text" name="nama" id="nama" value="{{ $mahasiswa->nama }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- NIM Field -->
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700">NIM:</label>
                    <input type="text" name="nim" id="nim" value="{{ $mahasiswa->nim }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Jenis Kelamin Field -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin:</label>
                    <select name="jenis_kelamin" id="jenis_kelamin"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="L" {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Prodi Field -->
                <div>
                    <label for="prodi" class="block text-sm font-medium text-gray-700">Prodi:</label>
                    <select name="prodi" id="prodi"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @foreach ($prodiList as $prodi)
                        <option value="{{ $prodi->nama_prodi }}" {{ $mahasiswa->prodi == $prodi->nama_prodi ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenjang Field -->
                <div>
                    <label for="jenjang" class="block text-sm font-medium text-gray-700">Jenjang:</label>
                    <input type="text" name="jenjang" id="jenjang" value="{{ $mahasiswa->jenjang }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Agama Field -->
                <div>
                    <label for="agama" class="block text-sm font-medium text-gray-700">Agama:</label>
                    <input type="text" name="agama" id="agama" value="{{ $mahasiswa->agama }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Angkatan Field -->
                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan:</label>
                    <input type="text" name="angkatan" id="angkatan" value="{{ $mahasiswa->angkatan }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-purple-400 text-white py-2 px-4 rounded-md hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Update Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection