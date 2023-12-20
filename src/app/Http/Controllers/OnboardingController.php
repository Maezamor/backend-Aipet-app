<?php

namespace App\Http\Controllers;

use Curl\Curl;
use App\Models\Dog;
use App\Models\Type;
use App\Models\Onboarding;
use App\Models\Sterlisation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OnboardingController extends Controller
{
    protected $durasi = 10;
    private  $timeThreshold ;
    private $day = 1;


    public function getTaskOnboardingFilter (): JsonResponse
    {
      
        $start = microtime(true);
        $this->timeThreshold =  now()->subDay($this->day);

        $newOrUpdateData = Type::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Type::where('deleted_at','>',$this->timeThreshold)->get();

        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('groupsDog_');
        }  

        
        $groups = Cache::remember('groupsDog_', now()->addMinutes($this->durasi), function () {
            return Type::distinct()->pluck('groups');
        }); 
        
        $newOrUpdateData = Sterlisation::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Sterlisation::where('deleted_at','>',$this->timeThreshold)->get();
        
        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('sterilDog_');
        }  

        $strilisations = Cache::remember('sterilDog_', now()->addMinutes($this->durasi), function ()  {
            return Sterlisation::all();
        }); 

        $newOrUpdateData = Dog::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Dog::where('deleted_at','>',$this->timeThreshold)->get();

        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('ageDog_');
        } 

        $age = Cache::remember('ageDog_', now()->addMinutes($this->durasi), function () {
            return Dog::distinct()->pluck('age');
        }); 
        
        
    

        if(!$groups && !$strilisations && !$age){
                return response()->json([
                    'error' => [
                        'message' => 'not found'
                    ]
                ])->setStatusCode(404);
        }

        $end = microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms'; // Konversi ke milidetik
        return response()->json([
            'time' => $executionTime,
            'error' => false,
            'message' => 'succses',
            'data' => [
                'groups' =>  $groups,
                'strilisations' => $strilisations,
                'age' =>  $age
            ]
        ])->setStatusCode(200);

    }

    public function getOnboardingData (): JsonResponse
    {

        $start = microtime(true);
        $this->timeThreshold =  now()->subDay($this->day);

        $newOrUpdateData = Onboarding::where('updated_at','>', $this->timeThreshold)->get();
      

        if ($newOrUpdateData->isNotEmpty()) {
            Cache::forget('onboardingDataresult_');
        }  

        //mengambil data auth siapa yang login        
        $user = Auth::user();
        
        $onbrResult = Cache::remember('onboardingDataresult_', now()->addMinutes($this->durasi), function () use ($user) {
            return Onboarding::where('user_id',$user->id)->get();
        }); 

        if (!$onbrResult) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }
        
        $end = microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms'; // Konversi ke milidetik
        return response()->json([
            'time' => $executionTime,
            'error' => false,
            'message' => 'Success',
            'data' => $onbrResult

        ])->setStatusCode(200);
        
        
    }

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


    public function Recomendations()
    {
        // $curl = new Curl();
        // $curl->get('http://192.168.100.8:5000');
        // dd($curl);
        // // Mendapatkan respons dalam bentuk array
        // $dataArray =  $curl->response;
        // dd($dataArray);
        //change column reads to false

        $dog = Dog::inRandomOrder()->take(3)->get();
        Dog::query()->update(['reads' => false]);

        return response()->json([
            'data' => $dog
        ])->setStatusCode(200);
    }
}
