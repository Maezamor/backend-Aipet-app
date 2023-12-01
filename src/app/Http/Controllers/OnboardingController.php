<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{

    public function onboarding1Start(Request $request)
    {
    }

    public function onboarding2Start(Request $request)
    {
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
