<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Onboarding;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{

    public function onboarding1Start(Request $request): JsonResponse
    {
        $validator = Validator([
            "group" => 'require',
            "sterlisasi" => "require",
            "age" => "require"
        ]);

        if ($validator->fails()) {
            // Validasi gagal, ambil pesan kesalahan
            $errors = $validator->errors()->toArray();

            return response()->json(['errors' => $errors], 400);
        }

        $user =  Auth::user();
        $obr1 = Onboarding::create([
            'user_id' => $user->id,
            'group' => $request->group,
            'sterlisasi' => $request->sterlisasi,
            'age' =>  $request->age
        ]);

        return response()->json([
            'status' => 'success',
        ])->setStatusCode(201);
    }

    public function onboarding2Start(Request $request): JsonResponse
    {

        $user = Auth::user();
        $validator = Validator([
            "rescueStory1" => 'require',
            "rescueStory2" => "require",
            "rescueStory3" => "require",
            "requestStory" => "require"
        ]);

        if ($validator->fails()) {
            // Validasi gagal, ambil pesan kesalahan
            $errors = $validator->errors()->toArray();

            return response()->json(['errors' => $errors], 400);
        }

        $obr =  Onboarding::where('user_id', $user->id);
        $obr->update([
            "rescue_story_1" => $request->rescueStory1,
            "rescue_story_2" => $request->rescueStory2,
            "rescue_story_3" => $request->rescueStory3,
            "request_story" => $request->requestStory
        ]);

        return response()->json([
            'status' => 'success',
        ])->setStatusCode(201);
    }


    public function onboardingEnd()
    {
        //change column reads to false
        Dog::query()->update(['reads' => false]);

        return response()->json([
            'data' => 'true'
        ])->setStatusCode(200);
    }
}
