<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;
use App\Services\CategoryServices;
use App\Services\MovieService;

class AdminController extends Controller
{

    public function index()
    {
        $categories = new CategoryServices($this->db());
        $movies = new MovieService($this->db());

        $categories = $categories->all();
        $movies = $movies->all();

        $this->view('admin/index', [
            'categories' => $categories,
            'movies' => $movies,
        ]);
    }
}