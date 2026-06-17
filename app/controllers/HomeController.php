<?php

class HomeController extends Controller {
    public function index() {
        $productModel = new Product();
        $featuredProducts = $productModel->getFeatured(12);

        $categoryModel = new Category();
        $categories = $categoryModel->findAll();

        $this->view('home/index', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
}
