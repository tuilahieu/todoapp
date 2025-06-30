<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::orderBy('created_at', 'desc')->paginate(5);
        $todos = Todo::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(100);
        return response()->json(['status' => true, 'data' => $todos]);
    }

    public function show($id)
    {
        $todo = Todo::find($id);
        if(!$todo) {
            return response()->json(data: ['status' => false, 'message' => 'Todo không tồn tại.']);
        }
        return response()->json(['status' => true, 'data' => $todo]);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_completed' => 'boolean',
    ]);

    $todo = Todo::create([
        'title' => $request->title,
        'description' => $request->description,
        'is_completed' => $request->is_completed ?? false,
        'user_id' => auth()->id(), // itelephense báo lỗi nhưng vẫn chạy đc
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Tạo thành công Todo mới',
        'data' => $todo,
    ]);
}


    public function destroy($id)
    {
        $todo = Todo::find($id);
        if(!$todo) {
            return response()->json(data: ['status' => false, 'message' => 'Todo không tồn tại.']);
        }
        $todo->delete();
        return response()->json(['status' => true, 'message' => 'Xóa thành công Todo ' . $id]);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if(!$todo) {
            return response()->json(data: ['status' => false, 'message' => 'Todo không tồn tại.']);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean'
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->is_completed
        ]);

        return response()->json(['status' => true, 'message' => 'Cập nhật thành công Todo ' . $id, 'data' => $todo]);
    }
}
