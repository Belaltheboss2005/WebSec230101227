<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        Log::info('Attempting to add product to cart', ['user_id' => $user->id, 'product_id' => $product->id]);

        // Check if the user has enough credits
        if ($user->credit < $product->price) {
            Log::warning('User does not have enough credits', ['user_id' => $user->id, 'user_credit' => $user->credit, 'product_price' => $product->price]);
            return redirect()->route('insufficient_credit');
        }

        // Check if the product is already in the cart for the user
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // If the product is already in the cart, increment the quantity
            $cartItem->quantity++;
            $cartItem->save();
            Log::info('Incremented product quantity in cart', ['cart_item_id' => $cartItem->id, 'quantity' => $cartItem->quantity]);
        } else {
            // If the product is not in the cart, create a new cart item
            $newCartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
            Log::info('Created new cart item', ['cart_item_id' => $newCartItem->id]);
        }

        return redirect()->route('products_list')->with('success', 'Product added to cart');
    }

    public function show()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        return view('products.show', compact('cartItems'));
    }
}
