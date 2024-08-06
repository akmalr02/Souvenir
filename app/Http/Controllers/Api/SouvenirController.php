<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SouvenirController extends Controller
{
    public function index()
    {
        return Souvenir::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:souvenirs',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|array|min:3',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $images = [];
            foreach ($request->file('image') as $file) {
                $images[] = $file->store('image', 'public');
            }
            $data['image'] = json_encode($images);
        }
        if ($request->file('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('pdf', 'public');
        }

        $data['total'] = $request->quantity * $request->price;
        var_dump($data['total']);

        $souvenir = Souvenir::create($data);
        return response()->json($souvenir, 201);
    }

    public function show(Souvenir $souvenir)
    {
        return $souvenir;
    }

    public function update(Request $request, Souvenir $souvenir)
    {
        $ruls = [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|array|min:3',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048|nullable',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ];

        $data = $request->validate($ruls);

        if ($request->hasFile('image')) {
            $images = [];
            foreach ($request->file('image') as $file) {
                $images[] = $file->store('image', 'public');
            }
            $data['image'] = json_encode($images);

            if ($souvenir->image) {
                $oldImages = json_decode($souvenir->image, true);
                foreach ($oldImages as $oldImage) {
                    Storage::delete('public/' . $oldImage);
                }
            }
        }
        if ($request->hasFile('pdf')) {
            if ($souvenir->pdf) {
                Storage::delete('public/' . $souvenir->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('pdf', 'public');
        }
        $souvenir->update($data);
        return response()->json($souvenir, 200);
    }

    public function destroy(Souvenir $souvenir)
    {
        $souvenir->delete();
        return response()->json(null, 204);
    }
}
