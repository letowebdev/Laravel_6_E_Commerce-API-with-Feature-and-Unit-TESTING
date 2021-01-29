<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected $user;

    protected $changed = false;

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

    //Calculating the subtotal
    public function subTotal()
    {
        $sub_total = $this->user->cart->sum(function ($product){
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($sub_total);
    }

    //The total counts the subtotal plus the shipping and taxes
    public function total()
    {
        return $this->subtotal();
    }

    //syncing the cart product quantities based on the stock and placed orders from other users
    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            if ($quantity !== $product->pivot->quantity) {
                $this->changed = true;
            }

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    //letting the user know that his cart has changed or not
    public function hasChanged()
    {
        return $this->changed;
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