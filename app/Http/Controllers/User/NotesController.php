<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponseHelper;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $notes = $user->notes;
            // return every note with notes resource
            $notes = $notes->map(function ($note) {
                return new NoteResource($note);
            });

            if ($notes->count() > 0) {
                return ApiResponseHelper::resData($notes, 'Notes found', 200);
            } else {
                return ApiResponseHelper::resData([], 'No notes found', 200);
            }
        } catch (\Exception $e) {
            \Log::error('Notes index error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'content' => 'required|string'
            ]);

            $validated['user_id'] = Auth::id();
            $note = Note::create($validated);

            return ApiResponseHelper::resData(new NoteResource($note), 'Note created successfully', 201);
        } catch (ValidationException $e) {
            return ApiResponseHelper::resData($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            \Log::error('Note store error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();
            $note = Note::where('id', $id)->where('user_id', $user->id)->first();

            if ($note) {
                return ApiResponseHelper::resData(new NoteResource($note), 'Note found', 200);
            } else {
                return ApiResponseHelper::resData([], 'Note not found or you do not have access to this note', 404);
            }
        } catch (\Exception $e) {
            \Log::error('Note show error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'content' => 'required|string'
            ]);

            $user = Auth::user();
            $note = Note::where('id', $id)->where('user_id', $user->id)->first();

            if ($note) {
                $note->update($validated);
                return ApiResponseHelper::resData(new NoteResource($note), 'Note updated successfully', 200);
            } else {
                return ApiResponseHelper::resData([], 'Note not found or you do not have access to this note', 404);
            }
        } catch (ValidationException $e) {
            return ApiResponseHelper::resData($e->errors(), 'Validation Error', 422);
        } catch (\Exception $e) {
            \Log::error('Note update error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $note = Note::where('id', $id)->where('user_id', $user->id)->first();

            if ($note) {
                $note->delete();
                return ApiResponseHelper::resData([], 'Note deleted successfully', 200);
            } else {
                return ApiResponseHelper::resData([], 'Note not found or you do not have access to this note', 404);
            }
        } catch (\Exception $e) {
            \Log::error('Note destroy error: ', ['error' => $e->getMessage()]);
            return ApiResponseHelper::resData([], 'Internal Server Error', 500);
        }
    }
}
