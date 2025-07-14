<?php

namespace Sisukma\V2\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      if (!in_array(request()->ip(), ['127.0.0.1', '::1'])) {
        if($request->getHttpHost()!='sisukma.bengkaliskab.go.id'){
           return redirect(str_replace($request->getHttpHost(),'sisukma.bengkaliskab.go.id',$request->fullUrl()));
        }
      }
        return $next($request);

    }
}
