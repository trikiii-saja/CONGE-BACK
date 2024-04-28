<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Conge;
use App\Http\Resources\V1\CongeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CongeController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 400);
        }

        $conges = Conge::where('user_id', $request->user_id)->get();
        $congeCollection = CongeResource::collection($conges);

        return response()->json([
            'data' => $congeCollection,
            'status' => 'success',
        ], 200);
    }

    public function show(Conge $conge)
    {
        return response()->json([
            'data' => new CongeResource($conge),
            'status' => 'success',
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $conge = Conge::create($request->all());

            return response()->json([
                'data' => new CongeResource($conge),
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create conge.', 500);
        }
    }

    protected function errorResponse($message, $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
