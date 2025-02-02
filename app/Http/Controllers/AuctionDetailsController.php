<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionDetailsController extends Controller
{
    // Display a listing of all auctions
    public function index()
    {
        $auctions = Auction::all(); // Fetch all auctions
        return response()->json($auctions); // Return auctions as a JSON response
    }

    // Show the details of a specific auction
    public function show($id)
    {
        $auction = Auction::find($id);

        // If the auction is found, return the auction details, else return a 404 error
        if ($auction) {
            return response()->json($auction);
        } else {
            return response()->json(['message' => 'Auction not found'], 404);
        }
    }

    // Store a new auction
    public function create(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'auction_name' => 'required|string|max:255',
            'auction_description' => 'required|nullable|string',
            'start_salary' => 'required|numeric',
            'current_salary' => 'required|nullable|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'photo' => 'required|nullable|string',
            'photos' => 'required|nullable|array',
            'isFav' => 'required|nullable|boolean',
            'isPublished' => 'required|nullable|boolean',
        ]);

        // Create a new auction using the validated data
        $auction = Auction::create($request->all());

        // Return the created auction as a JSON response
        return response()->json($auction, 201);
    }

    // Update an existing auction
    public function update(Request $request, $id)
    {
        // Find the auction by its ID
        $auction = Auction::find($id);

        // If the auction doesn't exist, return a 404 error
        if (!$auction) {
            return response()->json(['message' => 'Auction not found'], 404);
        }

        // Validate the incoming request data
        $request->validate([
            'auction_name' => 'nullable|string|max:255',
            'auction_description' => 'nullable|string',
            'start_salary' => 'nullable|numeric',
            'current_salary' => 'nullable|numeric',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'photo' => 'nullable|string',
            'photos' => 'nullable|array',
            'isFav' => 'nullable|boolean',
            'isPublished' => 'nullable|boolean',
        ]);

        // Update the auction with the new data
        $auction->update($request->all());

        // Return the updated auction as a JSON response
        return response()->json($auction);
    }

    // Delete an auction
    public function destroy($id)
    {
        // Find the auction by its ID
        $auction = Auction::find($id);

        // If the auction exists, delete it, else return a 404 error
        if ($auction) {
            $auction->delete();
            return response()->json(['message' => 'Auction deleted successfully']);
        } else {
            return response()->json(['message' => 'Auction not found'], 404);
        }
    }
}
