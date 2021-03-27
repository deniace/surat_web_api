<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Profile extends RestController
{
    private $data;
    public function __construct()
    {
        parent::__construct();
        //meload helper untuk membuat JWT token
        $this->load->helper(['jwt', 'authorization']);
        $this->load->model("profile_model");

        // mengambil semua header
        $header = $this->input->request_headers();

        // mengecek ada header atau tidak
        if (isset($header['Authorization'])) {
            // mengambil token dari header
            $token = $header['Authorization'];
            // jwt liblary throw an exception jika token tidak valid
            try {
                // validasi token
                // validasi yang sukses akan mengembalikan data yang sudah di decode jika tidak akan mengembalikan false
                $data = AUTHORIZATION::validateToken($token);
                if ($data === false) {
                    $response = [
                        "status" => false,
                        "message" => "Unauthorized Access!"
                    ];
                    $this->response($response, parent::HTTP_OK);
                    exit();
                } else {
                    $this->data = $data;
                }
            } catch (Exception $e) {
                // token invalid, mengirim unauthorized message
                $response = [
                    "status" => false,
                    "message" => "Unauthorized Access!"
                ];
                $this->response($response, parent::HTTP_OK);
            }
        } else {
            $response = [
                "status" => false,
                "message" => "authorized tidak ada"
            ];
            $this->response($response, parent::HTTP_OK);
        }
    }

    /**
     * mengambil data user berdasarkan id
     */
    public function index_get($id_user = null)
    {
        if ($id_user === null) {
            // Mengambil data dari model
            $data_user = $this->profile_model->get_all();
            // Check if the users data store contains users
            if ($data_user) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $data_user
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'tidak ada data'
                ], 200);
            }
        } else {
            $data_user_one = $this->profile_model->get_by_id($id_user);

            if ($data_user_one) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $data_user_one
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'tidak ada data'
                ], 200);
            }
        }
    }

    /**
     * mengambil update user berdasarkan id
     */
    public function index_put($id_user = null)
    {
        if (!$id_user == null) {
            $data_user = array(
                "id_user" => $id_user,
                "nama_user" => $this->put("nama_user"),
                "no_hp" => $this->put("no_hp"),
                "alamat" => $this->put("alamat"),
                "id_jabatan" => $this->put("id_jabatan"),
                "rt" => $this->put("rt"),
                "rw" => $this->put("rw"),
                "jenis_kelamin" => $this->put("jenis_kelamin"),
                "agama" => $this->put("agama"),
                "tempat_lahir" => $this->put("tempat_lahir"),
                "tanggal_lahir" => $this->put("tanggal_lahir"),
                "status_perkawinan" => $this->put("status_perkawinan"),
                "pekerjaan" => $this->put("pekerjaan")
            );

            $update = $this->profile_model->update($data_user);

            if ($update) {
                $this->response([
                    'status' => true,
                    'message' => 'data updated'
                ], parent::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'data not updated'
                ], parent::HTTP_OK);
            }
        } else {
            $response = array(
                "status" => false,
                "message" => "id_user tidak ada"
            );
            $this->response($response, parent::HTTP_OK);
        }
    }

    /**
     * mengambil data user berdasarkan per page
     */
    public function paging_post()
    {
        $rt = $this->post("rt");
        $rw = $this->post("rw");
        $id_jabatan = $this->post('id_jabatan');
        if (
            $this->post('page') !== null && $this->post('page') !== "" &&
            $this->post('page_size') !== null && $this->post('page_size') !== ""
        ) {
            $page = $this->post("page");
            $page_size = $this->post("page_size");
        } else {
            $page = 1;
            $page_size = 10;
        }
        if ($id_jabatan == 1) {
            // admin
            $where = "1 = 1";
        } else if ($id_jabatan == 2) {
            // ketua rt
            $where = "rw = '$rw' AND rt = '$rt'";
        } else if ($id_jabatan == 3) {
            // ketua rw
            $where = "rw = '$rw'";
        } else {
            // warga dan yang lain
            $response = [
                "status" => false,
                "total_data" => 0,
                "message" => "Bukan Pejabat"
            ];
            $this->response($response, parent::HTTP_OK);
            exit();
        }

        $limit = $page_size;
        $offset = ($page * $page_size) - $page_size;

        $result = $this->profile_model->get_page($limit, $offset, $where);

        // $max_page = $result['max_page'];
        $data_paging = $result['data'];
        $total_data = $result['total_data'];

        $this->response([
            "status" => TRUE,
            "total_data" => $total_data,
            "data" => $data_paging
        ], parent::HTTP_OK);
    }

    /**
     * menghapus profile / biodata warga
     */
    public function index_delete($id_user = null)
    {
        // cek ada id nya atau tidak
        if ($id_user !== null) {
            // id nya ada
            // mengecek id jabatan, 
            if ($this->data->id_jabatan <= 3) {
                // pejabat (ketua rt / rw / admin)

                // mengambil data user login
                $this->load->model("user_model");
                $data_user_login = $this->user_model->get_login_user($id_user);

                // meload surat model
                $this->load->model("surat_model");

                // menghapus surat berdasarkan id user
                $delete_surat_user = $this->surat_model->delete_by_id_user($id_user);
                // menghapus login user berdasarkan id_login_user
                $delete_login_user = $this->user_model->delete_login_user($data_user_login->id_login_user);
                // menghapus login berdasarkan id_login
                $delete_login = $this->user_model->delete_login($data_user_login->id_login);
                // menghapus user berdasarkan id_user
                $delete_user = $this->user_model->delete_user($data_user_login->id_user);

                if ($delete_surat_user && $delete_login_user && $delete_login && $delete_user) {
                    // delete berhasil
                    $this->response([
                        "status" => true,
                        "message" => "sukses menghapus data warga"
                    ], parent::HTTP_OK);
                } else {
                    // delete gagal
                    $this->response([
                        "status" => false,
                        "message" => "gagal menghapus data warga"
                    ], parent::HTTP_OK);
                }
            } else {
                // bukan pejabat
                $this->response([
                    'status' => false,
                    'message' => 'tidak dapat melakukan ini, harap hubungi ketua RT / RW anda'
                ], parent::HTTP_OK);
            }
        } else {
            // id nya tidak ada
            $this->response([
                'status' => false,
                'message' => 'id tidak ada'
            ], parent::HTTP_OK);
        }
    }
}
