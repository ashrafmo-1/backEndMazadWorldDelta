<?php

namespace App\Http\Controllers;

use App\Models\LatestNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LatestNewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $latestNews = LatestNews::all();
            return response()->json([
                'status' => true,
                'message' => 'All Latest News retrieved successfully',
                'data' => $latestNews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Latest News',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string|max:255', // Adjust as needed (e.g., file upload handling)
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'nullable|date', // Ensure date format is valid
            'published_at' => 'nullable|date' // Ensure datetime format is valid
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $latestNews = new LatestNews();
            $latestNews->image = $request->input('image'); // Handle file uploads if needed
            $latestNews->title = $request->input('title');
            $latestNews->description = $request->input('description');
            $latestNews->date = $request->input('date');
            $latestNews->published_at = $request->input('published_at');
            $latestNews->save();

            return response()->json([
                'status' => true,
                'message' => 'Latest News created successfully',
                'data' => $latestNews
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating Latest News',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $latestNews = LatestNews::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Latest News retrieved successfully',
                'data' => $latestNews
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string|max:255', // Adjust as needed (e.g., file upload handling)
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date', // Ensure date format is valid
            'published_at' => 'nullable|date' // Ensure datetime format is valid
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $latestNews = LatestNews::findOrFail($id);

            $latestNews->image = $request->input('image', $latestNews->image);
            $latestNews->title = $request->input('title', $latestNews->title);
            $latestNews->description = $request->input('description', $latestNews->description);
            $latestNews->date = $request->input('date', $latestNews->date);
            $latestNews->published_at = $request->input('published_at', $latestNews->published_at);
            $latestNews->save();

            return response()->json([
                'status' => true,
                'message' => 'Latest News updated successfully',
                'data' => $latestNews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating Latest News',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kill($id)
    {
        try {
            $latestNews = LatestNews::findOrFail($id);
            $latestNews->delete();

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
