<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class RegionController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/regions",
     * summary="Regions",
     * description="All regions",
     * operationId="regions",
     * tags={"Regions"},
     * @OA\Response(
     *    response=200,
     *    description="All regions",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="name", type="string", example="Farg'ona"),
     *    )
     * )
     * )
     */
    public function index()
    {
        $regions = Region::select('id', 'name_' . app()->getLocale() . ' AS name')->get();
        return response()->json($regions);
    }
}
