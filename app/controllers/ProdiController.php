<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
class ProdiController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('prodi', Prodi::find(['order' => 'id DESC']));
    }

    public function newAction()
    {
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('prodi/new');
        }

        $prodi = new Prodi();
        $prodi->kode_prodi = $this->request->getPost('kode_prodi', 'string');
        $prodi->kode_nim   = Prodi::generateKodeNim(); 
        $prodi->nama_prodi = $this->request->getPost('nama_prodi', 'string');
        $prodi->jenjang    = $this->request->getPost('jenjang', 'string');
        $prodi->akreditasi = $this->request->getPost('akreditasi', 'string');
        $prodi->created_at = date('Y-m-d H:i:s');

        if (!$prodi->save()) {
            foreach ($prodi->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->response->redirect('prodi/new');
        }

        $this->flash->success('Data prodi berhasil disimpan.');
        return $this->response->redirect('prodi/index');
    }

    public function editAction(int $id)
    {
        $prodi = Prodi::findFirst($id);

        if (!$prodi) {
            $this->flash->error('Data prodi tidak ditemukan.');
            return $this->response->redirect('prodi/index');
        }

        $this->view->setVar('prodi', $prodi);
    }

    public function updateAction(int $id)
    {
        $prodi = Prodi::findFirst($id);

        if (!$prodi) {
            $this->flash->error('Data prodi tidak ditemukan.');
            return $this->response->redirect('prodi/index');
        }

        $prodi->kode_prodi = $this->request->getPost('kode_prodi', 'string');
        $prodi->nama_prodi = $this->request->getPost('nama_prodi', 'string');
        $prodi->jenjang    = $this->request->getPost('jenjang', 'string');
        $prodi->akreditasi = $this->request->getPost('akreditasi', 'string');

        if (!$prodi->save()) {
            foreach ($prodi->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->response->redirect('prodi/edit/' . $id);
        }

        $this->flash->success('Data prodi berhasil diperbarui.');
        return $this->response->redirect('prodi/index');
    }

    public function deleteAction(int $id)
    {
        $prodi = Prodi::findFirst($id);

        if (!$prodi) {
            $this->flash->error('Data prodi tidak ditemukan.');
            return $this->response->redirect('prodi/index');
        }

        if (!$prodi->delete()) {
            foreach ($prodi->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
        } else {
            $this->flash->success('Data prodi berhasil dihapus.');
        }

        return $this->response->redirect('prodi/index');
    }

    public function exportexcelAction()
{
    $prodiList = Prodi::find(['order' => 'nama_prodi ASC']);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data Prodi');

    $headers = ['No', 'Kode Prodi', 'Nama Prodi', 'Jenjang', 'Akreditasi', 'Kode NIM'];
    $sheet->fromArray($headers, null, 'A1');
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    $row = 2;
    $no = 1;
    foreach ($prodiList as $item) {
        $sheet->setCellValueExplicit('A' . $row, $no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $sheet->setCellValue('B' . $row, $item->kode_prodi);
        $sheet->setCellValue('C' . $row, $item->nama_prodi);
        $sheet->setCellValue('D' . $row, $item->jenjang);
        $sheet->setCellValue('E' . $row, $item->akreditasi);
        $sheet->setCellValueExplicit('F' . $row, $item->kode_nim, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $row++;
        $no++;
    }

    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $this->view->disable();

    $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $this->response->setHeader('Content-Disposition', 'attachment; filename="data-prodi-' . date('Ymd-His') . '.xlsx"');
    $this->response->setHeader('Cache-Control', 'max-age=0');
    $this->response->sendHeaders();

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function exportpdfAction()
{
    $prodiList = Prodi::find(['order' => 'nama_prodi ASC']);

    $html = '<html><head><meta charset="UTF-8"><style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2a44; }
            h2 { text-align: center; margin-bottom: 2px; }
            p.sub { text-align: center; margin-top: 0; color: #555; }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; }
            th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
            th { background: #f0f2f5; }
        </style></head><body>';
    $html .= '<h2>Data Program Studi</h2>';
    $html .= '<p class="sub">Dicetak pada ' . date('d-m-Y H:i') . '</p>';
    $html .= '<table><thead><tr>
            <th>No</th><th>Kode Prodi</th><th>Nama Prodi</th><th>Jenjang</th><th>Akreditasi</th><th>Kode NIM</th>
        </tr></thead><tbody>';

    $no = 1;
    foreach ($prodiList as $item) {
        $html .= '<tr>'
            . '<td>' . $no . '</td>'
            . '<td>' . htmlspecialchars((string) $item->kode_prodi) . '</td>'
            . '<td>' . htmlspecialchars((string) $item->nama_prodi) . '</td>'
            . '<td>' . htmlspecialchars((string) $item->jenjang) . '</td>'
            . '<td>' . htmlspecialchars((string) $item->akreditasi) . '</td>'
            . '<td>' . htmlspecialchars((string) $item->kode_nim) . '</td>'
            . '</tr>';
        $no++;
    }

    if (count($prodiList) === 0) {
        $html .= '<tr><td colspan="6" style="text-align:center;">Belum ada data prodi.</td></tr>';
    }

    $html .= '</tbody></table></body></html>';

    $options = new Options();
    $options->set('isRemoteEnabled', false);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $this->view->disable();
    $dompdf->stream('data-prodi-' . date('Ymd-His') . '.pdf', ['Attachment' => true]);
    exit;
}

}
