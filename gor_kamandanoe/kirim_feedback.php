<?php
header('Content-Type: application/json');
session_start();
include "koneksi.php";

$name = isset($_POST['fb_name'])?mysqli_real_escape_string($conn,$_POST['fb_name']):'';
$msg = isset($_POST['fb_msg'])?mysqli_real_escape_string($conn,$_POST['fb_msg']):'';
if(!$msg){ echo json_encode(['success'=>false,'error'=>'Pesan kosong']); exit; }

// create table if not exists
$create = "CREATE TABLE IF NOT EXISTS feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) DEFAULT NULL,
  message TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'unread',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($conn,$create);

$ins = mysqli_query($conn, "INSERT INTO feedback (name,message,status) VALUES ('$name','$msg','unread')");
if($ins){ echo json_encode(['success'=>true]); } else { echo json_encode(['success'=>false,'error'=>mysqli_error($conn)]); }
