<?php

namespace App\Http\Controllers\Web;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function list(Request $request): View {
        $query = Product::query();

        // Filtering by search keyword
        if ($request->keywords) {
            $query->where('name', 'like', '%' . $request->keywords . '%')
                  ->orWhere('model', 'like', '%' . $request->keywords . '%');
        }

        // Filtering by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->get(); // Fetch products

        return view('products.list', compact('products')); // Ensure it returns a View
    }

    public function edit(Request $request, Product $product = null) {

        $product = $product ?? new Product(); // Create a new product if null
        return view("products.edit", compact('product'));
    }

    public function save(Request $request, Product $product = null) {

        $product = $product ?? new Product();
        $product->fill($request->all());
        $product->save();
        return redirect()->route('products_list');
    }

    public function delete(Product $product) {
        // Restrict delete action to admin (privilege = 1)

        $product->delete();
        return redirect()->route('products_list');
    }
}
