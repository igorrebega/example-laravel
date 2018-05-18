<?php

namespace App\Data\Models;

use App\Services\Api\Exceptions\ApiException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use League\OAuth2\Server\Exception\OAuthServerException;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * @return array
     */
    public static function userRoles()
    {
        return [
            self::ROLE_ADMIN        => _('Admin'),
            self::ROLE_USER         => _('User')
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'role',
        'email',
        'phone',
        'password',
        'confirmation_code',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return ($this->confirmation_code === null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }


    /**
     * @param $username
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     * @throws ApiException
     * @throws OAuthServerException
     */
    public function findForPassport($username)
    {
        $user = $this->where('email', $username)->first();
        if (!$user) {
            throw OAuthServerException::invalidCredentials();
        }
        if (!$user->isConfirmed()) {
            throw ApiException::userNotActivated($user->id);
        }
        if (!$user->isUser()) {
            throw new OAuthServerException('User must have "user" role', 6, 'account_inactive', 401);
        }

        return $user;
    }
}
