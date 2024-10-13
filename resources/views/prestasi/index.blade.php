<!-- resources/views/prestasi/index.blade.php -->

<h2>Daftar Prestasi Mahasiswa</h2>

<!-- Search Box -->
<input type="text" id="searchBox" placeholder="Cari Mahasiswa..." onkeyup="searchMahasiswa()">

<table border="1">
    <thead>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Prodi</th>
            <th>Prestasi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="mahasiswaTable">
        @forelse ($mahasiswas as $mahasiswa)
            <tr>
                <td>{{ $mahasiswa->nama }}</td>
                <td>{{ $mahasiswa->nim }}</td>
                <td>{{ $mahasiswa->prodi }}</td>
                <td>
                    @foreach ($mahasiswa->prestasi as $prestasi)
                        <p>
                            {{ $prestasi->nama_prestasi }} ({{ $prestasi->tingkatan_prestasi }})
                            <br>
                            <!-- Tautan Edit dan Delete untuk setiap prestasi -->
                            <a href="{{ route('prestasi.edit', $prestasi->id) }}">Edit</a> |
                            <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus prestasi ini?')">Delete</button>
                            </form>
                        </p>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('prestasi.create', $mahasiswa->id) }}">Tambah Prestasi</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No data available</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
function searchMahasiswa() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchBox");
    filter = input.value.toUpperCase();
    table = document.getElementById("mahasiswaTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
