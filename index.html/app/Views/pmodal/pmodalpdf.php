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
        text-align: center;
        width: 90%;
        margin: 15px auto;
    }

    th,
    td {
        border: 1px solid #444;
        padding: 6px 8px;
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
        background-color: #f8f8f8;
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
    <h3>LAPORAN PERUBAHAN MODAL</h3>
</div>

<div style="margin-left:8%;">
    <b>Periode:</b>
    <?= ($tglawal && $tglakhir)
        ? date('d/m/Y', strtotime($tglawal)) . ' s.d ' . date('d/m/Y', strtotime($tglakhir))
        : '-' ?>
</div>

<br>

<div style="width: 100%; display: flex; justify-content: center;">

    <!-- Container tabel -->
    <div style="text-align: center; width: 75%;">
        <table style="margin: 0 auto; border-collapse: separate; border-spacing: 0; width: 100%;">
            <tbody>
                <tr>
                    <td colspan="2" width="70%" class="text-left">Modal Awal</td>
                    <td width="15%" class="text-right"><?= number_format($dttransaksi['modal_awal'] ?? 0, 0, ",", ".") ?></td>
                </tr>


                <tr>
                    <td class="text-left" style="padding-left: 6em">Laba (Rugi) Bersih</td>
                    <td class="text-right"><?= number_format($dttransaksi['laba_rugi'] ?? 0, 0, ",", ".") ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="text-left" style="padding-left: 6em">Prive</td>
                    <td class="text-right"><?= number_format($dttransaksi['prive'] ?? 0, 0, ",", ".") ?></td>
                    <td></td>
                </tr>

                <tr class="total">
                    <td colspan="2" class="text-left">Penambahan Modal</td>
                    <td class="text-right"><?= number_format($dttransaksi['penambahan_modal'] ?? 0, 0, ",", ".") ?></td>
                </tr>

                <tr class="total" style="border-top: 2px solid #000;">
                    <td colspan="2" class="text-left">Modal Akhir</td>
                    <td class="text-right"><?= number_format($dttransaksi['modal_akhir'] ?? 0, 0, ",", ".") ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

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