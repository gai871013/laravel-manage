<?php

namespace App\Http\Middleware;

use App\Entities\MiniProgramTokens;
use Closure;

class MiniProgramApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        if (empty($token)) {
            $token = $request->input('token');
        }
        $tokenData = MiniProgramTokens::with(['user'])->where('token', $token)->first();
        if (!$token || empty($tokenData) || (!$tokenData->user && !$tokenData->admin)) {
            if (!$token || empty($tokenData)) {
                return response()->json(['status_code' => 401, 'message' => '授权失效，请重试']);
            }
        }
        $request->offsetSet('token', $tokenData);
        return $next($request);
    }
}
