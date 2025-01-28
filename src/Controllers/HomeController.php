<?php
namespace App\Controllers;

class HomeController extends BaseController {
    public function index(): void {
        $pageTitle = 'Baratie';
        $this->render('home/index', [
            'pageTitle' => $pageTitle,
        ]);
    }
}