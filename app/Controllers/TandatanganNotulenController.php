<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TandatanganNotulenModel;
use App\Models\NotulenModel;

class TandatanganNotulenController extends BaseController
{
    protected $tandatanganModel;

    public function __construct()
    {
        $this->tandatanganModel = new TandatanganNotulenModel();
    }

    public function create($notulenId = null)
{
    if (!$notulenId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Notulen ID tidak ditemukan.');
    }

    $notulenModel = new \App\Models\NotulenModel();

    $notulen = $notulenModel
        ->select('id, isi_notulens')
        ->where('id', $notulenId)
        ->first();

    if (!$notulen) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Notulen tidak ditemukan.');
    }

    // Ambil semua data tanda tangan terkait Notulen
    $tandatanganList = $this->tandatanganModel
        ->where('notulen_id', $notulenId)
        ->findAll();

    $data = [
        'title'          => 'Tambah Tanda Tangan',
        'notulen_id'     => $notulenId,
        'notulen'        => $notulen,
        'tandatanganList' => $tandatanganList, // Kirim semua tanda tangan ke view
    ];

    echo view('template/header', $data);
    echo view('template/top_menu');
    echo view('template/side_menu');
    echo view('notulen/tandatangan/create', $data);
    echo view('template/footer');
}
public function store()
{
    $data = $this->request->getPost();

    // Validasi input
    if (empty($data['notulen_id']) || empty($data['nama']) || empty($data['departemen'])) {
        return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi.');
    }

    $notulenId = $data['notulen_id'];

    // Validasi tanda tangan Base64
    $signatureBase64 = $data['signature'];
    if (empty($signatureBase64)) {
        return redirect()->back()->withInput()->with('error', 'Tanda tangan tidak valid.');
    }

    // Decode tanda tangan dari Base64
    $imageData = explode(',', $signatureBase64)[1];
    $imageData = base64_decode($imageData);

    // Tentukan nama file dan direktori penyimpanan
    $fileName = uniqid('signature_') . '.png';
    $filePath = WRITEPATH . '../public/tandatangan/' . $fileName;

    // Simpan file tanda tangan
    if (!is_dir(dirname($filePath))) {
        mkdir(dirname($filePath), 0755, true); // Buat direktori jika belum ada
    }
    file_put_contents($filePath, $imageData);

    // Simpan data ke database
    $this->tandatanganModel->insert([
        'notulen_id' => $notulenId,
        'nama'       => $data['nama'],
        'departemen' => $data['departemen'],
        'tandatangan' => $fileName,
    ]);

    return redirect()->to("/notulen/tandatangan/create/$notulenId")->with('success', 'Tanda tangan berhasil ditambahkan.');
}

public function edit($id)
{
    // Cari data tanda tangan berdasarkan ID
    $tandatangan = $this->tandatanganModel->find($id);

    if (!$tandatangan) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tanda tangan tidak ditemukan.');
    }

    $notulenModel = new NotulenModel();
    $notulen = $notulenModel->find($tandatangan['notulen_id']);

    if (!$notulen) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Data notulen terkait tidak ditemukan.');
    }

    $data = [
        'title'       => 'Edit Tanda Tangan',
        'tandatangan' => $tandatangan,
        'notulen'     => $notulen,
    ];

    echo view('template/header', $data);
    echo view('template/top_menu');
    echo view('template/side_menu');
    echo view('notulen/tandatangan/edit', $data);
    echo view('template/footer');
}

