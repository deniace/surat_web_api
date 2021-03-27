<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: application/json');
$data = array("status" => true, "message" => "wecome to web api");
echo (json_encode($data));
