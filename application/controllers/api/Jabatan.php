<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

use const chriskacerguis\RestServer\HTTP_OK;

class Jabatan extends RestController
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model("jabatan_model");

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
                if ($data) {
                    $this->data = $data;
                } else {
                    $response = [
                        "status" => false,
                        "message" => "token tidak valid"
                    ];
                    $this->response($response, parent::HTTP_OK);
                    exit();
                }
            } catch (Exception $e) {
                // token invalid, mengirim unauthorized message
                $response = [
                    "status" => false,
                    "message" => "harap login"
                ];
                $this->response($response, parent::HTTP_OK);
            }
        } else {
            $response = [
                "status" => false,
                "message" => "harap login"
            ];
            $this->response($response, parent::HTTP_OK);
        }
    }

    public function index_get($id = null)
    {
        if ($id === null) {
            // Users from a data store e.g. database
            $test_data = $this->jabatan_model->getAll();
            // Check if the users data store contains users
            if ($test_data) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $test_data
                ], 200);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], parent::HTTP_OK);
            }
        } else {
            $test_data_one = $this->jabatan_model->getById($id);

            if ($test_data_one) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $test_data_one
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], parent::HTTP_OK);
            }
        }
    }

    public function index_post()
    {
        $data = array(
            "id_jabatan" => null,
            "nama_jabatan" => $this->post('nama_jabatan')
        );
        $insert = $this->jabatan_model->insert($data);

        if ($insert) {
            $this->response([
                'status' => true,
                'message' => 'data inserted'
            ], parent::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'data not inserted'
            ], parent::HTTP_OK);
        }
    }

    public function index_put()
    {
        $data = array(
            "id_jabatan" => null,
            "nama_jabatan" => $this->post('nama_jabatan')
        );
        $insert = $this->jabatan_model->update($data);

        if ($insert) {
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
    }

    public function index_delete($id = null)
    {
        if ($id === null) {

            // Set the response and exit
            $this->response([
                'status' => false,
                'message' => 'id tidak valid'
            ], 404);
        } else {
            $delete = $this->jabatan_model->delete($id);

            if ($delete) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    'message' => 'delete success'
                ], parent::HTTP_OK);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'delete failed'
                ], parent::HTTP_OK);
            }
        }
    }
}
