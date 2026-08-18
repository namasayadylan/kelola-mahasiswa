<?php

use Dompdf\Dompdf;

class PdfHelper
{
    public static function buildKopSurat(): string
    {
        $instansi = require APP_PATH . '/app/config/instansi.php';

        $logoSrc = '';
        if (file_exists($instansi['logo'])) {
            $logoSrc = 'data:image/png;base64,' . base64_encode(file_get_contents($instansi['logo']));
        }

        $html = '<table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:80px; vertical-align:middle; padding:0;">';

        if ($logoSrc) {
            $html .= '<img src="' . $logoSrc . '" style="width:90px; height:auto;">';
        }

        $html .= '</td>
                <td style="vertical-align:middle; text-align:center; padding:0;">
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase; line-height:1.05;">'
                        . htmlspecialchars($instansi['nama_baris1']) . '</div>
                    <div style="font-size:16px; font-weight:bold; text-transform:uppercase; line-height:1.05;">'
                        . htmlspecialchars($instansi['nama_baris2']) . '</div>
                    <div style="font-size:9.5px; font-weight:bold; margin-top:2px;">'
                        . htmlspecialchars($instansi['alamat'])
                        . ' Telp. ' . htmlspecialchars($instansi['telp'])
                        . ' Fax. ' . htmlspecialchars($instansi['fax']) . '</div>
                    <div style="font-size:9.5px; margin-top:0px;">                                               
                        Website : <span style="color:#1a56db; text-decoration:underline;">'
                            . htmlspecialchars($instansi['website']) . '</span>
                        &nbsp;&nbsp;Email : <span style="color:#1a56db; text-decoration:underline;">'
                            . htmlspecialchars($instansi['email']) . '</span>
                    </div>
                </td>
                <td style="width:80px;"></td>
            </tr>
        </table>
        <hr style="border:none; border-top:3px solid #000; margin:8px 0 2px;">
        <hr style="border:none; border-top:1px solid #000; margin:0 0 16px;">';

        return $html;
    }

    public static function addRunningHeader(Dompdf $dompdf): void
    {
        $canvas = $dompdf->getCanvas();
        $font   = $dompdf->getFontMetrics()->getFont('helvetica', 'normal');
        $width  = $canvas->get_width();
        $color  = [0, 0, 0];
        

        $canvas->page_text($width - 150, 34, 'Page {PAGE_NUM}/{PAGE_COUNT}', $font, 5, $color);
        $canvas->page_text($width - 150, 46, 'Dicetak pada : ' . date('Y-m-d H:i:s'), $font, 5, $color);
    }
}