<style>
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 10pt;
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
        width: 100%;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #444;
        padding: 6px 8px;
        font-size: 10pt;
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
        font-size: 9pt;
        font-style: italic;
    }

    .signature-container {
        width: 100%;
        margin-top: 60px;
        display: flex;
        justify-content: flex-end;
    }

    .signature {
        width: 250px;
        text-align: right;
    }

    .signature-line {
        border-top: 1px solid #000;
        width: 150px;
        margin: 50px 0 5px auto;
    }
</style>

<div class="kop">
    <h3>NERACA SALDO</h3>
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
            <td width="70" class="aturtengah" style="font-weight: bold;">Kode Akun</td>
            <td width="200" class="aturtengah" style="font-weight: bold;">Keterangan</td>
            <td width="120" class="aturkanan" style="font-weight: bold;">Debit (Rp)</td>
            <td width="120" class="aturkanan" style="font-weight: bold;">Kredit (Rp)</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $td = 0;
        $tk = 0;
        foreach ($dttransaksi as $key => $value) :
            $d = $value->jumdebit;
            $k = $value->jumkredit;
            $neraca = $d - $k;

            $debitnew = $neraca > 0 ? $neraca : 0;
            $kreditnew = $neraca < 0 ? abs($neraca) : 0;

            $td += $debitnew;
            $tk += $kreditnew;
        ?>
            <tr>
                <td class="aturtengah" width="70"><?= $value->kode_akun3 ?></td>
                <td class="aturkiri" width="200"><?= $value->nama_akun3 ?></td>
                <td class="aturkanan" width="120"><?= number_format($debitnew, 0, ',', '.') ?></td>
                <td class="aturkanan" width="120"><?= number_format($kreditnew, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>

        <tr class="total">
            <td colspan="2" class="aturtengah">TOTAL</td>
            <td class="aturkanan"><?= number_format($td, 0, ',', '.') ?></td>
            <td class="aturkanan"><?= number_format($tk, 0, ',', '.') ?></td>
        </tr>
    </tbody>
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