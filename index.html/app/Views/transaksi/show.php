<?= $this->extend('layout/backend') ?>


<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>Blank Page</h1> -->
        <a href="<?= site_url('transaksi') ?>" class="btn btn-primary"> Back</a>

    </div>

    <div class="section-body">
        <!--dinamis-->
        <div class="card">
            <div class="card-header">
                <h4>Detail Transaksi</h4>
            </div>
            <div class="card-body p-4">
                <table>
                    <tr>
                        <td>No Kwitansi</td>
                        <td>:</td>
                        <td>
                            <?= $dttransaksi->kwitansi ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>
                            <?= $dttransaksi->tanggal ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td>
                            <?= $dttransaksi->deskripsi ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Ket Jurnal</td>
                        <td>:</td>
                        <td>
                            <?= $dttransaksi->ketjurnal ?>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dtnilai as $key => $item) : ?>
                            <tr>
                                <td>
                                    <?= $key + 1 ?>
                                </td>
                                <td>
                                    <?= $item->kode_akun3 ?>
                                </td>
                                <td>
                                    <?= $item->nama_akun3 ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($item->debit, 0, ",", ".") ?>
                                </td>
                                <td>
                                    <?= number_format($item->kredit, 0, ",", ".") ?>
                                </td>
                                <td>
                                    <?= $item->status ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>


        </div>
    </div>


</section>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        let no = 1;

        $('#BarisBaru').on('click', function(e) {
            e.preventDefault();

            let html = `
        <tr>
            <td>${no++}</td>
            <td><input type="text" name="kode_akun[]" class="form-control" placeholder="Kode Akun"></td>
            <td><input type="text" name="debit[]" class="form-control" placeholder="Debit"></td>
            <td><input type="text" name="kredit[]" class="form-control" placeholder="Kredit"></td>
            <td>
                <select name="status[]" class="form-control">
                    <option value="debit">Debit</option>
                    <option value="kredit">Kredit</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-warning btn-sm btnRemove">X</button></td>
        </tr>
        `;
            $('#tableLoop tbody').append(html);
        });

        $('#tableLoop').on('click', '.btnRemove', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
<?= $this->endSection() ?>