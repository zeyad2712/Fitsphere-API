<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    
    public function index(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members have wishlists'], 403);

        return response()->json(Wishlist::where('member_id', $member->id)->get());
    }

    
    public function store(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members have wishlists'], 403);

        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|integer|min:1'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $wishlist = Wishlist::create([
            'member_id' => $member->id,
            'quantity' => $request->quantity ?? 1,
            'status' => 'active'
        ]);

        return response()->json($wishlist, 201);
    }

    
    public function destroy(Request $request, $id)
    {
        $member = $request->user()->member;
        $wishlist = Wishlist::where('member_id', $member?->id)->find($id);
        
        if (!$wishlist) return response()->json(['message' => 'Not found'], 404);
        
        $wishlist->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
