<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    #[OA\Get(path: "/orders", summary: "List user's orders", tags: ["Store - Orders"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function index(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members have orders'], 403);

        return response()->json(Order::where('member_id', $member->id)->get());
    }

    #[OA\Post(path: "/orders", summary: "Checkout current cart and create order", tags: ["Store - Orders"])]
    #[OA\Response(response: 201, description: "Order created successfully")]
    public function store(Request $request)
    {
        $member = $request->user()->member;
        if (!$member) return response()->json(['message' => 'Only members can checkout'], 403);

        $validator = Validator::make($request->all(), [
            'street_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $carts = Cart::where('member_id', $member->id)->where('status', 'active')->get();
        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Simplistic order creation
        $order = Order::create([
            'member_id' => $member->id,
            'order_date' => now(),
            'order_price' => 100.00, // Typically calculated from cart items
            'status' => 'pending',
            'qty' => $carts->sum('quantity'),
            'street_name' => $request->street_name
        ]);

        // Mark carts as ordered
        Cart::where('member_id', $member->id)->update(['status' => 'ordered']);

        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
    }

    #[OA\Get(path: "/orders/{id}", summary: "Get order details", tags: ["Store - Orders"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function show(Request $request, $id)
    {
        $member = $request->user()->member;
        $order = Order::where('member_id', $member?->id)->find($id);

        if (!$order) return response()->json(['message' => 'Not found'], 404);

        return response()->json($order);
    }
}
