<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../routes/web.php';

$url = isset($_GET['url']) ? $_GET['url'] : '';

$router = new Router();
$router->route($url);
