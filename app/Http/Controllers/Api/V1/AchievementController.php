<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

use App\Http\Resources\V1\AchievementResource;
use App\Http\Resources\V1\AchievementCollection;

class AchievementController extends Controller
{

    /**
     * @return AchievementCollection
     */
    public function index(): AchievementCollection
    {

        return new AchievementCollection(Achievement::all());
    }


    public function store(Request $request)
    {
        //
    }

    /**
     * @param Achievement $achievement
     * @return AchievementResource
     */
    public function show(Achievement $achievement): AchievementResource
    {
        return new AchievementResource($achievement);
    }


    public function update(Request $request, Achievement $achievement)
    {
        //
    }

    public function destroy(Achievement $achievement)
    {
        //
    }
}
