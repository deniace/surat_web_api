<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

use const chriskacerguis\RestServer\HTTP_OK;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class NotFound extends RestController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // persiapan response 
        $response = ["status" => false, "message" => "not found"];
        $this->response($response, HTTP_OK);
    }
}
