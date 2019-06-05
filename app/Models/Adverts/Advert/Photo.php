<?php

namespace App\Models\Adverts\Advert;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Adverts\Advert\Photo
 *
 * @property int $id
 * @property int $advert_id
 * @property string $file
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo whereAdvertId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Photo whereId($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
	protected $table = 'advert_advert_photos';
	
	public $timestamps = false;
	
	protected $fillable = ['attribute_id'];
}
