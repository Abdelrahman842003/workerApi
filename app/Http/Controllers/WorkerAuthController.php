<?php

    namespace App\Http\Controllers;

    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Worker;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class WorkerAuthController extends Controller
    {
        use ApiResponseTrait;

        public function register(Request $request)
        {
            $validation = Validator::make($request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:workers'],
                    'password' => ['required', 'min:6'],
                    'phone' => ['required', 'numeric', 'digits:11'],
                    'photo' => 'required|image',
                    'location' => 'required|string',
                ]);
            if ($validation->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validation->errors());
            }

            Worker::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'location' => $request->location,
                'photo' => $request->photo,
            ]);

            return $this->apiResponse(200, 'Created');
        }


        public function login(Request $request)
        {
            $validation = Validator::make($request->all(),
                ['email' => ['required', 'string', 'email', 'max:255'], 'password' => ['required', 'min:6'],]);
            if ($validation->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validation->errors());
            }

            $userData = $request->only(['email', 'password']);
            if ($token = auth()->guard('worker')->attempt($userData)) {
                return $this->respondWithToken($token);
            }

            return $this->apiResponse(400, 'not found user', $validation->errors());
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

    }
