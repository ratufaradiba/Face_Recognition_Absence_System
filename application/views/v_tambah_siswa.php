<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-gray-800">Tambah/Perbarui Siswa</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">NIS</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nis" class="form-control" id="" placeholder="" value="<?php echo $fetch['nis']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">NISN</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nisn" class="form-control" id="" placeholder="" value="<?php echo $fetch['nisn']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nama_lengkap" class="form-control" id="" placeholder="" value="<?php echo $fetch['nama_lengkap']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <select required class="form-control" name="id_kelas">
                                    <option value="">Pilih Kelas</option>
                                    <?php foreach ($kelas as $i) { ?>
                                        <option <?php if ($i['id'] == $fetch['id_kelas']) {
                                                    echo "selected='selected'";
                                                } ?> value="<?php echo $i['id']; ?>"><?php echo $i['nama_kelas']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <input required type="text" name="alamat" class="form-control" id="" placeholder="" value="<?php echo $fetch['alamat']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Orang Tua</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nama_orang_tua" class="form-control" id="" placeholder="" value="<?php echo $fetch['nama_orang_tua']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">No Handphone</label>
                            <div class="col-sm-9">
                                <input required type="number" name="no_handphone" class="form-control" id="" placeholder="" value="<?php echo $fetch['no_handphone']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Foto</label>
                            <div class="col-sm-9">
                                <?php if ($fetch['foto']) { ?>
                                    <a href="<?php echo base_url() . 'assets/upload/siswa/' . $fetch['foto']; ?>" target="_blank"><img class="mb-3" src="<?php echo base_url() . 'assets/upload/siswa/' . $fetch['foto']; ?>" style="width: 200px; height: 200px; object-fit: cover;" /></a><br />
                                <?php } ?>
                                <input <?php if (empty($fetch)) { ?> required <?php } ?> type="file" name="foto" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button class="btn btn-md btn-info">Simpan</button>
                                <a href="<?php echo base_url(); ?>dashboard/daftar_siswa" class="btn btn-md btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->