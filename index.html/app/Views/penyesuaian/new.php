<?= $this->extend('layout/backend') ?>


<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <!-- <h1>Blank Page</h1> -->
        <a href="<?= site_url('penyesuaian') ?>" class="btn btn-primary"> Back</a>

    </div>

    <div class="section-body">
        <!--dinamis-->
        <div class="card">
            <div class="card-header">
                <h4>Tambah Data Penyesuaian</h4>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= site_url('penyesuaian') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" placeholder="Tanggal" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi" required>
                    </div>
                    <div class="form-group">
                        <label>Nilai yang disesuaikan</label>
                        <input type="text" class="form-control" onkeyup="hitung()" name="nilai" placeholder="Nilai" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Disesuaikan</label>
                        <input type="text" class="form-control" onkeyup="hitung()" name="waktu" placeholder="Waktu" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah disesuaikan</label>
                        <input type="text" class="form-control" name="jumlah" placeholder="Jumlah" readonly required>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered" id="tableLoop">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Akun</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Status</th>
                                    <th>
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="BarisBaru">
                                            <i class="fa fa-plus"></i> Add baris
                                        </button>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- disini form dinamis yang dibuat dengan jQuery -->
                            </tbody>
                        </table>


                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Save</button>
                        <button type="reset" class="btn btn-secondary"> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

</section>

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        let no = 1;

        // 🔹 Fungsi untuk memperbarui nomor baris
        function updateNomor() {
            $('#tableLoop tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // 🔹 Tombol tambah baris baru
        $('#BarisBaru').on('click', function(e) {
            e.preventDefault();

            let html = `
        <tr>
            <td>${no++}</td>
            <td><input type="text" name="kode_akun3[]" class="form-control" placeholder="Kode Akun" required></td>
            <td><input type="number" name="debit[]" class="form-control" placeholder="Debit" step="0.01"></td>
            <td><input type="number" name="kredit[]" class="form-control" placeholder="Kredit" step="0.01"></td>
            <td>
                <select name="id_status[]" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="1">Debit</option>
                    <option value="2">Kredit</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-warning btn-sm btnRemove">X</button></td>
        </tr>
        `;

            $('#tableLoop tbody').append(html);
            updateNomor(); // setiap kali nambah, nomor disusun ulang
        });

        // 🔹 Tombol hapus baris
        $('#tableLoop').on('click', '.btnRemove', function() {
            $(this).closest('tr').remove();
            updateNomor(); // setiap kali hapus, nomor disusun ulang
        });
    });
</script>
<?= $this->endSection() ?>