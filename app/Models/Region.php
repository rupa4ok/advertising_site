<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Region
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $children
 * @property-read \App\Models\Region $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region query()
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];
    
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }
    
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
