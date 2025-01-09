<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <!-- @vite('resources/css/app.css') -->
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div class="bg-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="bg-purple-400 px-6 py-4">
                        <h1 class="text-lg font-bold text-white">Detail Mahasiswa</h1>
                    </div>

                    <div class="p-6">
                        @if(!$mahasiswa)
                        <p class="text-center text-gray-600 italic">Data tidak ada.</p>
                        @else
                        <div class="mb-6">
                            <h2 class="text-3xl font-semibold text-gray-800 mb-2">{{ $mahasiswa->nama }}</h2>
                            <p class="text-sm text-gray-600">NIM: {{ $mahasiswa->nim }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-gray-700"><span class="font-semibold">Jenis Kelamin:</span> {{ $mahasiswa->jenis_kelamin }}</p>
                                <p class="text-gray-700"><span class="font-semibold">Program Studi:</span> {{ $mahasiswa->prodi }}</p>
                                <p class="text-gray-700"><span class="font-semibold">Jenjang:</span> {{ $mahasiswa->jenjang }}</p>
                            </div>
                            <div>
                                <p class="text-gray-700"><span class="font-semibold">Agama:</span> {{ $mahasiswa->agama }}</p>
                                <p class="text-gray-700"><span class="font-semibold">Angkatan:</span> {{ $mahasiswa->angkatan }}</p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">Ukm yang diikuti</h3>
                                @if($mahasiswa->ukms->isEmpty())
                                <p class="text-gray-600 italic">Tidak ada ukm yang diikuti</p>
                                @else
                                <div class="space-y-4">
                                    @foreach($mahasiswa->ukms as $ukm)
                                    <p class="text-gray-700 mb-2"> {{ $ukm->nama }} - Posisi: {{ $ukm->pivot->position }}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">HMPS yang diikuti</h3>
                                @if($mahasiswa->hmps->isEmpty())
                                <p class="text-gray-600 italic">Tidak ada hmps yang diikuti</p>
                                @else
                                <div class="space-y-4">
                                    @foreach($mahasiswa->hmps as $himpunan)
                                    <p class="text-gray-700 mb-2"> {{ $himpunan->nama }} - Posisi: {{ $himpunan->pivot->position }}</p>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Prestasi</h3>
                            @if($mahasiswa->prestasi->isEmpty())
                            <p class="text-gray-600 italic">Tidak ada prestasi yang terdaftar.</p>
                            @else
                            <div class="space-y-4">
                                @foreach($mahasiswa->prestasi as $prestasi)
                                <div class="bg-gray-50 rounded-lg p-2 shadow">
                                    <h4 class="text-lg font-semibold text-purple-600 mb-2">{{ $prestasi->nama_prestasi }}</h4>
                                    <p class="text-gray-700 mb-2">{{ $prestasi->deskripsi_prestasi }}</p>
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span><strong>Jenis:</strong> {{ $prestasi->jenis_prestasi }}</span>
                                        <span><strong>Tingkatan:</strong> {{ $prestasi->tingkatan_prestasi }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>