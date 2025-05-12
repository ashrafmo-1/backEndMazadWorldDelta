<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class BidController extends Controller
{
    // إضافة مزايدة جديدة (POST)
    public function store(Request $request)
       {
        try {
            $validated = $request->validate([
                // معلومات المزايد
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'bid_value' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:cash,credit,bank_transfer',
                
                // معلومات المنتج
                'product_title' => 'required|string|max:255',
                'product_image_url' => 'required|string',
                'product_price' => 'required|numeric|min:0'
            ]);

            $bid = Bid::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Bid submitted successfully!',
                'data' => $bid
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            Log::error('Bid store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    // عرض جميع المزايدات (GET)
    public function index(Request $request)
    {
        try {
            $query = Bid::query();
            
            if ($request->has('is_read')) {
                $query->where('is_read', $request->boolean('is_read'));
            }
            
            $bids = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $bids,
                'count' => $bids->count(),
                'code' => Response::HTTP_OK
            ]);

        } catch (\Exception $e) {
            Log::error('Bid index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bids',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // تمييز المزايدة كمقروءة (POST)
    public function markAsRead($id)
    {
        try {
            $bid = Bid::findOrFail($id);
            $bid->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Bid marked as read',
                'data' => $bid,
                'code' => Response::HTTP_OK
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bid not found',
                'error' => 'The requested bid does not exist',
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            Log::error('Bid markAsRead error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark bid as read',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // حذف مزايدة (DELETE)
    public function destroy($id)
    {
        try {
            $bid = Bid::findOrFail($id);
            $bid->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bid deleted successfully',
                'code' => Response::HTTP_OK
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bid not found',
                'error' => 'The requested bid does not exist',
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            Log::error('Bid destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bid',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}