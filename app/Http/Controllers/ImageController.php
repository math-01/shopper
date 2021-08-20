<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    private $messages = [
        'required' => 'O atributo :attribute é obrigatório',
        'file' => 'É necessário enviar um arquivo',
        'mimes' => 'O arquivo deve ter a extensão .jpg'
    ];

    public function index()
    {
        return Image::paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'file' => 'required|file|mimes:jpg'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $product = Product::findOrFail($request->product_id);
        $count = $product->image->count();

        if ($count >= 3) {
            return response()->json(['message' => 'Quantidade máxima de imagens anexadas ao produto'], 422);
        }

        $URL = Storage::put('/image', $request->file('file'));

        $data = [
            'product_id' => $request->product_id,
            'URL' => storage_path('app/' . $URL),
        ];

        $image = Image::create($data);

        return response()->json(['message' => 'Sucess', 'image' => $image]);
    }

    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    public function destroy(Image $image)
    {
        $image->delete();

        return response()->json(['message' => 'Sucess']);
    }
}
