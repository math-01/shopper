<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Models\History;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    private $messages = [
        'required' => 'O atributo :attribute é obrigatório',
        'max' => 'O atributo :attribute não deve ser maior que :max caracteres',
        'unique' => ':attribute indisponível'
    ];

    public function index()
    {
        return Stock::paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'size' => 'required|max:10',
            'sku' => 'max:50|unique:stocks',
            'quantity' => 'numeric'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $product = Product::findOrFail($request->product_id);

        if ($product) {
            $data = $request->only([
                'product_id',
                'size',
                'sku',
                'quantity',
            ]);

            $stock = Stock::create($data);

            History::create([
                'what' => "Estoque de {$request->sku} cadastrado",
                'user_id' => $request->user()->id,
                'product_id' => $stock->product_id
            ]);

            return response()->json(['message' => 'Sucess', 'stock' => $stock]);
        }
    }

    public function show(Stock $stock, Request $request)
    {
        History::create([
            'what' => "Estoque {$stock->sku} visualizado",
            'user_id' => $request->user()->id,
            'product_id' => $stock->product_id
        ]);

        return new StockResource($stock);
    }

    public function update(Request $request, $identifier)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|max:10',
            'sku' => 'max:50',
            'quantity' => 'numeric'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $stock = Stock::findOrFail($identifier);

        $data = $request->only([
            'size',
            'sku',
            'quantity'
        ]);

        $stock->update($data);

        History::create([
            'what' => "Estoque {$request->sku} atualizado",
            'user_id' => $request->user()->id,
            'product_id' => $stock->product_id
        ]);

        return response()->json(['message' => 'Sucess', 'Stock' => $stock]);
    }

    public function destroy(Stock $stock, Request $request)
    {
        History::create([
            'what' => "Estoque {$stock->code} excluído",
            'user_id' => $request->user()->id,
            'product_id' => $stock->product_id
        ]);

        $stock->delete();

        return response()->json(['message' => 'Sucess']);
    }
}
