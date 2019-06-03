<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Advert\Category
 *
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Adverts\Category[] $children
 * @property-read \App\Models\Adverts\Category $parent
 * @property-write mixed $parent_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Adverts\Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Adverts\Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\Adverts\Category query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $_lft
 * @property int $_rgt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Adverts\Attribute[] $attributes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Category whereSlug($value)
 */
class Category extends Model
{
    use NodeTrait;
    
    protected $table = 'advert_categories';
    public $timestamps = false;
	public $forceDeleting = true;
    protected $fillable = ['name', 'slug', 'parent_id'];
    
    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }
    
    /**
     * @return Attribute[]
     */
    public function allAttributes(): array
    {
        return array_merge($this->parentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
