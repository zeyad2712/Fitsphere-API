<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    
    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        if ($member->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action'], 403);
        }

        $validator = Validator::make($request->all(), [
            'weight' => 'sometimes|numeric',
            'height' => 'sometimes|numeric',
            'goal' => 'sometimes|string',
            'gender' => 'sometimes|in:male,female',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member->update($request->only(['weight', 'height', 'goal', 'gender']));

        return response()->json([
            'message' => 'Member profile updated successfully',
            'member' => $member
        ]);
    }
}
