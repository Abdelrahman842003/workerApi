<?php

    namespace App\Http\Middleware;

    use App\Http\Traits\ApiResponseTrait;
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;

    class RedirectIfAuthenticated
    {
        use ApiResponseTrait;

        /**
         * Handle an incoming request.
         *
         * @param Closure(Request): (Response) $next
         */
        public function handle(Request $request, Closure $next, string ...$guards): Response
        {
            $guards = empty($guards) ? [null] : $guards;

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return $next($request);
                }
            }

            return $this->apiResponse(400, 'User not Authenticated');

        }
    }
