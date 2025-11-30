<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ModelTransaksi;
use App\Models\ModelAkun3;
use App\Models\ModelStatus;
use App\Models\ModelNilai;
use TCPDF;

class NeracaSaldo extends BaseController
{
    protected $objNilai;
    protected $objAkun3;
    protected $objStatus;
    protected $objTransaksi;

    function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->objTransaksi = new ModelTransaksi();
        $this->objNilai = new ModelNilai();
        $this->objAkun3 = new ModelAkun3();
        $this->objStatus = new ModelStatus();
    }
    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        $rowdata = $this->objTransaksi->get_neracasaldo($tglawal, $tglakhir);

        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        return view('neracasaldo/index', $data);
    }

    public function neracasaldopdf()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        $rowdata = $this->objTransaksi->get_neracasaldo($tglawal, $tglakhir);
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $html = view('neracasaldo/neracasaldopdf', $data);

        $pdf = new \TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->SetFont('helvetica', '', 10);


        $pdf->AddPage();

        $pdf->writeHTML($html, true, false, true, false, '');

        // $this->response->setContentType('application/pdf');
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $pdf->Output('neracasaldo.pdf', 'I');
        exit();
    }
}
