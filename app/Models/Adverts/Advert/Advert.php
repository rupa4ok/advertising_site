<?php

namespace App\Models\Adverts\Advert;

use App\Models\Adverts\Category;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Advert\Attribute
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property int $region_id
 * @property string $title
 * @property int $price
 * @property string $address
 * @property string $content
 * @property string $status
 * @property string|null $reject_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed|null $published_at
 * @property string|null $expired_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Adverts\Category $category
 * @property-read \App\Models\Region $region
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Adverts\Advert\Value[] $photo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Adverts\Advert\Value[] $values
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Adverts\Advert\Value[] $photos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert forUser(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert forCategory(\App\Models\Adverts\Category $category)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Adverts\Advert\Advert forRegion(\App\Models\Region $region)
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
		'published_at' => 'datetime',
		'expires_at' => 'datetime',
	];
	public static function statusesList(): array
	{
		return [
			self::STATUS_DRAFT => 'Draft',
			self::STATUS_MODERATION => 'On Moderation',
			self::STATUS_ACTIVE => 'Active',
			self::STATUS_CLOSED => 'Closed',
		];
	}
	public function sendToModeration(): void
	{
		if (!$this->isDraft()) {
			throw new \DomainException('Advert is not draft.');
		}
		if (!\count($this->photos)) {
			throw new \DomainException('Upload photos.');
		}
		$this->update([
			'status' => self::STATUS_MODERATION,
		]);
	}
	public function moderate(Carbon $date): void
	{
		if ($this->status !== self::STATUS_MODERATION) {
			throw new \DomainException('Advert is not sent to moderation.');
		}
		$this->update([
			'published_at' => $date,
			'expires_at' => $date->copy()->addDays(15),
			'status' => self::STATUS_ACTIVE,
		]);
	}
	public function reject($reason): void
	{
		$this->update([
			'status' => self::STATUS_DRAFT,
			'reject_reason' => $reason,
		]);
	}
	public function expire(): void
	{
		$this->update([
			'status' => self::STATUS_CLOSED,
		]);
	}
	public function close(): void
	{
		$this->update([
			'status' => self::STATUS_CLOSED,
		]);
	}
	public function writeClientMessage(int $fromId, string $message): void
	{
		$this->getOrCreateDialogWith($fromId)->writeMessageByClient($fromId, $message);
	}
	public function writeOwnerMessage(int $toId, string $message): void
	{
		$this->getDialogWith($toId)->writeMessageByOwner($this->user_id, $message);
	}
	public function readClientMessages(int $userId): void
	{
		$this->getDialogWith($userId)->readByClient();
	}
	public function readOwnerMessages(int $userId): void
	{
		$this->getDialogWith($userId)->readByOwner();
	}
	private function getDialogWith(int $userId): Dialog
	{
		$dialog = $this->dialogs()->where([
			'user_id' => $this->user_id,
			'client_id' => $userId,
		])->first();
		if (!$dialog) {
			throw new \DomainException('Dialog is not found.');
		}
		return $dialog;
	}
	private function getOrCreateDialogWith(int $userId): Dialog
	{
		if ($userId === $this->user_id) {
			throw new \DomainException('Cannot send message to myself.');
		}
		return $this->dialogs()->firstOrCreate([
			'user_id' => $this->user_id,
			'client_id' => $userId,
		]);
	}
	public function getValue($id)
	{
		foreach ($this->values as $value) {
			if ($value->attribute_id === $id) {
				return $value->value;
			}
		}
		return null;
	}
	public function isDraft(): bool
	{
		return $this->status === self::STATUS_DRAFT;
	}
	public function isOnModeration(): bool
	{
		return $this->status === self::STATUS_MODERATION;
	}
	public function isActive(): bool
	{
		return $this->status === self::STATUS_ACTIVE;
	}
	public function isClosed(): bool
	{
		return $this->status === self::STATUS_CLOSED;
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}
	public function region()
	{
		return $this->belongsTo(Region::class, 'region_id', 'id');
	}
	public function values()
	{
		return $this->hasMany(Value::class, 'advert_id', 'id');
	}
	public function photos()
	{
		return $this->hasMany(Photo::class, 'advert_id', 'id');
	}
	public function favorites()
	{
		return $this->belongsToMany(User::class, 'advert_favorites', 'advert_id', 'user_id');
	}
	public function dialogs()
	{
		return $this->hasMany(Dialog::class, 'advert_id', 'id');
	}
	public function scopeActive(Builder $query)
	{
		return $query->where('status', self::STATUS_ACTIVE);
	}
	public function scopeForUser(Builder $query, User $user)
	{
		return $query->where('user_id', $user->id);
	}
	public function scopeForCategory(Builder $query, Category $category)
	{
		return $query->whereIn('category_id', array_merge(
			[$category->id],
			$category->descendants()->pluck('id')->toArray()
		));
	}
	public function scopeForRegion(Builder $query, Region $region)
	{
		$ids = [$region->id];
		$childrenIds = $ids;
		while ($childrenIds = Region::where(['parent_id' => $childrenIds])->pluck('id')->toArray()) {
			$ids = array_merge($ids, $childrenIds);
		}
		return $query->whereIn('region_id', $ids);
	}
	public function scopeFavoredByUser(Builder $query, User $user)
	{
		return $query->whereHas('favorites', function(Builder $query) use ($user) {
			$query->where('user_id', $user->id);
		});
	}
	
	public function getRouteKey(): string
	{
		return $this->id . '-' . 'slug';
	}
	
	public function getRouteKeyName(): string
	{
		return 'slug';
	}
}
