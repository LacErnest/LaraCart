<?php

namespace LacErnest\LaravelCart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents an item within a shopping cart.
 */
class CartItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'cart_id', 
        'itemable_id', 
        'itemable_type', 
        'quantity'
    ];

    /**
     * Initializes a new instance of the CartItem model with the specified attributes.
     * 
     * @param array $attributes Initial attributes to set on the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set the database table dynamically based on configuration.
        $this->table = config('laravel-cart.cart_items.table', 'cart_items');
    }

    /**
     * Defines a polymorphic relationship to the itemable model.
     * 
     * @return MorphTo
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Defines an inverse one-to-many relationship with the Cart model.
     * 
     * @return BelongsTo
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
