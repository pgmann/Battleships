<?php

header('Content-Type: application/json');

include "model.php";

session_start();

if (isset($_SESSION["data"])) {
  echo json_encode(unserialize($_SESSION["data"]));
}