<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $cart;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(OrderStoreRequest $request, Cart $cart)
    {
        $order = $this->create_subtotal($request, $cart);
    }

    protected function create_subtotal(Request $request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->only(['address_id', 'shipping_method_id', 'payment_method_id']), [
                'subtotal' => $cart->subTotal()->amount()
            ])
        );
    }
}
