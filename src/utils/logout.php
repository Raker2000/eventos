<?php
require_once('../config.php');
session_start();
unset($_SESSION['userLogged']);

header('Location: ' . constant('ROOT_URL') . '/');
exit();
