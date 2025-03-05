<?php

namespace App\Http\Controllers;
use App\Models\Auction;

use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'showSingleAuction']);
    }

    public function index()
    {
        $auctions = Auction::all();
        return response()->json(['message' => 'Auctions found', 'auctions' => $auctions]);
    }

    public function showSingleAuction($id)
    {
        $auction = Auction::find($id);
        if ($auction) {
            return response()->json($auction);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'starting_price' => 'required|numeric',
            'current_price' => 'nullable|numeric',
            'user_id' => 'required|integer|exists:users,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $total_price = $validatedData['starting_price'] + ($validatedData['current_price'] ?? 0);

        $auction = Auction::create($validatedData);

        return response()->json([
            'message' => 'Auction created successfully',
            'auction' => array_merge($auction->toArray(), ['total_price' => $total_price]),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $auction = Auction::find($id);
        if ($auction) {
            $auction->update($request->all());
            return response()->json($auction);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }

    public function kill($id)
    {
        $auction = Auction::find($id);
        if ($auction) {
            $auction->delete();
            return response()->json(['message' => 'Auction deleted']);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }
}
