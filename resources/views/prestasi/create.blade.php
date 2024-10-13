<!-- resources/views/prestasi/create.blade.php -->
<h2>Tambah Prestasi Mahasiswa: {{ $mahasiswa->nama }}</h2>

<!-- Form untuk submit -->
<form action="{{ route('prestasi.store', $mahasiswa->id) }}" method="POST" enctype="multipart/form-data" id="prestasiForm">
    @csrf
    <div id="prestasi-forms">
        <!-- Form Prestasi 1 -->
        <div class="prestasi-form">
            <h3>Prestasi 1</h3>
            <label for="nama_prestasi">Judul Prestasi:</label>
            <input type="text" name="nama_prestasi[]" required><br>

            <label for="deskripsi_prestasi">Deskripsi:</label>
            <textarea name="deskripsi_prestasi[]" required></textarea><br>

            <label for="jenis_prestasi">Jenis Prestasi:</label>
            <select name="jenis_prestasi[]" required>
                <option value="Akademik">Akademik</option>
                <option value="Non-Akademik">Non Akademik</option>
            </select><br>

            <label for="tingkatan_prestasi">Tingkatan Prestasi:</label>
            <select name="tingkatan_prestasi[]" required>
                <option value="Lokal">Lokal</option>
                <option value="Nasional">Nasional</option>
                <option value="Internasional">Internasional</option>
            </select><br>

            <label for="file_prestasi">Upload File (PDF atau Gambar):</label>
            <input type="file" name="file_prestasi[]" accept=".pdf,.jpg,.jpeg,.png"><br>
        </div>
    </div>

    <!-- Tombol untuk menambah form prestasi baru -->
    <button type="button" id="addPrestasiButton">Tambah Prestasi</button>
    <button type="submit">Simpan Semua Prestasi</button>
</form>

<script>
    let formCounter = 1;

    document.getElementById('addPrestasiButton').addEventListener('click', function() {
        formCounter++;

        const formContainer = document.createElement('div');
        formContainer.classList.add('prestasi-form');
        formContainer.innerHTML = `
            <h3>Prestasi ${formCounter}</h3>
            <label for="nama_prestasi">Judul Prestasi:</label>
            <input type="text" name="nama_prestasi[]" required><br>

            <label for="deskripsi_prestasi">Deskripsi:</label>
            <textarea name="deskripsi_prestasi[]" required></textarea><br>

            <label for="jenis_prestasi">Jenis Prestasi:</label>
            <select name="jenis_prestasi[]" required>
                <option value="Akademik">Akademik</option>
                <option value="Non-Akademik">Non Akademik</option>
            </select><br>

            <label for="tingkatan_prestasi">Tingkatan Prestasi:</label>
            <select name="tingkatan_prestasi[]" required>
                <option value="Lokal">Lokal</option>
                <option value="Nasional">Nasional</option>
                <option value="Internasional">Internasional</option>
            </select><br>

            <label for="file_prestasi">Upload File (PDF atau Gambar):</label>
            <input type="file" name="file_prestasi[]" accept=".pdf,.jpg,.jpeg,.png"><br>
        `;

        document.getElementById('prestasi-forms').appendChild(formContainer);
    });
</script>
