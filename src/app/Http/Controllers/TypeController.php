<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class TypeController extends Controller
{

    
    public function get(Request $request): JsonResponse
    {
        $type = Type::paginate($request->limit, ["*"], "page", $request->page);

        return response()->json([
            "data" => $type
        ])->setStatusCode(200);
    }
    public function create(Request $request): JsonResponse
    {
        $validator =  Validator::make($request->all(), [
            'type' => 'required|string|max:50',
            'size' => 'required|string|max:50',
            'activityLevel' => 'required|string|max:50',
            'groups' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $type = Type::create([
            'type' => $request->type,
            'size' => $request->size,
            'activity_level' => $request->activityLevel,
            'groups' => $request->groups
        ]);

        return response()->json([
            'data' => $type
        ]);
    }

    public function update( Request $request): JsonResponse
    {
        $validator =  Validator::make($request->all(), [
            'type' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'activityLevel' => 'nullable|string|max:50',
            'groups' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $type = Type::where('id', $request->id)->first();
        if (!$type) {
            return response()->json(['errors' => ['message' => 'not found']], 404);
        }
        $type->update([
            'type' => $request->type,
            'size' => $request->size,
            'activity_level' => $request->activityLevel,
            'groups' => $request->groups
        ]);

        return response()->json([
            "data" => $type
        ])->setStatusCode(200);
    }

    public function delete(Request $request)
    {
        $type =  Type::where('id', $request->id)->first();
        $type->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
