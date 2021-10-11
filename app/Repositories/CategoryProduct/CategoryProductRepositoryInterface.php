<?php

 namespace App\Repositories\CategoryProduct;

 interface CategoryProductRepositoryInterface
 {
     function getAllCategory($request);

     function create($thumbnail , $request);
 }
