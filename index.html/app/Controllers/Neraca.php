<?php

namespace App\Controllers;

use App\Models\ModelAkun3;
use App\Models\ModelNilai;
use App\Models\ModelStatus;
use App\Models\ModelTransaksi;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Neraca extends BaseController
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
        $this->objNilai = new ModelNilai();
        $this->objAkun3 = new ModelAkun3();
        $this->objStatus = new ModelStatus();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Ambil tanggal dari input user
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Ambil data transaksi untuk neraca
        $dttransaksi = $this->objTransaksi->get_neraca($tglawal, $tglakhir);

        // Inisialisasi variabel
        $modal_awal = 0;
        $pendapatan = 0;
        $prive = 0;
        $jumdebit = 0;
        $jumdebits = 0;
        $beban = 0;

        // Loop semua data transaksi
        foreach ($dttransaksi as $row) {
            // Modal awal (kode akun 3101)
            if ($row->kode_akun3 == 3101) {
                $modal_awal = $row->jumkredit;
            }

            // Pendapatan (kode akun 4101)
            if ($row->kode_akun3 == 4101) {
                $pendapatan = $row->jumkredit + $row->jumkredits;
            }

            // Prive (kode akun 3201)
            if ($row->kode_akun3 == 3201) {
                $prive = $row->jumdebit;
            }

            // Beban (kode akun mulai dengan 5)
            if (substr($row->kode_akun3, 0, 1) == '5') {
                $jumdebit += $row->jumdebit;
                $jumdebits += $row->jumdebits;
            }
        }

        // Hitung hasil akhir
        $beban = $jumdebit + $jumdebits;
        $laba_rugi = $pendapatan - $beban;   // Laba bersih periode berjalan
        $laba_ditahan = $laba_rugi - $prive; // Laba ditahan = laba - prive
        $total_ekuitas = $modal_awal + $laba_ditahan; // Total ekuitas akhir

        // Kirim data ke view
        $data = [
            'dttransaksi'   => $dttransaksi,
            'tglawal'       => $tglawal,
            'tglakhir'      => $tglakhir,
            'modal_awal'    => $modal_awal,
            'pendapatan'    => $pendapatan,
            'prive'         => $prive,
            'beban'         => $beban,
            'laba_rugi'     => $laba_rugi,
            'laba_ditahan'  => $laba_ditahan,
            'total_ekuitas' => $total_ekuitas
        ];

        // Untuk debug (kalau mau cek hasilnya)
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;

        // Tampilkan ke view
        return view('neraca/index', $data);
    }

    public function neracapdf()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Ambil data transaksi untuk neraca
        $rowdata = $this->objTransaksi->get_neraca($tglawal, $tglakhir);

        // === Perhitungan Ekuitas (seperti di index) ===
        $modal_awal = 0;
        $pendapatan = 0;
        $prive = 0;
        $jumdebit = 0;
        $jumdebits = 0;
        $beban = 0;

        foreach ($rowdata as $row) {
            if ($row->kode_akun3 == 3101) { // Modal awal
                $modal_awal = $row->jumkredit;
            }
            if ($row->kode_akun3 == 4101) { // Pendapatan
                $pendapatan = $row->jumkredit + $row->jumkredits;
            }
            if ($row->kode_akun3 == 3201) { // Prive
                $prive = $row->jumdebit;
            }
            if (substr($row->kode_akun3, 0, 1) == 5) { // Beban
                $jumdebit += $row->jumdebit;
                $jumdebits += $row->jumdebits;
            }
        }

        $beban = $jumdebit + $jumdebits;
        $laba_rugi = $pendapatan - $beban;
        $laba_ditahan = $laba_rugi - $prive;
        $total_ekuitas = $modal_awal + $laba_ditahan;

        // Data untuk dikirim ke view PDF
        $data['dttransaksi'] = $rowdata;
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        $data['modal_awal'] = $modal_awal;
        $data['laba_rugi'] = $laba_rugi;
        $data['prive'] = $prive;
        $data['laba_ditahan'] = $laba_ditahan;
        $data['total_ekuitas'] = $total_ekuitas;

        // === Generate HTML dari view ===
        $html = view('neraca/neracapdf', $data);

        // === Konfigurasi TCPDF ===
        $pdf = new \TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->SetFont('helvetica', '', 9);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        // Bersihkan buffer output agar tidak error
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Output PDF ke browser
        $pdf->Output('Laporan_Posisi_Keuangan.pdf', 'I');
        exit();
    }
}
