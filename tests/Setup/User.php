<?php

namespace DeadPixelStudio\Lockdown\Tests\Setup;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DeadPixelStudio\Lockdown\Models\Traits\Lockdown;

class User extends Authenticatable
{
    use Lockdown;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email'];
    public $timestamps = false;
    protected $table = 'users';
}
