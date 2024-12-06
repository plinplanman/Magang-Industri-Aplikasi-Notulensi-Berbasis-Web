<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotulenModel;
use App\Models\AgendaModel;

class NotulenController extends BaseController
{
    protected $notulenModel;
    protected $agendaModel;
    protected $db;

    public function __construct()
    {
        $this->notulenModel = new NotulenModel();
        $this->agendaModel = new AgendaModel();
        $this->db = \Config\Database::connect(); // Inisialisasi database
    }

    // Tampilkan semua notulen
    public function index()
    {
        $builder = $this->db->table('notulens');
        $builder->select('notulens.id, agendas.judul, notulens.isi_notulens');
        $builder->join('agendas', 'agendas.id = notulens.agenda_id');
        $data['notulens'] = $builder->get()->getResultArray();

        $data['title'] = 'Daftar Notulen';
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/index', $data);
        echo view('template/footer');
    }



    // Form untuk membuat notulen baru
    public function create()
    {
        // Ambil daftar agenda
        $agendas = $this->agendaModel->findAll();

        // Ambil ID agenda yang sudah memiliki notulen
        $agendaIdsWithNotulen = $this->notulenModel
            ->select('agenda_id')
            ->findColumn('agenda_id');

        $data = [
            'title' => 'Tambah Notulen',
            'agendas' => $agendas,
            'agendaIdsWithNotulen' => $agendaIdsWithNotulen ?? [],
        ];
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/create', $data);
        echo view('template/footer');
    }

    // Simpan notulen baru
    public function store()
    {
        $agendaId = $this->request->getPost('agenda_id');

        // Validasi bahwa agenda hanya boleh memiliki satu notulen
        $existingNotulen = $this->notulenModel->where('agenda_id', $agendaId)->first();
        if ($existingNotulen) {
            return redirect()->back()->with('error', 'Agenda ini sudah memiliki notulen.');
        }

        // Gabungkan isi notulen menjadi string tanpa penomoran
        $isiNotulens = $this->request->getPost('isi_notulens');
        $isiNotulensFormatted = implode("\n", $isiNotulens);

        $this->notulenModel->save([
            'agenda_id' => $agendaId,
            'isi_notulens' => $isiNotulensFormatted,
        ]);

        return redirect()->to('/notulen')->with('success', 'Notulen berhasil dibuat.');
    }


    // Form untuk mengedit notulen
    public function edit($id)
    {
        $notulen = $this->notulenModel->find($id);
        if (!$notulen) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Notulen dengan ID $id tidak ditemukan.");
        }

        $agendas = $this->agendaModel->findAll(); // Ambil semua agenda
        $agendaIdsWithNotulen = $this->notulenModel->select('agenda_id')->where('id !=', $id)->findAll(); // ID agenda yang sudah digunakan

