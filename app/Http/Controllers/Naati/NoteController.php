<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiNote;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dialogue_id' => 'required|integer',
            'type_id'     => 'required|integer',
            'note'        => 'nullable|string',
        ]);

        $userId = auth()->id();

        if (empty($request->note)) {
            // Delete the note if it exists
            NaatiNote::where([
                'user_id'     => $userId,
                'dialogue_id' => $request->dialogue_id,
                'type_id'     => $request->type_id,
            ])->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empty Note',
                'note'    => null,
            ]);
        }

        // Otherwise, save or update the note
        $note = NaatiNote::updateOrCreate(
            [
                'user_id'     => $userId,
                'dialogue_id' => $request->dialogue_id,
                'type_id'     => $request->type_id,
            ],
            [
                'note'        => $request->note,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Note saved successfully.',
            'note'    => $note->note,
        ]);
    }
    public function show($dialogueId, $typeId)
    {
        $note = NaatiNote::where('user_id', auth()->id())
            ->where('dialogue_id', $dialogueId)
            ->where('type_id', $typeId)
            ->first();

        return response()->json([
            'success' => true,
            'note' => $note?->note ?? 'not found',
        ]);
    }

}
