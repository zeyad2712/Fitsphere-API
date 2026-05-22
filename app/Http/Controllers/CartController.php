<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    
    public function index(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members have carts'], 403);

        return response()->json(Cart::where('member_id', $member->id)->get());
    }

    
    public function store(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members have carts'], 403);

        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $cart = Cart::create([
            'member_id' => $member->id,
            'quantity' => $request->quantity ?? 1,
            'status' => 'active'
        ]);

        return response()->json($cart, 201);
    }

    
    public function show(Request $request, $id)
    {
        $member = $request->user()->member;
        $cart = Cart::where('member_id', $member?->id)->find($id);
        if (!$cart) return response()->json(['message' => 'Not found'], 404);
        return response()->json($cart);
    }

    
    public function update(Request $request, $id)
    {
        $member = $request->user()->member;
        $cart = Cart::where('member_id', $member?->id)->find($id);
        if (!$cart) return response()->json(['message' => 'Not found'], 404);

        $cart->update($request->only('quantity'));
        return response()->json($cart);
    }

    
    public function destroy(Request $request, $id)
    {
        $member = $request->user()->member;
        $cart = Cart::where('member_id', $member?->id)->find($id);
        if (!$cart) return response()->json(['message' => 'Not found'], 404);
        
        $cart->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
