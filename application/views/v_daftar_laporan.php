<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-gray-800 mb-3">Daftar Laporan</h1>
    <!-- <a href="<?php echo base_url(); ?>dashboard/tambah_laporan" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mb-3"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Laporan</a> -->
    <a href="#" id="exportData" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-3"><i class="fas fa-download fa-sm text-white-50"></i> Export Data</a>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Pilih Tanggal</label>
                                    <input type="date" name="from_date" class="form-control" value="<?php echo $from_date ? $from_date : date('Y-m-d'); ?>" />
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <input type="date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" />
                                </div>
                            </div> -->
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label style="visibility: hidden;">.</label> <br />
                                    <input type="submit" class="btn btn-md btn-success w-100" value="Filter Tanggal" />
                                    <?php
                                    // if ($from_date && $to_date) {
                                    if ($from_date) {
                                    ?>
                                        <a href="<?php echo base_url(); ?>dashboard/daftar_laporan" class="btn btn-md btn-danger w-100 mt-2">Reset Filter</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr />

                    <div class="table-responsive">
                        <table class="table table-bordered" id="datatable2" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Pulang</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
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
                                        <td><?php echo $d['wali_kelas']; ?></td>
                                        <td><?php echo $d['tanggal']; ?></td>
                                        <td><?php echo $d['waktu_masuk']; ?></td>
                                        <td><?php echo $d['waktu_pulang']; ?></td>
                                        <td><?php echo $d['status']; ?></td>
                                        <td><?php echo $d['keterangan']; ?></td>
                                        <td class="exclude-export">
                                            <a class="btn btn-sm btn-info" href="<?php echo base_url(); ?>dashboard/tambah_laporan?tanggal=<?php echo $from_date; ?>&id_siswa=<?php echo $d['id']; ?>"><i class="fa fa-pen"></i></a>
                                            <!-- <?php if (!$d['status_atasan']) { ?>
                                                <a class="btn btn-sm btn-danger" href="<?php echo base_url(); ?>dashboard/delete_laporan?id=<?php echo $d['id']; ?>"><i class="fa fa-trash"></i></a>
                                            <?php } ?> -->
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <form id="fileUploadForm" method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>dashboard/import">
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