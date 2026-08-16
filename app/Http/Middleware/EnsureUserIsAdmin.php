<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->is_admin, 403);

        if ($user->is_readonly && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return redirect()
                ->back()
                ->with('admin_status', 'Tài khoản xem thử chỉ có quyền xem, không thể thay đổi dữ liệu.');
        }

        return $next($request);
    }
}
