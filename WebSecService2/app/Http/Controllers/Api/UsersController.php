<?php

namespace App\Http\Controllers\Api;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Artisan;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {
// public function login(Request $request) {
//     // This endpoint no longer issues tokens directly.
//     // Please use the /oauth/token endpoint with grant_type=password to obtain an access token.
//     // Example request parameters (send as POST to /oauth/token):
//     // grant_type: password
//     // client_id: [your client id]
//     // client_secret: [your client secret]
//     // username: [user email]
//     // password: [user password]
//     // scope: (optional)
//     return response()->json([
//         'message' => 'Please use the /oauth/token endpoint to obtain an access token using the Password Grant. This endpoint does not issue tokens directly.'
//     ], 400);
// }

public function login(Request $request) {
if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    return response()->json(['error' => 'Invalid login info.'], 401);
}
    $user = User::where('email', $request->email)->select('id', 'name', 'email')->first();
    $token = $user->createToken('app');
return response()->json(['token'=>$token->accessToken, 'user'=>$user->getAttributes()]);
}


public function users(Request $request) {
    $users = User::select('id', 'name', 'email')->get()->toArray();
return response()->json(['users'=>$users]);
}
public function logout(Request $request) {
    auth()->user()->token()->revoke();
    return response()->json(['message' => 'Logged out successfully']);
}

}


// php artisan passport:install
// php artisan passport:client --personal
