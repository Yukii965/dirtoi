public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->role === 'ADMIN') {
        return $next($request);
    }

    return redirect('/')->with('error', 'Accès refusé.');
}