<style>
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9pt;
        color: #000;
        margin: 35px;
    }

    .kop {
        text-align: center;
        margin-bottom: 15px;
    }

    .kop h3 {
        margin: 0;
        font-size: 13pt;
    }

    table {
        border-collapse: collapse;
        width: 90%;
        margin: 15px auto;
    }

    th,
    td {
        border: 1px solid #444;
        padding: 5px 6px;
        font-size: 9pt;
        vertical-align: middle;
    }

    td.text-right {
        text-align: right;
    }

    td.text-center {
        text-align: center;
    }

    td.text-left {
        text-align: left;
    }

    tr.total {
        font-weight: bold;
    }

    .footer {
        text-align: right;
        margin-top: 20px;
        font-size: 8pt;
        font-style: italic;
    }

    .signature-container {
        width: 90%;
        margin: 50px auto 0 auto;
        display: flex;
        justify-content: flex-end;
    }

    .signature {
        width: 220px;
        text-align: right;
    }
</style>

<div class="kop">
    <h3>LAPORAN POSISI KEUANGAN</h3>
    <p style="margin-top:3px;">Periode:
        <?= ($tglawal && $tglakhir) ? date('d/m/Y', strtotime($tglawal)) . ' s.d ' . date('d/m/Y', strtotime($tglakhir)) : '-' ?>
    </p>
</div>

<?php
// ====== INISIALISASI TOTAL (WAJIB AGAR TIDAK UNDEFINED) ======
$total_aktiva_lancar   = $total_aktiva_lancar   ?? 0;
$total_aktiva_tetap    = $total_aktiva_tetap    ?? 0;
$total_kewajiban_pendek = $total_kewajiban_pendek ?? 0;
$total_kewajiban_panjang = $total_kewajiban_panjang ?? 0;

// Ekuitas dari controller (fallback 0 jika belum ada)
$modal_pemilik = $modal_awal   ?? 0;
$laba_rugi_v   = $laba_rugi    ?? 0;
$prive_v       = $prive        ?? 0;
$laba_ditahan  = $laba_rugi_v - $prive_v;
$total_ekuitas = $modal_pemilik + $laba_ditahan;
?>

<table cellspacing="0" cellpadding="3">
    <tbody>
        <!-- ===================== ASET ===================== -->
        <tr class="total">
            <td colspan="3" class="text-center" width="110%"><b>ASET</b></td>
        </tr>

        <!-- ASET LANCAR -->
        <tr>
            <td colspan="3" class="text-left"><b>Aset Lancar</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ((int)$value->kode_akun2 === 11): ?>
                <?php
                $debit  = (float)$value->jumdebit  + (float)$value->jumdebits;
                $kredit = (float)$value->jumkredit + (float)$value->jumkredits;
                $saldo  = $debit - $kredit;
                $total_aktiva_lancar += $saldo;
                ?>
                <tr>
                    <td width="50%" nowrap class="text-left" style="text-indent:10px;"><?= $value->nama_akun3 ?></td>
                    <td width="30%" class="text-right"><?= number_format($saldo, 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Aset Lancar</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format($total_aktiva_lancar, 0, ",", ".") ?></td>
        </tr>

        <!-- ASET TETAP -->
        <tr>
            <td colspan="3" class="text-left"><b>Aset Tetap</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ((int)$value->kode_akun2 === 12): ?>
                <?php
                $debit  = (float)$value->jumdebit  + (float)$value->jumdebits;
                $kredit = (float)$value->jumkredit + (float)$value->jumkredits;
                $saldo  = $debit - $kredit;
                $total_aktiva_tetap += $saldo;
                ?>
                <tr>
                    <td width="50%" nowrap class="text-left" style="text-indent:10px;"><?= $value->nama_akun3 ?></td>
                    <td width="30%" class="text-right"><?= number_format(abs($saldo), 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Aset Tetap</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format($total_aktiva_tetap, 0, ",", ".") ?></td>
        </tr>

        <tr class="total" style="border-top:2px solid #000;">
            <td colspan="2" class="text-left">JUMLAH ASET</td>
            <td width="30%" class="text-right"><?= number_format($total_aktiva_lancar + $total_aktiva_tetap, 0, ",", ".") ?></td>
        </tr>

        <!-- ===================== LIABILITAS ===================== -->
        <tr class="total">
            <td colspan="3" class="text-center"><b>LIABILITAS & EKUITAS</b></td>
        </tr>

        <!-- LIABILITAS JANGKA PENDEK -->
        <tr>
            <td colspan="3" class="text-left"><b>Liabilitas Jangka Pendek</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ((int)$value->kode_akun2 === 21): ?>
                <?php
                $debit  = (float)$value->jumdebit  + (float)$value->jumdebits;
                $kredit = (float)$value->jumkredit + (float)$value->jumkredits;
                $saldo  = $debit - $kredit;
                $total_kewajiban_pendek += $saldo;
                ?>
                <tr>
                    <td width="50%" nowrap class="text-left" style="text-indent:10px;"><?= $value->nama_akun3 ?></td>
                    <td width="30%" class="text-right"><?= number_format(abs($saldo), 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Liabilitas Jangka Pendek</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format(abs($total_kewajiban_pendek), 0, ",", ".") ?></td>
        </tr>

        <!-- LIABILITAS JANGKA PANJANG -->
        <tr>
            <td colspan="3" class="text-left"><b>Liabilitas Jangka Panjang</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ((int)$value->kode_akun2 === 22): ?>
                <?php
                $debit  = (float)$value->jumdebit  + (float)$value->jumdebits;
                $kredit = (float)$value->jumkredit + (float)$value->jumkredits;
                $saldo  = $debit - $kredit;
                $total_kewajiban_panjang += $saldo;
                ?>
                <tr>
                    <td width="50%" nowrap class="text-left" style="text-indent:10px;"><?= $value->nama_akun3 ?></td>
                    <td width="30%" class="text-right"><?= number_format(abs($saldo), 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Liabilitas Jangka Panjang</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format($total_kewajiban_panjang, 0, ",", ".") ?></td>
        </tr>

        <!-- ===================== EKUITAS ===================== -->
        <tr>
            <td colspan="3" class="text-left"><b>Ekuitas</b></td>
        </tr>
        <tr>
            <td width="50%" nowrap class="text-left" style="text-indent:10px;">Modal Pemilik</td>
            <td width="30%" class="text-right"><?= number_format($modal_pemilik, 0, ",", ".") ?></td>
            <td width="30%"></td>
        </tr>
        <tr>
            <td width="50%" nowrap class="text-left" style="text-indent:10px;">Laba Ditahan</td>
            <td width="30%" class="text-right"><?= number_format($laba_ditahan, 0, ",", ".") ?></td>
            <td width="30%"></td>
        </tr>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Ekuitas</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format($total_ekuitas, 0, ",", ".") ?></td>
        </tr>

        <tr class="total" style="border-top:2px solid #000;">
            <td colspan="2" class="text-left">JUMLAH LIABILITAS & EKUITAS</td>
            <td width="30%" class="text-right">
                <?= number_format(abs($total_kewajiban_pendek) + abs($total_kewajiban_panjang) + $total_ekuitas, 0, ",", ".") ?>
            </td>
        </tr>
    </tbody>
</table>

<div class="signature-container">
    <div class="signature">
        Mengetahui,<br><br><br>
        _________________________<br>
        <i>Bagian Akuntansi</i>
    </div>
</div>

<p class="footer">Dicetak pada: <?= date('d/m/Y') ?></p>