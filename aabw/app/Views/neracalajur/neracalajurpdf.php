<style>
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9pt;
        color: #000;
    }

    .kop {
        text-align: center;
        margin-bottom: 10px;
    }

    .kop h3 {
        margin: 0;
        font-size: 13pt;
        font-weight: bold;
    }

    table {
        border-collapse: collapse;
        width: 90%;
        margin-top: 10px auto;
        margin-left: 5%;
        margin-right: 5%;
    }

    th,
    td {
        border: 1px solid #444;
        padding: 3px 4px;
        font-size: 8pt;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    }

    td.aturkanan {
        text-align: right;
    }

    td.aturtengah {
        text-align: center;
    }

    td.aturkiri {
        text-align: left;
    }

    tr.total {
        font-weight: bold;
        background-color: #fafafa;
    }

    .footer {
        text-align: right;
        margin-top: 15px;
        font-size: 8pt;
        font-style: italic;
    }

    .signature-container {
        width: 100%;
        margin-top: 60px;
        display: flex;
        justify-content: flex-end;
    }

    .signature {
        width: 260px;
        text-align: right;
    }

    .signature-line {
        border-top: 1px solid #000;
        width: 160px;
        margin: 60px 0 5px auto;
    }
</style>

<div class="kop">
    <h3>NERACA LAJUR</h3>
</div>

<div>
    <b>Periode:</b>
    <?= ($tglawal && $tglakhir)
        ? date('d/m/Y', strtotime($tglawal)) . ' s.d ' . date('d/m/Y', strtotime($tglakhir))
        : '-' ?>
</div>

<table>
    <thead>
        <tr>
            <td width="60" rowspan="2" style="font-weight: bold;" class="aturtengah">Kode</td>
            <td width="125" rowspan="2" style="font-weight: bold;" class="aturtengah">Keterangan</td>
            <td colspan="2" width="120" style="font-weight: bold;" class="aturtengah">Neraca Saldo</td>
            <td colspan="2" width="120" style="font-weight: bold;" class="aturtengah">Jurnal Penyesuaian</td>
            <td colspan="2" width="120" style="font-weight: bold;" class="aturtengah">Neraca Saldo Penyesuaian</td>
            <td colspan="2" width="120" style="font-weight: bold;" class="aturtengah">Laba Rugi</td>
            <td colspan="2" width="120" style="font-weight: bold;" class="aturtengah">Posisi Keuangan</td>
        </tr>
        <tr>
            <td width="60" style="font-weight: bold;" class="aturtengah">Debit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Kredit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Debit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Kredit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Debit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Kredit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Debit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Kredit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Debit</td>
            <td width="60" style="font-weight: bold;" class="aturtengah">Kredit</td>
        </tr>
    </thead>

    <tbody>
        <?php
        $td = $tk = $tdjp = $tkjp = $totd = $totk = $lb_td = $lb_tk = $totns = $totkd = 0;

        foreach ($dttransaksi as $value):
            $d = $value->jumdebit;
            $k = $value->jumkredit;
            $neraca = $d - $k;

            $djp = $value->jumdebits;
            $kjp = $value->jumkredits;
            $neracajp = $djp - $kjp;

            $debitnewjp = $neracajp > 0 ? $neracajp : 0;
            $kreditnewjp = $neracajp < 0 ? abs($neracajp) : 0;
            $tdjp += $debitnewjp;
            $tkjp += $kreditnewjp;

            $debitnew = $neraca > 0 ? $neraca : 0;
            $kreditnew = $neraca < 0 ? abs($neraca) : 0;
            $td += $debitnew;
            $tk += $kreditnew;

            $ns = $debitnew - $kreditnew + $djp - $kjp;
            $debs = $ns > 0 ? $ns : 0;
            $kres = $ns < 0 ? abs($ns) : 0;
            $totd += $debs;
            $totk += $kres;

            $kode = substr($value->kode_akun3, 0, 1);
            $lb_db = $kode == '4' ? $kres : 0;
            $lb_kr = $kode == '5' ? $debs : 0;
            $lb_td += $lb_db;
            $lb_tk += $lb_kr;

            $lpkd = ($kode <= 3 && $ns > 0) ? $debs : 0;
            $lpkk = ($kode <= 3 && $ns < 0) ? abs($kres) : 0;
            $totns += $lpkd;
            $totkd += $lpkk;
        ?>
            <tr>
                <td class="aturtengah" width="60"><?= $value->kode_akun3 ?></td>
                <td class="aturkiri" width="125"><?= $value->nama_akun3 ?></td>
                <td class="aturkanan" width="60"><?= number_format($debitnew, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($kreditnew, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($debitnewjp, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($kreditnewjp, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($debs, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($kres, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($lb_db, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($lb_kr, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($lpkd, 0, ',', '.') ?></td>
                <td class="aturkanan" width="60"><?= number_format($lpkk, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr class="total">
            <td colspan="2" class="aturtengah">TOTAL</td>
            <td class="aturkanan"><?= number_format($td, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($tk, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($tdjp, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($tkjp, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($totd, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($totk, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($lb_td, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($lb_tk, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($totns, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($totkd, 0, ',', '.') ?></td>
        </tr>
    </tfoot>
</table>

<div class="signature-container">
    <div class="signature">
        Mengetahui,<br><br><br>
        <strong>_</strong><br>
        <i>Bagian Akuntansi</i>
    </div>
</div>

<p class="footer">
    Dicetak pada: <?= date('d/m/Y') ?>
</p>