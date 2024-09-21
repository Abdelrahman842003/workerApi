<?php

    namespace App\Http\Controllers;


    use App\Http\Requests\LoginRequest;
    use App\Http\Requests\RegisterRequest;
    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Worker;
    use App\Services\WorkerServices\WorkerLoginServices;
    use App\Services\WorkerServices\WorkerRegisterServices;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;


    class WorkerAuthController extends Controller
    {
        use ApiResponseTrait;

        public function register(RegisterRequest $request)
        {
//            $validation = Validator::make($request->all(),$request->rules());
//            if ($validation->fails()) {
//                return $this->apiResponse(400, 'Validation Error', $validation->errors());
//            }
//
//            Worker::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'password' => Hash::make($request->password),
//                'phone' => $request->phone,
//                'location' => $request->location,
//                'photo' => $request->photo,
//            ]);
//
//            return $this->apiResponse(200, 'Created');
//            dd([
//                'MAILGUN_DOMAIN' => env('MAILGUN_DOMAIN'),
//                'MAILGUN_SECRET' => env('MAILGUN_SECRET'),
//                'MAILGUN_ENDPOINT' => env('MAILGUN_ENDPOINT'),
//            ]);
            return (new WorkerRegisterServices())->register($request);
        }


        public function login(LoginRequest $request)
        {
//            $validation = Validator::make($request->all(),
//                [
//                    'email' => [
//                        'required',
//                        'string',
//                        'email',
//                        'max:255'
//                    ],
//                    'password' => [
//                        'required',
//                        'min:6'
//                    ],
//                ]);
//            if ($validation->fails()) {
//                return $this->apiResponse(400, 'Validation Error', $validation->errors());
//            }
//
//            $userData = $request->only([
//                'email',
//                'password'
//            ]);
//            if ($token = auth()->guard('worker')->attempt($userData)) {
//                return $this->respondWithToken($token);
//            }
//
//            return $this->apiResponse(400, 'not found user', $validation->errors());
//            dd($request);
            return (new WorkerLoginServices())->login($request);
        }

        public function me()
        {
            if ($date = auth()->guard('worker')->user()) {
                return $this->apiResponse(200, 'Successfully', null, $date);
            }
            return $this->apiResponse(400, 'not found user');

        }

        public function logout()
        {
            if (auth()->guard('worker')->user()) {
                auth()->guard('worker')->logout();
                return $this->apiResponse(200, 'Successfully logged out');
            }
            return $this->apiResponse(400, 'not found user');
        }

        public function refresh()
        {
            if (auth()->guard('worker')->user()) {
                $refresh = auth()->guard('worker')->refresh();
                return $this->apiResponse(200, 'refreshed', null, $refresh);
            }
            return $this->apiResponse(400, 'not found user');
        }

        protected function respondWithToken($token)
        {
            $array = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()
                        ->getTTL() * 60,
                'user' => auth()->guard('worker')->user(),
            ];
            return $this->apiResponse(200, 'login', null, $array);
        }

    }
