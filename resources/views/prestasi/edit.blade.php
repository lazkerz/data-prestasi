@extends('layouts.app')

@section('content')
<div class="bg-gray-50 p-6">
    <div class="h-fit md:ml-40 md:justify-center md:items-center mt-0 mb-10 gap-2">
        <h2 class="text-xl font-bold text-center mx-auto text-purple-400 mb-5">Edit Prestasi</h2>
        <div class="flex justify-center mx-auto w-[450px]">
            <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data"
                class="w-full gap-3 p-5 bg-white rounded-lg shadow-lg">
                @csrf
                @method('PUT')

                <!-- Nama Prestasi Field -->
                <div>
                    <label for="nama_prestasi" class="block text-sm font-medium text-gray-700">Nama Prestasi:</label>
                    <input type="text" name="nama_prestasi" id="nama_prestasi"
                        value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Deskripsi Prestasi Field -->
                <div>
                    <label for="deskripsi_prestasi" class="block text-sm font-medium text-gray-700">Deskripsi
                        Prestasi:</label>
                    <textarea name="deskripsi_prestasi" id="deskripsi_prestasi"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required>{{ old('deskripsi_prestasi', $prestasi->deskripsi_prestasi) }}</textarea>
                </div>

                <!-- Jenis Prestasi Field -->
                <div>
                    <label for="jenis_prestasi" class="block text-sm font-medium text-gray-700">Jenis Prestasi:</label>
                    <select name="jenis_prestasi" id="jenis_prestasi"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Akademik" {{ $prestasi->jenis_prestasi == 'Akademik' ? 'selected' : ''
                            }}>Akademik</option>
                        <option value="Non-Akademik" {{ $prestasi->jenis_prestasi == 'Non-Akademik' ? 'selected' : ''
                            }}>Non-Akademik</option>
                    </select>
                </div>

                <!-- Tingkatan Prestasi Field -->
                <div>
                    <label for="tingkatan_prestasi" class="block text-sm font-medium text-gray-700">Tingkatan
                        Prestasi:</label>
                    <select name="tingkatan_prestasi" id="tingkatan_prestasi"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Lokal" {{ $prestasi->tingkatan_prestasi == 'Lokal' ? 'selected' : '' }}>Lokal
                        </option>
                        <option value="Nasional" {{ $prestasi->tingkatan_prestasi == 'Nasional' ? 'selected' : ''
                            }}>Nasional</option>
                        <option value="Internasional" {{ $prestasi->tingkatan_prestasi == 'Internasional' ? 'selected' :
                            '' }}>Internasional</option>
                    </select>
                </div>

                <!-- File Prestasi Field -->
                <div>
                    <label for="file_prestasi" class="block text-sm font-medium text-gray-700">File Prestasi:</label>
                    <input type="file" name="file_prestasi" id="file_prestasi" accept=".pdf,.jpg,.jpeg,.png"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @if ($prestasi->file_prestasi)
                    <p class="text-sm mt-2">File saat ini: <a href="{{ asset('storage/' . $prestasi->file_prestasi) }}"
                            class="text-indigo-600 hover:text-indigo-900" target="_blank">Lihat File</a></p>
                    @endif
                </div>

                <!-- Date Field -->
                <div>
                    <label for="tahun_prestasi" class="block text-sm font-medium mb-3 text-gray-700">Tahun Prestasi:</label>
                    <input type="date" name="tahun_prestasi" id="tahun_prestasi"
                        value="{{ old('tahun_prestasi', $prestasi->tahun_prestasi ?? '') }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-purple-400 text-white py-2 px-4 rounded-md mt-3 hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Update Prestasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
