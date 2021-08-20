<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends Controller
{
    private $messages = [
        'required' => 'O atributo :attribute é obrigatório',
        'max' => 'O atributo :attribute não deve ser maior que :max caracteres',
        'unique' => ':attribute indisponível',
    ];

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'username' => 'required|max:10|unique:users',
            'password' => 'required'
        ], $this->messages);

        if ($validator->fails()) {
            return response()->json([$validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        $authorization = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['authorization' => $authorization, 'type' => 'Bearer'], 200);
    }

    public function connect(Request $request) {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['message' => 'Nome de usuário e/ou senha estão incorretos'], 401);
        }

        $user = User::where('username', $request['username'])->firstOrFail();

        $authorization = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['authorization' => $authorization, 'type' => 'Bearer'], 200);
    }

    public function disconnect(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sucesso'], 200);
    }
}
