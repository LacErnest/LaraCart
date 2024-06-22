<?php

namespace Tests\SetUp\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Represents a user entity in the testing environment.
 * This model is used to interact with the 'users' table in the database.
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'email'];
}
