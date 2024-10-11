<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-gray-800 mb-3">Daftar Siswa</h1>

    <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="<?php echo base_url(); ?>dashboard/tambah_siswa" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mb-3"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data</a>
        <!-- <a href="<?php echo base_url(); ?>assets/template_pegawai.csv" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mb-3"><i class="fas fa-file fa-sm text-white-50"></i> Download Template</a> -->
    <?php } ?>

    <a href="#" id="exportData" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-3"><i class="fas fa-download fa-sm text-white-50"></i> Export Data</a>

    <?php if ($_SESSION['role'] == 'admin') { ?>
        <!-- <a onclick="$('#filename').click();" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm mb-3" style="cursor: pointer;"><i class="fas fa-upload fa-sm text-white-50"></i> Import Data</a> -->
    <?php } ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="datatable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Lengkap</th>
                                    <th>Kelas</th>
                                    <th>Alamat</th>
                                    <th>Nama Orang Tua</th>
                                    <th>No Handphone</th>
                                    <th>Wali Kelas</th>
                                    <th class="exclude-export">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($datasets as $d) { ?>
                                    <tr>
                                        <td><?php echo $no;
                                            $no++; ?></td>
                                        <td><?php echo $d['nis']; ?></td>
                                        <td><?php echo $d['nisn']; ?></td>
                                        <td><?php echo $d['nama_lengkap']; ?></td>
                                        <td><?php echo $d['nama_kelas']; ?></td>
                                        <td><?php echo $d['alamat']; ?></td>
                                        <td><?php echo $d['nama_orang_tua']; ?></td>
                                        <td><?php echo $d['no_handphone']; ?></td>
                                        <td><?php echo $d['wali_kelas']; ?></td>
                                        <td class="exclude-export">
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url(); ?>dashboard/tambah_siswa?id=<?php echo $d['id']; ?>"><i class="fa fa-pen"></i></a>
                                            <?php if ($_SESSION['role'] == 'admin') { ?>
                                                <a class="btn btn-sm btn-danger" href="<?php echo base_url(); ?>dashboard/delete_siswa?id=<?php echo $d['id']; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <form id="fileUploadForm" method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>dashboard/import_pegawai">
                        <input style="display: none;" type="file" id="filename" name="file" />
                    </form>
                    <script>
                        const fileInput = document.getElementById('filename');
                        fileInput.addEventListener('change', () => {
                            if (fileInput.files.length > 0) {
                                document.getElementById('fileUploadForm').submit();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->