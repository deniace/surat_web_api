<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

// use \ApiResponse;

class Test extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model("test_model");
    }

    public function index_get($id = null)
    {
        // $id = $this->get('id');

        if ($id === null) {
            // Users from a data store e.g. database
            $test_data = $this->test_model->getAllTest();
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
                ], 404);
            }
        } else {
            $test_data_one = $this->test_model->getTest($id);

            if ($test_data_one) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    "data" => $test_data_one
                ], 200);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        }
    }

    public function index_post()
    {
        $data = array(
            "id" => null,
            "nama" => $this->post('nama'),
            "email" => $this->post('email'),
            "no_hp" => $this->post('no_hp')
        );
        $insert = $this->test_model->insert($data);

        if ($insert) {
            $this->response([
                'status' => true,
                'message' => 'data inserted'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'data not inserted'
            ], 304);
        }
    }

    public function index_put()
    {
        $data = array(
            "id" => $this->put('id'),
            "nama" => $this->put('nama'),
            "email" => $this->put('email'),
            "no_hp" => $this->put('no_hp')
        );
        $insert = $this->test_model->update($data);

        if ($insert) {
            $this->response([
                'status' => true,
                'message' => 'data updated'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'data not updated'
            ], 304);
        }
    }

    public function index_delete($id = null)
    {
        if ($id === null) {

            // Set the response and exit
            $this->response([
                'status' => false,
                'message' => 'No id were found'
            ], 404);
        } else {
            $delete = $this->test_model->delete($id);

            if ($delete) {
                // Set the response and exit
                $this->response([
                    "status" => TRUE,
                    'message' => 'delete success'
                ], 200);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'delete failed'
                ], 304);
            }
        }
    }
}
