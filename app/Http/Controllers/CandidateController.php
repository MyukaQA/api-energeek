<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Candidate::orderBy('created_at', 'desc')->get();

            foreach ($data as $item) {
                $item->skills->pluck('name');
            }

            return response()->json(['status' => 'OK', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ada kesalahan', 'error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Candidate::find($id);

            $data->skills->pluck('name');

            return response()->json(['status' => 'OK', 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ada kesalahan', 'error' => $e->getMessage()]);
        }
    }
}
