<?php

if (!isset($_COOKIE['api_token'])) {
    header('Location: /');
    exit;
}

?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/static/login-style.css">
    <title>AdminJosmar - Página principal</title>
</head>
<body>
    <ul class="navbar">
        <li class="navbar-item"><a class="active" href="#home">Home</a></li>
        <li class="navbar-item"><a href="#news">News</a></li>
        <li class="navbar-item"><a href="#contact">Contact</a></li>

        <li class="navbar-item-right"><a href="#logout" onclick="return logout(this);">Logout</a></li>
    </ul>
    <div class="container"></div>

    <script>
        const token = '<?= $_COOKIE['api_token']?>';

        function logout() {
            document.cookie = "api_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

            window.location.href = "/";
        }
    </script>
</body>
</html>