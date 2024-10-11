<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-gray-800">Tambah/Perbarui Pegawai</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">NIP</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nip_pegawai" class="form-control" id="" placeholder="" value="<?php echo $fetch['nip_pegawai']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input required type="text" name="nama_pegawai" class="form-control" id="" placeholder="" value="<?php echo $fetch['nama_pegawai']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">No Handphone</label>
                            <div class="col-sm-9">
                                <input required type="number" name="no_handphone" class="form-control" id="" placeholder="" value="<?php echo $fetch['no_handphone']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-9">
                                <select required class="form-control" name="jabatan_id">
                                    <option value="">Pilih Jabatan</option>
                                    <?php foreach ($jabatan as $i) { ?>
                                        <option <?php if ($i['id'] == $fetch['jabatan_id']) {
                                                    echo "selected='selected'";
                                                } ?> value="<?php echo $i['id']; ?>"><?php echo $i['nama_jabatan']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <input type="text" name="keterangan" class="form-control" id="" placeholder="" value="<?php echo $fetch['keterangan']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Foto</label>
                            <div class="col-sm-9">
                                <?php if ($fetch['foto']) { ?>
                                    <a href="<?php echo base_url() . 'assets/upload/' . $fetch['foto']; ?>" target="_blank"><img class="mb-3" src="<?php echo base_url() . 'assets/upload/' . $fetch['foto']; ?>" style="width: 200px; height: 200px; object-fit: cover;" /></a><br />
                                <?php } ?>
                                <input type="file" name="foto" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button class="btn btn-md btn-info">Simpan</button>
                                <a href="<?php echo base_url(); ?>dashboard/daftar_pegawai" class="btn btn-md btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->