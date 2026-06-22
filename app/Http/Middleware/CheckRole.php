<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // التحقق من أن المستخدم مسجل الدخول
        if (!auth()->check()) {
            return redirect('/login');
        }

        // جلب دور المستخدم
        $userRole = auth()->user()->role;
        
        // التحقق من أن دور المستخدم موجود في الأدوار المسموح بها
        if (!in_array($userRole, $roles)) {
            // إذا كان دور المستخدم غير مسموح، نعرض صفحة 403 مع رسالة واضحة
            abort(403, 'Accès non autorisé. Rôle requis: ' . implode(' ou ', $roles) . '. Votre rôle actuel: ' . $userRole);
        }

        return $next($request);
    }
}