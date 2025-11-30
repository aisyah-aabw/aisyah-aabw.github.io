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
    <h3>LAPORAN ARUS KAS</h3>
    <p style="margin-top:3px;">Periode:
        <?= ($tglawal && $tglakhir) ? date('d/m/Y', strtotime($tglawal)) . ' s.d ' . date('d/m/Y', strtotime($tglakhir)) : '-' ?>
    </p>
</div>

<?php
$totpenerimaan = 0;
$totpengeluaran = 0;
$modal = 0;
$tprive = 0;
$tpendanaan = 0;
$saldo_awal = $saldo_awal ?? 0; // fallback jika belum diset
?>

<table cellspacing="0" cellpadding="3">
    <tbody>
        <!-- ===================== AKTIVITAS OPERASI ===================== -->
        <tr class="total">
            <td colspan="3" class="text-center" width="110%"><b>ARUS KAS DARI AKTIVITAS OPERASI</b></td>
        </tr>

        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->id_status == 1): ?>
                <?php $penerimaan = $value->debit;
                $totpenerimaan += $penerimaan; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <td width="50%" class="text-left" style="font-weight:bold;">Penerimaan Kas dari Pelanggan</td>
            <td width="30%" class="text-right"><?= number_format($totpenerimaan, 0, ",", ".") ?></td>
            <td width="30%"></td>
        </tr>

        <tr>
            <td colspan="3" class="text-left"><b>Pengeluaran Kas</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->id_status == 2): ?>
                <?php $pengeluaran = $value->kredit;
                $totpengeluaran += $pengeluaran; ?>
                <tr>
                    <td width="50%" class="text-left" style="text-indent:10px;"><?= $value->ketjurnal ?></td>
                    <td width="30%" class="text-right"><?= number_format($pengeluaran, 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td width="50%" class="text-left" style="text-indent:15px;">Total Pengeluaran Kas</td>
            <td width="30%"></td>
            <td width="30%" class="text-right"><?= number_format($totpengeluaran, 0, ",", ".") ?></td>
        </tr>

        <tr class="total" style="border-top:2px solid #000;">
            <td colspan="2" class="text-left">Arus Kas Bersih dari Aktivitas Operasi</td>
            <td width="30%" class="text-right"><?= number_format($totpenerimaan - $totpengeluaran, 0, ",", ".") ?></td>
        </tr>

        <!-- ===================== AKTIVITAS INVESTASI ===================== -->
        <?php
        function format_akuntansi($angka)
        {
            if ($angka < 0) {
                return '(' . number_format(abs($angka), 0, ",", ".") . ')';
            } else {
                return number_format($angka, 0, ",", ".");
            }
        }
        ?>
        <tr class="total">
            <td colspan="3" class="text-center"><b>ARUS KAS DARI AKTIVITAS INVESTASI</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->id_status == 3): ?>
                <?php $setor = $value->debit;
                $modal += $setor; ?>
                <tr>
                    <td width="50%" class="text-left" style="text-indent:10px;"><?= $value->ketjurnal ?></td>
                    <td width="30%" class="text-right"><?= format_akuntansi($setor, 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->id_status == 4): ?>
                <?php $prive = $value->kredit;
                $tprive += $prive; ?>
                <tr>
                    <td width="50%" class="text-left" style="text-indent:10px;"><?= $value->ketjurnal ?></td>
                    <td width="30%" class="text-right"><?= format_akuntansi($prive, 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr class="total">
            <td colspan="2" class="text-left">Arus Kas Bersih dari Aktivitas Investasi</td>
            <td width="30%" class="text-right"><?= number_format($modal - $tprive, 0, ",", ".") ?></td>
        </tr>

        <!-- ===================== AKTIVITAS PENDANAAN ===================== -->
        <tr class="total">
            <td colspan="3" class="text-center"><b>ARUS KAS DARI AKTIVITAS PENDANAAN</b></td>
        </tr>
        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->id_status == 5): ?>
                <?php $kas_dana = $value->debit - $value->kredit;
                $tpendanaan += $kas_dana; ?>
                <tr>
                    <td width="50%" class="text-left" style="text-indent:10px;"><?= $value->ketjurnal ?></td>
                    <td width="30%" class="text-right"><?= number_format($kas_dana, 0, ",", ".") ?></td>
                    <td width="30%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="2" class="text-left">Arus Kas Bersih dari Aktivitas Pendanaan</td>
            <td width="30%" class="text-right"><?= number_format($tpendanaan, 0, ",", ".") ?></td>
        </tr>

        <!-- ===================== SALDO AWAL & AKHIR ===================== -->
        <?php
        $kas_bersih_periode = ($totpenerimaan - $totpengeluaran) + ($modal - $tprive) + $tpendanaan;
        $saldo_akhir = $saldo_awal + $kas_bersih_periode;
        ?>
        <tr class="total" style="border-top:2px solid #000;">
            <td colspan="2" class="text-left">Saldo Kas Awal Periode</td>
            <td width="30%" class="text-right"><?= number_format($saldo_awal, 0, ",", ".") ?></td>
        </tr>
        <tr class="total">
            <td colspan="2" class="text-left">Kas Bersih Selama Periode</td>
            <td width="30%" class="text-right"><?= number_format($kas_bersih_periode, 0, ",", ".") ?></td>
        </tr>
        <tr class="total" style="border-top:1px solid #000;">
            <td colspan="2" class="text-left"><b>Saldo Kas Akhir Periode</b></td>
            <td width="30%" class="text-right"><b><?= number_format($saldo_akhir, 0, ",", ".") ?></b></td>
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