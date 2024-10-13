<!-- resources/views/mahasiswa/create.blade.php -->

<h2>Tambah Mahasiswa</h2>

<form action="{{ route('mahasiswa.store') }}" method="POST">
    @csrf
    <label>Nama:</label>
    <input type="text" name="nama" required><br>

    <label>NIM:</label>
    <input type="text" name="nim" required><br>

    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select><br>

    <label>Prodi:</label>
    <select name="prodi" required>
        @foreach ($prodiList as $prodi)
            <option value="{{ $prodi->nama_prodi }}">{{ $prodi->nama_prodi }}</option>
        @endforeach
    </select><br>

    <label>Jenjang:</label>
    <input type="text" name="jenjang" required><br>

    <label>Agama:</label>
    <input type="text" name="agama" required><br>

    <label>Angkatan:</label>
    <input type="text" name="angkatan" required><br>

    <button type="submit">Tambah</button>
</form>
