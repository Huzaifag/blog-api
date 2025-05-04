<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::get();
        return PostResource::collection($posts);
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ]);
        }

        try {
            $post = Post::create([
                'title' => $request->title,
                'body' => $request->body,
                'user_id' => Auth::user()->id
            ]);

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Post Created successfully',
                    'data' => $post
                ],
                200
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'status' => 'Failed',
                    'message' => 'Post Created not successfully',
                    'error' => $exception->getMessage()
                ],
                404
            );
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors()
            ]);
        }

        try {
            $post = Post::findOrFail($id);
            $post->update([
                'title' => $request->title,
                'body' => $request->body,
            ]);
            $post->save();

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Post Created successfully',
                    'data' => $post
                ],
                200
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'status' => 'Failed',
                    'message' => 'Post Created not successfully',
                    'error' => $exception->getMessage()
                ],
                404
            );
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Post Deleted successfully',
                'data' => $post
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Post Deleted not successfully',
                'error' => $exception->getMessage()
            ], 404);
        }
    }
}
