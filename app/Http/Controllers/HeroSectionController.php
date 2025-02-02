<?php

namespace App\Http\Controllers;

use App\Models\hero_sections;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->validate([
                'logo' => 'required|string',
                'best_auctions' => 'required|array',
                'categories' => 'required|array',
                'navbar_links' => 'required|array',
                'contact_numbers' => 'required|array',
            ]);
    
            $heroSection = hero_sections::create([
                'logo' => $request->logo,
                'best_auctions' => $request->best_auctions,
                'categories' => $request->categories,
                'navbar_links' => $request->navbar_links,
                'contact_numbers' => $request->contact_numbers,
            ]);
    
            return response()->json($heroSection, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function index()
    {
        $heroSections = hero_sections::all();
        return response()->json($heroSections);
    }

    public function show($id)
    {
        $heroSection = hero_sections::findOrFail($id);
        return response()->json($heroSection);
    }

    public function update(Request $request, $id)
    {
        $heroSection = hero_sections::findOrFail($id);

        $request->validate([
            'logo' => 'required|string',
            'best_auctions' => 'required|array',
            'categories' => 'required|array',
            'navbar_links' => 'required|array',
            'contact_numbers' => 'required|array',
        ]);

        $heroSection->update([
            'logo' => $request->logo,
            'best_auctions' => $request->best_auctions,
            'categories' => $request->categories,
            'navbar_links' => $request->navbar_links,
            'contact_numbers' => $request->contact_numbers,
        ]);

        return response()->json($heroSection); // إرجاع الكائن المحدث
    }

    //! Delete not used from dashboard
    public function destroy($id)
    {
        $heroSection = hero_sections::findOrFail($id);
        $heroSection->delete();
        return response()->json(['message' => 'Hero section deleted successfully']);
    }
}