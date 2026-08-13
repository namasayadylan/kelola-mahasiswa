<?php

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
        $prodi->kode_nim   = Prodi::generateKodeNim(); // otomatis: 11, 22, 33, dst
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
}
