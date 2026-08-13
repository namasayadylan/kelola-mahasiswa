<div class="page-header">
    <h1>Data Mahasiswa</h1>
    <a href="{{ url('mahasiswa/new') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        {% set no = 1 %}
        {% for item in mahasiswa %}
        <tr>
            <td>{{ no }}</td>
            <td>{{ item.nim }}</td>
            <td>{{ item.nama }}</td>
            <td>{{ item.prodi.nama_prodi }}</td>
            <td>{{ item.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td>{{ item.alamat }}</td>
            <td class="table-actions">
                <a href="{{ url('mahasiswa/edit/' ~ item.id) }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{ url('mahasiswa/delete/' ~ item.id) }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        {% set no = no + 1 %}
        {% else %}
        <tr>
            <td colspan="7" class="text-center">Belum ada data mahasiswa.</td>
        </tr>
        {% endfor %}
    </tbody>
</table>
