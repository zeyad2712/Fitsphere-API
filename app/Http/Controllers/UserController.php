<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPhone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(path: "/profile", summary: "Get logged-in user profile", tags: ["Auth - User"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function getProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->load('phones');

        if ($user->role === 'member') {
            $user->load('member');
        } elseif ($user->role === 'trainer') {
            $user->load('trainer');
        } elseif ($user->role === 'gym') {
            $user->load('gym');
        }

        return response()->json($user);
    }

    #[OA\Put(path: "/profile", summary: "Update logged-in user profile", tags: ["Auth - User"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Ziad Waleed'),
                new OA\Property(property: 'email', type: 'string', example: 'ziad.waleed@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'newpassword123'),
                new OA\Property(property: 'phone', type: 'string', example: '+201234567890'),
                new OA\Property(property: 'birth_date', type: 'string', format: 'date', example: '1999-05-15'),
                new OA\Property(property: 'height', type: 'number', example: 182),
                new OA\Property(property: 'weight', type: 'number', example: 78),
                new OA\Property(property: 'fitness_goal', type: 'string', example: 'Hypertrophy')
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Profile updated successfully")]
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $request->user()->id,
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'sometimes|string|max:20',
            'birth_date' => 'sometimes|date',
            'height' => 'sometimes|numeric',
            'weight' => 'sometimes|numeric',
            'fitness_goal' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update phone
        if ($request->has('phone')) {
            $phoneRecord = $user->phones()->first();
            if ($phoneRecord) {
                $phoneRecord->update(['phone' => $request->phone]);
            } else {
                $user->phones()->create(['phone' => $request->phone]);
            }
        }

        // Update Member profile
        if ($user->role === 'member') {
            $memberData = $request->only(['birth_date', 'height', 'weight', 'fitness_goal']);
            if (!empty($memberData)) {
                if ($user->member) {
                    $user->member->update($memberData);
                } else {
                    $user->member()->create($memberData);
                }
            }
        }

        $user->load('phones');
        if ($user->role === 'member') $user->load('member');

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function index()
    {
        $phones = request()->user()->phones;
        return response()->json($phones);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $request->user()->phones()->create([
            'phone' => $request->phone,
        ]);

        return response()->json($phone, 201);
    }

    public function destroy($id)
    {
        $phone = request()->user()->phones()->find($id);

        if (!$phone) {
            return response()->json(['message' => 'Phone not found'], 404);
        }

        $phone->delete();

        return response()->json(['message' => 'Phone deleted successfully']);
    }
}
