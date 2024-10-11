<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-gray-800">Tambah/Perbarui Laporan</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="id_siswa" value="<?php echo $_REQUEST['id_siswa']; ?>" />
                        <input type="hidden" name="tanggal" value="<?php echo $_REQUEST['tanggal']; ?>" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">NIS</label>
                            <div class="col-sm-9">
                                <input disabled type="text" value="<?php echo $siswa['nis']; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">NISN</label>
                            <div class="col-sm-9">
                                <input disabled type="text" value="<?php echo $siswa['nisn']; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input disabled type="text" value="<?php echo $siswa['nama_lengkap']; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <input disabled type="text" value="<?php echo $siswa['kelas']; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Tanggal</label>
                            <div class="col-sm-9">
                                <input disabled type="date" name="tanggalz" class="form-control" id="" placeholder="" value="<?php echo $_REQUEST['tanggal']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Waktu Masuk</label>
                            <div class="col-sm-9">
                                <input required type="datetime-local" name="waktu_masuk" class="form-control" id="" placeholder="" value="<?php echo $fetch['waktu_masuk']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Waktu Pulang</label>
                            <div class="col-sm-9">
                                <input type="datetime-local" name="waktu_pulang" class="form-control" id="" placeholder="" value="<?php echo $fetch['waktu_pulang']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select <?php if ($fetch['status_atasan']) {
                                            echo "disabled";
                                        } ?> required class="form-control" <?php if (!$fetch['status_atasan']) {
                                                                                echo "name='status'";
                                                                            } ?>>
                                    <!-- <option value="">Pilih Status</option> -->
                                    <option <?php if ($fetch['status'] == 'Tidak Hadir' || empty($fetch['status'])) {
                                                echo "selected='selected'";
                                            } ?> value="Tidak Hadir">Tidak Hadir</option>
                                    <option <?php if ($fetch['status'] == 'Hadir') {
                                                echo "selected='selected'";
                                            } ?> value="Hadir">Hadir</option>
                                    <option <?php if ($fetch['status'] == 'Izin') {
                                                echo "selected='selected'";
                                            } ?> value="Izin">Izin</option>
                                    <option <?php if ($fetch['status'] == 'Sakit') {
                                                echo "selected='selected'";
                                            } ?> value="Sakit">Sakit</option>
                                    <option <?php if ($fetch['status'] == 'Terlambat') {
                                                echo "selected='selected'";
                                            } ?> value="Terlambat">Terlambat</option>
                                </select>
                                <?php if ($fetch['status_atasan']) { ?>
                                    <input type="hidden" name="status" value="<?php echo $fetch['status']; ?>" />
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <input type="text" name="keterangan" class="form-control" id="" placeholder="" value="<?php echo $fetch['keterangan']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button class="btn btn-md btn-info">Simpan</button>
                                <a style="cursor: pointer;" onclick="window.history.back();" class="btn btn-md btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->