public function update($id)
{
    // Cari data tanda tangan berdasarkan ID
    $tandatangan = $this->tandatanganModel->find($id);

    if (!$tandatangan) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tanda tangan tidak ditemukan.');
    }

    $data = $this->request->getPost();

    // Validasi input
    if (empty($data['nama']) || empty($data['departemen'])) {
        return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi.');
    }

    // Update tanda tangan jika ada file baru
    if (!empty($data['signature'])) {
        $signatureBase64 = $data['signature'];

        // Decode tanda tangan dari Base64
        $imageData = explode(',', $signatureBase64)[1];
        $imageData = base64_decode($imageData);

        // Tentukan nama file baru
        $fileName = uniqid('signature_') . '.png';
        $filePath = WRITEPATH . '../public/tandatangan/' . $fileName;

        // Simpan file tanda tangan baru
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        file_put_contents($filePath, $imageData);

        // Hapus file tanda tangan lama
        $oldFilePath = WRITEPATH . '../public/tandatangan/' . $tandatangan['tandatangan'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        $data['tandatangan'] = $fileName;
    }

    // Update data di database
    $this->tandatanganModel->update($id, [
        'nama'       => $data['nama'],
        'departemen' => $data['departemen'],
        'tandatangan' => $data['tandatangan'] ?? $tandatangan['tandatangan'], // Gunakan file lama jika tidak ada file baru
    ]);

    return redirect()->to("/notulen/tandatangan/edit/$id")->with('success', 'Data tanda tangan berhasil diperbarui.');
}


    public function delete($id)
    {
        $tandatangan = $this->tandatanganModel->find($id);
        if (!$tandatangan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tanda tangan tidak ditemukan');
        }

        $filePath = WRITEPATH . '../public/tandatangan/' . $tandatangan['tandatangan'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->tandatanganModel->delete($id);

        return redirect()->back()->with('success', 'Tanda tangan berhasil dihapus.');
    }

    public function getAllApi($notulenId){
        $tandatanganList=$this->tandatanganModel->where('notulen_id',$notulenId)->findAll();

        if(empty($tandatanganList)){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Tanda Tangan Tidak Ditemukan Pada Notulen Ini'
            ]);
        }

        return $this->response->setJSON([
            'status'=>'success',
            'data'=>$tandatanganList
        ]);
    }
    public function showApi($id){
        $tandatanganList=$this->tandatanganModel->find($id);
        if(!$tandatanganList){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'tanda tangan tidakk ditemukan'
            ]);
        }
        return $this->response->setJSON([
            'status'=>'success',
            'data'=>$tandatanganList
        ]);
    }

    public function storeApi()
    {
        $input = $this->request->getJSON();

        if (!isset($input->notulen_id) || !isset($input->nama) || !isset($input->departemen) || !isset($input->signature)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Notulen ID, nama, departemen, and signature are required'
            ]);
        }

        // Decode Base64 signature
        $signatureBase64 = $input->signature;
        $imageData = explode(',', $signatureBase64)[1];
        $imageData = base64_decode($imageData);

        // Save the file
        $fileName = uniqid('signature_') . '.png';
        $filePath = WRITEPATH . '../public/tandatangan/' . $fileName;

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        file_put_contents($filePath, $imageData);

        // Save data to database
        $this->tandatanganModel->insert([
            'notulen_id' => $input->notulen_id,
            'nama' => $input->nama,
            'departemen' => $input->departemen,
            'tandatangan' => $fileName,
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Tandatangan successfully added'
        ]);
    }

    public function updateApi($id){
        $input = $this->request->getJSON();
        if(!isset($input->nama) || !isset($input->departemen)){
            return $this->response->setJSON([
                'status'=>'Error',
                'message'=>'Nama dan Departemen diperlukan'
            ]);
        }
        $tandatangan= $this->tandatanganModel->find($id);
        if (!$tandatangan) {
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Tanda Tangan tidak ditemukan'
            ]);
        }
        if(isset($input->signature)){
            $signatureBase64=$input->signature;
            $imageData=explode(',',$signatureBase64)[1];
            $imageData=base64_decode($imageData);

            $fileName =uniqid('Signature_').'.png';
            $filePath=WRITEPATH .'../public/tandatangan/'. $fileName;

            if (!is_dir(dirname($filePath))){
                mkdir(dirname($filePath),0755,true);
            }
            file_put_contents($filePath,$imageData);

            $oldFilePath=WRITEPATH . '../public/tandatangan/' . $tandatangan['tandatangan'];
            if(file_exists($oldFilePath)){
                unlink(($oldFilePath));
            }
            $input->tandatangan=$fileName;
        }
        $this->tandatanganModel->update($id,[
            'nama'=>$input->nama,
            'departemen'=>$input->departemen,
            'tandatangan'=>$input->tandatangan??$tandatangan['tandatangan'],
        ]);

        return $this->response->setJSON([
            'status'=>'success',
            'message'=>'Tandatangan Berhasiil Diperbarui'
        ]);
    }
    public function deleteApi($id)
    {
        $tandatangan=$this->tandatanganModel->find($id);
        if(!$tandatangan){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'tandatangan Tidak ditemukan'
            ]);
        }
        $filePath=WRITEPATH . '../public/tandatangan/'. $tandatangan['tandatangan'];
        if (file_exists($filePath)){
            unlink($filePath);
        }

        $this->tandatanganModel->delete($id);
        return $this->response->setJSON([
            'status'=>'success',
            'message'=>'Tandatangan berhasil dihapus'
        ]);
    }
}
