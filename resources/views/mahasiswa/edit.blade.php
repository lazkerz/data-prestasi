<!-- resources/views/mahasiswa/edit.blade.php -->

<h2>Edit Mahasiswa</h2>

<form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nama:</label>
    <input type="text" name="nama" value="{{ $mahasiswa->nama }}" required><br>

    <label>NIM:</label>
    <input type="text" name="nim" value="{{ $mahasiswa->nim }}" required><br>

    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="L" {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
    </select><br>

    <label>Prodi:</label>
    <select name="prodi" required>
        @foreach ($prodiList as $prodi)
            <option value="{{ $prodi->nama_prodi }}" {{ $mahasiswa->prodi == $prodi->nama_prodi ? 'selected' : '' }}>
                {{ $prodi->nama_prodi }}
            </option>
        @endforeach
    </select><br>

    <label>Jenjang:</label>
    <input type="text" name="jenjang" value="{{ $mahasiswa->jenjang }}" required><br>

    <label>Agama:</label>
    <input type="text" name="agama" value="{{ $mahasiswa->agama }}" required><br>

    <label>Angkatan:</label>
    <input type="text" name="angkatan" value="{{ $mahasiswa->angkatan }}" required><br>

    <button type="submit">Update</button>
</form>
