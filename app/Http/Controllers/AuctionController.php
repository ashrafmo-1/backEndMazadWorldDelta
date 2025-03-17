<?php

namespace App\Http\Controllers;
use App\Models\Auction;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AuctionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'showSingleAuction']);
    }

    public function index(Request $request)
    {

        $auctions = Auction::when($request->has('categoryId'), function ($query) use ($request) {
                return $query->where('category_id', $request->categoryId);
            })
            ->get();
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
        // php artisan storage:link
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array|min:1',
            'starting_price' => 'required|numeric',
            'current_price' => 'nullable|numeric',
            'category_id' => 'required|integer|exists:users,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $total_price = $validatedData['starting_price'] + ($validatedData['current_price'] ?? 0);

        $imagePaths = "";

        foreach ($request->images as $index => $image) {
            $imageName = time(). '.'. $image->getClientOriginalExtension();

            $image->storeAs('public/auction_images', $imageName);
            $imagePaths.= 'auction_images/'.$imageName. ',';
        }

        $imagePaths = rtrim($imagePaths, ',');

        $auction = Auction::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'images' => $imagePaths,
            'starting_price' => $validatedData['starting_price'],
            'current_price' => $validatedData['current_price'],
            'category_id' => $validatedData['category_id'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return response()->json([
            'message' => 'Auction created successfully',
            'auction' => array_merge($auction->toArray(), ['total_price' => $total_price]),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $auction = Auction::find($id);
        if ($auction) {
            $auction->title = $request->title;
            $auction->description = $request->description;
            $auction->starting_price = $request->starting_price;
            $auction->current_price = $request->current_price;
            $auction->category_id = $request->category_id;
            $auction->start_time = $request->start_time;
            $auction->save();

            if($request->file('images')){
                $imagePaths = "";
                foreach ($request->images as $index => $image) {
                    $imageName = time(). '.'. $image->getClientOriginalExtension();

                    $image->storeAs('public/auction_images', $imageName);
                    $imagePaths.= 'auction_images/'.$imageName. ',';
                }

                $imagePaths = rtrim($imagePaths, ',');
                $auction->images = $imagePaths;
            }
            $auction->save();

            return response()->json($auction);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }

    public function kill($id)
    {
        $auction = Auction::find($id);
        if ($auction) {
            if ($auction->images){
                $imagePaths = explode(',', $auction->getRawOriginal('images'));
                foreach ($imagePaths as $imagePath) {
                    Storage::delete('public/'. $imagePath);
                }
            }
            $auction->delete();
            return response()->json(['message' => 'Auction deleted']);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }

    public function placeBid(Request $request, $id)
    {
        $auction = Auction::find($id);

        if (!$auction) {
            return response()->json(['error' => 'Auction not found'], 404);
        }

        if ($auction->end_time < now()) {
            return response()->json(['error' => 'This auction has ended'], 403);
        }

        // dd($auction->start_time, now());

        if ($auction->start_time > now()) {
            return response()->json(['error' => 'This auction has not started yet'], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'bid_amount' => 'required|numeric|min:' . ($auction->current_price ?? $auction->starting_price),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $bidAmount = $request->input('bid_amount');

        if ($bidAmount <= ($auction->current_price ?? $auction->starting_price)) {
            return response()->json(['error' => 'Your bid must be higher than the current price'], 400);
        }

        // Update the auction's current price
        $auction->current_price = $bidAmount;
        $auction->save();

        // Optionally, log the bid or associate it with the user
        // $user = Auth::user();
        // $auction->bids()->create([
        //     'user_id' => $user->id,
        //     'amount' => $bidAmount,
        // ]);

        return response()->json([
            'message' => 'Bid placed successfully',
            'auction' => $auction,
        ], 200);
    }
}
