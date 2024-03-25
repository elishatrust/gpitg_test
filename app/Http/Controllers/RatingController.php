<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rating;

class RatingController extends Controller
{
    ## Rate a product
    public function rateProduct(Request $request)
    {
        ## Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        ## Get the product
        $product = Product::findOrFail($request->product_id);

        ## Create or update the rating
        $product->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        return response()->json(['message' => 'Product rated successfully']);
    }

    ## Remove rating for a product
    public function removeRating(Request $request)
    {
        ## Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        ## Get the product
        $product = Product::findOrFail($request->product_id);

        ## Remove the rating
        $product->ratings()->where('user_id', auth()->id())->delete();

        return response()->json(['message' => 'Rating removed successfully']);
    }

    ## Change a rating for a product
    public function changeRating(Request $request)
    {
        ## Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        ## Get the product
        $product = Product::findOrFail($request->product_id);

        ## Update the rating
        $product->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        return response()->json(['message' => 'Rating changed successfully']);
    }
}
