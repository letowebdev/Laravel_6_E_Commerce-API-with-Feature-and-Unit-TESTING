<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {

        $this->user->cart()->syncWithoutDetaching(
            $this->getStorePayLoad($products)
        );
    }

    public function update($productId, $quantity)
    {

        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    public function delete($productId)
    {

        $this->user->cart()->detach($productId);
    }

    //If we place an order successfully we need to empty the cart
    public function emtpy()
    {
        $this->user->cart()->detach();
    }

    //check if the cart is empty
    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }


    protected function getStorePayLoad($products)
    {
       return collect($products)->keyBy('id')->map(function($product){
            return [
                'quantity' => $product['quantity'] + $this->getCurentQuantity($product['id'])
            ];
        })->toArray();
    }

    protected function getCurentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }
}