<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Penyesuaian extends ResourceController
{
    protected $db;
    protected $objTransaksi;
    protected $objNilai;
    protected $objAkun3;
    protected $objStatus;
    protected $objPenyesuaian;
    protected $objNilaiPenyesuaian;
    protected $ModelPenyesuaian;
    protected $ModelNilaiPenyesuaian;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = new \App\Models\ModelTransaksi();
        $this->objNilai = new \App\Models\ModelNilai();
        $this->objAkun3 = new \App\Models\ModelAkun3();
        $this->objStatus = new \App\Models\ModelStatus();
        $this->objPenyesuaian = new \App\Models\ModelPenyesuaian();
        $this->objNilaiPenyesuaian = new \App\Models\ModelNilaiPenyesuaian();
        $this->ModelPenyesuaian = new \App\Models\ModelPenyesuaian();
        $this->ModelNilaiPenyesuaian = new \App\Models\ModelNilaiPenyesuaian();
    }


    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtpenyesuaian'] = $this->objPenyesuaian->findAll();
        return view('penyesuaian/index', $data);
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
        $penyesuaian = $this->objPenyesuaian->find($id);

        if (!$penyesuaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // ambil nilai penyesuaian dari model anak
        $nilaipenyesuaian = $this->db->table('tbl_nilaipenyesuaian')
            ->select('tbl_nilaipenyesuaian.*, akun3s.nama_akun3')
            ->join('akun3s', 'akun3s.kode_akun3 = tbl_nilaipenyesuaian.kode_akun3', 'left')
            ->where('tbl_nilaipenyesuaian.id_penyesuaian', $id)
            ->get()
            ->getResult();


        $data = [
            'dtpenyesuaian'       => $penyesuaian,
            'dtnilaipenyesuaian'  => $nilaipenyesuaian,
            'dtakun3'             => $this->objAkun3->findAll(),
            'dtstatus'            => $this->objStatus->findAll()
        ];

        return view('penyesuaian/show', $data);
    }


    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        return view('penyesuaian/new');
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */

    public function create()
    {
        // 1️⃣ Ambil data dari form untuk tabel penyesuaian
        $data1 = [
            'tanggal'   => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'nilai'     => $this->request->getVar('nilai'),
            'waktu'     => $this->request->getVar('waktu'),
            'jumlah'    => $this->request->getVar('jumlah'),
        ];

        // 2️⃣ Simpan data ke tbl_penyesuaian (tabel induk)
        $this->ModelPenyesuaian->insert($data1);
        $id_penyesuaian = $this->ModelPenyesuaian->getInsertID(); // ambil id terakhir

        // 3️⃣ Ambil data detail untuk tabel tbl_nilaipenyesuaian (tabel anak)
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $nama_akun3 = $this->request->getVar('nama_akun3');
        $debit      = $this->request->getVar('debit');
        $kredit     = $this->request->getVar('kredit');
        $id_status  = $this->request->getVar('id_status');

        // 4️⃣ Loop simpan data anak
        for ($i = 0; $i < count($kode_akun3); $i++) {
            $data2 = [
                'id_penyesuaian' => $id_penyesuaian,
                'kode_akun3'     => $kode_akun3[$i],


                'debit'          => $debit[$i],
                'kredit'         => $kredit[$i],
                'id_status'      => $id_status[$i],
            ];

            $this->ModelNilaiPenyesuaian->insert($data2);
        }

        // 5️⃣ Redirect setelah semua data tersimpan
        return redirect()->to('/penyesuaian')->with('success', 'Data penyesuaian berhasil disimpan!');
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
        $penyesuaian = $this->objPenyesuaian->find($id);

        if (!$penyesuaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // ✅ ambil data anak dari tabel tbl_nilaipenyesuaian pakai model yang benar
        $nilaipenyesuaian = $this->objNilaiPenyesuaian->ambilrelasiid($id);

        $data = [
            'dtpenyesuaian'       => $penyesuaian,
            'dtnilaipenyesuaian'  => $nilaipenyesuaian,
            'dtakun3'             => $this->objAkun3->findAll(),
            'dtstatus'            => $this->objStatus->findAll()
        ];

        return view('penyesuaian/edit', $data);
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
        // ✅ 1. Update data utama (tbl_penyesuaian)
        $data1 = [
            'tanggal'   => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'nilai'     => $this->request->getVar('nilai'),
            'waktu'     => $this->request->getVar('waktu'),
            'jumlah'    => $this->request->getVar('jumlah'),
        ];

        $this->db->table('tbl_penyesuaian')
            ->where('id_penyesuaian', $id)
            ->update($data1);


        // ✅ 2. Ambil data anak dari form
        $ids        = $this->request->getVar('id');
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit      = $this->request->getVar('debit');
        $kredit     = $this->request->getVar('kredit');
        $id_status  = $this->request->getVar('id_status');

        // ✅ 3. Siapkan array untuk batch update
        $result = [];
        foreach ($ids as $key => $value) {
            $result[] = [
                'id'         => $ids[$key],
                'kode_akun3' => $kode_akun3[$key],
                'debit'      => $debit[$key],
                'kredit'     => $kredit[$key],
                'id_status'  => $id_status[$key],
            ];
        }

        // ✅ 4. Update data anak (tbl_nilaipenyesuaian)
        if (!empty($result)) {
            $this->objNilaiPenyesuaian->updateBatch($result, 'id');
        }

        // ✅ 5. Redirect setelah sukses
        return redirect()
            ->to(site_url('penyesuaian'))
            ->with('success', 'Data penyesuaian berhasil diupdate!');
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
        $this->objPenyesuaian->delete($id);
        return redirect()->to(site_url('penyesuaian'))->with('success', 'Data Berhasil Di Hapus');
    }
}
