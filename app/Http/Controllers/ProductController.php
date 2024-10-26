<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function getProductByType($type)
    {
        $products = Product::where('product_type', $type)
            ->whereDoesntHave('loanedProducts', function ($query) {
                $query->where('returned', false);
            })
            ->with('serialNumbers')
            ->get();

        return response()->json($products);
    }

    public function getAvailableProducts()
    {
        $products = Product::whereDoesntHave('loanedProducts', function ($query) {
                $query->where('returned', false);
            })
            ->with('serialNumbers')
            ->get();

        return response()->json($products);
    }

    public function getLoanedProducts()
    {
        $loanedProducts = Product::whereHas('loanedProducts', function ($query) {
                $query->where('returned', false);
            })
            ->with(['serialNumbers', 'loanedProducts.loan.userGiver' , 'loanedProducts.loan.userReceiver'])
            ->get();

        return response()->json($loanedProducts);
    }
}
