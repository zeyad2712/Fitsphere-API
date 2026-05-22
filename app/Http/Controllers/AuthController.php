<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\Gym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    #[OA\Post(path: "/register", summary: "Register a new user", tags: ["Auth"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'role', 'phone'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'secret123'),
                new OA\Property(property: 'role', type: 'string', enum: ['member', 'trainer', 'gym'], example: 'member'),
                new OA\Property(property: 'phone', type: 'string', example: '+1 (555) 000-0000'),
                new OA\Property(property: 'dob', type: 'string', format: 'date', example: '1990-01-01', description: 'Required for member and trainer'),
                new OA\Property(property: 'fitness_goal', type: 'string', example: 'Lose weight', description: 'Required for member'),
                new OA\Property(property: 'weight', type: 'number', example: 70, description: 'Required for member'),
                new OA\Property(property: 'height', type: 'number', example: 175, description: 'Required for member'),
                new OA\Property(property: 'experience_years', type: 'integer', example: 5, description: 'Required for trainer'),
                new OA\Property(property: 'specialization', type: 'string', example: 'Yoga', description: 'Required for trainer'),
                new OA\Property(property: 'price_per_month', type: 'number', example: 50, description: 'Required for trainer'),
                new OA\Property(property: 'bio', type: 'string', example: 'Experienced trainer...', description: 'Required for trainer'),
                new OA\Property(property: 'manager_name', type: 'string', example: 'Manager Name', description: 'Required for gym'),
                new OA\Property(property: 'manager_email', type: 'string', format: 'email', example: 'manager@gym.com', description: 'Required for gym'),
                new OA\Property(property: 'city', type: 'string', example: 'Cairo', description: 'Required for gym'),
                new OA\Property(property: 'street_name', type: 'string', example: 'Tahrir Street', description: 'Required for gym')
            ]
        )
    )]
    #[OA\Response(response: 201, description: "User registered successfully")]
    #[OA\Response(response: 422, description: "Validation Error")]
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:member,trainer,gym',
            'phone' => 'required|string|max:20',
        ];

        if ($request->role === 'member') {
            $rules['dob'] = 'required|date';
            $rules['fitness_goal'] = 'required|string|max:255';
            $rules['weight'] = 'required|numeric|min:0';
            $rules['height'] = 'required|numeric|min:0';
        } elseif ($request->role === 'trainer') {
            $rules['dob'] = 'required|date';
            $rules['experience_years'] = 'required|integer|min:0';
            $rules['specialization'] = 'required|string|max:255';
            $rules['price_per_month'] = 'required|numeric|min:0';
            $rules['bio'] = 'nullable|string';
        } elseif ($request->role === 'gym') {
            $rules['manager_name'] = 'required|string|max:255';
            $rules['manager_email'] = 'required|string|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->phones()->create([
            'phone' => $request->phone
        ]);

        // Create the respective profile
        switch ($request->role) {
            case 'member':
                Member::create([
                    'user_id' => $user->id,
                    'birth_date' => $request->dob,
                    'fitness_goal' => $request->fitness_goal,
                    'weight' => $request->weight,
                    'height' => $request->height,
                ]);
                break;
            case 'trainer':
                Trainer::create([
                    'user_id' => $user->id,
                    'birth_date' => $request->dob,
                    'experience_years' => $request->experience_years,
                    'specialization' => $request->specialization,
                    'price_per_month' => $request->price_per_month,
                    'bio' => $request->bio,
                ]);
                break;
            case 'gym':
                Gym::create([
                    'user_id' => $user->id,
                    'manager_name' => $request->manager_name,
                    'manager_email' => $request->manager_email,
                    'city' => $request->city, 
                    'street_name' => $request->street_name
                ]);
                break;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user->load($request->role),
            'token' => $token
        ], 201);
    }

    #[OA\Post(path: "/login", summary: "Login user", tags: ["Auth"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123')
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Login successful")]
    #[OA\Response(response: 401, description: "Invalid credentials")]
    #[OA\Response(response: 422, description: "Validation Error")]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load($user->role),
            'token' => $token
        ]);
    }

    #[OA\Post(path: "/logout", summary: "Logout user", tags: ["Auth"])]
    #[OA\Response(response: 200, description: "Logged out successfully")]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
