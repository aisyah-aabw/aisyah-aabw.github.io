<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Umum</title>
    <style type="text/css">
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 11px;
            /* Semua font sama */
        }

        body {
            margin: 15px;
            padding: 0;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
            font-style: italic;

        }

        .periode {
            margin-bottom: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* TANGAL - Lebih kecil */
        .tanggal {
            width: 80px;
            text-align: center;
        }

        /* KETERANGAN - Header tengah, konten kiri */
        .keterangan {
            width: 180px;
        }

        th.keterangan {
            text-align: center;
        }

        td.keterangan {
            text-align: left;
        }

        /* REF - Lebih kecil */
        .ref {
            width: 60px;
            text-align: center;
        }

        /* DEBIT & KREDIT - Header tengah, konten kanan */
        .debit {
            width: 100px;
            text-align: right;
            padding-right: 8px;
        }

        th.debit {
            text-align: center;
        }

        .kredit {
            width: 100px;
            text-align: right;
            padding-right: 8px;
        }

        th.kredit {
            text-align: center;
        }

        /* INDEKSASI untuk akun kredit - TANPA ITALIC, HANYA SPASI */
        .indent {
            padding-left: 20px;
            font-style: italic;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-style: italic;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <p class="judul">Jurnal Umum</p>
    <div class="judul-laporan">Laporan Jurnal Umum</div>
    <div class="periode">Periode: <?= $tglawal ?> s/d <?= $tglakhir ?></div>

    <table>
        <thead>
            <tr>
                <th class="tanggal">Tanggal</th>
                <th class="keterangan">Keterangan</th>
                <th class="ref">Ref</th>
                <th class="debit">Debit</th>
                <th class="kredit">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dttransaksi as $value) : ?>
                <tr>
                    <td class="tanggal"><?= $value->tanggal ?></td>
                    <td class="<?= ($value->kredit > 0 && $value->debit == 0) ? 'keterangan indent' : 'keterangan' ?>">
                        <?= $value->nama_akun3 ?>
                    </td>
                    <td class="ref"><?= $value->kode_akun3 ?></td>
                    <td class="debit"><?= number_format($value->debit, 0, ',', '.') ?></td>
                    <td class="kredit"><?= number_format($value->kredit, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br />
    <?php


    ?>
    <br />
    <br />
    Pimpinan AKN
    _____________



    <div class="footer">
        Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?>
    </div>
</body>

</html>