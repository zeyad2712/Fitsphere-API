<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Gym;
use Illuminate\Support\Facades\Validator;

class GymController extends Controller
{
    #[OA\Get(path: "/gyms", summary: "List all gyms", tags: ["Gyms"])]
    #[OA\Parameter(name: "name", in: "query", required: false, description: "Filter by gym name", schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function index(Request $request)
    {
        $query = Gym::with('user')->withAvg('feedbackGyms', 'rating');
        
        // Filter gyms by Gym Name (stored in the users table)
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }
        
        $gyms = $query->get();

        return response()->json($gyms);
    }

    #[OA\Get(path: "/gyms/{id}", summary: "Get gym details", tags: ["Gyms"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "Gym ID", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    #[OA\Response(response: 404, description: "Gym not found")]
    public function show($id)
    {
        $gym = Gym::with('user')->withAvg('feedbackGyms', 'rating')->find($id);
        if (!$gym) return response()->json(['message' => 'Gym not found'], 404);
        return response()->json($gym);
    }

    public function update(Request $request, $id)
    {
        $gym = Gym::find($id);

        if (!$gym) return response()->json(['message' => 'Gym not found'], 404);

        if ($gym->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $validator = Validator::make($request->all(), [
            'city' => 'sometimes|string',
            'street_name' => 'sometimes|string',
            'price_per_session' => 'sometimes|numeric',
            'price_per_month' => 'sometimes|numeric',
            'features' => 'sometimes|array',
            'cover_image' => 'sometimes|string',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $gym->update($request->only(['city', 'street_name', 'price_per_session', 'price_per_month', 'features', 'cover_image']));

        return response()->json(['message' => 'Gym profile updated successfully', 'gym' => $gym]);
    }
}
