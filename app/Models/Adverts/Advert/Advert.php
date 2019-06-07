<?php

namespace App\Models\Adverts\Advert;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Region;
use App\Models\Adverts\Category;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Advert\Attribute.
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
        'expired_at',
    ];

    public function sendToModeration()
    {
        if (! $this->isDraft()) {
            throw new \DomainException('Advert is not draft');
        }
        if (! $this->photos()->count()) {
            throw new \DomainException('Upload photos');
        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function moderate(Carbon $date): void
    {
        if ($this->status !== self::STATUS_MODERATION) {
            throw new \DomainException('Advert is not send to moderation');
        }

        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDay(15),
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function isDraft(): bool
    {
        return $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status = self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status = self::STATUS_CLOSED;
    }

    public function isModerate(): bool
    {
        return $this->status = self::STATUS_MODERATION;
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
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }
}
