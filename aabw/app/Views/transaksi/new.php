<?= $this->extend('layout/backend') ?>
<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('transaksi'); ?>" class="btn btn-primary"> Back</a>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Transaksi</h4>
            </div>

            <div class="card-body p-4">
                <form method="post" action="<?= site_url('transaksi') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" required>
                    </div>

                    <div class="form-group">
                        <label>Ket Jurnal</label>
                        <input type="text" class="form-control" name="ketjurnal" required>
                    </div>

                    <div class="table-responsive" style="padding: 0 20px;">
                        <table class="table table-bordered" id="tableLoop">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Akun</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Status</th>
                                    <th>
                                        <button class="btn btn-primary btn-sm btn-block" id="BarisBaru">Tambah Baris</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Save
                        </button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Pastikan JQuery tersedia dengan menggunakan function wrapper
    $(document).ready(function() {
        let akun3 = <?= json_encode($dtakun3, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;
        let status = <?= json_encode($status_list, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;

        // Listener untuk Tombol Tambah Baris
        $('#BarisBaru').click(function(e) {
            e.preventDefault();

            // PENTING: Gunakan .find('tr').length untuk tabel kosong
            let nomor = $('#tableLoop tbody').find('tr').length + 1;

            let optAkun = akun3.map(a =>
                `<option value="${a.kode_akun3}">${a.kode_akun3} - ${a.nama_akun3}</option>`
            ).join('');

            let optStatus = status.map(s =>
                `<option value="${s.id_status}">${s.status}</option>`
            ).join('');

            let baris = `
                <tr>
                    <td>${nomor}</td>
                    <td>
                        <select name="kode_akun3[]" class="form-control" required>
                            <option value="">-- Pilih Akun --</option>
                            ${optAkun}
                        </select>
                    </td>
                    <td><input type="number" name="debit[]" class="form-control" value="0"></td>
                    <td><input type="number" name="kredit[]" class="form-control" value="0"></td>
                    <td>
                        <select name="id_status[]" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            ${optStatus}
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm hapusBaris">X</button>
                    </td>
                </tr>`;

            $('#tableLoop tbody').append(baris);
        });

        // Listener untuk tombol hapus baris
        $(document).on('click', '.hapusBaris', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            // Optional: Re-index nomor baris setelah hapus
            $('#tableLoop tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        });

    }); // Penutup $(document).ready
</script>

<?= $this->endSection() ?> // <-- TUTUP SECTION JS