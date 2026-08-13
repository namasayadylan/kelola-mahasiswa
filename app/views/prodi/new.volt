<div class="page-header">
    <h1>Tambah Program Studi</h1>
</div>

<form action="{{ url('prodi/create') }}" method="post" class="form-card">
    <div class="form-group">
        <label>Kode Prodi</label>
        <input type="text" name="kode_prodi" class="form-control" required maxlength="10">
    </div>

    <div class="form-group">
        <label>Nama Prodi</label>
        <input type="text" name="nama_prodi" class="form-control" required maxlength="100">
    </div>

    <div class="form-group">
        <label>Jenjang</label>
        <select name="jenjang" class="form-control" required>
            <option value="D3">D3</option>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
        </select>
    </div>

    <div class="form-group">
        <label>Akreditasi</label>
        <input type="text" name="akreditasi" class="form-control" maxlength="5" placeholder="A / B / C">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ url('prodi/index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
