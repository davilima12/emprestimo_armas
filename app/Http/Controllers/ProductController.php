<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
            ->with(['serialNumbers', 'loanedProducts.loan.userGiver', 'loanedProducts.loan.userReceiver', 'loanedProducts.loan.userReceipt'])
            ->get();

        return response()->json($loanedProducts);
    }

    public function getAll()
    {
        return Product::select('products.*',
            DB::raw('IF(MAX(loan_products.product_id IS NOT NULL AND loan_products.returned = 0), true, false) as is_loaned')
        )
        ->leftJoin('loan_products', 'loan_products.product_id', 'products.id')
        ->groupBy('products.id')
        ->get();
    }
}
