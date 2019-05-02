<?php

namespace App\Models\Advert;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Advert\Category
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Advert\Category[] $children
 * @property-read \App\Models\Advert\Category $parent
 * @property-write mixed $parent_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Advert\Category d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Advert\Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Advert\Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Advert\Category query()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use NodeTrait;
    
    protected $table = 'advert_categories';
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'parent_id'];
    
}
