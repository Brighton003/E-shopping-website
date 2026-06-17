<?php

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewFile = APP . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View does not exist: " . $view);
        }
    }

    public function redirect($url) {
        header("Location: /" . ltrim($url, '/'));
        exit();
    }
}
