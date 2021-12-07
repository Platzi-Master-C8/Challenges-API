<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RankCollection;
use App\Http\Resources\V1\RankResource;

use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankController extends Controller
{

    public function index()
    {
        return new RankCollection(DB::table('ranks')->orderBy('id', 'desc')->get());

    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $rank = Rank::find($id);
        return new RankResource($rank);

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
