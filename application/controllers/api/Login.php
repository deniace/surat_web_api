<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

header("Access-Control-Allow-Methods: POST");

class Login extends RestController
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
        ) {
            // mengambil email dan password
            $email = $this->post("email");
            $password = $this->post("password");

            // cek email valid atau tidak
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // valid email
                $result = $this->user_model->login($email, $password);
                if ($result["status"]) {
                    // ada di database
                    // mempersiapkan data token
                    $tokenData = array(
                        'timestamp' => now(),
                        'id_user' => $result['data']->id_user,
                        'no_hp' => $result['data']->no_hp,
                        'nama_user' => $result['data']->nama_user,
                        'id_jabatan' => $result['data']->id_jabatan,
                        'nama_jabatan' => $result['data']->nama_jabatan
                    );
                    // membuat token dari data user dan menjadikan response
                    $token = AUTHORIZATION::generateToken($tokenData);
                    $response = array(
                        "status" => true,
                        "message" => "login success",
                        "id_user" => $result['data']->id_user,
                        "token" => $token
                    );
                    $this->response($response, parent::HTTP_OK);
                } else {
                    //tidak ada di database
                    $response = array(
                        "status" => false,
                        "message" => "email atau password salah"
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
}
