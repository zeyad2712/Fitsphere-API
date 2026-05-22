<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use App\Models\Trainer;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    #[OA\Get(path: "/trainers", summary: "List all trainers with advanced filtering and sorting", tags: ["Trainers"])]
    #[OA\Parameter(name: "name", in: "query", required: false, description: "Filter by trainer name", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "speciality", in: "query", required: false, description: "Filter by specialization", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "min_price", in: "query", required: false, description: "Minimum price per month", schema: new OA\Schema(type: "number"))]
    #[OA\Parameter(name: "max_price", in: "query", required: false, description: "Maximum price per month", schema: new OA\Schema(type: "number"))]
    #[OA\Parameter(name: "min_rating", in: "query", required: false, description: "Minimum average rating (e.g. 4.0)", schema: new OA\Schema(type: "number"))]
    #[OA\Parameter(name: "sort_by", in: "query", required: false, description: "Sort by option (top_rated, price_asc, price_desc)", schema: new OA\Schema(type: "string", enum: ["top_rated", "price_asc", "price_desc"]))]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function index(Request $request)
    {
        // Start the query builder and include the average rating calculation
        $query = Trainer::with('user')->withAvg('feedbackTrainers', 'rating');

        // Filter by Name (on related User model)
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Filter by Specialization
        if ($request->filled('speciality')) {
            $query->where('specialization', 'like', '%' . $request->speciality . '%');
        }

        // Filter by Minimum Price
        if ($request->filled('min_price')) {
            $query->where('price_per_month', '>=', $request->min_price);
        }

        // Filter by Maximum Price
        if ($request->filled('max_price')) {
            $query->where('price_per_month', '<=', $request->max_price);
        }

        // Filter by Minimum Rating (using having clause on the calculated average)
        if ($request->filled('min_rating')) {
            $query->having('feedback_trainers_avg_rating', '>=', $request->min_rating);
        }

        // Apply Sorting
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'top_rated':
                    // Note: Since feedback_trainers_avg_rating is a calculated aggregate, ordering by it works directly.
                    $query->orderBy('feedback_trainers_avg_rating', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price_per_month', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price_per_month', 'desc');
                    break;
            }
        }

        $trainers = $query->get();

        return response()->json($trainers);
    }

    #[OA\Get(path: "/trainers/{id}", summary: "Get trainer profile details", tags: ["Trainers"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "Trainer ID", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    #[OA\Response(response: 404, description: "Trainer not found")]
    public function show($id)
    {
        $trainer = Trainer::with('user')->withAvg('feedbackTrainers', 'rating')->find($id);
        if (!$trainer) return response()->json(['message' => 'Trainer not found'], 404);
        return response()->json($trainer);
    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) return response()->json(['message' => 'Trainer not found'], 404);

        if ($trainer->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $validator = Validator::make($request->all(), [
            'experience_years' => 'sometimes|integer',
            'bio' => 'sometimes|string',
            'birth_date' => 'sometimes|date',
            'specialization' => 'sometimes|string',
            'price_per_month' => 'sometimes|numeric',
            'location' => 'sometimes|string',
            'certifications' => 'sometimes|array',
            'total_sessions' => 'sometimes|integer',
            'active_clients' => 'sometimes|integer',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $trainer->update($request->only([
            'experience_years', 'bio', 'birth_date', 'specialization', 
            'price_per_month', 'location', 'certifications', 
            'total_sessions', 'active_clients'
        ]));

        return response()->json(['message' => 'Trainer profile updated successfully', 'trainer' => $trainer]);
    }
}
