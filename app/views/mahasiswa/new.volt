<div class="page-header">
    <h1>Tambah Mahasiswa</h1>
</div>

<form action="{{ url('mahasiswa/create') }}" method="post" class="form-card">
    <p class="form-note">
        NIM akan dibuat otomatis oleh sistem setelah data disimpan
        (format: 2 digit tahun + kode prodi + nomor urut, contoh <strong>2611001</strong>).
    </p>

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required maxlength="100">
    </div>

    <div class="form-group">
        <label>Program Studi</label>
        <select name="prodi_id" class="form-control" required>
            <option value="">-- Pilih Prodi --</option>
            {% for p in prodiList %}
            <option value="{{ p.id }}">{{ p.nama_prodi }} ({{ p.jenjang }})</option>
            {% endfor %}
        </select>
    </div>

    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3"></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ url('mahasiswa/index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
