<?php
namespace App\Services;

use App\Product;
use App\Repositories\Product\ProductRepositories;
use Illuminate\Support\Str;

class ProductService
{
    protected $product_repositories;
    protected $product;

    /**
     * ProductService constructor.
     *
     * @param ProductRepositories $product_repositories
     * @param Product $product
     */

    public function __construct(ProductRepositories $product_repositories , Product $product)
    {
        $this->product_repositories = $product_repositories;
        $this->product = $product;
    }

    /**
     * @param $request
     * @param $thumbnail
     *
     * @return mixed
     */

    public function createProduct($request , $thumbnail)
    {
        $str_random = Str::random(5);
        $product =  $this->product::create([
            'code' => $str_random,
            'product_title' => $request->product_title,
            'thumbnail_product' => url('public/uploads/' . $thumbnail),
            'price' => $request->price,
            'category_id' => $request->category,
            'content' => $request->contents,
            'excerpts' => $request->excerpts,
            'user_id' => $request->user_id,
            'status_product' => $request->status_product,
            'quantity_product' => $request->quantity_product,
            'featured_product' => $request->featured_product,
            'selling_products' =>  $request->selling_products,
            'status'  => $request->status,
            'discount' => $request->discount
        ]);
        $price_sale = $this->saleDiscountProduct($product->price , $product->discount);
        $this->product_repositories->updateSalePrice($price_sale , $product->id);
       return $product;
    }

    /**
     * @param $request
     * @param $thumbnail
     * @param $id
     *
     * @return bool
     */

    public function updateProduct($request , $thumbnail , $id)
    {
        $product_detail = $this->product_repositories->getItemProduct($id);
        if($thumbnail)
        {
            $update_product = $this->product::where('id' , $id)->update([
                'code' => $product_detail->code,
                'product_title' => $request->product_title,
                'thumbnail_product' => url('public/uploads/' . $thumbnail),
                'price' => $request->price,
                'category_id' => $request->category,
                'content' => $request->contents,
                'excerpts' => $request->excerpts,
                'user_id' => $request->user_id,
                'status_product' => $request->status_product,
                'quantity_product' => $request->quantity_product,
                'featured_product' => $request->featured_product,
                'selling_products' =>  $request->selling_products,
                'status'  => $request->status,
                'discount' => $request->discount
            ]);
            $product_detail_app = $this->product_repositories->getItemProduct($id);
            $price_sale = $this->saleDiscountProduct($product_detail_app->price , $product_detail_app->discount);
            $this->product_repositories->updateSalePrice($price_sale , $product_detail_app->id);
            if($update_product)
            {
                return true;
            }else
            {
                return false;
            }
        }else
        {
            $update_product = $this->product::where('id' , $id)->update([
                'code' => $product_detail->code,
                'product_title' => $request->product_title,
                'thumbnail_product' => $product_detail->thumbnail_product,
                'price' => $request->price,
                'category_id' => $request->category,
                'content' => $request->contents,
                'excerpts' => $request->excerpts,
                'user_id' => $request->user_id,
                'status_product' => $request->status_product,
                'quantity_product' => $request->quantity_product,
                'featured_product' => $request->featured_product,
                'selling_products' =>  $request->selling_products,
                'status'  => $request->status,
                'discount' => $request->discount
            ]);
            $product_detail_app = $this->product_repositories->getItemProduct($id);
            $price_sale = $this->saleDiscountProduct($product_detail_app->price , $product_detail_app->discount);
            $this->product_repositories->updateSalePrice($price_sale , $product_detail_app->id);
            if($update_product)
            {
                return true;
            }else
            {
                return false;
            }
        }
    }

    /**
     * @param $price
     * @param null $sale
     *
     * @return false|float|int
     */

    public function saleDiscountProduct($price , $sale = null)
    {
        if($sale)
        {
            $total_sale = (100-$sale)/100;
            return ceil($price * $total_sale);
        }else
        {
            return 0;
        }

    }
}
