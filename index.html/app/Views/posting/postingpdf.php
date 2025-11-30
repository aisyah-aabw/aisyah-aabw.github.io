<!DOCTYPE html>
<html>

<head>
    <title>Laporan Posting</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            text-align: center;
        }

        h2,
        h3,
        h4 {
            font-family: "Times New Roman", serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        h3 {
            font-size: 22px;
            margin-bottom: 3px;
        }

        h4 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* === TABEL DIPERKECIL === */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            /* DIPERKECIL */
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            /* DIPERKECIL */
            text-align: center;
        }

        .footer-section {
            width: 100%;
            margin-top: 50px;
            text-align: left;
            font-size: 16px;
        }

        .footer-section table {
            border: none;
        }

        .footer-section td {
            border: none;
            padding: 6px;
            font-family: "Times New Roman", serif;
        }
    </style>
</head>

<body>

    <!-- ====== JUDUL LAPORAN ====== -->
    <h2>LAPORAN BUKU BESAR</h2>
    <h3>KONSULTAN AKN IPB</h3>
    <h4>Periode: <?= date('F Y') ?></h4>

    <!-- ====== IDENTITAS AKUN ====== -->
    <h4>Kode Akun: <?= $kode_akun3 ?></h4>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Kode Akun</th>
                <th rowspan="2">Keterangan</th>
                <th rowspan="2">Debit</th>
                <th rowspan="2">Kredit</th>
                <th colspan="2">Saldo</th>
            </tr>
            <tr>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $saldo = 0;
            $saldo_debit = 0;
            $saldo_kredit = 0;
            ?>

            <?php if (!empty($dttransaksi)) : ?>
                <?php foreach ($dttransaksi as $row): ?>

                    <?php
                    // Jika transaksi Debit
                    if ($row->debit > 0) {
                        $saldo += $row->debit;
                        $saldo_debit = $saldo;
                        $saldo_kredit = 0;

                        // Jika transaksi Kredit
                    } else {
                        $saldo -= $row->kredit;

                        if ($saldo > 0) {
                            $saldo_debit = $saldo;
                            $saldo_kredit = 0;
                        } else {
                            $saldo_kredit = abs($saldo);
                            $saldo_debit = 0;
                        }
                    }
                    ?>

                    <tr>
                        <td><?= $row->tanggal ?></td>
                        <td><?= $row->kode_akun3 ?></td>
                        <td><?= $row->ketjurnal ?></td>

                        <td><?= $row->debit > 0 ? number_format($row->debit, 0, ',', '.') : '-' ?></td>
                        <td><?= $row->kredit > 0 ? number_format($row->kredit, 0, ',', '.') : '-' ?></td>

                        <td><?= $saldo_debit > 0 ? number_format($saldo_debit, 0, ',', '.') : '-' ?></td>
                        <td><?= $saldo_kredit > 0 ? number_format($saldo_kredit, 0, ',', '.') : '-' ?></td>
                    </tr>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

    <!-- ====== FOOTER ====== -->
    <div class="footer-section">
        <table style="width: 100%;">
            <tr>
                <td style="width:50%; text-align:center;">
                    Mengetahui,<br>
                    Kepala Bagian Akuntansi<br><br><br><br>
                    _____________________________<br>
                    (Nama Pejabat)
                </td>

                <td style="width:50%; text-align:center;">
                    Dicetak pada: <?= date('d F Y') ?><br>
                    Petugas Pembuat Laporan<br><br><br><br>
                    _____________________________<br>
                    (Nama Petugas)
                </td>
            </tr>
        </table>
    </div>

</body>

</html>