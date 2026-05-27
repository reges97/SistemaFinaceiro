<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_cache_expire(120);
    session_set_cookie_params(0, '', '', !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', true);
    session_start();
}

if (!isset($_SESSION['id'], $_SESSION['nivel'])) {
    header('Location: ?router=Site/login');
    exit();
}
