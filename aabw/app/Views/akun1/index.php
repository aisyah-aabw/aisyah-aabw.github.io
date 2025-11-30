<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA IPB &mdash; Akun 1</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('akun1/new') ?>" class="btn btn-primary"> Add New</a>
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
                <h4>Data Akun 1</h4>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-md" id="myTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Akun1</th>
                                <th>Nama Akun</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dtakun1 as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value->kode_akun1 ?></td>
                                    <td><?= $value->nama_akun1 ?></td>
                                    <td class="text-center" style="width:15%">
                                        <a href="<?= site_url('akun1/edit/' . $value->id_akun1) ?>" class="btn btn-warning">
                                            <i class="fas fa-pencil-alt btn-small"></i> Edit
                                        </a>
                                        <form action="<?= site_url('akun1/' . $value->id_akun1) ?>" method="post" id="del-<?= $value->id_akun1 ?>" class="d-inline">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" class="btn btn-danger btn-small" onclick="hapus(<?= $value->id_akun1 ?>)">
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
            text: "yakin nih mau hapus data?",
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