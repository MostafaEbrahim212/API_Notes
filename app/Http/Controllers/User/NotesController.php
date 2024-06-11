<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $notes = $user->notes;
        // return every note with notes resource
        $notes = $notes->map(function ($note) {
            return new NoteResource($note);
        });
        if ($notes->count() > 0) {
            return res_data(
                $notes,
                'notes found',
                200
            );
        } else {
            return res_data(
                [],
                'there is no notes yet',
                200
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
        $request['user_id'] = Auth::id();
        $note = Note::create($request->all());
        return res_data(
            new NoteResource($note),
            'Note created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $note = Note::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($note) {
            return res_data(
                new NoteResource($note),
                'note found',
                200
            );
        } else {
            return res_data(
                [],
                'note not found or you do not have access to this note',
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);
        $user = Auth::user();
        $note = Note::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if ($note) {
            $note->update($request->all());
            return res_data(
                new NoteResource($note),
                'note updated successfully',
                200
            );
        } else {
            return res_data(
                [],
                'note not found or you do not have access to this note',
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $note = Note::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if ($note) {
            $note->delete();
            return res_data(
                [],
                'note deleted successfully',
                200
            );
        } else {
            return res_data(
                [],
                'note not found or you do not have access to this note',
                404
            );
        }
    }
}
