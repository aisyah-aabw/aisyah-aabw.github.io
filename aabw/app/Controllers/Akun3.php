<?php

namespace App\Controllers;

use App\Models\ModelAkun2;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use CodeIgniter\Database\Config;

class Akun3 extends BaseController
{
    protected $db;
    protected $objAkun2;
    protected $objAkun3;

    // inisiasi object data
    public function __construct()
    {
        $this->objAkun2 = new \App\Models\ModelAkun2(); // model akun2
        $this->objAkun3 = new \App\Models\ModelAkun3(); // model akun3
        $this->db = \Config\Database::connect();   // koneksi database

    }
    public function index()
    {
        // $data['dtakun3'] = $this->objAkun3->findAll();
        $data['dtakun3'] = $this->objAkun3->ambilrelasi();
        return view('akun3/index', $data);
    }
    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('akun1s'); // kalau maksudnya ambil data akun1
        $query = $builder->get();
        $data['dtakun2'] = $this->objAkun2->findall();
        $data['dtakun1'] = $query->getResult();
        return view('akun3/new', $data);
    }
    public function create()
    {
        // Ambil data dari form
        $data = [
            'kode_akun3' => $this->request->getPost('kode_akun3'),
            'nama_akun3' => $this->request->getPost('nama_akun3'),
            'kode_akun2' => $this->request->getPost('kode_akun2'),
            'kode_akun1' => $this->request->getPost('kode_akun1'),
        ];



        // Simpan data ke tabel akun2 lewat model
        $this->objAkun3->insert($data);

        // Redirect ke index akun2 setelah sukses
        return redirect()->to('/akun3')->with('success', 'Data Akun3 berhasil ditambahkan!');
    }
    public function edit($id = null)
    {
        $builder = $this->db->table('akun1s');
        $query = $builder->get();

        $akun2 = $this->objAkun2->findAll($id);
        $akun3 = $this->objAkun3->find($id);

        if (is_object($akun3)) {
            $data['dtakun3'] = $akun3;
            $data['dtakun2'] = $akun2;
            $data['dtakun1'] = $query->getResult();
            return view('akun3/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */

    public function update($id = null)
    {
        $data = [
            'kode_akun3' => $this->request->getPost('kode_akun3'),
            'nama_akun3' => $this->request->getPost('nama_akun3'),
            'kode_akun2' => $this->request->getPost('kode_akun2'),
            'kode_akun1' => $this->request->getPost('kode_akun1'),

        ];

        // update(id, data)
        if ($this->objAkun3->update($id, $data)) {
            return redirect()->to('/akun3')->with('success', 'Data Akun3 berhasil di Update!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Update gagal!');
        }
    }


    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->objAkun3->delete($id);
        return redirect()->to(site_url('akun3'))->with('success', 'Data Berhasil Di Hapus');
    }
}
