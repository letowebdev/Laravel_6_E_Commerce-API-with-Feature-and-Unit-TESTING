<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request, Cart $cart)
    {
        $cart->sync();
        
        $request->user()->load(['cart.product', 'cart.product.variations.stock', 'cart.stock', 'cart.type']);
        return (new CartResource($request->user()))
            ->additional([
                'meta' => $this->meta($cart, $request)
            ]);
    }

    public function store(CartStoreRequest $request, Cart $cart)
    {
        return $cart->add($request->products);

        $cart->sync();
    }

    public function update(ProductVariation $productVariation,CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }

    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation->id);
    }

    
    protected function meta(Cart $cart, Request $request)
    {
        return [
            'empty' => $cart->isEmpty(),
            'subtotal' => $cart->subTotal()->formatted(),
            'total' => $cart->total()->formatted(),
            'changed' => $cart->hasChanged()
        ];
    }
}
