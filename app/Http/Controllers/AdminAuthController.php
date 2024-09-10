<?php

    namespace App\Http\Controllers;

    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Admin;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class AdminAuthController
    {
        use ApiResponseTrait;

        public function register(Request $request)
        {
            $validation = Validator::make($request->all(),
                ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'], 'password' => ['required', 'min:6'],]);

            if ($validation->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validation->errors());
            }

            Admin::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password),]);

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
            if ($token = auth()->guard('admin')->attempt($userData)) {
                return $this->respondWithToken($token);
            }

            return $this->apiResponse(400, 'not found', $validation->errors());
        }

        protected function respondWithToken($token)
        {
            $array = ['access_token' => $token, 'token_type' => 'bearer', 'expires_in' => auth()->guard('admin')->factory()
                    ->getTTL() * 60,
                'user' => auth()->guard('admin')->user(),

                ];
            return $this->apiResponse(200, 'login', null, $array);
        }

        public function me()
        {
            if (auth()->guard('admin')->user()) {
                return $this->apiResponse(200, 'Successfully', null, auth()->guard('admin')->user());
            }
            return $this->apiResponse(400, 'not found user');

        }


        public function logout()
        {
            if (auth()->guard('admin')->user()) {
                auth()->guard('admin')->logout();
                return $this->apiResponse(200, 'Successfully logged out');
            }
            return $this->apiResponse(400, 'not found user');
        }


        public function refresh()
        {
            if (auth()->guard('admin')->user()) {
                $refresh = auth()->guard('admin')->refresh();
                return $this->apiResponse(200, 'refreshed', null, $refresh);
            }
            return $this->apiResponse(400, 'not found user');
        }

    }
