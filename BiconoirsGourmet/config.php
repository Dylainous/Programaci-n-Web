<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('DB_PATH',   ROOT_PATH . 'database' . DIRECTORY_SEPARATOR);
define('BASE_URL',  '//' . $_SERVER['HTTP_HOST']
                  . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
define('URL',     BASE_URL . '/index.php');
define('IMG_URL', BASE_URL . '/img/');
define('JS_URL',  BASE_URL . '/js/');