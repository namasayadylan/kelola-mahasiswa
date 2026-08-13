<?php

class MahasiswaController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setVar('mahasiswa', Mahasiswa::find(['order' => 'id DESC']));
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
        $mahasiswa->nim           = Mahasiswa::generateNim($prodi); // otomatis, urut per prodi & tahun
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
}
