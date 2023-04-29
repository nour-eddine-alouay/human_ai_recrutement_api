<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        $this->validate($request,[
            'email' => 'required|email'
        ]);
        $email = $request->email;

        if(User::where('email', $email)->doesntExist()){
            return response(['message'=>'Email Does not exists.'], 200);
        }
        
        $passwordRest = DB::table('password_reset_tokens')->where('email', $email)->first();

        // If token exists, delete it
        if($passwordRest){
            DB::table('password_reset_tokens')->where('email', $email)->delete();
        }

        $token = Str::random(10);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send Mail
        Mail::send('mail.password_reset', ['token'=>$token], function ($message) use($email){
            $message->to($email);
            $message->subject('Reset Your Password');
        });

        return response(['message' => 'Check your email.'], 200);
    }

    public function reset(Request $request){
        $this->validate($request, [
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $token = $request->token;
        $passwordRest = DB::table('password_reset_tokens')->where('token', $token)->first();

        // Verify
        if(!$passwordRest){
            return response(['message' => 'Token Not Found.'], 200);
        }

        // Validate exipire time
        if(Carbon::now()->diffInMinutes(Carbon::parse($passwordRest->created_at)) > 5){
            return response(['message' => 'Token has expired.'], 200);
        }

        $user = User::where('email', $passwordRest->email)->first();

        if(!$user){
            return response(['message' => 'User does not exists.'], 200);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('token', $token)->delete();

        return response(['message'=>'Password Successfully Updated.'], 200);
    }
}
