<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advert\Attribute
 *
 * @property int $id
 * @property int $category_id
 */
class Advert extends Model
{
	public const STATUS_DRAFT = 'draft';
	public const STATUS_MODERATION = 'moderation';
	public const STATUS_ACTIVE = 'active';
	public const STATUS_CLOSED = 'closed';
	
	protected $table = 'advert_adverts';
	
	protected $guarded = ['id'];
	
	protected $casts = [
		'published_at' => 'datatime',
		'expired_at'
	];

}
