<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $phone
 * @property bool $phone_verified
 * @property string $email
 * @property string $status
 * @property string $verify_token
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property string $role
 * @property boolean $phone_auth
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStatus($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerifyToken($value)
 * @property int $phone_verifyed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhoneVerifyToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhoneVerifyTokenExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhoneVerifyed($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    
    protected $fillable = [
        'name', 'email', 'last_name', 'phone', 'password', 'verify_token', 'status', 'role',
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
        'phone_auth' => 'boolean',
    ];
    
    protected $primaryKey = 'id';
    
    public static function new($name, $email): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => Str::uuid(),
            'role' => self::ROLE_USER,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    
    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'role' => self::ROLE_USER,
            'status' => 'wait',
        ]);
    }
    
    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }
    
    public function changeRole($role): void
    {
        if (!in_array($role, [ self::ROLE_USER, self::ROLE_ADMIN ], true)) {
            throw new InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new DomainException('Role is already assigned');
        }
        $this->update([ 'role' => $role ]);
    }
    
    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->phone_auth = false;
        $this->saveOrFail();
    }
    
    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty');
        }
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire
        && $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Token is already request');
        }
        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSecond(300);
        $this->saveOrFail();
        
        return $this->phone_verify_token;
    }
    
    public function verifyPhone($token, Carbon $now):void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token');
        }
        if ($this->phone_verify_token_expire ->lt($now)) {
            throw new \DomainException('Token is expired');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }
    
    public function enablePhoneAuth(): void
    {
        if (!empty($this->phone) && !$this->isPhoneVerified()) {
            throw new \DomainException('Phone number is empty.');
        }
        $this->phone_auth = true;
        $this->saveOrFail();
    }
    
    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }
    
    
    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }
    
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }
    
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
    
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
    
    public function isPhoneAuthEnabled(): bool
    {
        return (bool)$this->phone_auth;
    }
    
    public function hasFilledProfile()
    {
        return empty($this->name) || empty($this->last_name) || !$this->isPhoneVerified();
    }
}
