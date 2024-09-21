<?php

    namespace App\Services\WorkerServices;

    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Worker;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;

    class WorkerLoginServices
    {
        use ApiResponseTrait;

        protected $model;

        public function __construct()
        {
            $this->model = new Worker();
        }

        public function login($request)
        {
            $validationResult = $this->validation($request);
            $token = $this->isValidData($validationResult);
            $this->isVerified($request->email);

            if ($this->getStatus($request->email) == 0) {
                return $this->apiResponse(401, 'Your Email Status Is Not Verified');
            }
            return $this->respondWithToken($token);


        }

        public function validation($request)
        {
            $validated = Validator::make($request->all(), $request->rules());
            if ($validated->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validated->errors());
            }
            return $validated->validated();
        }

        public function isValidData($data)
        {
            $credentials = $data;
//            dd($credentials);
            if (!$token = Auth::guard('worker')->attempt($credentials)) {
                return $this->apiResponse(401, 'Invalid credentials');
            }
            return $token; // Return the token if credentials are valid
        }

        public function isVerified($email)
        {
            $worker = $this->model->where('email', $email)->first();
            $verified = $worker->verification_token;
            if (!$email) {
                return $this->apiResponse(401, 'Please Verify Your Email');
            }
            return $verified;
        }

        public function getStatus($email)
        {
            $worker = $this->model->where('email', $email)->first();
            $status = $worker->status;
            return $status; // Return the worker if everything is fine
        }

        protected function respondWithToken($token)
        {
            $array = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('worker')->factory()->getTTL() * 60,
                'user' => Auth::guard('worker')->user(),
            ];

            return $this->apiResponse(200, 'Login successful', null, $array);
        }
    }
