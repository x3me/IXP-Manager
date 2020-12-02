<?php

namespace IXP\Models;

/*
 * Copyright (C) 2009 - 2020 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use Eloquent;

use Illuminate\Database\Eloquent\{Builder,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne};

use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Str;
use IXP\Events\Auth\ForgotPassword as ForgotPasswordEvent;
use PragmaRX\Google2FALaravel\Support\Authenticator as GoogleAuthenticator;

/**
 * IXP\Models\User
 *
 * @property int $id
 * @property int|null $custid
 * @property string|null $username
 * @property string|null $password
 * @property string|null $email
 * @property string|null $authorisedMobile
 * @property int|null $uid
 * @property int|null $privs
 * @property int|null $disabled
 * @property string|null $lastupdated
 * @property int|null $lastupdatedby
 * @property string|null $creator
 * @property string|null $created
 * @property string|null $name
 * @property int|null $peeringdb_id
 * @property mixed|null $extra_attributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\IXP\Models\ApiKey[] $apiKeys
 * @property-read int|null $api_keys_count
 * @property-read \IXP\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\IXP\Models\Customer[] $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\IXP\Models\UserRememberToken[] $userRememberTokens
 * @property-read int|null $user_remember_tokens_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAuthorisedMobile($value)
 * @method static Builder|User whereCreated($value)
 * @method static Builder|User whereCreator($value)
 * @method static Builder|User whereCustid($value)
 * @method static Builder|User whereDisabled($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereExtraAttributes($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastupdated($value)
 * @method static Builder|User whereLastupdatedby($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePeeringdbId($value)
 * @method static Builder|User wherePrivs($value)
 * @method static Builder|User whereUid($value)
 * @method static Builder|User whereUsername($value)
 * @mixin Eloquent
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\IXP\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\IXP\Models\User whereUpdatedAt($value)
 * @property-read \IXP\Models\User2FA|null $user2FA
 * @property-read \Illuminate\Database\Eloquent\Collection|\IXP\Models\CustomerToUser[] $customerToUser
 * @property-read int|null $customer_to_user_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'custid',
        'username',
        'password',
        'email',
        'privs',
        'creator',
        'name',
        'peeringdb_id',
        'extra_attributes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'extra_attributes' => 'json',
    ];

    public const AUTH_PUBLIC    = 0;
    public const AUTH_CUSTUSER  = 1;
    public const AUTH_CUSTADMIN = 2;
    public const AUTH_SUPERUSER = 3;

    public static $PRIVILEGES = [
        User::AUTH_CUSTUSER  => 'CUSTUSER',
        User::AUTH_CUSTADMIN => 'CUSTADMIN',
        User::AUTH_SUPERUSER => 'SUPERUSER',
    ];

    public static $PRIVILEGES_ALL = [
        User::AUTH_PUBLIC    => 'PUBLIC',
        User::AUTH_CUSTUSER  => 'CUSTUSER',
        User::AUTH_CUSTADMIN => 'CUSTADMIN',
        User::AUTH_SUPERUSER => 'SUPERUSER',
    ];

    public static $PRIVILEGES_TEXT = [
        User::AUTH_CUSTUSER  => 'Customer User',
        User::AUTH_CUSTADMIN => 'Customer Administrator',
        User::AUTH_SUPERUSER => 'Superuser',
    ];

    public static $PRIVILEGES_TEXT_ALL = [
        User::AUTH_PUBLIC    => 'Public / Non-User',
        User::AUTH_CUSTUSER  => 'Customer User',
        User::AUTH_CUSTADMIN => 'Customer Administrator',
        User::AUTH_SUPERUSER => 'Superuser',
    ];

    public static $PRIVILEGES_TEXT_NONSUPERUSER = [
        User::AUTH_CUSTUSER  => 'Customer User',
        User::AUTH_CUSTADMIN => 'Customer Administrator',
    ];

    /**
     * Get the remember tokens for the user
     */
    public function userRememberTokens(): HasMany
    {
        return $this->hasMany(UserRememberToken::class, 'user_id' );
    }

    /**
     * Get the remember tokens for the user
     */
    public function user2FA(): HasOne
    {
        return $this->hasOne(User2FA::class, 'user_id' );
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'custid');
    }

    /**
     * Get the api keys for the user
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class, 'user_id' );
    }

    /**
     * Get all the customers for the user
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_to_users', 'user_id' )
            ->orderBy( 'id', 'asc' );
    }

    /**
     * Get all the customers for the user
     */
    public function customerToUser(): HasMany
    {
        return $this->HasMany(CustomerToUser::class, 'user_id' );
    }

    /**
     * Is the user of the named type?
     *
     * @return bool
     */
    public function custUser(): bool
    {
        return $this->privs === self::AUTH_CUSTUSER;
    }

    /**
     * Is the user of the named type?
     * @return bool
     */
    public function custAdmin(): bool
    {
        return $this->privs === self::AUTH_CUSTADMIN;
    }

    /**
     * Is the user of the named type?
     *
     * @return bool
     */
    public function superUser(): bool
    {
        return $this->privs() === self::AUTH_SUPERUSER;
    }

    /**
     * Get privilege from the table CustomerToUser
     *
     * @return int|null
     */
    public function privs(): ?int
    {
        $c2u = CustomerToUser::where( 'customer_id' , $this->custid )->where( 'user_id' , $this->id )->first();
        if( $c2u ) {
            return $c2u->privs;
        }
        return null;
    }

    /**
     * Does 2fa need to be enforced for this user?
     *
     * @return bool
     */
    public function is2faEnforced(): bool
    {
        if( !config('google2fa.enabled') ) {
            return false;
        }

        return $this->privs() >= config( "google2fa.ixpm_2fa_enforce_for_users" )
            && ( !$this->user2FA || !$this->user2FA->enabled );
    }

    /**
     * Check if the user is required to authenticate with 2FA for the current session
     *
     * @return bool
     */
    public function is2faAuthRequiredForSession(): bool
    {
        if( !config('google2fa.enabled') ) {
            return false;
        }

        if( !$this->user2FA || !$this->user2FA->enabled ) {
            // If the user does not have 2fa configured or enabled but it is required, then return true:
            if( $this->is2faEnforced() ) {
                return true;
            }
            return false;
        }

        $authenticator = new GoogleAuthenticator( request() );

        if( $authenticator->isAuthenticated() ) {
            return false;
        }
        return true;
    }

    /**
     * Get the current customer to user - if one exists.
     *
     * @return CustomerToUser|null
     */
    public function currentCustomerToUser(): ?CustomerToUser
    {
        if( !$this->customer ) {
            return null;
        }

        $c2u = CustomerToUser::where( 'customer_id', $this->custid )
            ->where( 'user_id', $this->id )->first();

        return $c2u ?? null;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification( $token ): void
    {
        event( new ForgotPasswordEvent( $token, $this ) );
    }

    /**
     * Get the "remember me" token value.
     *
     * // We have overridden Laravel's remember token functionality and do not rely on this.
     * // However, some Laravel functionality if triggered on this returning a non-false value
     * // to execute certain functionality. As such, we'll just return something random:
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        // We have overridden Laravel's remember token functionality and do not rely on this.
        // However, some Laravel functionality if triggered on this returning a non-false value
        // to execute certain functionality. As such, we'll just return something random:
        return Str::random(60);
    }
}