<?= $this->extend('layout/backend') ?>

<?= $this->section('content') ?>
<title>SIA IPB &mdash; User </title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
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
                <h4>Profile Pengguna Aplikasi</h4>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">

                    <!-- <?php d($user) ?> -->

                    <div class="row">
                        <div class="col-lg-8">

                            <div class="card mb-3" style="max-width: 540px;">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="<?= base_url('imgFiles/' . $user->gbr) ?>" class="img-fluid rounded-start rounded-circle mr-1" alt="...">
                                    </div>
                                    <div class="col-md-8">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><?= $user->fullname ?></li>
                                            <li class="list-group-item"><?= $user->username ?></li>
                                            <li class="list-group-item"><?= $user->email ?></li>
                                            <li class="list-group-item"><span class="badge badge-success"><?= $user->name ?></span></li>
                                            <li class="list-group-item">
                                                <a href="<?= base_url('admin') ?>">&laquo; Back to List</a>

                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
</section>


<?= $this->endSection() ?>