<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = User::all();
            return response()->json([
                'success' => true,
                'message' => 'List data user',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data list user gagal diakses: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditampilkan',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User tidak di temukan',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $data = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        // dd($data);
        if ($data->fails()) {
            return response()->json($data->errors(), 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User berhasil diupdate',
            'data' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User berhasil dihapus',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }
    }
}
