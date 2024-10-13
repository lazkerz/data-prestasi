{{-- resources/views/mahasiswa/show.blade.php --}}

<div class="container">
    <h1>Detail Mahasiswa</h1>

    <h2>{{ $mahasiswa->nama }}</h2>
    <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
    <p><strong>Jenis Kelamin:</strong> {{ $mahasiswa->jenis_kelamin }}</p>
    <p><strong>Program Studi:</strong> {{ $mahasiswa->prodi }}</p>
    <p><strong>Jenjang:</strong> {{ $mahasiswa->jenjang }}</p>
    <p><strong>Agama:</strong> {{ $mahasiswa->agama }}</p>
    <p><strong>Angkatan:</strong> {{ $mahasiswa->angkatan }}</p>

    <h3>Prestasi</h3>
    @if($mahasiswa->prestasi->isEmpty())
        <p>Tidak ada prestasi yang terdaftar.</p>
    @else
        <ul>
            @foreach($mahasiswa->prestasi as $prestasi)
                <li>
                    <strong>{{ $prestasi->nama_prestasi }}</strong><br>
                    {{ $prestasi->deskripsi_prestasi }}<br>
                    <strong>Jenis:</strong> {{ $prestasi->jenis_prestasi }}<br>
                    <strong>Tingkatan:</strong> {{ $prestasi->tingkatan_prestasi }}<br>
                </li>
            @endforeach
        </ul>
    @endif
</div>

