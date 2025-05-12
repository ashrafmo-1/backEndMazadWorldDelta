<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    // إرسال رسالة جديدة (POST)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string',
            ]);

            $contact = Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => $contact
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            Log::error('Contact store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal Server Error',
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // عرض جميع الرسائل (GET)
    public function index(Request $request)
    {
        try {
            $query = Contact::query();
            
            // تصفية حسب حالة القراءة إذا تم توفيرها
            if ($request->has('is_read')) {
                $isRead = filter_var($request->input('is_read'), FILTER_VALIDATE_BOOLEAN);
                $query->where('is_read', $isRead);
            }
            
            $messages = $query->get();

            return response()->json([
                'success' => true,
                'data' => $messages,
                'count' => $messages->count(),
                'code' => Response::HTTP_OK
            ]);

        } catch (\Exception $e) {
            Log::error('Contact index error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve messages',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // تمييز الرسالة كمقروءة (PUT/PATCH)
    public function markAsRead($id)
    {
        try {
            $message = Contact::findOrFail($id);
            $message->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read',
                'data' => $message,
                'code' => Response::HTTP_OK
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found',
                'error' => 'The requested message does not exist',
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            Log::error('Contact markAsRead error: ' . $e->getMessage(), [
                'message_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as read',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // حذف رسالة (DELETE)
    public function destroy($id)
    {
        try {
            $message = Contact::findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully',
                'code' => Response::HTTP_OK
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found',
                'error' => 'The requested message does not exist',
                'code' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            Log::error('Contact destroy error: ' . $e->getMessage(), [
                'message_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete message',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}