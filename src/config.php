<?php
//extraer la url y establacerla como una variable global "URL"
// $folderPath = dirname($_SERVER['SCRIPT_NAME']);
// $url = $_SERVER['REQUEST_URI'];
// $url = substr($url, strlen($folderPath));
// define('URL', $url);

//
$folderName = 'Eventos'; //nombre de la carpeta donde esta el proyecto, se cambia en producción
//

// Obtener el protocolo (http o https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";

// Obtener el nombre del host (ej. localhost o dominio.com)
$host = $_SERVER['HTTP_HOST'];

// Construir la URL raíz
$rootUrl = $protocol . "://" . $host;

define('ROOT_URL', $rootUrl . '/' . $folderName);
//Datos de DB
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'caov');
define('DB_PORT', 0);

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'caov');
// define('DB_PORT', null);
