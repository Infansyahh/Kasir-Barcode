<?php
session_start();
include "koneksi.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if($username=='' || $password=='' || $role==''){
    echo "<script>
    alert('Data Belum Lengkap!');
    location='index.php';
    </script>";
    exit;
}

$query = mysqli_query($koneksi, "
      SELECT * FROM user
      WHERE username='$username'
      AND password='$password'
      AND role='$role'
      AND status_aktif=1
      ");

if(mysqli_num_rows($query)==1){
    $data = mysqli_fetch_assoc($query);

    $_SESSION['login'] = true;
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];

    // redirect sesuai role
    if($role=='admin'){
        header("Location: admin/index.php");
    }elseif($role=='petugas'){
        header("Location: petugas/index.php");
    }else{
        header("Location: owner/index.php");
    }
    exit;
}else{
    echo"<script>
    alert('Login gagal! periksa username/password/role');
    location='index.php';
    </script>";
}
?>