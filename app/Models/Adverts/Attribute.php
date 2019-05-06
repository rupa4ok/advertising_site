<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advert\Attribute
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Attribute query()
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    protected $fillable = ['name', 'type', 'requires', 'default', 'variants', 'sort'];
    
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';
    
    protected $table = 'advert_attributes';
    
    public $timestamps = false;
    
    protected $casts = [
        'variants' => 'array'
    ];
    
    public static function typesList(): array
    {
        return [
            self::TYPE_STRING => 'String',
            self::TYPE_INTEGER => 'Integer',
            self::TYPE_FLOAT => 'Float',
        ];
    }
    
    public function isString(): bool {
        return $this->type === self::TYPE_STRING;
    }
    
    public function isInteger(): bool {
        return $this->type === self::TYPE_INTEGER;
    }
    
    public function isFloat(): bool {
        return $this->type === self::TYPE_FLOAT;
    }
    
    public function isSelect(): bool {
        return count($this->variants) > 0;
    }
}
