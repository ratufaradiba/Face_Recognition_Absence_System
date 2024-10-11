<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginPresensi extends CI_Controller
{

	public function index()
    {
        $this->load->view('v_login_presensi');
    }

    public function do_login()
    {
        $params = $this->input->post();
        $this->db->where('username', $params['username']);
        $this->db->where('password', md5($params['password']));
        $login = $this->db->get('users')->row_array();
        if ($login) {
            if($login['role'] != 'admin'){
                $_SESSION['id'] = $login['id'];
                $_SESSION['username'] = $params['username'];
                $_SESSION['password'] = $params['password'];
                $_SESSION['email'] = $login['email'];
                $_SESSION['foto'] = $login['foto'];
                // $_SESSION['role'] = $login['role'];
                // hotfix
                $_SESSION['role'] = 'pegawai';
                $_SESSION['id_pegawai'] = $login['id_pegawai'];
    
                $this->db->where('id', $login['id_pegawai']);
                $_SESSION['pegawai'] = $this->db->get('pegawai')->row_array();
                
                $this->db->where('id_pegawai', $login['id_pegawai']);
                $_SESSION['kelas'] = $this->db->get('kelas')->row_array();;
                
                redirect('/siswa', 'refresh');
            }else{
                echo "<script>alert('Login presensi harus guru'); location.href = '" . base_url() . "loginpresensi';</script>";
            }
        
        } else {
            echo "<script>alert('Username/Password salah, silahkan coba kembali'); location.href = '" . base_url() . "loginpresensi';</script>";
        }
    }

}
