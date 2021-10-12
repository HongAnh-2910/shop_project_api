<?php

  namespace App\Repositories\Product;

  interface ProductRepositoryInterface
  {
        public function updateSalePrice($price_sale , $product_id);

        public function getItemProduct($id);

        public function getProductByCategory($request);

        public function getAllProduct($request);
  }
