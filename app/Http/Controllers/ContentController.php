<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;
use App\Models\Video;
use App\Models\Muscle;
use App\Models\VideoCategory;

class ContentController extends Controller
{
    #[OA\Get(path: "/muscles", summary: "Get all muscles", tags: ["Content - Videos"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function muscles()
    {
        return response()->json(Muscle::all());
    }

    #[OA\Get(path: "/video-categories", summary: "Get all video categories", tags: ["Content - Videos"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function videoCategories()
    {
        return response()->json(VideoCategory::all());
    }

    #[OA\Get(path: "/videos", summary: "List videos with advanced filtering", tags: ["Content - Videos"])]
    #[OA\Parameter(name: "category", in: "query", required: false, description: "Filter by category name (e.g. Workout, Recovery)", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "muscle", in: "query", required: false, description: "Filter by muscle name (e.g. Full Body, Chest)", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "name", in: "query", required: false, description: "Filter by video title", schema: new OA\Schema(type: "string"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function videos(Request $request)
    {
        $query = Video::with(['category', 'muscle']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('muscle')) {
            $query->whereHas('muscle', function ($q) use ($request) {
                $q->where('name', $request->muscle);
            });
        }

        if ($request->filled('name')) {
            $query->where('title', 'like', '%' . $request->name . '%');
        }

        return response()->json($query->get());
    }

    #[OA\Get(path: "/videos/{id}", summary: "Get single video and related videos", tags: ["Content - Videos"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "Video ID", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    #[OA\Response(response: 404, description: "Video not found")]
    public function showVideo($id)
    {
        $video = Video::with(['category', 'muscle'])->find($id);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        // Fetch up to 4 related videos in the same category
        $relatedVideos = Video::with(['category', 'muscle'])
            ->where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->limit(4)
            ->get();

        return response()->json([
            'video' => $video,
            'related' => $relatedVideos
        ]);
    }
}
