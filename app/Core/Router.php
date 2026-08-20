<?php
// app/Core/Router.php

class Router {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if (isset($url[0])) {
            $requested = strtolower($url[0]) . 'controller.php';
            $dir = __DIR__ . '/../../app/Controllers/';
            if (is_dir($dir)) {
                $files = scandir($dir);
                foreach ($files as $file) {
                    if (strtolower($file) === $requested) {
                        $this->controller = str_replace('.php', '', $file);
                        unset($url[0]);
                        break;
                    }
                }
            }
        }

        require_once __DIR__ . '/../../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            if (strpos($url, 'cit_ums/') === 0) {
                $url = substr($url, 8);
            }
            return explode('/', $url);
        }
        return [];
    }
}
