<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class MahasiswaController extends ControllerBase
{
   public function indexAction()
{
    [$conditions, $bind, $prodiId, $angkatan] = $this->buildFilter();

    $this->view->setVar('mahasiswa', Mahasiswa::find([
        'conditions' => $conditions,
        'bind'       => $bind,
        'order'      => 'id DESC',
    ]));
    $this->view->setVar('prodiList', Prodi::find(['order' => 'nama_prodi ASC']));
    $this->view->setVar('angkatanList', Mahasiswa::getAngkatanList());
    $this->view->setVar('selectedProdi', $prodiId);
    $this->view->setVar('selectedAngkatan', $angkatan);
}

private function buildFilter(): array
{
    $prodiId  = $this->request->getQuery('prodi_id', 'int', null);
    $angkatan = $this->request->getQuery('angkatan', 'string', null);

    $conditionParts = [];
    $bind = [];

    if (!empty($prodiId)) {
        $conditionParts[] = 'prodi_id = :prodi_id:';
        $bind['prodi_id'] = $prodiId;
    }

    if (!empty($angkatan)) {
        $conditionParts[] = 'nim LIKE :angkatan:';
        $bind['angkatan'] = $angkatan . '%';
    }

    $conditions = count($conditionParts) > 0 ? implode(' AND ', $conditionParts) : null;

    return [$conditions, $bind, $prodiId, $angkatan];
    }
    public function newAction()
    {
        $this->view->setVar('prodiList', Prodi::find(['order' => 'nama_prodi ASC']));
    }
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('mahasiswa/new');
        }

        $prodiId = $this->request->getPost('prodi_id', 'int');
        $prodi   = Prodi::findFirst($prodiId);

        if (!$prodi) {
            $this->flash->error('Prodi yang dipilih tidak valid.');
            return $this->response->redirect('mahasiswa/new');
        }

        $mahasiswa = new Mahasiswa();
        $mahasiswa->nim           = Mahasiswa::generateNim($prodi); 
        $mahasiswa->nama          = $this->request->getPost('nama', 'string');
        $mahasiswa->prodi_id      = $prodiId;
        $mahasiswa->jenis_kelamin = $this->request->getPost('jenis_kelamin', 'string');
        $mahasiswa->alamat        = $this->request->getPost('alamat', 'string');
        $mahasiswa->created_at    = date('Y-m-d H:i:s');

        if (!$mahasiswa->save()) {
            foreach ($mahasiswa->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->response->redirect('mahasiswa/new');
        }

        $this->flash->success('Data mahasiswa berhasil disimpan.');
        return $this->response->redirect('mahasiswa/index');
    }
    public function editAction(int $id)
    {
        $mahasiswa = Mahasiswa::findFirst($id);

        if (!$mahasiswa) {
            $this->flash->error('Data mahasiswa tidak ditemukan.');
            return $this->response->redirect('mahasiswa/index');
        }

        $this->view->setVar('mahasiswa', $mahasiswa);
        $this->view->setVar('prodiList', Prodi::find(['order' => 'nama_prodi ASC']));
    }

    public function updateAction(int $id)
    {
        $mahasiswa = Mahasiswa::findFirst($id);

        if (!$mahasiswa) {
            $this->flash->error('Data mahasiswa tidak ditemukan.');
            return $this->response->redirect('mahasiswa/index');
        }

        $mahasiswa->nama          = $this->request->getPost('nama', 'string');
        $mahasiswa->prodi_id      = $this->request->getPost('prodi_id', 'int');
        $mahasiswa->jenis_kelamin = $this->request->getPost('jenis_kelamin', 'string');
        $mahasiswa->alamat        = $this->request->getPost('alamat', 'string');

        if (!$mahasiswa->save()) {
            foreach ($mahasiswa->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->response->redirect('mahasiswa/edit/' . $id);
        }

        $this->flash->success('Data mahasiswa berhasil diperbarui.');
        return $this->response->redirect('mahasiswa/index');
    }
    public function deleteAction(int $id)
    {
        $mahasiswa = Mahasiswa::findFirst($id);

        if (!$mahasiswa) {
            $this->flash->error('Data mahasiswa tidak ditemukan.');
            return $this->response->redirect('mahasiswa/index');
        }

        if (!$mahasiswa->delete()) {
            foreach ($mahasiswa->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
        } else {
            $this->flash->success('Data mahasiswa berhasil dihapus.');
        }

        return $this->response->redirect('mahasiswa/index');
    }

    public function exportexcelAction()
{
    [$conditions, $bind] = $this->buildFilter();

    $mahasiswaList = Mahasiswa::find([
        'conditions' => $conditions,
        'bind'       => $bind,
        'order'      => 'nim ASC',
    ]);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Mahasiswa');

    $headers = ['No', 'NIM', 'Nama', 'Program Studi', 'Jenis Kelamin', 'Alamat'];
    $sheet->fromArray($headers, null, 'A1');
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    $row = 2;
    $no = 1;
    foreach ($mahasiswaList as $item) {
        $sheet->setCellValueExplicit('A' . $row, $no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $sheet->setCellValueExplicit('B' . $row, $item->nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('C' . $row, $item->nama);
        $sheet->setCellValue('D' . $row, $item->prodi ? $item->prodi->nama_prodi : '-');
        $sheet->setCellValue('E' . $row, $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
        $sheet->setCellValue('F' . $row, $item->alamat);
        $row++;
        $no++;
    }

    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $this->view->disable();

    $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->response->setHeader('Content-Disposition', 'attachment; filename="data-mahasiswa-' . date('Ymd-His') . '.xlsx"');
    $this->response->setHeader('Cache-Control', 'max-age=0');
    $this->response->sendHeaders();

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function exportpdfAction()
{
    [$conditions, $bind, $prodiId, $angkatan] = $this->buildFilter();

    $mahasiswaList = Mahasiswa::find([
        'conditions' => $conditions,
        'bind'       => $bind,
        'order'      => 'nim ASC',
    ]);

    $prodiLabel = 'Semua Program Studi';
    if (!empty($prodiId)) {
        $prodi = Prodi::findFirst($prodiId);
        $prodiLabel = $prodi ? $prodi->nama_prodi : '-';
    }

    $angkatanLabel = !empty($angkatan) ? '20' . $angkatan : 'Semua Angkatan';
    $totalData = count($mahasiswaList);

    $html = '<html><head><meta charset="UTF-8"><style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2a44; }
            .info-table { width: 100%; margin-bottom: 14px; font-size: 11px; }
            .info-table td { border: none; padding: 1px 0; }
            .info-table td.label { width: 130px; }
            .info-table td.sep { width: 14px; }
            table.data { width: 100%; border-collapse: collapse; }
            table.data th, table.data td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
            table.data th { background: #f0f2f5; }
            .total { text-align: right; margin-top: 10px; font-weight: bold; }
        </style></head><body>';


    $html .= PdfHelper::buildKopSurat();

    $html .= '<h3 style="text-align:center; text-transform:uppercase; margin:0 0 14px;">Laporan Data Mahasiswa</h3>';

    $html .= '<table class="info-table">
            <tr><td class="label">Tanggal Cetak</td><td class="sep">:</td><td>' . date('d-m-Y H:i') . '</td></tr>
            <tr><td class="label">Jumlah Data</td><td class="sep">:</td><td>' . $totalData . ' mahasiswa</td></tr>
            <tr><td class="label">Program Studi</td><td class="sep">:</td><td>' . htmlspecialchars($prodiLabel) . '</td></tr>
            <tr><td class="label">Angkatan</td><td class="sep">:</td><td>' . htmlspecialchars($angkatanLabel) . '</td></tr>
        </table>';

    $html .= '<table class="data"><thead><tr>
            <th>No</th><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Jenis Kelamin</th><th>Alamat</th>
        </tr></thead><tbody>';

    $no = 1;
    foreach ($mahasiswaList as $item) {
        $html .= '<tr>'
            . '<td>' . $no . '</td>'
            . '<td>' . htmlspecialchars((string) $item->nim) . '</td>'
            . '<td>' . htmlspecialchars((string) $item->nama) . '</td>'
            . '<td>' . htmlspecialchars($item->prodi ? $item->prodi->nama_prodi : '-') . '</td>'
            . '<td>' . ($item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') . '</td>'
            . '<td>' . htmlspecialchars((string) $item->alamat) . '</td>'
            . '</tr>';
        $no++;
    }

    if ($totalData === 0) {
        $html .= '<tr><td colspan="6" style="text-align:center;">Belum ada data mahasiswa.</td></tr>';
    }

    $html .= '</tbody></table>';
    $html .= '<p class="total">Total Mahasiswa: ' . $totalData . ' orang</p>';
    $html .= '</body></html>';

    $options = new Options();
    $options->set('isRemoteEnabled', false);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'potrait');
    $dompdf->render();

    PdfHelper::addRunningHeader($dompdf);

    $this->view->disable();
    $dompdf->stream('laporan-mahasiswa-' . date('Ymd-His') . '.pdf', ['Attachment' => true]);
    exit;
}

}
