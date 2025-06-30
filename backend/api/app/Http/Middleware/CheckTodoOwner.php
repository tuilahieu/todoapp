<?php

namespace App\Http\Middleware;

use App\Models\Todo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTodoOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $todoId = $request->route('id');
        $todo = Todo::find($todoId);
        if (!$todo) {
            return response()->json([
                'status' => false,
                'message' => 'Todo không tồn tại.'
            ], 404);
        }
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['status' => false, 'message' => 'Bạn không có quyền truy cập.'], 403);
        }
        return $next($request);
    }
}
