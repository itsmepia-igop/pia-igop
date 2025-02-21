<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->has('read')) {
            $path = $request->path();
            $notification = $request->user()->notifications()->where('id', $request->read)->first();
            if($notification) {
                $notification->markAsRead();
            }
            return redirect()->to($path);
        }
        return $next($request);
    }
}
