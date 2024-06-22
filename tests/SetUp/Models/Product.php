<?php

namespace Tests\SetUp\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a product entity in the testing environment.
 * This model is used to interact with the 'products' table in the database.
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = ['title', 'price'];

    /**
     * Constructor for the Product model.
     * 
     * @param array $attributes Initial attributes to set on the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
