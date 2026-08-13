<div class="page-header">
    <h1>Edit Mahasiswa</h1>
</div>

<form action="{{ url('mahasiswa/update/' ~ mahasiswa.id) }}" method="post" class="form-card">
    <div class="form-group">
        <label>NIM</label>
        <input type="text" class="form-control" value="{{ mahasiswa.nim }}" readonly disabled>
        <small class="form-hint">NIM bersifat permanen dan tidak bisa diubah.</small>
    </div>

    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required maxlength="100"
               value="{{ mahasiswa.nama }}">
    </div>

    <div class="form-group">
        <label>Program Studi</label>
        <select name="prodi_id" class="form-control" required>
            <option value="">-- Pilih Prodi --</option>
            {% for p in prodiList %}
            <option value="{{ p.id }}" {% if mahasiswa.prodi_id == p.id %}selected{% endif %}>
                {{ p.nama_prodi }} ({{ p.jenjang }})
            </option>
            {% endfor %}
        </select>
    </div>

    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="L" {% if mahasiswa.jenis_kelamin == 'L' %}selected{% endif %}>Laki-laki</option>
            <option value="P" {% if mahasiswa.jenis_kelamin == 'P' %}selected{% endif %}>Perempuan</option>
        </select>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3">{{ mahasiswa.alamat }}</textarea>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url('mahasiswa/index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
