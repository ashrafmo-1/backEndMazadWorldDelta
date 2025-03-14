<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'showSingleCategory']);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json(['message' => 'Categories found', 'categories' => $categories]);
    }

    public function showSingleCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $category = Category::create([
            'name' => $validatedData['name'],
            'photo' => $validatedData['photo']->store('categories', 'public'),
        ]);
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category) {
 
            if($request->has('photo') && $request->file('photo')) {
                if($category->photo && $category->photo != "") {
                    Storage::disk('public')->delete($category->getRawOriginal('photo'));
                }
                $category->photo = $request->photo->store('categories', 'public');
            }
            $category->name = $request->name;
            $category->save();
            return response()->json($category);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function kill($id)
    {
        $category = Category::find($id);
        if ($category) {
            Storage::disk('public')->delete($category->getRawOriginal('photo'));
            $category->delete();
            return response()->json(['message' => 'Category deleted']);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }
}
