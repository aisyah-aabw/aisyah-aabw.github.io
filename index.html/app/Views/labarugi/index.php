<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA-IPB &mdash; Laba Rugi</title>
<?= $this->endsection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <h1>Laporan Laba Rugi</h1>
    </div>
    <!-- ini untuk menangkap session success dengan bawaan with -->

    <div class="section-body">
        <div class="card-body">
            <form action="<?= site_url('labarugi') ?>" method="GET">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col">
                        <input type="date" class="form-control" name="tglawal" value="<?= $tglawal ?>">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" name="tglakhir" value="<?= $tglakhir ?>">
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-list"></i> Tampilkan </button>
                        <input type="submit" class="btn btn-danger" formtarget="_blank" formaction="labarugi/labarugipdf" value="Cetak pdf">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-md">
                    <thead>
                    </thead>
                    <tbody>
                        <?php
                        $pendapatan = 0;
                        $totpendapatan = 0;
                        $beban = 0;
                        $totbeban = 0;
                        ?>
                        <?php foreach ($dttransaksi as $key => $value) : ?>
                            <?php
                            $pendapatan = $value->jumkredit + $value->jumkredits;
                            $totpendapatan = $totpendapatan + $pendapatan;
                            ?>

                            <?php if ($value->kode_akun2 == 41) : ?>
                                <tr>
                                    <td>Pendapatan</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 3em;"><?= $value->nama_akun3 ?></td>
                                    <td></td>
                                    <td class="text-right"><?= number_format($pendapatan, 0, ",", ",") ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <tr>
                            <td class="text-left">BEBAN-BEBAN</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                        <?php foreach ($dttransaksi as $key => $value) : ?>
                            <?php if ($value->kode_akun2 == 51) : ?>
                                <?php
                                $beban = $value->jumdebit + $value->jumdebits;
                                $totbeban = $totbeban + $beban;
                                ?>
                                <tr>

                                    <td style="padding-left: 3em;"><?= $value->nama_akun3; ?></td>
                                    <td class="text-right"><?= number_format($beban, 0, ",", ","); ?></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                        <td class="text-left">Laba/Rugi</td>
                        <td></td>
                        <td class="text-right"><?= number_format($totpendapatan - $totbeban, 0, ",", ","); ?></td>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endsection() ?>