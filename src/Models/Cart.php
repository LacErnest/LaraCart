<?php

namespace LacErnest\LaravelCart\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['user_id'];

    /**
     * Relationships to automatically load with every query.
     *
     * @var array<string>
     */
    protected $with = ['items'];

    /**
     * Specify the table associated with the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('laravel-cart.carts.table', 'carts');
    }

    /**
     * Define a one-to-many relationship with CartItem.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope a query to either find an existing cart or create a new one, then store items.
     *
     * @param Builder $query
     * @param Model $item
     * @param int $quantity
     * @param int|null $userId
     * @return Builder
     */
    public function scopeFirstOrCreateWithStoreItems(Builder $query, Model $item, int $quantity = 1, ?int $userId = null)
    {
        $userId = $userId ?? Auth::id();
        $cart = $query->firstOrCreate(['user_id' => $userId]);
        $cart->items()->create([
            'itemable_id' => $item->getKey(),
            'itemable_type' => $item::class,
            'quantity' => $quantity,
        ]);

        return $query;
    }

    /**
     * Calculate the total price based on the quantity of items in the cart.
     *
     * @return int
     */
    public function calculatedPriceByQuantity()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->itemable->price;
        });
    }

    /**
     * Store multiple items in the cart.
     *
     * @param array $items
     * @return Cart
     */
    public function storeItems(array $items)
    {
        foreach ($items as $item) {
            $this->items()->create([
                'itemable_id' => $item['itemable']->getKey(),
                'itemable_type' => get_class($item['itemable']),
                'quantity' => (int) $item['quantity'],
            ]);
        }

        return $this;
    }
}
