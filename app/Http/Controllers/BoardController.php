<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function show(Request $request, Board $board)
    {
        if (!$board->exists) {
            $board = $request->user()->boards()->first();
        }

        $board?->loadMissing(['columns.cards']);

        return inertia('Kanban', [
            'board' => $board ? BoardResource::make($board) : null,
        ]);
    }
}
