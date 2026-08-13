<div class="page-header">
    <h1>Edit Program Studi</h1>
</div>

<form action="{{ url('prodi/update/' ~ prodi.id) }}" method="post" class="form-card">
    <div class="form-group">
        <label>Kode Prodi</label>
        <input type="text" name="kode_prodi" class="form-control" required maxlength="10"
               value="{{ prodi.kode_prodi }}">
    </div>

    <div class="form-group">
        <label>Nama Prodi</label>
        <input type="text" name="nama_prodi" class="form-control" required maxlength="100"
               value="{{ prodi.nama_prodi }}">
    </div>

    <div class="form-group">
        <label>Jenjang</label>
        <select name="jenjang" class="form-control" required>
            {% for j in ['D3', 'S1', 'S2'] %}
            <option value="{{ j }}" {% if prodi.jenjang == j %}selected{% endif %}>{{ j }}</option>
            {% endfor %}
        </select>
    </div>

    <div class="form-group">
        <label>Akreditasi</label>
        <input type="text" name="akreditasi" class="form-control" maxlength="5"
               value="{{ prodi.akreditasi }}">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url('prodi/index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
