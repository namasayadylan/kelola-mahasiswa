<?php

use Phalcon\Mvc\Model;

class Prodi extends Model
{
    public ?int $id = null;
    public ?string $kode_prodi = null;
    public ?string $kode_nim = null;
    public ?string $nama_prodi = null;
    public ?string $jenjang = null;
    public ?string $akreditasi = null;
    public ?string $created_at = null;

    public function initialize()
    {
        $this->setSource('prodi');

        $this->hasMany('id', 'Mahasiswa', 'prodi_id', [
            'alias' => 'Mahasiswa',
        ]);
    }

    public static function generateKodeNim(): string
    {
        $urutan = self::count() + 1;
        return (string) $urutan . (string) $urutan;
    }
}
