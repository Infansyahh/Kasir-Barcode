<?php
session_start();

if(isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Kasir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
    <div class="login-box">
        <h2>Sistem Kasir</h2>
        <p class="subtitle">Silahkan Login</p>
        <form action="proses_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">PILIH ROLE</option>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="owner">Owner</option>
            </select>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
</body>
</html>