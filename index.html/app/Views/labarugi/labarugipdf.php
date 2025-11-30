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
    }

    table {
        border-collapse: collapse;
        width: 90%;
        margin: 10px auto;
    }

    th,
    td {
        border: 1px solid #444;
        padding: 5px 6px;
        font-size: 8pt;
        vertical-align: middle;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
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
        margin-top: 50px;
        display: flex;
        justify-content: flex-end;
    }

    .signature {
        width: 220px;
        text-align: right;
    }
</style>

<div class="kop">
    <h3>LAPORAN LABA RUGI</h3>
</div>

<div style="margin-left:5%;">
    <b>Periode:</b>
    <?= ($tglawal && $tglakhir)
        ? date('d/m/Y', strtotime($tglawal)) . ' s.d ' . date('d/m/Y', strtotime($tglakhir))
        : '-' ?>
</div>

<br>

<table>

    <tbody>
        <?php
        $totpendapatan = 0;
        $totbeban = 0;
        ?>

        <!-- ========================= -->
        <!-- BAGIAN PENDAPATAN -->
        <!-- ========================= -->
        <tr>
            <td colspan="3" style="font-weight: bold;">PENDAPATAN</td>
        </tr>

        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->kode_akun2 == 41): ?>
                <?php
                $pendapatan = ($value->jumkredit ?? 0) + ($value->jumkredits ?? 0);
                $totpendapatan += $pendapatan;
                ?>
                <tr>
                    <td width="70%"><?= str_repeat('&nbsp;', 10) . esc($value->nama_akun3) ?></td>
                    <td width="15%" class="text-right"><?= number_format($pendapatan, 0, ",", ".") ?></td>
                    <td width="15%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Total Pendapatan -->
        <tr class="total" style="font-weight: bold;">
            <td width="70%"><?= str_repeat('&nbsp;', 6) ?>Total Pendapatan</td>
            <td width="15%"></td>
            <td width="15%" class="text-right"><?= number_format($totpendapatan, 0, ",", ".") ?></td>
        </tr>

        <!-- ========================= -->
        <!-- BAGIAN BEBAN -->
        <!-- ========================= -->
        <tr>
            <td colspan="3" style="font-weight: bold;">BEBAN-BEBAN</td>
        </tr>

        <?php foreach ($dttransaksi as $value): ?>
            <?php if ($value->kode_akun2 == 51): ?>
                <?php
                $beban = ($value->jumdebit ?? 0) + ($value->jumdebits ?? 0);
                $totbeban += $beban;
                ?>
                <tr>
                    <td width="70%"><?= str_repeat('&nbsp;', 10) . esc($value->nama_akun3) ?></td>
                    <td width="15%" class="text-right"><?= number_format($beban, 0, ",", ".") ?></td>
                    <td width="15%"></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Total Beban -->
        <tr class="total" style="font-weight: bold;">
            <td width="70%"><?= str_repeat('&nbsp;', 6) ?>Total Beban</td>
            <td width="15%"></td>
            <td width="15%" class="text-right"><?= number_format($totbeban, 0, ",", ".") ?></td>
        </tr>

        <!-- ========================= -->
        <!-- LABA (RUGI) BERSIH -->
        <!-- ========================= -->
        <tr class="total">
            <td colspan="2" class="text-left" style="font-weight: bold;">Laba (Rugi) Bersih</td>
            <td width="15%" class="text-right" style="font-weight: bold;"><?= number_format($totpendapatan - $totbeban, 0, ",", ".") ?></td>
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

<p class="footer">
    Dicetak pada: <?= date('d/m/Y') ?>
</p>