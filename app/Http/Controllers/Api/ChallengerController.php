<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChallengerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return  JsonResponse
     */
    public function index(): JsonResponse
    {
        $challengers = Challenger::all();
        return response()->json($challengers);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nickname' => 'required|string|min:3',
        ]);

        Challenger::create($request->all());
        return response()->json(['message' => 'Challenger created successfully'], 201);

    }

    /**
     * Display the specified resource.
     *
     * @param Challenger $challenger
     * @return JsonResponse
     */
    public function show(Challenger $challenger)
    {
        return response()->json(['Challenger' => $challenger]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Challenger $challenger
     * @return JsonResponse
     */
    public function update(Request $request, Challenger $challenger)
    {
        $request->validate([
            'nickname' => 'required|string|min:3',
        ]);

        $challenger->update($request->all());
        return response()->json(['message' => 'Challenger updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Challenger $challenger
     * @return JsonResponse
     */
    public function destroy(Challenger $challenger)
    {
        $challenger->delete();
        return response()->json(['message' => 'Successfully Removed'],204);
    }
}
