<?php

namespace App\Controllers;

use App\Models\ModelTransaksi;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ModelAkun3;
use App\Models\ModelStatus;
use App\Models\ModelNilai;


class Transaksi extends ResourceController
{
    protected $objTransaksi;
    protected $db;
    protected $request;
    protected $objNilai;
    protected $objAkun3;
    protected $objStatus;


    function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = new ModelTransaksi();
        $this->objNilai = new ModelNilai();
        $this->objAkun3 = new ModelAkun3();
        $this->objStatus = new ModelStatus();
    }
    /*** 
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dttransaksi'] = $this->objTransaksi->findAll();
        return view('transaksi/index', $data);
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
        $transaksi = $this->objTransaksi->find($id);
        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilai->ambilrelasiid($id);
        $data['dtnilai'] = $nilai;

        if (is_object($transaksi)) {
            $data['dtakun3'] = $akun3;
            $data['dtstatus'] = $status;
            $data['dttransaksi'] = $transaksi;

            return view('transaksi/show', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $akunModel = new \App\Models\ModelAkun3();
        $statusModel = new \App\Models\ModelStatus();

        $data = [
            'dtakun3' => $akunModel->findAll(),
            'status_list' => $statusModel->findAll(),
        ];

        return view('transaksi/new', $data);
    }


    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data1 = [
            // ini untuk data tbl_transaksi
            // 'kwitansi' => $this->request->getVar('kwitansi'),
            'kwitansi' => $this->objTransaksi->noKwitansi(),
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'ketjurnal' => $this->request->getVar('ketjurnal'),
        ];
        // simpan data ke tbl_transaksi
        $this->db->table('tbl_transaksi')->insert($data1);

        // kita ambil ID dari tbl_transaksi
        $id_transaksi = $this->objTransaksi->insertID();
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit = $this->request->getVar('debit');
        $kredit = $this->request->getVar('kredit');
        $id_status = $this->request->getVar('id_status');

        for ($i = 0; $i < count($kode_akun3); $i++) {
            $data2[] = [
                'id_transaksi' => $id_transaksi,
                'kode_akun3' => $kode_akun3[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'id_status' => $id_status[$i],
            ];
        }

        $this->objNilai->insertBatch($data2);
        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil di Simpan');
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
        $transaksi = $this->objTransaksi->find($id);
        $akun3 = $this->objAkun3->findAll();
        $status = $this->objStatus->findAll();
        $nilai = $this->objNilai->ambilrelasiid($id);
        $data['dtnilai'] = $nilai;

        if (is_object($transaksi)) {
            $data['dtakun3'] = $akun3;
            $data['dtstatus'] = $status;
            $data['dttransaksi'] = $transaksi;

            return view('transaksi/edit', $data);
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
        $data1 = [
            'tanggal' => $this->request->getVar('tanggal'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'ketjurnal' => $this->request->getVar('ketjurnal'),
        ];
        // simpan data ke tbl_transaksi
        $this->db->table('tbl_transaksi')->where(['id_transaksi' => $id])->update($data1);

        $ids = $this->request->getVar('id_nilai');
        $kode_akun3 = $this->request->getVar('kode_akun3');
        $debit = $this->request->getVar('debit');
        $kredit = $this->request->getVar('kredit');
        $id_status = $this->request->getVar('id_status');

        foreach ($ids as $key => $value) {
            $result[] = [
                'id_nilai' => $ids[$key],
                'kode_akun3' => $kode_akun3[$key],
                'debit' => $debit[$key],
                'kredit' => $kredit[$key],
                'id_status' => $id_status[$key],
            ];
        }
        $this->objNilai->updateBatch($result, 'id_nilai');
        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil di Update');
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
        $this->objNilai->where(['id_transaksi' => $id])->delete();
        $this->objTransaksi->delete($id);
        return redirect()->to(site_url('transaksi'))->with('success', 'Data Berhasil Di Hapus');
    }

    public function akun3()
    {
        $akun3 = model(ModelAkun3::class);
        $result = $akun3->findAll();
        return $this->response->setJSON($result);
    }
    public function status()
    {
        $status = model(ModelStatus::class);
        $result = $status->findAll();
        return $this->response->setJSON($result);
    }
}
