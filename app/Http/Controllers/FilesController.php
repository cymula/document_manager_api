<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function index()
    {
        $files = auth()->user()->files;

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    public function show($id)
    {
        $file = auth()->user()->files()->find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found '
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $file->toArray()
        ], 400);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $file = new Post();
        $file->title = $request->title;
        $file->description = $request->description;

        if (auth()->user()->files()->save($file))
            return response()->json([
                'success' => true,
                'data' => $file->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'File not added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $file = auth()->user()->files()->find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 400);
        }

        $updated = $file->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'File can not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $file = auth()->user()->files()->find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 400);
        }

        if ($file->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'File can not be deleted'
            ], 500);
        }
    }
}
