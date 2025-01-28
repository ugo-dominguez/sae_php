<?php
namespace App\Controllers;

class BaseController {
    protected function render(string $template, array $data = []): void {
        extract($data);
        
        ob_start();
        require ROOT_DIR . '/templates/layouts/default.php';
        ob_end_flush();
    }
    
    protected function redirect(string $path): void {
        header("Location: /$path");
        exit;
    }
}