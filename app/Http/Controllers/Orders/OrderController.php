<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Events\Order\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(OrderStoreRequest $request, Cart $cart)
    {
        //checking if the cart is empty before placing an order
        if ($cart->isEmpty()) {
            return response(null, 400);
        }
        $order = $this->createOrder($request, $cart);

        //after refactoring
        $order->products()->sync($cart->products()->forSyncing());

        event(new OrderCreated($order));

        // $products = $cart->products()->keyBy('id')->map(function ($product){
        //     return [
        //         'quantity' => $product->pivot->quantity
        //     ];
        // });
        // $order->products()->sync($products);
    }

    protected function createOrder(Request $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id']), [
                'subtotal' => $cart->subTotal()->amount()
            ])
        );
    }
}
