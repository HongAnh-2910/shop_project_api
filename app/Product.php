<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * @var string[]
     */

    protected $fillable = [
        'code' ,'product_title' ,'thumbnail_product',
        'price','price_sale','category_id' ,'content',
        'excerpts' , 'user_id' ,'status_product' ,'quantity_product',
        'featured_product' ,'selling_products' ,'status' ,'discount'
    ];

    /**
     * @var string
     */

    protected $table = 'products';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    function detail_product_thumbnail()
    {
        return $this->hasMany('App\DetailProductThumbnail' , 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo('App\User' , ' user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function category()
    {
        return $this->belongsTo('App\Category' , 'category_id');
    }

}
