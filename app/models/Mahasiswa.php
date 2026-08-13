<?php

use Phalcon\Mvc\Model;

class Mahasiswa extends Model
{
    public ?int $id = null;
    public ?string $nim = null;
    public ?string $nama = null;
    public ?int $prodi_id = null;
    public ?string $jenis_kelamin = null;
    public ?string $alamat = null;
    public ?string $created_at = null;

    public function initialize()
    {
        $this->setSource('mahasiswa');

        $this->belongsTo('prodi_id', 'Prodi', 'id', [
            'alias'    => 'Prodi',
            'reusable' => true,
        ]);
    }

    public static function generateNim(Prodi $prodi): string
    {
        $tahun  = date('y'); 
        $prefix = $tahun . $prodi->kode_nim;

        $last = self::findFirst([
            'conditions' => 'nim LIKE :prefix:',
            'bind'       => ['prefix' => $prefix . '%'],
            'order'      => 'nim DESC',
        ]);

        $nomorUrut = 1;
        if ($last) {
            $urutTerakhir = (int) substr($last->nim, strlen($prefix));
            $nomorUrut = $urutTerakhir + 1;
        }

        return $prefix . str_pad((string) $nomorUrut, 3, '0', STR_PAD_LEFT);
    }
}
