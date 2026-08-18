<div class="page-header">
    <h1>Data Program Studi</h1>
    <div class="page-header-actions">
        <a href="{{ url('prodi/exportexcel') }}" class="btn btn-secondary">Export Excel</a>
        <a href="{{ url('prodi/exportpdf') }}" class="btn btn-secondary" target="_blank">Export PDF</a>
        <a href="{{ url('prodi/new') }}" class="btn btn-primary">+ Tambah Prodi</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Prodi</th>
            <th>Nama Prodi</th>
            <th>Jenjang</th>
            <th>Akreditasi</th>
            <th>Kode NIM</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        {% set no = 1 %}
        {% for item in prodi %}
        <tr>
            <td>{{ no }}</td>
            <td>{{ item.kode_prodi }}</td>
            <td>{{ item.nama_prodi }}</td>
            <td>{{ item.jenjang }}</td>
            <td>{{ item.akreditasi }}</td>
            <td>{{ item.kode_nim }}</td>
            <td class="table-actions">
                <a href="{{ url('prodi/edit/' ~ item.id) }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{ url('prodi/delete/' ~ item.id) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        {% set no = no + 1 %}
        {% else %}
        <tr>
            <td colspan="7" class="text-center">Belum ada data prodi.</td>
        </tr>
        {% endfor %}
    </tbody>
</table>
