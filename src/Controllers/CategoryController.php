<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;
use App\Services\CategoryServices;

class CategoryController extends Controller
{
    private CategoryServices $services;


    public function add():void
    {
        $this->view('admin/categories/add');
    }

    public function store():void
    {
        $validation = $this->request()->validate([
            'name'=>['required', 'min:3', 'max:255']
        ]);

        if (! $validation) {
            foreach ($this->request()->errors() as $field=>$errors) {
                $this->session()->set($field, $errors);
            }
            $this->redirect('/admin/categories/add');
        }

        $this->services()->store($this->request()->input('name'));

        $this->redirect('/admin');
    }

    public function destroy()
    {
        $this->services()->delete($this->request()->input('id'));

        $this->redirect('/admin');
    }

    public function edit()
    {
        $category = $this->services()->find($this->request()->input('id'));

        $this->view('admin/categories/update', [
            "category"=>$category,
        ]);
    }

    public function update()
    {
        $validation = $this->request()->validate([
           'name'=>['required', 'min:3', 'max:255']
        ]);

        if (! $validation) {
            foreach ($this->request()->errors() as $field=>$errors) {
                $this->session()->set($field, $errors);
            }
            $this->redirect('/admin/categories/update?id=' . $this->request()->input('id'));
        }

        $this->services()->update(
            $this->request()->input('id'),
            $this->request()->input('name'),
        );

        $this->redirect("/admin");
    }

    private function services(): CategoryServices
    {
        if (! isset($this->services)) {
            $this->services = new CategoryServices($this->db());
        }

        return $this->services;
    }
}