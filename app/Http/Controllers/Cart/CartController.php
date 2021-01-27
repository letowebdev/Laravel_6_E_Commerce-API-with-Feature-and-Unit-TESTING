<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(CartStoreRequest $request, Cart $cart)
    {
        return $cart->add($request->products);
    }

    public function update(ProductVariation $productVariation,CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }
}
