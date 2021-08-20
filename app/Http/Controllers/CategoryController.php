<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $messages = [
        'required' => 'O atributo :attribute é obrigatório',
        'max' => 'O atributo :attribute não deve ser maior que :max caracteres'
    ];

    public function index()
    {
        return Category::paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'name' => 'required|max:50'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $product = Product::findOrFail($request->product_id);

        if ($product) {
            $data = $request->only([
                'product_id',
                'name'
            ]);

            $category = Category::create($data);

            return response()->json(['message' => 'Sucess', 'category' => $category]);
        }
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Request $request, $identifier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $category = Category::findOrFail($identifier);

        $data = $request->only([
            'name'
        ]);

        $category->update($data);

        return response()->json(['message' => 'Sucess', 'category' => $category]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Sucess']);
    }
}
