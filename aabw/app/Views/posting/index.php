<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <h1>Laporan Posting</h1>
    </div>

    <div class="section-body">
        <div class="card-body">
            <form action="<?= site_url('posting') ?>" method="get">

                <div class="row g-3">
                    <div class="col">
                        <input type="date" class="form-control" name="tglawal" value="<?= $tglawal ?>">
                    </div>
                    <div class="col">
                        <input type="date" class="form-control" name="tglakhir" value="<?= $tglakhir ?>">
                    </div>
                    <div class="col">
                        <select class="form-control" name="kode_akun3">
                            <option selected>Pilih Kode Akun</option>
                            <?php foreach ($dtakun3 as $key => $value) : ?>
                                <option value="<?= $value->kode_akun3 ?>">
                                    <<?= $kode_akun3 == $value->kode_akun3 ? 'selected' : null ?>><?= $value->kode_akun3 ?> | <?= $value->nama_akun3 ?>
                                </option>
                            <?php endforeach; ?>


                        </select>
                        <div class="col">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-list"></i> Tampilkan </button>
                            <input type="submit" class="btn btn-success"
                                formtarget="_blank"
                                formaction="<?= base_url('posting/postingpdf') ?>"
                                value="Cetak PDF">

                        </div>
                    </div>
            </form>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-md">
                    <thead>
                        <tr>
                            <td class="text-center" rowspan="2">Tanggal</td>
                            <td class="text-center" rowspan="2">Ref</td>
                            <td class="text-center" rowspan="2">Keterangan</td>
                            <td class="text-center" rowspan="2">Debit</td>
                            <td class="text-center" rowspan="2">Kredit</td>
                            <td class="text-center" colspan="2">Saldo</td>

                        </tr>
                        <tr>
                            <td>Debit</td>
                            <td>Kredit</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $dbt = 0;


                        ?>
                        <?php foreach ($dttransaksi as $value) : ?>
                            <?php
                            if ($value->debit) {
                                $dbt = $dbt + $value->debit;
                            } else {
                                $dbt = $dbt - $value->kredit;
                            }

                            $ndbt1 = $dbt >= 0 ? $dbt : 0;
                            $ndbt2 = $dbt < 0 ? $dbt : 0;

                            ?>
                            <tr>
                                <td><?= $value->tanggal ?></td>
                                <td><?= $value->kode_akun3 ?></td>
                                <td><?= $value->ketjurnal ?></td>
                                <td><?= number_format($value->debit, 0, ',', '.') ?></td>
                                <td><?= number_format($value->kredit, 0, ',', '.') ?></td>
                                <td><?= number_format($ndbt1, 0, ',', '.') ?></td>
                                <td><?= number_format(abs($ndbt2), 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapus(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data akan terhapus permanen",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('del-' + id).submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>