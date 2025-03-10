<?php

namespace App\Http\Controllers;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'showSingleFaq']);
    }

    public function index()
    {
        return response()->json(Faq::where('is_active', true)->get());
    }

    public function showSingleFaq($id)
    {
        $faq = Faq::find($id);
        if ($faq) {
            return response()->json($faq);
        } else {
            return response()->json(['error' => 'Auction not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $faq = Faq::create($request->all());

        return response()->json(['message' => 'FAQ created successfully', 'faq' => $faq]);
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['message' => 'faq not found'], 404);
        }

        $faq->update($request->all());

        return response()->json($faq);
    }


    public function kill($id)
    {
        $faq = Faq::find($id);
        if (!$faq) {
            return response()->json(['message' => 'faq not found'], 404);
        }

        $faq->delete();

        return response()->json(['message' => 'faq deleted successfully']);
    }
}
