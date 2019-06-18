<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Region
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $children
 * @property-read \App\Models\Region $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region roots()
 */
class Region extends Model
{
	
	protected $fillable = ['name', 'slug', 'parent_id'];
	
	public function getPath(): string
	{
		return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
	}
	
	public function getAddress(): string
	{
		return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
	}
    
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }
    
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
    
    public function scopeRoots(Builder $query)
    {
        return $query->where('parent_id', null);
    }
}
