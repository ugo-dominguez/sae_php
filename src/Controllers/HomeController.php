<?php
namespace App\Controllers;

class HomeController extends BaseController {
    public function home(): void {
        $pageTitle = 'Baratie';
        $this->render('home/index', [
            'pageTitle' => $pageTitle,
        ]);
    }
}