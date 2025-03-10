<?php

namespace App\Http\Controllers;

use App\Models\FeaturedSeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeaturedSellerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $featuredSellers = FeaturedSeller::all();
            return response()->json([
                'status' => true,
                'message' => 'All Featured Sellers retrieved successfully',
                'data' => $featuredSellers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Featured Sellers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'startcount' => 'required|integer',
            'countReviews' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $featuredSeller = new FeaturedSeller();
            $featuredSeller->name = $request->input('name');
            $featuredSeller->status = $request->input('status');
            $featuredSeller->startcount = $request->input('startcount');
            $featuredSeller->countReviews = $request->input('countReviews');
            $featuredSeller->save();

            return response()->json([
                'status' => true,
                'message' => 'Featured Seller created successfully',
                'data' => $featuredSeller
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating Featured Seller',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $featuredSeller = FeaturedSeller::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Latest News retrieved successfully',
                'data' => $featuredSeller
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Latest News not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'startcount' => 'nullable|integer',
            'countReviews' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $featuredSeller = FeaturedSeller::findOrFail($id);

            $featuredSeller->name = $request->input('name', $featuredSeller->name);
            $featuredSeller->status = $request->input('status', $featuredSeller->status);
            $featuredSeller->startcount = $request->input('startcount', $featuredSeller->startcount);
            $featuredSeller->countReviews = $request->input('countReviews', $featuredSeller->countReviews);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $featuredSeller->image = $imageName;
            }

            $featuredSeller->save();

            return response()->json([
                'status' => true,
                'message' => 'Featured Seller updated successfully',
                'data' => $featuredSeller
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating Featured Seller',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function kill($id)
    {
        try {
            $featuredSeller = FeaturedSeller::findOrFail($id);
            $featuredSeller->delete();

            return response()->json([
                'status' => true,
                'message' => 'Latest News deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting Latest News',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
