<?php

return [
    /*
     * Configuration for user-related settings.
     * Includes table name and key references for user entities.
     */
    'users' => [
        'table' => 'users',  // Name of the table where user data is stored.
        'foreign_key' => 'user_id',  // Column name that acts as a foreign key in related tables.
    ],

    /*
     * Configuration for cart-related settings.
     * Defines the storage and reference settings for carts.
     */
    'carts' => [
        'table' => 'carts',  // Table name for storing cart data.
        'foreign_key' => 'cart_id',  // Foreign key column in related tables (e.g., cart_items).
    ],

    /*
     * Settings specific to items within a cart.
     * This includes table names and could be extended to include item constraints, types, etc.
     */
    'cart_items' => [
        'table' => 'cart_items',  // Table for storing individual cart items.
        // Consider adding more settings here, such as 'max_items_per_cart' or 'allowed_item_types'.
    ],
];
