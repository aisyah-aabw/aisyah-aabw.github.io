<?php

namespace App\Controllers;


use App\Models\ModelAkun2;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Controller;
use CodeIgniter\Database\Config;


/** @var \CodeIgniter\HTTP\IncomingRequest $request */
class Akun2 extends ResourceController
{
    protected $db;
    protected $objAkun2;

    // inisiasi object data
    public function __construct()
    {
        $this->db = \Config\Database::connect();   // koneksi database
        $this->objAkun2 = new \App\Models\ModelAkun2(); // model akun2
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        // $data['dtakun2'] = $this->objAkun2->findAll();
        $data['dtakun2'] = $this->objAkun2->ambilrelasi();

        return view('akun2/index', $data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('akun1s'); // kalau maksudnya ambil data akun1
        $query = $builder->get();
        $data['dtakun1'] = $query->getResult();
        return view('akun2/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        // Ambil data dari form
        $data = [
            'kode_akun2' => $this->request->getPost('kode_akun2'),
            'nama_akun2' => $this->request->getPost('nama_akun2'),
            'kode_akun1' => $this->request->getPost('kode_akun1'),
        ];



        // Simpan data ke tabel akun2 lewat model
        $this->objAkun2->insert($data);

        // Redirect ke index akun2 setelah sukses
        return redirect()->to('/akun2')->with('success', 'Data Akun2 berhasil ditambahkan!');
    }


    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        $builder = $this->db->table('akun1s');
        $query = $builder->get();

        $akun2 = $this->objAkun2->find($id);
        if (is_object($akun2)) {
            $data['dtakun2'] = $akun2;
            $data['dtakun1'] = $query->getResult();
            return view('akun2/edit', $data);
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
            'kode_akun2' => $this->request->getPost('kode_akun2'),
            'nama_akun2' => $this->request->getPost('nama_akun2'),
            'kode_akun1' => $this->request->getPost('kode_akun1'),
        ];

        // update(id, data)
        if ($this->objAkun2->update($id, $data)) {
            return redirect()->to('/akun2')->with('success', 'Data Akun2 berhasil diUpdate!');
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
        $this->objAkun2->delete($id);
        return redirect()->to(site_url('akun2'))->with('success', 'Data Berhasil Di Hapus');
    }
}
