<?php
session_start();
$_SESSION['login'] = false;

header("Location:/sistema-sanbenito/login.php");