        $data = [
            'title' => 'Edit Notulen',
            'notulen' => $notulen,
            'agendas' => $agendas,
            'agendaIdsWithNotulen' => array_column($agendaIdsWithNotulen, 'agenda_id'), // Array sederhana
        ];
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/edit', $data);
        echo view('template/footer');
    }

    public function update($id)
    {
        $notulen = $this->notulenModel->find($id);

        if (!$notulen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Notulen tidak ditemukan.");
        }

        $agendaId = $this->request->getPost('agenda_id');

        // Validasi agenda
        $existingNotulen = $this->notulenModel
            ->where('agenda_id', $agendaId)
            ->where('id !=', $id) // Kecualikan notulen yang sedang diedit
            ->first();

        if ($existingNotulen) {
            return redirect()->back()->with('error', 'Agenda ini sudah memiliki notulen lain.');
        }

        // Gabungkan isi notulen menjadi string tanpa penomoran
        $isiNotulens = $this->request->getPost('isi_notulens');
        $isiNotulensFormatted = implode("\n", $isiNotulens);

        $this->notulenModel->update($id, [
            'agenda_id' => $agendaId,
            'isi_notulens' => $isiNotulensFormatted,
        ]);

        return redirect()->to('/notulen')->with('success', 'Notulen berhasil diperbarui.');
    }
    // Hapus notulen
    public function delete($id)
    {
        $notulen = $this->notulenModel->find($id);

        if (!$notulen) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Notulen tidak ditemukan.");
        }

        $this->notulenModel->delete($id);

        return redirect()->to('/notulen')->with('success', 'Notulen berhasil dihapus.');
    }
    public function detail($id)
    {
        // Query untuk mengambil data notulen beserta agenda dan dokumentasi
        $builder = $this->db->table('notulens');
        $builder->select('notulens.isi_notulens, agendas.judul, dokumentasi_notulens.image');
        $builder->join('agendas', 'agendas.id = notulens.agenda_id');
        $builder->join('dokumentasi_notulens', 'dokumentasi_notulens.notulen_id = notulens.id', 'left');
        $builder->where('notulens.id', $id);
        $result = $builder->get()->getResultArray();

        if (empty($result)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Notulen tidak ditemukan');
        }

        // Mengatur data untuk diteruskan ke view
        $data = [
            'title'       => 'Detail Notulen',
            'notulen'     => $result[0], // Mengambil data pertama (karena ID unik)
            'dokumentasi' => $result,    // Untuk menampilkan semua gambar jika ada banyak
        ];

        // Memproses isi_notulens menjadi array berdasarkan garis baru
        $data['notulen']['isi_notulens'] = explode("\n", $result[0]['isi_notulens']); // Pisahkan berdasarkan '\n'


        // Memuat view dengan data
        echo view('template/header', $data);
        echo view('template/top_menu');
        echo view('template/side_menu');
        echo view('notulen/detail', $data);
        echo view('template/footer');
    }

    //REST API - get all
    public function getAllApi()
    {
        $builder = $this->db->table("notulens");
        $builder->select('notulens.id,agendas.judul, notulens.isi_notulens');
        $builder->join('agendas', 'agendas.id=notulens.agenda_id');
        $notulens = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $notulens
        ]);
    }

    public function showApi($id)
    {
        $builder = $this->db->table('notulens');
        $builder->select('notulens.id, agendas.judul, notulens.isi_notulens');
        $builder->join('agendas', 'agendas.id=notulens.agenda_id');
        $builder->where('notulens.id', $id);
        $notulen = $builder->get()->getRowArray();

        if (!$notulen) {
            return $this->response->setJSON([]);
        }
        return $this->response->setJSON([
            'status' => 'succes',
            'data' => $notulen
        ]);
    }

    public function storeApi(){
        $input=$this->request->getJSON();

        if (!isset($input->agenda_id)|| !isset($input->isi_notulens)){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Data Agenda_id dan isi_notulens harus diisi'
            ]);

        }
        $existingNotulen=$this->notulenModel->where('agenda_id',$input->agenda_id)->first();
        if ($existingNotulen){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Agenda ini sudah memiliki notulen'
            ]);
        }
        $this->notulenModel->save([
            'agenda_id'=>$input->agenda_id,
            'isi_notulens'=>implode("\n",$input->isi_notulens)
        ]);
        return $this->response->setJSON([
            'status'=>'succes',
            'message'=>'Notulen Berhasil dibuat'
        ]);
    }

    public function updateApi($id)
    {
        $input=$this->request->getJSON();

        if(!isset($input->agenda_id) || !isset($input->isi_notulens)){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Data Agenda_id dan isi_notulens harus di isi'
            ]);
        }

        $existingNotulen = $this->notulenModel->where('agenda_id',$input->agenda_id)->where('id !=',$id)->first();
        if($existingNotulen){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Agenda ini sudah memiliki notulen lain'
            ]);
        }
        $this->notulenModel->update($id,[
            'agenda_id'=>$input->agenda_id,
            'isi_notulens'=>implode("\n",$input->isi_notulens)
        ]);

        return $this->response->setJSON([
            'status'=>'succes',
            'message'=>'Notulen Berhasil Diperbarui'
        ]);
    }

    public function deleteApi($id)
    {
        $notulen=$this->notulenModel->find($id);
        if(!$notulen){
            return $this->response->setJSON([
                'status'=>'error',
                'message'=>'Notulen Tidak ditemukan'
            ]);
        }
        $this->notulenModel->delete($id);
        
        return $this->response->setJSON([
            'status'=>'success',
            'message'=>'Notulen Berhasil dihapus'
        ]);
    }
}
