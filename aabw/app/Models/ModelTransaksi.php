<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelTransaksi extends Model
{
    protected $table            = 'tbl_transaksi';
    protected $primaryKey       = 'id_transaksi';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kwitansi', 'tanggal', 'deskripsi', 'ketjurnal'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    //     protected $validationRules      = [];
    //     protected $validationMessages   = [];
    //     protected $skipValidation       = false;
    //     protected $cleanValidationRules = true;

    //     // Callbacks
    //     protected $allowCallbacks = true;
    //     protected $beforeInsert   = [];
    //     protected $afterInsert    = [];
    //     protected $beforeUpdate   = [];
    //     protected $afterUpdate    = [];
    //     protected $beforeFind     = [];
    //     protected $afterFind      = [];
    //     protected $beforeDelete   = [];
    //     protected $afterDelete    = [];




    public function noKwitansi()
    {

        $number = $this->db->table('tbl_transaksi')->select('RIGHT(tbl_transaksi.kwitansi,4) as kwitansi', FALSE)
            ->orderBy('kwitansi', 'DESC')->limit(1)->get()->getRowArray();

        if ($number == null) {
            $no = 1;
        } else {
            $no = intval($number['kwitansi']) + 1;
        }
        $nomor_kwitansi = str_pad($no, 4, "0", STR_PAD_LEFT);
        return $nomor_kwitansi;
    }
    public function get_jurnalumum($tglawal, $tglakhir)
    {
        $sql = $this->db->table('tbl_nilai')
            ->join('tbl_transaksi', 'tbl_transaksi.id_transaksi=tbl_nilai.id_transaksi')
            ->join('akun3s', 'akun3s.kode_akun3=tbl_nilai.kode_akun3')
            ->orderBy('id_nilai');
        if ($tglawal && $tglakhir) {
            $sql->where('tanggal >=', $tglawal)->where('tanggal<=', $tglakhir);
        }
        return $sql->get()->getResultObject();
    }
    public function get_posting($tglawal, $tglakhir, $kode_akun3)
    {
        $sql = $this->db->table('tbl_nilai')
            ->join('tbl_transaksi', 'tbl_transaksi.id_transaksi=tbl_nilai.id_transaksi')
            ->join('akun3s', 'akun3s.kode_akun3=tbl_nilai.kode_akun3')
            ->orderBy('akun3s.kode_akun3');
        if ($tglawal && $tglakhir) {
            $sql->where('tanggal >=', $tglawal)->where('tanggal<=', $tglakhir)->where('tbl_nilai.kode_akun3=', $kode_akun3);
        }
        return $sql->get()->getResultObject();
    }
    public function get_jpenyesuaian($tglawal, $tglakhir)
    {
        $sql = $this->db->table('tbl_nilaipenyesuaian')
            ->join('tbl_penyesuaian', 'tbl_penyesuaian.id_penyesuaian=tbl_nilaipenyesuaian.id_penyesuaian')
            ->join('akun3s', 'akun3s.kode_akun3=tbl_nilaipenyesuaian.kode_akun3')
            ->selectSum('debit', 'jumdebit')
            ->selectSum('kredit', 'jumkredit')
            ->select('akun3s.kode_akun3, akun3s.nama_akun3, tbl_penyesuaian.tanggal')
            ->groupBy('akun3s.kode_akun3');
        // ->orderBy('id_nilai');
        if ($tglawal && $tglakhir) {
            $sql->where('tanggal >=', $tglawal)->where('tanggal <=', $tglakhir);
        }
        $query =  $sql->get()->getResultObject();
        return $query;
    }
    public function get_neracasaldo($tglawal, $tglakhir)
    {
        $sql = $this->db->table('tbl_nilai')
            ->join('tbl_transaksi', 'tbl_transaksi.id_transaksi=tbl_nilai.id_transaksi')
            ->join('akun3s', 'akun3s.kode_akun3=tbl_nilai.kode_akun3')
            ->selectSum('debit', 'jumdebit')
            ->selectSum('kredit', 'jumkredit')
            ->select('akun3s.kode_akun3, akun3s.nama_akun3, tbl_transaksi.tanggal, debit, kredit')
            ->groupBy('akun3s.kode_akun3');
        // ->orderBy('id_nilai');
        if ($tglawal && $tglakhir) {
            $sql->where('tanggal >=', $tglawal)->where('tanggal <=', $tglakhir);
        }
        $query =  $sql->get()->getResultObject();
        return $query;
    }

    public function get_neracalajur($tglawal, $tglakhir)
    {
        $where1 = '';
        $where2 = '';

        if ($tglawal && $tglakhir) {
            $where1 = "where tb3.tanggal >= '" . $tglawal . "' and tb3.tanggal <= '" . $tglakhir . "' ";
            $where2 = "where tb4.tanggal >= '" . $tglawal . "' and tb4.tanggal <= '" . $tglakhir . "' ";
        }

        $sql = $this->db->query("
        SELECT * FROM (
            SELECT 
                tbak.nama_akun3,
                tbl.kode_akun3,
                tb3.tanggal AS tanggal,
                SUM(tbl.debit) AS jumdebit,
                SUM(tbl.kredit) AS jumkredit,
                COALESCE(tb2.debit, 0) AS jumdebits,
                COALESCE(tb2.kredit, 0) AS jumkredits
            FROM tbl_nilai AS tbl
            JOIN tbl_transaksi AS tb3 ON tb3.id_transaksi = tbl.id_transaksi
            LEFT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tbl.kode_akun3 = tbak.kode_akun3
            $where1
            GROUP BY tbl.kode_akun3

            UNION

            SELECT 
                tbak.nama_akun3,
                tb2.kode_akun3,
                tb4.tanggal AS tanggal,
                SUM(COALESCE(tbl.debit, 0)) AS jumdebit,
                SUM(COALESCE(tbl.kredit, 0)) AS jumkredit,
                tb2.debit AS jumdebits,
                tb2.kredit AS jumkredits
            FROM tbl_nilai AS tbl
            RIGHT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tb2.kode_akun3 = tbak.kode_akun3
            JOIN tbl_penyesuaian AS tb4 ON tb4.id_penyesuaian = tb2.id_penyesuaian
            $where2
            GROUP BY tb2.kode_akun3
        ) AS tbl_new
        GROUP BY tbl_new.kode_akun3
    ");

        return $sql->getResult();   // ← WAJIB
    }

    public function get_labarugi($tglawal, $tglakhir)
    {
        $where1 = '';
        $where2 = '';

        if ($tglawal && $tglakhir) {
            $where1 = "where tb3.tanggal >= '" . $tglawal . "' and tb3.tanggal <= '" . $tglakhir . "' ";
            $where2 = "where tb4.tanggal >= '" . $tglawal . "' and tb4.tanggal <= '" . $tglakhir . "' ";
        }
        $sql = $this->db->query("
        SELECT * FROM (
            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tbl.kode_akun3,
                tb3.tanggal AS tanggal,
                SUM(tbl.debit) AS jumdebit,
                SUM(tbl.kredit) AS jumkredit,
                COALESCE(tb2.debit, 0) AS jumdebits,
                COALESCE(tb2.kredit, 0) AS jumkredits
            FROM tbl_nilai AS tbl
            JOIN tbl_transaksi AS tb3 ON tb3.id_transaksi = tbl.id_transaksi
            LEFT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tbl.kode_akun3 = tbak.kode_akun3
            " . $where1 . "
            GROUP BY tbl.kode_akun3

            UNION

            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tb2.kode_akun3,
                tb4.tanggal AS tanggal,
                SUM(COALESCE(tbl.debit, 0)) AS jumdebit,
                SUM(COALESCE(tbl.kredit, 0)) AS jumkredit,
                tb2.debit AS jumdebits,
                tb2.kredit AS jumkredits
            FROM tbl_nilai AS tbl
            RIGHT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tb2.kode_akun3 = tbak.kode_akun3
            JOIN tbl_penyesuaian AS tb4 ON tb4.id_penyesuaian = tb2.id_penyesuaian
            " . $where2 . "
            GROUP BY tb2.kode_akun3
        ) AS tbl_new where tbl_new.kode_akun1 >= 4
        GROUP BY tbl_new.kode_akun3
    ");

        return $sql->getResultObject();
    }
    public function get_pmodal($tglawal, $tglakhir)
    {
        $where1 = '';
        $where2 = '';

        if ($tglawal && $tglakhir) {
            $where1 = "where tb3.tanggal >= '" . $tglawal . "' and tb3.tanggal <= '" . $tglakhir . "' ";
            $where2 = "where tb4.tanggal >= '" . $tglawal . "' and tb4.tanggal <= '" . $tglakhir . "' ";
        }
        $sql = $this->db->query("
        SELECT * FROM (
            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tbl.kode_akun3,
                tb3.tanggal AS tanggal,
                SUM(tbl.debit) AS jumdebit,
                SUM(tbl.kredit) AS jumkredit,
                COALESCE(tb2.debit, 0) AS jumdebits,
                COALESCE(tb2.kredit, 0) AS jumkredits
            FROM tbl_nilai AS tbl
            JOIN tbl_transaksi AS tb3 ON tb3.id_transaksi = tbl.id_transaksi
            LEFT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tbl.kode_akun3 = tbak.kode_akun3
            " . $where1 . "
            GROUP BY tbl.kode_akun3

            UNION

            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tb2.kode_akun3,
                tb4.tanggal AS tanggal,
                SUM(COALESCE(tbl.debit, 0)) AS jumdebit,
                SUM(COALESCE(tbl.kredit, 0)) AS jumkredit,
                tb2.debit AS jumdebits,
                tb2.kredit AS jumkredits
            FROM tbl_nilai AS tbl
            RIGHT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tb2.kode_akun3 = tbak.kode_akun3
            JOIN tbl_penyesuaian AS tb4 ON tb4.id_penyesuaian = tb2.id_penyesuaian
            " . $where2 . "
            GROUP BY tb2.kode_akun3
        ) AS tbl_new
        GROUP BY tbl_new.kode_akun3
    ");

        return $sql->getResultObject();
    }

    public function get_neraca($tglawal, $tglakhir)
    {
        $where1 = '';
        $where2 = '';

        if ($tglawal && $tglakhir) {
            $where1 = "where tb3.tanggal >= '" . $tglawal . "' and tb3.tanggal <= '" . $tglakhir . "' ";
            $where2 = "where tb4.tanggal >= '" . $tglawal . "' and tb4.tanggal <= '" . $tglakhir . "' ";
        }
        $sql = $this->db->query("
        SELECT * FROM (
            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tbl.kode_akun3,
                tb3.tanggal AS tanggal,
                SUM(tbl.debit) AS jumdebit,
                SUM(tbl.kredit) AS jumkredit,
                COALESCE(tb2.debit, 0) AS jumdebits,
                COALESCE(tb2.kredit, 0) AS jumkredits
            FROM tbl_nilai AS tbl
            JOIN tbl_transaksi AS tb3 ON tb3.id_transaksi = tbl.id_transaksi
            LEFT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tbl.kode_akun3 = tbak.kode_akun3
            " . $where1 . "
            GROUP BY tbl.kode_akun3

            UNION

            SELECT 
                tbak.nama_akun3, tbak.kode_akun2, tbak.kode_akun1,
                tb2.kode_akun3,
                tb4.tanggal AS tanggal,
                SUM(COALESCE(tbl.debit, 0)) AS jumdebit,
                SUM(COALESCE(tbl.kredit, 0)) AS jumkredit,
                tb2.debit AS jumdebits,
                tb2.kredit AS jumkredits
            FROM tbl_nilai AS tbl
            RIGHT JOIN tbl_nilaipenyesuaian AS tb2 ON tbl.kode_akun3 = tb2.kode_akun3
            JOIN akun3s AS tbak ON tb2.kode_akun3 = tbak.kode_akun3
            JOIN tbl_penyesuaian AS tb4 ON tb4.id_penyesuaian = tb2.id_penyesuaian
            " . $where2 . "
            GROUP BY tb2.kode_akun3
        ) AS tbl_new where tbl_new.kode_akun1 <=7
        GROUP BY tbl_new.kode_akun3
    ");

        return $sql->getResultObject();
    }

    public function get_aruskas($tglawal, $tglakhir)
    {
        $sql = $this->db->table('tbl_nilai')
            ->join('tbl_transaksi', 'tbl_transaksi.id_transaksi=tbl_nilai.id_transaksi')
            ->join('akun3s', 'akun3s.kode_akun3=tbl_nilai.kode_akun3')
            ->select('akun3s.kode_akun3, akun3s.nama_akun3, tbl_transaksi.tanggal, debit, kredit, id_status, ketjurnal')
            ->where('akun3s.kode_akun3=1101');
        // ->orderBy('id_nilai');
        if ($tglawal && $tglakhir) {
            $sql->where('tanggal >=', $tglawal)->where('tanggal <=', $tglakhir);
        }
        $query =  $sql->get()->getResultObject();
        return $query;
    }

    public function get_saldo_kas_awal($tglawal)
    {
        if (!$tglawal) {
            return 0; // kalau belum ada tanggal, abaikan
        }

        $sql = "
        SELECT 
            SUM(tbl_nilai.debit) AS total_debit,
            SUM(tbl_nilai.kredit) AS total_kredit
        FROM tbl_nilai
        JOIN tbl_transaksi ON tbl_transaksi.id_transaksi = tbl_nilai.id_transaksi
        WHERE tbl_transaksi.tanggal < ?
        AND (tbl_nilai.kode_akun3 LIKE '1101')
    ";

        $query = $this->db->query($sql, [$tglawal]);
        $result = $query->getRow();

        if ($result) {
            return ($result->total_debit ?? 0) - ($result->total_kredit ?? 0);
        }

        return 0;
    }
}
