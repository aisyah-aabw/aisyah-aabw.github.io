<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA-IPB &mdash; Posisi Keuangan</title>
<?= $this->endsection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <h1>Laporan Posisi Keuangan</h1>
    </div>
    <!-- ini untuk menangkap session success dengan bawaan with -->

    <div class="section-body">
        <div class="card-body">
            <form action="<?= site_url('neraca') ?>" method="GET">
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
                        <input type="submit" class="btn btn-danger" formtarget="_blank" formaction="neraca/neracapdf" value="Cetak pdf">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-md">
                    <tr>
                        <td colspan="3" class="text-center font-weight-bold">ASET</td>
                    </tr>
                    <?php
                    $aktiva_lancar = 0;
                    $total_aktiva_lancar = 0;
                    $aktiva_tetap = 0;
                    $total_aktiva_tetap = 0;
                    $total_kewajiban = 0;
                    $kewajiban = 0;
                    $total_kewajiban_tetap = 0;
                    $modal_pemilik = 0;
                    ?>
                    <tr>
                        <td class="text-left">ASET LANCAR</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php foreach ($dttransaksi as $key => $value) : ?>
                        <?php if ($value->kode_akun2 == 11): ?>
                            <?php
                            $debit = $value->jumdebit + $value->jumdebits;
                            $kredit = $value->jumkredit + $value->jumkredits;
                            $aktiva_lancar = $debit - $kredit;
                            $total_aktiva_lancar = $total_aktiva_lancar + $aktiva_lancar;
                            ?>
                            <tr>
                                <td class="text-left" style="padding-left:3em"><?= $value->nama_akun3 ?></td>
                                <td class="text-right"><?= number_format($aktiva_lancar, 0, ",", ".") ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td style="padding-left:4em">TOTAL ASET LANCAR</td>
                        <td></td>
                        <td class="text-right"><?= number_format($total_aktiva_lancar, 0, ",", ".") ?></td>
                    </tr>
                    <tr>
                        <td class="text-left">ASET TETAP</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php foreach ($dttransaksi as $key => $value) : ?>
                        <?php if ($value->kode_akun2 == 12): ?>
                            <?php
                            $debit = $value->jumdebit + $value->jumdebits;
                            $kredit = $value->jumkredit + $value->jumkredits;
                            $aktiva_tetap = $debit - $kredit;
                            $total_aktiva_tetap = $total_aktiva_tetap + $aktiva_tetap;
                            ?>
                            <tr>
                                <td class="text-left" style="padding-left:3em"><?= $value->nama_akun3 ?></td>
                                <td class="text-right"><?= number_format(abs($aktiva_tetap), 0, ",", ".") ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td style="padding-left:4em">TOTAL ASET TETAP</td>
                        <td></td>
                        <td class="text-right"><?= number_format($total_aktiva_tetap, 0, ",", ".") ?></td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold">JUMLAH ASET</td>
                        <td></td>
                        <td class="text-right font-weight-bold"><?= number_format($total_aktiva_tetap + $total_aktiva_lancar, 0, ",", ".") ?></td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-center font-weight-bold">LIABILITAS & EKUITAS</td>
                    </tr>
                    <tr>
                        <td class="text-left">LIABILITAS JANGKA PENDEK</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php foreach ($dttransaksi as $key => $value) : ?>
                        <?php if ($value->kode_akun2 == 21): ?>
                            <?php
                            $debit = $value->jumdebit + $value->jumdebits;
                            $kredit = $value->jumkredit + $value->jumkredits;
                            $kewajiban = $debit - $kredit;
                            $total_kewajiban = $total_kewajiban + $kewajiban;
                            ?>
                            <tr>
                                <td class="text-left" style="padding-left:3em"><?= $value->nama_akun3 ?></td>
                                <td class="text-right"><?= number_format(abs($kewajiban), 0, ",", ".") ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td class="text-left" style="padding-left:4em">TOTAL LIABILITAS JANGKA PENDEK</td>
                        <td></td>
                        <td class="text-right"><?= number_format(abs($total_kewajiban), 0, ",", ".") ?></td>
                    </tr>

                    <tr>
                        <td class="text-left">LIABILITAS JANGKA PANJANG</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php foreach ($dttransaksi as $key => $value) : ?>
                        <?php if ($value->kode_akun2 == 22): ?>
                            <?php
                            $debit = $value->jumdebit + $value->jumdebits;
                            $kredit = $value->jumkredit + $value->jumkredits;
                            $kewajiban_tetap = $debit - $kredit;
                            $total_kewajiban_tetap = $total_kewajiban_tetap + $kewajiban_tetap;
                            ?>
                            <tr>
                                <td class="text-left" style="padding-left:3em"><?= $value->nama_akun3 ?></td>
                                <td class="text-right"><?= number_format(abs($kewajiban_tetap), 0, ",", ".") ?></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td style="padding-left:4em">TOTAL LIABILITAS JANGKA PANJANG</td>
                        <td></td>
                        <td class="text-right"><?= number_format($total_kewajiban_tetap, 0, ",", ".") ?></td>
                    </tr>

                    <tr>
                        <td>EKUITAS</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $modal_pemilik   = isset($modal_awal) ? $modal_awal : 0; // Modal awal
                    $laba_rugi       = isset($laba_rugi) ? $laba_rugi : 0;   // Laba tahun berjalan
                    $prive           = isset($prive) ? $prive : 0;           // Prive
                    $laba_ditahan    = $laba_rugi - $prive;                  // Laba ditahan = laba - prive
                    $total_ekuitas   = $modal_pemilik + $laba_ditahan;
                    ?>
                    <tr>
                        <td class="text-left" style="padding-left:3em">Modal Pemilik</td>
                        <td class="text-right"><?= number_format($modal_pemilik, 0, ",", ".") ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td class="text-left" style="padding-left:3em">Laba Ditahan</td>
                        <td class="text-right"><?= number_format($laba_ditahan, 0, ",", ".") ?></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td style="padding-left:4em">TOTAL EKUITAS</td>
                        <td></td>
                        <td class="text-right"><?= number_format($total_ekuitas, 0, ",", ".") ?></td>
                    </tr>
                    <tr>
                        <td class="text-center font-weight-bold">JUMLAH LIABILITAS & EKUITAS</td>
                        <td></td>
                        <td class="text-right font-weight-bold"><?= number_format(abs($total_kewajiban + $total_kewajiban_tetap - $total_ekuitas), 0, ",", ".") ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endsection() ?>