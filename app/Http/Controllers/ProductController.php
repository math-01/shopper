<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\History;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $messages = [
        'required' => 'O atributo :attribute é obrigatório',
        'max' => 'O atributo :attribute não deve ser maior que :max caracteres',
        'unique' => ':attribute indisponível',
    ];

    public function index(Request $request)
    {
        return Product::paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:50|unique:products',
            'name' => 'required|max:50|unique:users',
            'price' => 'required|numeric',
            'composition' => 'max:255'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $data = $request->only([
            'code',
            'name',
            'price',
            'composition'
        ]);

        $product = Product::create($data);

        History::create([
            'what' => "Produto {$request->code} cadastrado",
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ]);

        return response()->json(['message' => 'Sucesso', 'product' => $product]);
    }

    public function show(Product $product, Request $request)
    {
        History::create([
            'what' => "Produto {$product->code} visualizado",
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ]);
        return new ProductResource($product);
    }

    public function update(Request $request, $identifier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:users',
            'price' => 'required|numeric',
            'composition' => 'max:255'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $product = Product::findOrFail($identifier);

        $data = $request->only([
            'name',
            'price',
            'composition'
        ]);

        $product->update($data);

        History::create([
            'what' => "Produto {$request->code} atualizado",
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ]);

        return response()->json(['message' => 'Sucess', 'product' => $product]);
    }

    public function destroy(Product $product, Request $request)
    {
        History::create([
            'what' => "Produto {$product->code} excluído",
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ]);

        $product->delete();

        return response()->json(['message' => 'Sucess']);
    }
}
