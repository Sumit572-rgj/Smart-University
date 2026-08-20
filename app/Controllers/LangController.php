<?php
// app/Controllers/LangController.php

class LangController extends Controller {
    public function switch() {
        $lang = $_GET['l'] ?? 'en';
        setcookie('lang', $lang, time() + (86400 * 30), "/"); // 30 days
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'dashboard';
        header("Location: $referer");
        exit;
    }
}
