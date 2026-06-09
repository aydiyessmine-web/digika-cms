protected $routeMiddleware = [
    // ... autres middlewares
    'role' => \App\Http\Middleware\CheckRole::class,
];