<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * @var string[]
     */

    protected $fillable = ['name_category' ,'img' ,'parent_id'];
    //

    /**
     * @var string
     */

    protected $table  = 'categorys';

    /**
     * @return HasMany
     */

    function category_child()
    {
        return $this->hasMany('App\Category','parent_id');
    }

}
