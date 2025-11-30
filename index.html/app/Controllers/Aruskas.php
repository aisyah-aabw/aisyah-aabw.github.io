<?php

namespace App\Controllers;

use App\Models\ModelTransaksi;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Aruskas extends BaseController
{
    protected $objTransaksi;
    protected $objStatus;
    protected $objAkun3;
    protected $objNilai;
    protected $db;
    protected $request;

    function __construct()
    {
        $this->objTransaksi = new ModelTransaksi();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        $rowdata = $this->objTransaksi->get_aruskas($tglawal, $tglakhir);

        $data = [
            'dttransaksi' => $rowdata,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ];

        // echo "<pre>";
        // echo print_r($data);
        // echo "</pre>";
        // die;

        return view('aruskas/index', $data);
    }

    public function aruskaspdf()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        $rowdata = $this->objTransaksi->get_aruskas($tglawal, $tglakhir);

        $saldo_awal = $this->objTransaksi->get_saldo_kas_awal($tglawal);

        $data = [
            'dttransaksi' => $rowdata,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'saldo_awal' => $saldo_awal,
        ];

        // Load HTML view
        $html = view('aruskas/aruskaspdf', $data);

        // Gunakan TCPDF
        $pdf = new \TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $pdf->Output('Laporan_Arus_Kas.pdf', 'I');
        exit();
    }
}
