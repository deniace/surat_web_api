<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Surat extends RestController
{
    private $data;
    public function __construct()
    {
        parent::__construct();
        //meload helper untuk membuat JWT token
        $this->load->helper(['jwt', 'authorization']);
        $this->load->model("surat_model");
        $this->load->model("user_model");

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
     * mengambil data surat berdasarkan id surat
     */
    public function index_get($id_surat = null)
    {
        if ($id_surat === null) {
            // Mengambil data dari model
            $data_surat = $this->surat_model->get_all();
            // Check if the surat data store contains data
            if ($data_surat) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $data_surat
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'tidak ada data'
                ], 200);
            }
        } else {
            $data_surat_one = $this->surat_model->get_by_id($id_surat);

            if ($data_surat_one) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $data_surat_one
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
     * mengambil update surat berdasarkan id
     */
    public function index_put($id_surat = null)
    {
        if (!$id_surat == null) {
            $id_jabatan = $this->put('id_jabatan');

            if ($id_jabatan == 2) {
                // ketua rt
                $data_surat = array(
                    "id_rt" => $this->put("id_rt"),
                    "id_status_rt" => $this->put("id_status_rt"),
                    "tgl_rt" => date("Y-m-d"),
                    "id_surat" => $id_surat
                );
            } else if ($id_jabatan == 3) {
                // ketua rw
                $data_surat = array(
                    "id_rw" => $this->put("id_rw"),
                    "id_status_rw" => $this->put("id_status_rw"),
                    "id_surat" => $id_surat
                );
            } else {
                // warga dan yang lain
                $response = [
                    "status" => false,
                    "message" => "Bukan Pejabat"
                ];
                $this->response($response, parent::HTTP_OK);
                exit();
            }

            $update = $this->surat_model->update($data_surat);

            if ($update) {
                $this->response([
                    'status' => true,
                    'message' => 'data tersimpan'
                ], parent::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'terjadi kesalahan'
                ], parent::HTTP_OK);
            }
        } else {
            $response = array(
                "status" => false,
                "message" => "id_surat tidak ada"
            );
            $this->response($response, parent::HTTP_OK);
        }
    }

    /**
     * mengambil data surat berdasarkan per page
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
            $where = "rw = '$rw' AND id_status_rt = 1";
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

        $result = $this->surat_model->get_page($limit, $offset, $where);

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
     * mengiput data surat
     */
    public function index_post()
    {
        if (
            $this->post("id_user") !== null && $this->post("id_user") !== "" &&
            $this->post("keperluan") !== null && $this->post("keperluan") !== "" &&
            $this->post("foto") !== null && $this->post("foto") !== ""
        ) {
            $id_user = $this->post('id_user');

            // membuat string dari tanggal dan jam saat ini
            $name_date = date("YmdHis", now());

            $foto_64 = $this->post('foto');

            // membuat nama file
            $nama_foto = $id_user . "_" . $name_date . ".jpg";

            // menkonversi String 64 menjadi file
            $foto_decoded_image = base64_decode($foto_64);
            // path / folder tempat menuyimpan file
            $path_foto = './uploads/image/foto';
            // mengecek tempat menyimpan file
            if (!file_exists($path_foto)) {
                // membuat folder
                mkdir($path_foto);
            }

            // data inputnya lengkap
            $data = array(
                "id_user" => $id_user,
                "keperluan" => $this->post('keperluan'),
                "nama_file" => $nama_foto
            );

            // mengecek user terdaftar atau tidak
            $cek_user = $this->user_model->get_user_by_id($data["id_user"]);

            if ($cek_user) {
                // ada user nya
                // menyimpan file 
                file_put_contents($path_foto . "/" . $nama_foto, $foto_decoded_image);

                // menginsert data
                $insert = $this->surat_model->insert($data);

                if ($insert) {
                    $this->response([
                        'status' => true,
                        'message' => 'Permohonan Surat Diterima'
                    ], parent::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'permohonan surat terjadi kesalahan'
                    ], parent::HTTP_OK);
                }
            } else {
                // tidak ada usernya
                $this->response([
                    'status' => false,
                    'message' => 'user tidak ada'
                ], parent::HTTP_OK);
            }
        } else {
            // data inputan nya tidak lengkap
            $this->response([
                'status' => false,
                'message' => 'user dan keperluan tidak boleh kosong'
            ], parent::HTTP_OK);
        }
    }

    /**
     * mengambil data surat berdasarkan id user
     */
    public function user_get($id_user = null)
    {
        if ($id_user === null) {
            // Mengambil data dari model
            // Set the response and exit
            $this->response([
                'status' => false,
                'message' => 'id user tidak ada'
            ], 200);
        } else {
            $data_surat_user = $this->surat_model->get_by_id_user($id_user);

            if ($data_surat_user) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $data_surat_user
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'tidak ada data surat'
                ], 200);
            }
        }
    }
}
