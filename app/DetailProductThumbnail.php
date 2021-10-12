<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailProductThumbnail extends Model
{
    /**
     * @var string[]
     */

    protected $fillable = [
        'img_detail' , 'product_id'
    ];

    /**
     * @var string
     */

    protected $table ='detail_product_thumbnail';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    function product()
    {
       return $this->belongsTo('App\Product' , 'product_id');
    }
}
