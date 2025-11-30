<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA IPB &mdash; Transaksi</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('transaksi/new') ?>" class="btn btn-primary"> Add New</a>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"> x </button>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"> x </button>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Transaksi Jurnal</h4>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-md" id="myTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kwitansi</th>
                                <th>Tanggal</th>
                                <th>Keterangan Jurnal</th>
                                <th>Deskripsi</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dttransaksi as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value->kwitansi ?></td>
                                    <td><?= $value->tanggal ?></td>
                                    <td><?= $value->deskripsi ?></td>
                                    <td><?= $value->ketjurnal ?></td>


                                    <td class="text-center" style="width:20%">
                                        <a href="<?= site_url('transaksi/' . $value->id_transaksi) ?>" class="btn btn-info btn-small"><i class="fas fa-bars btn-small"></i> Detail </a>


                                        <a href="<?= site_url('transaksi/' . $value->id_transaksi) . '/edit' ?>" class="btn btn-warning">
                                            <i class="fas fa-pencil-alt btn-small"></i> Edit
                                        </a>
                                        <form action="<?= site_url('transaksi/' . $value->id_transaksi) ?>" method="post" id="del-<?= $value->id_transaksi ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" class="btn btn-danger btn-small" onclick="hapus(<?= $value->id_transaksi ?>)">
                                                <i class="fas fa-trash"></i> Del
                                            </button>
                                        </form>
                                    </td>
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
<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function hapus(id) {
        console.log('Function hapus dipanggil dengan id:', id);

        Swal.fire({
            title: 'Hapus Data?',
            text: "yakin hapus data? Soalnya bakal kehapus semua",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yaudah!',
            cancelButtonText: 'Ga jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('User mengkonfirmasi hapus data id:', id);
                document.getElementById('del-' + id).submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>