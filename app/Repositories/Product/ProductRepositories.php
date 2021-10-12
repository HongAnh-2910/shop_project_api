<?php
 namespace App\Repositories\Product;

 use App\Product;

 class ProductRepositories implements ProductRepositoryInterface
 {
     protected $product;

     /**
      * ProductRepositories constructor.
      *
      * @param Product $product
      */

     public function __construct(Product $product)
     {
         $this->product = $product;
     }

     /**
      * @param $price_sale
      * @param $product_id
      *
      * @return mixed
      */

     public function updateSalePrice($price_sale , $product_id)
     {
        return $this->product::where('id' , $product_id)->update([
             'price_sale' => $price_sale != 0 ? $price_sale : null,
         ]);
     }

     /**
      * @param $id
      *
      * @return mixed
      */

     public function getItemProduct($id)
     {
         return $this->product::with('category')->find($id);
     }

     /**
      * @param $request
      *
      * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
      */

     public function getProductByCategory($request)
     {
         return $this->product::with('category')->where([
             ['status' , 'public'],
             ['category_id' , $request->category]
         ])->paginate();
     }

     /**
      * @param $request
      *
      * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
      */

     public function getAllProduct($request)
     {
         return $this->product::with('category')->where('status' , 'public')->paginate();
     }
 }
