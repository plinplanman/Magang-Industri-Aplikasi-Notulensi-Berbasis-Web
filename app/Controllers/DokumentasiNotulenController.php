<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumentasiNotulenModel;
use App\Models\AgendaModel;
use App\Models\NotulenModel;

class DokumentasiNotulenController extends BaseController
{
    protected $dokumentasiModel;

    public function __construct()
    {
        $this->dokumentasiModel = new DokumentasiNotulenModel();
    }

    public function create($notulenId = null)
    {
        $notulenModel = new NotulenModel();

        // Fetch the selected notulen and its related agenda
        $notulen = $notulenModel
            ->select('notulens.id as notulen_id, notulens.isi_notulens, agendas.judul')
            ->join('agendas', 'agendas.id = notulens.agenda_id')
            ->where('notulens.id', $notulenId)
            ->first();

        if (!$notulen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Notulen not found');
        }

        $data = [
            'title'   => 'Create Dokumentasi Notulen',
            'notulen' => $notulen,
        ];

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/dokumentasi/create', $data);
        echo view('template/footer');
    }

    public function store()
    {
        $files = $this->request->getFileMultiple('images');
        $notulenId = $this->request->getPost('notulen_id');

        if (empty($files)) {
            return redirect()->back()->withInput()->with('error', 'Please upload at least one image.');
        }

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Define the destination path in the public folder
                $destination = WRITEPATH . '../public/dokumentasi_notulen';
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true); // Create folder if it doesn't exist
                }

                $imageName = $file->getRandomName();
                $file->move($destination, $imageName);

                // Save record in database
                $this->dokumentasiModel->insert([
                    'notulen_id' => $notulenId,
                    'image'      => $imageName,
                ]);
            }
        }

        return redirect()->to('/notulen')->with('success', 'Data successfully saved.');
    }

    public function edit($id)
    {
        $dokumentasi = $this->dokumentasiModel->where('notulen_id', $id)->findAll();

        $notulenModel = new \App\Models\NotulenModel();
        $notulen = $notulenModel
            ->select('notulens.id as notulen_id, notulens.isi_notulens, agendas.judul')
            ->join('agendas', 'agendas.id = notulens.agenda_id')
            ->where('notulens.id', $id)
            ->first();

        if (!$notulen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Notulen not found');
        }

        $data = [
            'title'       => 'Edit Dokumentasi Notulen',
            'notulen'     => $notulen,
            'dokumentasi' => $dokumentasi,
        ];

        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/dokumentasi/edit', $data);
        echo view('template/footer');
    }



    public function update($id)
    {
        $dokumentasi = $this->dokumentasiModel->find($id);

        if (!$dokumentasi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data not found');
        }

        $file = $this->request->getFile('image');
        if ($file->isValid() && !$file->hasMoved()) {
            $destination = WRITEPATH . '../public/dokumentasi_notulen';
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            // Delete old file
            $oldImagePath = $destination . '/' . $dokumentasi['image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Move new file and update record
            $imageName = $file->getRandomName();
            $file->move($destination, $imageName);

            $this->dokumentasiModel->update($id, [
                'image' => $imageName,
            ]);
        }

        return redirect()->to('/notulen/dokumentasi/edit/' . $id)->with('success', 'Data updated successfully.');
    }

    public function delete($id)
    {
        $gambar = $this->dokumentasiModel->find($id);

        if (!$gambar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Gambar tidak ditemukan');
        }

        $filePath = WRITEPATH . '../public/dokumentasi_notulen/' . $gambar['image'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->dokumentasiModel->delete($id);

        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function getAllApi($notulenId)
    {
        $dokumentasi = $this->dokumentasiModel->where('notulen_id', $notulenId)->findAll();

        if (empty($dokumentasi)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No dokumentasi found for this notulen'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $dokumentasi
        ]);
    }

    public function showApi($id){
        $dokumentasi =$this->dokumentasiModel->find($id);

        if(!$dokumentasi){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Dokumentasi Tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status'=>'success',
            'data'=>$dokumentasi
        ]);
    }

    public function storeApi()
    {
        $input = $this->request->getJSON();

        if(!isset($input->notulen_id)|| empty($input->images)){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>"Data notulen_id dan images harus diisi"
            ]);

        }
        foreach ($input->images as $image){
            $this->dokumentasiModel->insert([
                'notulen_id' => $input->notulen_id,
                'image' => $image // Assume base64 or image name is sent
            ]);
        }
        return $this->response->setJSON([
            'status'=>'success',
            'message'=>'Dokumentasi berhasil ditambah'
        ]);
    }

    public function updateApi($id)
    {
        $input = $this->request->getJSON();
        if (!isset($input->image)) {
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Gambar diperlukan'
            ]);
        }
        $dokumentasi = $this->dokumentasiModel->find($id);
        if (!$dokumentasi){
            return $this->response->setJSON([
                 'status' => 'error',
                'message' => 'Dokumentasi tidak Ditemukan'
            ]);
        }

        $this->dokumentasiModel->update(
            $id, [
                'image' => $input->image
            ]);

            return $this->response->setJSON([
                'status' => 'success',
            'message' => 'Dokumentasi Berhasil Diperbarui'
            ]);
    }

    public function deleteApi($id)
    {
        $dokumentasi = $this->dokumentasiModel->find($id);

        if(!$dokumentasi){
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dokumentasi Tidak Ditemukan'
            ]);
        }
        $filePath = WRITEPATH . '../public/dokumentasi_notulen/' . $dokumentasi['image'];

        if (file_exists($filePath)){
            unlink($filePath);
        }
        $this->dokumentasiModel->delete($id);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Dokumentasi Berhasil Dihapus'
        ]);
    }

}
