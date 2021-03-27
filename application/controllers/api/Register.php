<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

header("Access-Control-Allow-Methods: POST");

class Register extends RestController
{
    public function __construct()
    {
        parent::__construct();

        //meload helper untuk membuat JWT token
        $this->load->helper(['jwt', 'authorization']);
        $this->load->model("user_model");
    }

    public function index_post()
    {
        // cek email dan password 
        if (
            $this->post("email") !== null && $this->post("email") !== "" && $this->post("password") !== null && $this->post("password") !== ""
            && $this->post("passconf") !== null && $this->post("passconf") !== ""
        ) {
            // mengambil email dan password
            $email = $this->post("email");
            $password = $this->post("password");
            $passconf = $this->post("passconf");

            // cek email valid atau tidak
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // valid email
                if ($password === $passconf) {
                    // password dan password confirmation sama (matching)
                    // validasi sukses
                    $this->cek_ketua();
                } else {
                    // password dan password confirmation tidak sama (not match)
                    $response = array(
                        "status" => false,
                        "message" => "password tidak sama"
                    );
                    $this->response($response, parent::HTTP_OK);
                }
            } else {
                // invalid email
                $response = array(
                    "status" => false,
                    "message" => "email tidak valid"
                );
                $this->response($response, parent::HTTP_OK);
            }
        } else {
            // email dan password tidak ada
            $response = array(
                "status" => false,
                "message" => "tolong masukan email dan password anda"
            );
            $this->response($response, parent::HTTP_OK);
        }
    }

    private function prosesRegister()
    {
        // register logic disini
        $nama_user = $this->post('nama_user');
        $email = $this->post('email');
        $no_hp = $this->post('no_hp');
        $alamat = $this->post('alamat');
        $password = $this->post('password');
        $id_jabatan = $this->post('id_jabatan');
        $rt = $this->post('rt');
        $rw = $this->post('rw');
        $jenis_kelamin = $this->post('jenis_kelamin');
        $agama = $this->post('agama');
        $tempat_lahir = $this->post('tempat_lahir');
        $tanggal_lahir = $this->post('tanggal_lahir');
        $status_perkawinan = $this->post('status_perkawinan');
        $pekerjaan = $this->post('pekerjaan');

        $is_resgistered = $this->user_model->cek_email($email);
        if (!$is_resgistered) {
            // email belum terdaftar
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $data = array(
                "nama_user" => $nama_user,
                "email" => $email,
                "no_hp" => $no_hp,
                "alamat" => $alamat,
                "password" => $password_hash,
                "id_jabatan" => $id_jabatan,
                "rt" => $rt,
                "rw" => $rw,
                "jenis_kelamin" => $jenis_kelamin,
                "agama" => $agama,
                "tempat_lahir" => $tempat_lahir,
                "tanggal_lahir" => $tanggal_lahir,
                "status_perkawinan" => $status_perkawinan,
                "pekerjaan" => $pekerjaan
            );

            $result = $this->user_model->register($data);

            if ($result["insert"]) {
                $response = array(
                    "status" => true,
                    "id_user" => $result["id_user"],
                    "message" => "tambah data sukses"
                );
                $this->response($response, parent::HTTP_OK);
            } else {
                $response = array(
                    "status" => false,
                    "message" => "terjadi kesalahan saat tambah data"
                );
                $this->response($response, parent::HTTP_OK);
            }
        } else {
            // email sudah terdaftar
            $response = array(
                "status" => false,
                "message" => "email sudah terdaftar"
            );
            $this->response($response, parent::HTTP_OK);
        }
    }

    private function cek_ketua()
    {
        $rt = $this->post('rt');
        $rw = $this->post('rw');
        $id_jabatan = $this->post('id_jabatan');

        if ($id_jabatan == 1) {
            // admin tidak boleh daftar lagi, admin hanya 1
            $response = array(
                "status" => false,
                "message" => "jabatan forbiden"
            );
            $this->response($response, parent::HTTP_OK);
            exit();
        } elseif ($id_jabatan == 2) {
            // cek ketua rt
            if ($this->user_model->cek_rt($rt, $rw)) {
                // ketua rt sudah ada
                $response = array(
                    "status" => false,
                    "message" => "ketua RT sudah ada"
                );
                $this->response($response, parent::HTTP_OK);
                exit();
            } else {
                $this->prosesRegister();
            }
        } elseif ($id_jabatan == 3) {
            // cek ketua rw
            if ($this->user_model->cek_rw($rw)) {
                // ketua rw sudah ada
                $response = array(
                    "status" => false,
                    "message" => "ketua RW sudah ada"
                );
                $this->response($response, parent::HTTP_OK);
                exit();
            } else {
                // ketua rw tidak ada
                $this->prosesRegister();
            }
        } else {
            // warga
            $this->prosesRegister();
        }
    }
}
