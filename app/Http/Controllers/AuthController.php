<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\canPersonalInfo;
use App\Models\canProfessionalInfo;
use App\Models\ValeursHumaines;
use App\Models\recEntrepriseInfo;
use App\Models\recAdminInfo;
use App\Models\Formation;
use App\Models\Experience;
use App\Models\Certification;
use Hash;
use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Date;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;



class AuthController extends Controller
{

    // register candidat -------------------------------
    public function registerCandidat(Request $request){
        $validation = $request->validate([
            'nom'=>'required|max:255',
            'prenom'=>'required|max:255',
            'email'=>'required|unique:users|max:255',
            'type'=>'required|max:255|in:candidat,recruteur',
            'password'=>'required|min:8',
            'pays'=>'required|max:255',
            'profession'=>'required|max:255'
        ]);

        try{
            DB::beginTransaction();
            $user = User::create([
                'email'=>$request->email,
                'type'=>$request->type,
                'password'=>Hash::make($request->password),
                'email_verification_token' => Str::random(60),
            ]);


    return response()->json(['message' => 'Registration successful']);
            $candidat_personal_information = canPersonalInfo::create([
                'nom'=>$request->nom,
                'prenom'=>$request->prenom,
                'pays'=>$request->pays,
                'isCompleted'=>false,
                'user_id'=>$user->id
            ]);
            $candidat_professional_information = canProfessionalInfo::create([
                'profession'=>$request->profession,
                'isCompleted'=>false,
                'user_id'=>$user->id
            ]);
            $candidat_valeurs_humaines = ValeursHumaines::create([
                'user_id'=>$user->id
            ]);

            $token = $user->createToken('auth_token');

            DB::commit();
            return response([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'success' => false,
            ]);
        }
    }

    // register recruteur ------------------------------
    public function registerRecruteur(Request $request){
        $validation = $request->validate([
            'nom'=>'required|max:255',
            'prenom'=>'required|max:255',
            'email'=>'required|unique:users|max:255',
            'type'=>'required|max:255|in:candidat,recruteur',
            'password'=>'required|min:8',
            'pays'=>'max:255',
            'site'=>'max:255',
            'domaine'=>'max:255',
        ]);

        try{
            DB::beginTransaction();
            $user = User::create([
                'email'=>$request->email,
                'type'=>$request->type,
                'password'=>Hash::make($request->password),
                'email_verification_token' => Str::random(60),
            ]);


            $recruteur_entreprise_information = recEntrepriseInfo::create([
                'pays'=>$request->pays,
                'domaine'=>$request->domaine,
                'site'=>$request->site,
                'isCompleted'=>false,
                'user_id'=>$user->id
            ]);
            $recruteur_admin_information = recAdminInfo::create([
                'nom'=>$request->nom,
                'prenom'=>$request->prenom,
                'isCompleted'=>false,
                'user_id'=>$user->id
            ]);
            $recruteur_valeurs_humaines = ValeursHumaines::create([
                'user_id'=>$user->id
            ]);

            $token = $user->createToken('auth_token');

            DB::commit();
            return response([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
            return response([
                'success' => false
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'success' => false,
            ]);
        }
    }

    // isEmailUnique -------------------------------
    public function isEmailUnique(Request $request){
        $user = User::where('email',$request->email)->first();
        if($user){
            return response([
                'isEmailUnique' => false
            ]);
        }
        return response([
            'isEmailUnique' => true
        ]);
    }

    // login -------------------------------
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $user = User::where('email',$request->email)->first();
        if( $user ){
            if(Hash::check($request->password,$user->password)){
                $token=$user->createToken('auth_token');
                return response([
                    'success' => true,
                    'message' => 'Connection successive !',
                    'user' => $user,
                    'token' => $token
                ]);
            }else{
                return response([
                    'success' => false,
                    'message' => 'Le mot de passe que vous avez saisi est incorrect'
                ]);
            }
        }else{
            return response([
                'success' => false,
                'message' => 'Cette email n\'existe pas'
            ]);
        }
    }

    // logout ------------------------------
    public function logout(){
        $user = Auth::user();
        $user->tokens->each(function ($token) {
            $token->delete();
        });
        return response([
            'success' => true,
            'message' => 'Logged out !'
        ],200);
    }

    // delete user -----------------
    public function deleteUser($id){
        $user = User::find($id);
        if ($user) {
            $user_type = $user->type;
            if($user_type == "candidat"){
                $candidat_personal_information = canPersonalInfo::where("user_id",$user->id)->first();
                $candidat_professional_information = canProfessionalInfo::where("user_id",$user->id)->first();
                $formations = Formation::where("user_id",$user->id)->first();
                $experience = Experience::where("user_id",$user->id)->first();
                $certification = Certification::where("user_id",$user->id)->first();
                if($candidat_personal_information) $candidat_personal_information->delete();
                if($candidat_professional_information) $candidat_professional_information->delete();
                if($formations) $formations->delete();
                if($experience) $experience->delete();
                if($certification) $certification->delete();
                $user->delete();
                return response()->json([
                    'message' => 'candidat user deleted',
                    'user_id' => $user->id,
                    'candidat_personal_information_id' => $candidat_personal_information->id,
                    'candidat_professional_information_id' => $candidat_professional_information->id
                ], 200);
            }else{
                $recruteur_entreprise_information = recEntrepriseInfo::where("user_id",$user->id)->first();
                $recruteur_admin_information = recAdminInfo::where("user_id",$user->id)->first();
                if($recruteur_entreprise_information->delete() && $recruteur_admin_information->delete()){
                    $user->delete();
                    return response()->json([
                        'message' => 'recruteur user deleted',
                        'user_id' => $user->id,
                        'recruteur_entreprise_information_id' => $recruteur_entreprise_information->id,
                        'recruteur_admin_information_id' => $recruteur_admin_information->id
                    ], 200);
                }
            }
        }else{
            return response()->json([
                'error' => 'user not found'
            ], 404);
        }
    }

    // change user password -------------------------
    public function changePassword(Request $request){
        $request->validate([
            'id'=>'required',
            'old_password'=>'required',
            'new_password'=>'required'
        ]);
        $user = User::find($request->id);
        if(Hash::check($request->old_password,$user->password)){
            $user->password = Hash::make($request->new_password);
            $user->updated_at = Carbon::now();
            $user->save();
            return response([
                'success' => true,
                'message' => 'password updated successfully',
            ]);
        }else{
            return response([
                'success' => false,
                'message' => 'Le mot de passe actuel que vous avez saisi est incorrect.'
            ]);
        }
    }




}
