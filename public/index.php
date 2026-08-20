<?php
// Auto-detect environment to prevent broken links on TinkerHost vs Local XAMPP
$isLocal = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false || strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false);
define('BASE_URL', $isLocal ? '/cit_ums/' : '/');

// public/index.php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Core/JWT.php';

$router = new Router();
