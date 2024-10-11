<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$_SESSION['username']) {
            echo "<script>alert('Anda harus login terlebih dahulu untuk dapat mengakses halaman ini'); location.href = '" . base_url() . "login'</script>";
        }
        if ($_SESSION['role'] == 'pasien') {
            echo "<script>alert('Anda tidak memiliki izin untuk mengakses halaman ini'); location.href = '" . base_url() . "feedback'</script>";
        }
    }

    public function index()
    {
        // $user = $this->db->get('users')->result_array();
        // $pegawai = $this->db->get('pegawai')->result_array();
        // $jabatan = $this->db->get('jabatan')->result_array();
        // $divisi = $this->db->get('divisi')->result_array();

        $user = [];
        $pegawai = [];
        $jabatan = [];
        $divisi = [];

        $data['user'] = count($user);
        $data['pegawai'] = count($pegawai);
        $data['jabatan'] = count($jabatan);
        $data['divisi'] = count($divisi);

        $this->load->view('v_header', $data);
        $this->load->view('v_dashboard', $data);
        $this->load->view('v_footer');
    }

    #region kelas
    public function daftar_kelas()
    {
        $data['datasets'] = $this->db->query('SELECT kelas.*, pegawai.nama_pegawai as wali_kelas FROM kelas LEFT JOIN pegawai ON pegawai.id = kelas.id_pegawai')->result_array();
        $data['active'] = 'datakelas';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_kelas');
        $this->load->view('v_footer');
    }

    public function tambah_kelas()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            if ((int) $params['id'] > 0) {
                $this->db->where('id', $params['id']);
                $this->db->update('kelas', [
                    'nama_kelas' => $params['nama_kelas'],
                    'id_pegawai' => $params['id_pegawai'],
                    'jam_masuk' => $params['jam_masuk'],
                    'jam_pulang' => $params['jam_pulang'],
                ]);
            } else {
                $this->db->insert('kelas', [
                    'nama_kelas' => $params['nama_kelas'],
                    'id_pegawai' => $params['id_pegawai'],
                    'jam_masuk' => $params['jam_masuk'],
                    'jam_pulang' => $params['jam_pulang'],
                ]);
            }

            echo "<script>alert('Data Kelas berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_kelas';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('kelas')->row_array();
            }

            $data['pegawai'] = $this->db->get('pegawai')->result_array();
            $data['id'] = $id;
            $data['active'] = 'datakelas';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_kelas');
            $this->load->view('v_footer');
        }
    }

    public function delete_kelas()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('kelas');

        echo "<script>alert('Data Kelas berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_kelas';</script>";
    }
    #endregion

    #region data siswa
    public function daftar_siswa()
    {
        $data['datasets'] = $this->db->query(
            'SELECT siswa.*, kelas.nama_kelas, pegawai.nama_pegawai as wali_kelas
            FROM siswa
            LEFT JOIN kelas ON kelas.id = siswa.id_kelas
            LEFT JOIN pegawai ON pegawai.id = kelas.id_pegawai'
        )->result_array();

        $data['active'] = 'datasiswa';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_siswa');
        $this->load->view('v_footer');
    }

    public function tambah_siswa()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            $this->db->where('id', $params['id_kelas']);
            $kelas = $this->db->get('kelas')->row_array();

            $foto = '';
            if ($_FILES['foto']['name']) {
                $upload_dir = './assets/upload/siswa/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $temp_file = $_FILES['foto']['tmp_name'];
                $original_file = $_FILES['foto']['name'];

                $unique_filename = uniqid() . '_' . $original_file;
                if (move_uploaded_file($temp_file, $upload_dir . $unique_filename)) {
                    $foto = $unique_filename;
                    unlink('./assets/upload/siswa/representations_vgg_face.pkl');
                }
            }

            if ((int) $params['id'] > 0) {
                $this->db->where('id', $params['id']);
                $this->db->update('siswa', [
                    'nis' => $params['nis'],
                    'nisn' => $params['nisn'],
                    'nama_lengkap' => $params['nama_lengkap'],
                    'id_kelas' => $params['id_kelas'],
                    'kelas' => $kelas['nama_kelas'],
                    'alamat' => $params['alamat'],
                    'nama_orang_tua' => $params['nama_orang_tua'],
                    'no_handphone' => $params['no_handphone'],
                ]);

                if (!empty($foto)) {
                    $this->db->where('id', $params['id']);
                    $file_lama = $this->db->get('siswa')->row_array();
                    unlink('./assets/upload/siswa/' . $file_lama['foto']);

                    $this->db->where('id', $params['id']);
                    $this->db->update('siswa', [
                        'foto' => $foto,
                    ]);
                }
            } else {
                $this->db->insert('siswa', [
                    'nis' => $params['nis'],
                    'nisn' => $params['nisn'],
                    'nama_lengkap' => $params['nama_lengkap'],
                    'id_kelas' => $params['id_kelas'],
                    'kelas' => $kelas['nama_kelas'],
                    'alamat' => $params['alamat'],
                    'nama_orang_tua' => $params['nama_orang_tua'],
                    'no_handphone' => $params['no_handphone'],
                    'foto' => $foto,
                ]);
            }

            echo "<script>alert('Data Siswa berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_siswa';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('siswa')->row_array();
            }

            $data['kelas'] = $this->db->get('kelas')->result_array();
            $data['id'] = $id;
            $data['active'] = 'datasiswa';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_siswa');
            $this->load->view('v_footer');
        }
    }

    public function delete_siswa()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('pegawai');

        echo "<script>alert('Data Pegawai berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_siswa';</script>";
    }
    #endregion

    #region jabatan
    public function daftar_jabatan()
    {
        $data['datasets'] = $this->db->get('jabatan')->result_array();
        $data['active'] = 'datajabatan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_jabatan');
        $this->load->view('v_footer');
    }

    public function tambah_jabatan()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            if ((int) $params['id'] > 0) {
                $this->db->where('id', $params['id']);
                $this->db->update('jabatan', [
                    'nama_jabatan' => $params['nama_jabatan'],
                ]);
            } else {
                $this->db->insert('jabatan', [
                    'nama_jabatan' => $params['nama_jabatan'],
                ]);
            }

            echo "<script>alert('Data Jabatan berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_jabatan';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('jabatan')->row_array();
            }

            $data['id'] = $id;
            $data['active'] = 'datajabatan';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_jabatan');
            $this->load->view('v_footer');
        }
    }

    public function delete_jabatan()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('jabatan');

        echo "<script>alert('Data Jabatan berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_jabatan';</script>";
    }
    #endregion

    #region pegawai
    public function daftar_pegawai()
    {
        $data['datasets'] = $this->db->query(
            'SELECT pegawai.*, jabatan.nama_jabatan as jabatan
            FROM pegawai
            LEFT JOIN jabatan ON jabatan.id = pegawai.jabatan_id'
        )->result_array();

        $data['active'] = 'datapegawai';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_pegawai');
        $this->load->view('v_footer');
    }

    public function tambah_pegawai()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            $foto = '';
            if ($_FILES['foto']['name']) {
                $upload_dir = './assets/upload/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $temp_file = $_FILES['foto']['tmp_name'];
                $original_file = $_FILES['foto']['name'];

                $unique_filename = uniqid() . '_' . $original_file;
                if (move_uploaded_file($temp_file, $upload_dir . $unique_filename)) {
                    $foto = $unique_filename;
                }
            }

            if ((int) $params['id'] > 0) {
                $this->db->where('id', $params['id']);
                $this->db->update('pegawai', [
                    'nip_pegawai' => $params['nip_pegawai'],
                    'nama_pegawai' => $params['nama_pegawai'],
                    'no_handphone' => $params['no_handphone'],
                    'jabatan_id' => $params['jabatan_id'],
                    'keterangan' => $params['keterangan'],
                ]);

                if ($foto) {
                    $this->db->where('id', $params['id']);
                    $this->db->update('pegawai', [
                        'foto' => $foto,
                    ]);
                }
            } else {
                $input = [
                    'nip_pegawai' => $params['nip_pegawai'],
                    'nama_pegawai' => $params['nama_pegawai'],
                    'no_handphone' => $params['no_handphone'],
                    'jabatan_id' => $params['jabatan_id'],
                    'keterangan' => $params['keterangan'],
                ];
                if ($foto) {
                    $input['foto'] = $foto;
                }
                $this->db->insert('pegawai', $input);
            }

            echo "<script>alert('Data Pegawai berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_pegawai';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('pegawai')->row_array();
            }

            $data['jabatan'] = $this->db->get('jabatan')->result_array();
            $data['id'] = $id;
            $data['active'] = 'datapegawai';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_pegawai');
            $this->load->view('v_footer');
        }
    }

    public function delete_pegawai()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('pegawai');

        echo "<script>alert('Data Pegawai berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_pegawai';</script>";
    }
    #endregion

    #region kegiatan
    public function daftar_kegiatan()
    {
        if (in_array($_SESSION['role'], ['admin'])) {
            $data['datasets'] = $this->db->query(
                'SELECT kegiatan.*, jabatan.nama_jabatan as jabatan, divisi.nama_divisi as divisi, pegawai.nama_pegawai as pegawai, pegawai.nip_pegawai as nip
                FROM kegiatan
                JOIN pegawai ON pegawai.id = kegiatan.id_pegawai
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                JOIN divisi ON divisi.id = pegawai.divisi_id'
            )->result_array();
        } else if (in_array($_SESSION['role'], ['manager'])) {
            $this->db->where('atasan_id', $_SESSION['id_pegawai']);
            $ids_pegawai = $this->db->get('pegawai')->result_array();
            $ids_pegawai = array_column($ids_pegawai, 'id');
            $ids_pegawai = implode(',', $ids_pegawai);

            $data['datasets'] = $this->db->query(
                'SELECT kegiatan.*, jabatan.nama_jabatan as jabatan, divisi.nama_divisi as divisi, pegawai.nama_pegawai as pegawai, pegawai.nip_pegawai as nip
                FROM kegiatan
                JOIN pegawai ON pegawai.id = kegiatan.id_pegawai
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                JOIN divisi ON divisi.id = pegawai.divisi_id
                WHERE kegiatan.id_pegawai IN (' . $ids_pegawai . ') '
            )->result_array();
        } else {
            $data['datasets'] = $this->db->query(
                'SELECT kegiatan.*, jabatan.nama_jabatan as jabatan, divisi.nama_divisi as divisi, pegawai.nama_pegawai as pegawai, pegawai.nip_pegawai as nip
                FROM kegiatan
                JOIN pegawai ON pegawai.id = kegiatan.id_pegawai
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                JOIN divisi ON divisi.id = pegawai.divisi_id
                WHERE kegiatan.id_pegawai = ' . $_SESSION['id_pegawai']
            )->result_array();
        }
        $data['active'] = 'datakegiatan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_kegiatan');
        $this->load->view('v_footer');
    }

    public function tambah_kegiatan()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            if ((int) $params['id'] > 0) {
                $this->db->where('id', $params['id']);
                $this->db->update('kegiatan', [
                    'uraian' => $params['uraian'],
                    'satuan' => $params['satuan'],
                    'target' => $params['target'],
                    'id_pegawai' => $params['id_pegawai'],
                ]);
            } else {
                $this->db->insert('kegiatan', [
                    'uraian' => $params['uraian'],
                    'satuan' => $params['satuan'],
                    'target' => $params['target'],
                    'id_pegawai' => $params['id_pegawai'],
                ]);
            }

            echo "<script>alert('Data Kegiatan berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_kegiatan';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('kegiatan')->row_array();
            }

            if (in_array($_SESSION['role'], ['manager'])) {
                $this->db->where('atasan_id', $_SESSION['id_pegawai']);
                $ids_pegawai = $this->db->get('pegawai')->result_array();
                $ids_pegawai = array_column($ids_pegawai, 'id');

                $this->db->where_in('id', $ids_pegawai);
                $data['pegawai'] = $this->db->get('pegawai')->result_array();
            } else {
                $data['pegawai'] = $this->db->get('pegawai')->result_array();
            }

            $data['id'] = $id;
            $data['active'] = 'datakegiatan';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_kegiatan');
            $this->load->view('v_footer');
        }
    }

    public function delete_kegiatan()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('kegiatan');

        echo "<script>alert('Data Kegiatan berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_kegiatan';</script>";
    }
    #endregion

    #region user
    public function daftar_user()
    {
        $data['datasets'] = $this->db->query(
            'SELECT users.*, pegawai.nama_pegawai as nama, pegawai.nip_pegawai as nip, pegawai.keterangan as keterangan, jabatan.nama_jabatan as jabatan
            FROM users 
            LEFT JOIN pegawai ON pegawai.id = users.id_pegawai
            LEFT JOIN jabatan ON jabatan.id = pegawai.jabatan_id '
        )->result_array();
        $data['active'] = 'datauser';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_user');
        $this->load->view('v_footer');
    }

    public function tambah_user()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            if ($_FILES['foto']['name']) {
                $upload_dir = './assets/upload/avatar/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $temp_file = $_FILES['foto']['tmp_name'];
                $original_file = $_FILES['foto']['name'];

                $unique_filename = uniqid() . '_' . $original_file;
                if (move_uploaded_file($temp_file, $upload_dir . $unique_filename)) {
                    // echo "File uploaded successfully!";
                } else {
                    // echo "Error uploading file.";
                }
            } else {
                // echo "No file uploaded or an error occurred.";
            }

            if ($unique_filename) {
                if ((int) $params['id'] > 0) {
                    $input = [
                        'email' => $params['email'],
                        'username' => $params['username'],
                        'password' => md5($params['password']),
                        'foto' => $unique_filename,
                        'role' => $params['role'],
                        'id_pegawai' => $params['id_pegawai'],
                    ];
                    if (empty($params['password'])) {
                        unset($input['password']);
                    }
                    if ($input['role'] == 'admin') {
                        $input['id_pegawai'] = 0;
                    }

                    $this->db->where('id', $params['id']);
                    $this->db->update('users', $input);
                } else {
                    $this->db->insert('users', [
                        'email' => $params['email'],
                        'username' => $params['username'],
                        'password' => md5($params['password']),
                        'foto' => $unique_filename,
                        'role' => $params['role'],
                        'id_pegawai' => $params['role'] == 'admin' ? 0 : $params['id_pegawai'],
                    ]);
                }
            } else {
                if ((int) $params['id'] > 0) {
                    $input = [
                        'email' => $params['email'],
                        'username' => $params['username'],
                        'password' => md5($params['password']),
                        'role' => $params['role'],
                        'id_pegawai' => $params['id_pegawai'],
                    ];
                    if (empty($params['password'])) {
                        unset($input['password']);
                    }
                    if ($input['role'] == 'admin') {
                        $input['id_pegawai'] = 0;
                    }

                    $this->db->where('id', $params['id']);
                    $this->db->update('users', $input);
                } else {
                    $this->db->insert('users', [
                        'email' => $params['email'],
                        'username' => $params['username'],
                        'password' => md5($params['password']),
                        'foto' => 'default.jpg',
                        'role' => $params['role'],
                        'id_pegawai' => $params['role'] == 'admin' ? 0 : $params['id_pegawai'],
                    ]);
                }
            }

            echo "<script>alert('Data berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_user';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = $params['id'];
            if ((int) $id > 0) {
                $this->db->where('id', $id);
                $data['fetch'] = $this->db->get('users')->row_array();
            }

            $data['id'] = $id;
            $data['active'] = 'datauser';
            $data['pegawai'] = $this->db->query(
                'SELECT pegawai.* FROM pegawai
                LEFT JOIN users ON users.id_pegawai = pegawai.id
                WHERE users.id IS NULL'
            )->result_array();
            $data['pegawai2'] = $this->db->get('pegawai')->result_array();
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_user');
            $this->load->view('v_footer');
        }
    }

    public function delete_user()
    {
        $params = $_REQUEST;
        $this->db->where('id', $params['id']);
        $this->db->delete('users');

        echo "<script>alert('Dataset berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_user';</script>";
    }
    #endregion

    #region laporan
    public function daftar_laporan()
    {
        // if (in_array($_SESSION['role'], ['admin', 'manager'])) {
        //     $data['datasets'] = $this->db->query(
        //         'SELECT kegiatan.*, jabatan.nama_jabatan as jabatan, divisi.nama_divisi as divisi, pegawai.nama_pegawai as pegawai, pegawai.nip_pegawai as nip
        //         FROM laporan
        //         JOIN pegawai ON pegawai.id = kegiatan.id_pegawai
        //         JOIN jabatan ON jabatan.id = pegawai.jabatan_id
        //         JOIN divisi ON divisi.id = pegawai.divisi_id'
        //     )->result_array();
        // } else {
        //     $data['datasets'] = $this->db->query(
        //         'SELECT kegiatan.*, jabatan.nama_jabatan as jabatan, divisi.nama_divisi as divisi, pegawai.nama_pegawai as pegawai, pegawai.nip_pegawai as nip
        //         FROM laporan
        //         JOIN pegawai ON pegawai.id = kegiatan.id_pegawai
        //         JOIN jabatan ON jabatan.id = pegawai.jabatan_id
        //         JOIN divisi ON divisi.id = pegawai.divisi_id
        //         WHERE kegiatan.id_pegawai = '.$_SESSION['id_pegawai']
        //     )->result_array();
        // }

        $from_date = $_REQUEST['from_date'] ? $_REQUEST['from_date'] : date('Y-m-d');
        $to_date = $_REQUEST['to_date'] ? $_REQUEST['to_date'] : '';

        // if ($from_date && $to_date) {
        if ($from_date) {
            $olah_data = $this->db->query(
                'SELECT siswa.*, kelas.nama_kelas, pegawai.nama_pegawai as wali_kelas
                FROM siswa
                LEFT JOIN kelas ON kelas.id = siswa.id_kelas
                LEFT JOIN pegawai ON pegawai.id = kelas.id_pegawai
                ORDER BY siswa.nama_lengkap ASC'
            )->result_array();

            $this->db->where('tanggal', $from_date);
            $data_absen = $this->db->get('laporan')->result_array();

            $mapping = [];
            foreach ($data_absen as $i) {
                $mapping[$i['id_siswa']] = $i;
            }

            foreach ($olah_data as &$a) {
                $a['waktu_masuk'] = $mapping[$a['id']]['waktu_masuk'];
                $a['waktu_pulang'] = $mapping[$a['id']]['waktu_pulang'];
                $a['tanggal'] = $mapping[$a['id']]['tanggal'];
                $a['status'] = $mapping[$a['id']]['status'];
                $a['keterangan'] = $mapping[$a['id']]['keterangan'];
            }

            $data['datasets'] = $olah_data;
        } else {
            $data['datasets'] = [];
        }

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $data['active'] = 'laporan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_laporan');
        $this->load->view('v_footer');
    }

    public function tambah_laporan()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $params = $this->input->post();

            $id = 0;

            $this->db->where('tanggal', $params['tanggal']);
            $this->db->where('id_siswa', $params['id_siswa']);
            $data['fetch'] = $this->db->get('laporan')->row_array();
            if (!empty($data['fetch'])) {
                $id = $data['fetch']['id'];
            }

            if ((int) $id > 0) {
                $input = [
                    'id_siswa' => $params['id_siswa'],
                    'waktu_masuk' => $params['waktu_masuk'],
                    'waktu_pulang' => $params['waktu_pulang'],
                    'tanggal' => $params['tanggal'],
                    'status' => $params['status'],
                    'keterangan' => $params['keterangan'],
                ];
                $this->db->where('id', $id);
                $this->db->update('laporan', $input);
            } else {
                $input = [
                    'id_siswa' => $params['id_siswa'],
                    'waktu_masuk' => $params['waktu_masuk'],
                    'waktu_pulang' => $params['waktu_pulang'],
                    'tanggal' => $params['tanggal'],
                    'status' => $params['status'],
                    'keterangan' => $params['keterangan'],
                ];
                $this->db->insert('laporan', $input);
            }

            echo "<script>alert('Data Presensi berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_laporan?from_date=" . $params['tanggal'] . "';</script>";
        } else {
            $params = $_REQUEST;
            $data['fetch'] = [];
            $id = 0;

            $this->db->where('tanggal', $params['tanggal']);
            $this->db->where('id_siswa', $params['id_siswa']);
            $data['fetch'] = $this->db->get('laporan')->row_array();

            $this->db->where('id', $params['id_siswa']);
            $data['siswa'] = $this->db->get('siswa')->row_array();

            $data['id'] = $id;
            $data['active'] = 'laporan';
            $this->load->view('v_header', $data);
            $this->load->view('v_tambah_laporan');
            $this->load->view('v_footer');
        }
    }

    // public function delete_laporan()
    // {
    //     $params = $_REQUEST;
    //     $this->db->where('id', $params['id']);
    //     $this->db->delete('laporan');

    //     echo "<script>alert('Data Laporan berhasil dihapus'); location.href = '" . base_url() . "dashboard/daftar_laporan';</script>";
    // }

    public function laporan()
    {
        date_default_timezone_set("Asia/Jakarta");
        $from_date = $_REQUEST['from_date'] ? $_REQUEST['from_date'] : date('Y-m-01');
        $to_date = $_REQUEST['to_date'] ? $_REQUEST['to_date'] : date('Y-m-d', strtotime('+1 day'));
        $pegawai_sel = $_REQUEST['pegawai_sel'] ? $_REQUEST['pegawai_sel'] : "";

        $this->db->where('atasan_id', $_SESSION['id_pegawai']);
        $ids_pegawai = $this->db->get('pegawai')->result_array();
        $_ids_pegawai = array_column($ids_pegawai, 'id');
        $ids_pegawai = implode(',', $_ids_pegawai);

        $data['datasets'] = $this->db->query(
            'SELECT laporan.*, kegiatan.uraian, kegiatan.satuan, kegiatan.target
            FROM laporan
            JOIN kegiatan ON kegiatan.id = laporan.kegiatan_id
            WHERE kegiatan.id_pegawai = "' . $pegawai_sel . '"
            AND laporan.waktu >= "' . $from_date . '"
            AND laporan.waktu <= "' . $to_date . '"
            AND status_atasan = "Approved"
            ORDER BY laporan.id DESC'
        )->result_array();

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['pegawai_sel'] = $pegawai_sel;
        $data['pegawai_info'] = [];
        if ($pegawai_sel) {
            $data['pegawai_info'] = $this->db->query(
                'SELECT pegawai.*, divisi.nama_divisi as divisi, jabatan.nama_jabatan as jabatan
                FROM pegawai
                JOIN divisi ON divisi.id = pegawai.divisi_id
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                WHERE pegawai.id = "' . $pegawai_sel . '" '
            )->row_array();
        }

        if ($_SESSION['role'] == 'manager') {
            $this->db->where_in('id', $_ids_pegawai);
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        } else {
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        }

        $data['active'] = 'laporan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_laporan_hari');
        $this->load->view('v_footer');
    }

    public function laporan_cetak()
    {
        date_default_timezone_set("Asia/Jakarta");
        $from_date = $_REQUEST['from_date'] ? $_REQUEST['from_date'] : date('Y-m-d');
        $to_date = $_REQUEST['to_date'] ? $_REQUEST['to_date'] : date('Y-m-d', strtotime('+1 day'));
        $pegawai_sel = $_REQUEST['pegawai_sel'] ? $_REQUEST['pegawai_sel'] : "";

        $this->db->where('atasan_id', $_SESSION['id_pegawai']);
        $ids_pegawai = $this->db->get('pegawai')->result_array();
        $_ids_pegawai = array_column($ids_pegawai, 'id');
        $ids_pegawai = implode(',', $_ids_pegawai);

        $data['datasets'] = $this->db->query(
            'SELECT laporan.*, kegiatan.uraian, kegiatan.satuan, kegiatan.target
            FROM laporan
            JOIN kegiatan ON kegiatan.id = laporan.kegiatan_id
            WHERE kegiatan.id_pegawai = "' . $pegawai_sel . '"
            AND laporan.waktu >= "' . $from_date . '"
            AND laporan.waktu <= "' . $to_date . '"
            AND status_atasan = "Approved"
            ORDER BY laporan.waktu_selesai ASC'
        )->result_array();

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['pegawai_sel'] = $pegawai_sel;
        $data['pegawai_info'] = [];
        $data['atasan_info'] = [];
        if ($pegawai_sel) {
            $data['pegawai_info'] = $this->db->query(
                'SELECT pegawai.*, divisi.nama_divisi as divisi, jabatan.nama_jabatan as jabatan
                FROM pegawai
                JOIN divisi ON divisi.id = pegawai.divisi_id
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                WHERE pegawai.id = "' . $pegawai_sel . '" '
            )->row_array();
            $data['atasan_info'] = $this->db->query(
                'SELECT pegawai.*, divisi.nama_divisi as divisi, jabatan.nama_jabatan as jabatan
                FROM pegawai
                JOIN divisi ON divisi.id = pegawai.divisi_id
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                WHERE pegawai.id = "' . $data['pegawai_info']['atasan_id'] . '" '
            )->row_array();
        }

        if ($_SESSION['role'] == 'manager') {
            $this->db->where_in('id', $_ids_pegawai);
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        } else {
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        }

        $month_str = '';
        $curr_month = date('m');
        if ($curr_month == '01') {
            $month_str = 'Januari';
        } elseif ($curr_month == '02') {
            $month_str = 'Februari';
        } elseif ($curr_month == '03') {
            $month_str = 'Maret';
        } elseif ($curr_month == '04') {
            $month_str = 'April';
        } elseif ($curr_month == '05') {
            $month_str = 'Mei';
        } elseif ($curr_month == '06') {
            $month_str = 'Juni';
        } elseif ($curr_month == '07') {
            $month_str = 'Juli';
        } elseif ($curr_month == '08') {
            $month_str = 'Agustus';
        } elseif ($curr_month == '09') {
            $month_str = 'September';
        } elseif ($curr_month == '10') {
            $month_str = 'Oktober';
        } elseif ($curr_month == '11') {
            $month_str = 'November';
        } elseif ($curr_month == '12') {
            $month_str = 'Desember';
        }

        $from_month = date('m', strtotime($from_date));
        $to_month = date('m', strtotime($to_date));
        $from_year = date('Y', strtotime($from_date));
        if ($from_month == $to_month) {
            $periode = $month_str . ' ' . $from_year;
        } else {
            $periode = $from_date . ' s/d ' . $to_date;
        }

        $data['periode'] = $periode;
        $data['month_str'] = $month_str;
        $data['active'] = 'laporan';
        // $this->load->view('v_header', $data);
        $this->load->view('v_laporan_cetak', $data);
        // $this->load->view('v_footer');
    }

    /*
    public function daftar_laporan_hari()
    {
        date_default_timezone_set("Asia/Jakarta");
        $from_date = $_REQUEST['from_date'] ? $_REQUEST['from_date'] : date('Y-m-d');
        $to_date = $_REQUEST['to_date'] ? $_REQUEST['to_date'] : date('Y-m-d', strtotime('+1 day'));
        $pegawai_sel = $_REQUEST['pegawai_sel'] ? $_REQUEST['pegawai_sel'] : "";

        $this->db->where('atasan_id', $_SESSION['id_pegawai']);
        $ids_pegawai = $this->db->get('pegawai')->result_array();            
        $_ids_pegawai = array_column($ids_pegawai, 'id');
        $ids_pegawai = implode(',', $_ids_pegawai);

        $data['datasets'] = $this->db->query(
            'SELECT laporan.*, kegiatan.uraian, kegiatan.satuan, kegiatan.target
            FROM laporan
            JOIN kegiatan ON kegiatan.id = laporan.kegiatan_id
            WHERE kegiatan.id_pegawai = "'.$pegawai_sel.'"
            AND laporan.waktu >= "'.$from_date.'"
            AND laporan.waktu <= "'.$to_date.'"
            AND status_atasan = "Approved"
            ORDER BY laporan.id DESC'
        )->result_array(); 

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['pegawai_sel'] = $pegawai_sel;
        $data['pegawai_info'] = [];
        if ($pegawai_sel) {
            $data['pegawai_info'] = $this->db->query(
                'SELECT pegawai.*, divisi.nama_divisi as divisi, jabatan.nama_jabatan as jabatan
                FROM pegawai
                JOIN divisi ON divisi.id = pegawai.divisi_id
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                WHERE pegawai.id = "'.$pegawai_sel.'" '
            )->row_array();
        }

        if ($_SESSION['role'] == 'manager') {
            $this->db->where_in('id', $_ids_pegawai);
            $data['pegawai'] = $this->db->get('pegawai')->result_array();

        } else {
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        }

        $data['active'] = 'laporan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_laporan_hari');
        $this->load->view('v_footer');
    }
    */

    /*
    public function daftar_laporan_bulan()
    {
        date_default_timezone_set("Asia/Jakarta");
        $from_date = $_REQUEST['from_date'] ? $_REQUEST['from_date'] : date('Y-m-d');
        $to_date = $_REQUEST['to_date'] ? $_REQUEST['to_date'] : date('Y-m-d', strtotime('+1 day'));
        $pegawai_sel = $_REQUEST['pegawai_sel'] ? $_REQUEST['pegawai_sel'] : "";

        $this->db->where('atasan_id', $_SESSION['id_pegawai']);
        $ids_pegawai = $this->db->get('pegawai')->result_array();            
        $_ids_pegawai = array_column($ids_pegawai, 'id');
        $ids_pegawai = implode(',', $_ids_pegawai);

        $data['datasets'] = $this->db->query(
            'SELECT laporan.*, kegiatan.uraian, kegiatan.satuan, kegiatan.target
            FROM laporan
            JOIN kegiatan ON kegiatan.id = laporan.kegiatan_id
            WHERE kegiatan.id_pegawai = "'.$pegawai_sel.'"
            AND laporan.waktu >= "'.$from_date.'"
            AND laporan.waktu <= "'.$to_date.'"
            AND status_atasan = "Approved"
            ORDER BY laporan.id DESC
            GROUP BY kegiatan.uraian'
        )->result_array(); 

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['pegawai_sel'] = $pegawai_sel;
        $data['pegawai_info'] = [];
        if ($pegawai_sel) {
            $data['pegawai_info'] = $this->db->query(
                'SELECT pegawai.*, divisi.nama_divisi as divisi, jabatan.nama_jabatan as jabatan
                FROM pegawai
                JOIN divisi ON divisi.id = pegawai.divisi_id
                JOIN jabatan ON jabatan.id = pegawai.jabatan_id
                WHERE pegawai.id = "'.$pegawai_sel.'" '
            )->row_array();
        }

        if ($_SESSION['role'] == 'manager') {
            $this->db->where_in('id', $_ids_pegawai);
            $data['pegawai'] = $this->db->get('pegawai')->result_array();

        } else {
            $data['pegawai'] = $this->db->get('pegawai')->result_array();
        }

        $data['active'] = 'laporan';
        $this->load->view('v_header', $data);
        $this->load->view('v_daftar_laporan_hari');
        $this->load->view('v_footer');
    }
    */
    #endregion

    public function import()
    {
        $upload_success = false;
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if ($file_ext == "csv") {
            $upload_success = true;
        }
        if ($upload_success) {
            $file = fopen($_FILES['file']['tmp_name'], "r");
            $excel_data = explode("\r", reset(fgetcsv($file)));
            // var_dump($excel_data);
            // exit;
            while (($arr = fgetcsv($file)) !== false) {
                if ($arr[2] == 'positif') {
                    $label = '1';
                } elseif ($arr[2] == 'negatif') {
                    $label = '0';
                } else {
                    $label = '2';
                }
                $this->db->insert('datasets', [
                    'text' => $arr[1],
                    'label' => $label,
                    'pre_processing_text' => '',
                    'predicted_label' => 0
                ]);
            }
            echo "<script>alert('Dataset berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_dataset';</script>";
        } else {
            echo "<script>alert('Dataset gagal disimpan, pastikan format import .csv dan data sudah terisi semua'); location.href = '" . base_url() . "dashboard/daftar_dataset';</script>";
        }
    }

    public function import2()
    {
        $upload_success = false;
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if ($file_ext == "csv") {
            $upload_success = true;
        }
        if ($upload_success) {
            $file = fopen($_FILES['file']['tmp_name'], "r");
            $excel_data = explode("\r", reset(fgetcsv($file)));
            // var_dump($excel_data);
            // exit;
            while (($arr = fgetcsv($file)) !== false) {
                if ($arr[2] == 'positif') {
                    $label = '1';
                } elseif ($arr[2] == 'negatif') {
                    $label = '0';
                } else {
                    $label = '2';
                }
                $this->db->insert('datalatih', [
                    'text' => $arr[1],
                    'label' => $label,
                    'pre_processing_text' => '',
                    'predicted_label' => 0
                ]);
            }
            echo "<script>alert('Data Latih berhasil disimpan'); location.href = '" . base_url() . "dashboard/daftar_datalatih';</script>";
        } else {
            echo "<script>alert('Data Latih gagal disimpan, pastikan format import .csv dan data sudah terisi semua'); location.href = '" . base_url() . "dashboard/daftar_datalatih';</script>";
        }
    }

    public function data_admin()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $foto = '';
            if ($_FILES['foto']['name']) {
                $upload_dir = './assets/upload/avatar/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $temp_file = $_FILES['foto']['tmp_name'];
                $original_file = $_FILES['foto']['name'];

                $unique_filename = uniqid() . '_' . $original_file;
                if (move_uploaded_file($temp_file, $upload_dir . $unique_filename)) {
                    // echo "File uploaded successfully!";
                } else {
                    // echo "Error uploading file.";
                }
            } else {
                // echo "No file uploaded or an error occurred.";
            }

            $params = $this->input->post();
            if ($unique_filename) {
                $this->db->where('id', $_SESSION['id']);
                $this->db->update('users', [
                    'email' => $params['email'],
                    'username' => $params['username'],
                    'password' => md5($params['password']),
                    'foto' => $unique_filename
                ]);
                $_SESSION['email'] = $params['email'];
                $_SESSION['username'] = $params['username'];
                $_SESSION['password'] = $params['password'];
                $_SESSION['foto'] = $unique_filename;
            } else {
                $this->db->where('id', $_SESSION['id']);
                $this->db->update('users', [
                    'email' => $params['email'],
                    'username' => $params['username'],
                    'password' => md5($params['password']),
                ]);
                $_SESSION['email'] = $params['email'];
                $_SESSION['username'] = $params['username'];
                $_SESSION['password'] = $params['password'];
            }

            echo "<script>alert('Data berhasil disimpan'); location.href = '" . base_url() . "dashboard/data_admin';</script>";
        } else {
            $data['active'] = 'data_admin';
            $this->load->view('v_header', $data);
            $this->load->view('v_data_admin');
            $this->load->view('v_footer');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/login', 'refresh');
    }
}
