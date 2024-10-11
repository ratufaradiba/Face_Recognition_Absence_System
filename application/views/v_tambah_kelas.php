<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-gray-800">Tambah/Perbarui Kelas</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Kelas</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nama_kelas" class="form-control" id="" placeholder="" value="<?php echo $fetch['nama_kelas']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Wali Kelas</label>
                            <div class="col-sm-9">
                                <select required class="form-control" name="id_pegawai">
                                    <option value="">Pilih Wali Kelas</option>
                                    <?php foreach ($pegawai as $i) { ?>
                                        <option <?php if ($i['id'] == $fetch['id_pegawai']) {
                                                    echo "selected='selected'";
                                                } ?> value="<?php echo $i['id']; ?>"><?php echo $i['nama_pegawai']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Jam Masuk</label>
                            <div class="col-sm-9">
                                <input required type="time" name="jam_masuk" class="form-control" id="" placeholder="" value="<?php echo $fetch['jam_masuk']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Jam Pulang</label>
                            <div class="col-sm-9">
                                <input type="time" name="jam_pulang" class="form-control" id="" placeholder="" value="<?php echo $fetch['jam_pulang']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button class="btn btn-md btn-info">Simpan</button>
                                <a href="<?php echo base_url(); ?>dashboard/daftar_kelas" class="btn btn-md btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->