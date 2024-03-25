<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UserRating;
use Illuminate\Support\Facades\DB;


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


        ## Check if the user has already rated the product
        $existingRating = UserRating::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($existingRating) {
        return response()->json(['error' => 'You have already rated this product'], 403);
        }

        ## Create the rating
        $rating = new UserRating();
        $rating->product_id = $request->product_id;
        $rating->user_id = auth()->id();
        $rating->rating = $request->rating;
        $rating->save();
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


    ## Display all products
    public function index()
    {
        ## Fetch all products
        // $products = Product::all();

        $products = Product::select('product.*', DB::raw('AVG(user_rating.rating) as average_rating'))
            ->leftJoin('user_rating', 'product.id', '=', 'rating.product_id')
            ->groupBy('product.id')
            ->get();

        return view('products.index', compact('products'));
    }
}
