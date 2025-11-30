<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ModelNilai;
use App\Models\ModelTransaksi;
use App\Models\ModelAkun3;

use CodeIgniter\Controller;
use TCPDF;

class Posting extends BaseController
{
    protected $db;
    protected $objTransaksi;
    protected $objNilai;
    protected $objAkun3;
    protected $objStatus;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = new \App\Models\ModelTransaksi();
        $this->objNilai = new \App\Models\ModelNilai();
        $this->objAkun3 = new \App\Models\ModelAkun3();
        $this->objStatus = new \App\Models\ModelStatus();
    }
    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $kode_akun3 = $this->request->getVar('kode_akun3') ? $this->request->getVar('kode_akun3') : '';

        $rowdata = $this->objTransaksi->get_posting($tglawal, $tglakhir, $kode_akun3);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['kode_akun3'] = $kode_akun3;
        $data['dtakun3'] = $this->objAkun3->ambilrelasi();
        return view('posting/index', $data);
    }
    public function postingpdf()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $kode_akun3 = $this->request->getVar('kode_akun3') ? $this->request->getVar('kode_akun3') : '';

        $rowdata = $this->objTransaksi->get_posting($tglawal, $tglakhir, $kode_akun3);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['kode_akun3'] = $kode_akun3;
        $data['dtakun3'] = $this->objAkun3->ambilrelasi();
        $html = view('posting/postingpdf', $data);

        // create new PDF document
        $pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set font
        $pdf->setFont('helvetica', '', 8);
        // add a page
        $pdf->AddPage();
        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');
        // This method has several options, check the source code documentation for more information.
        $this->response->setContentType('application/pdf');
        $pdf->Output('posting.pdf', 'I');
    }
}
