<!-- resources/views/mahasiswa/index.blade.php -->

<h2>Daftar Mahasiswa</h2>

<!-- Button Tambah Mahasiswa -->
<a href="{{ route('mahasiswa.create') }}" style="margin-bottom: 10px; display: inline-block;">Tambah Mahasiswa</a>

<table border="1">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jenis Kelamin</th>
            <th>Prodi</th>
            <th>Jenjang</th>
            <th>Agama</th>
            <th>Angkatan</th>
            <th>Action</th> <!-- Kolom untuk Action (Edit & Delete) -->
        </tr>
    </thead>
    <tbody>
        @forelse ($mahasiswa as $mhs)
            <tr>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->jenis_kelamin }}</td>
                <td>{{ $mhs->prodi }}</td>
                <td>{{ $mhs->jenjang }}</td>
                <td>{{ $mhs->agama }}</td>
                <td>{{ $mhs->angkatan }}</td>
                <td>
                    <!-- Button Edit -->
                    <a href="{{ route('mahasiswa.edit', $mhs->id) }}">Edit</a>

                    <!-- Form Delete -->
                    <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No data available</td> <!-- Sesuaikan colspan agar sesuai dengan jumlah kolom -->
            </tr>
        @endforelse
    </tbody>
</table>
