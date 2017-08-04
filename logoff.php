<?php
session_start();
ob_start();

unset($_SESSION['UserLogin']);
header('Location: login.php');
