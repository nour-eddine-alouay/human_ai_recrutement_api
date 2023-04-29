<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\recEntrepriseInfo;
use App\Models\recAdminInfo;

class RecruteurController extends Controller
{
    // get recruteur entreprise information
    public function getEntrepriseInfo($id){
        $user = User::find($id);
        if($user){
            $recruteur_entreprise_information = recEntrepriseInfo::where("user_id",$user->id)->first();
            return response([
                'success' => true,
                'entreprise_information' => $recruteur_entreprise_information
            ]);
        }else{
            return response([
                'success' => false,
                'message' => "not found"
            ]);
        }
    }
    // get recruteur admin information
    public function getAdminInfo($id){
        $user = User::find($id);
        if($user){
            $recruteur_admin_information = recAdminInfo::where("user_id",$user->id)->first();
            return response([
                'success' => true,
                'admin_information' => $recruteur_admin_information
            ]);
        }else{
            return response([
                'success' => false,
                'message' => "not found"
            ]);
        }
    }

    // save recruteur entreprise information
    public function saveEntrepriseInfo(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'nom'=>'required|max:255',
            'domaine'=>'required|max:255',
            'type'=>'required|max:255',
            'apercu'=>'required',
            'pays'=>'required|max:255',
            'adresse'=>'required'
        ]);

        $recruteur_entreprise_information = recEntrepriseInfo::find($request->id);
        $recruteur_entreprise_information->nom = $request->nom;
        $recruteur_entreprise_information->domaine = $request->domaine;
        $recruteur_entreprise_information->type = $request->type;
        $recruteur_entreprise_information->apercu = $request->apercu;
        $recruteur_entreprise_information->slogan = $request->slogan;
        $recruteur_entreprise_information->pays = $request->pays;
        $recruteur_entreprise_information->adresse = $request->adresse;
        $recruteur_entreprise_information->site = $request->site;
        $recruteur_entreprise_information->isCompleted = true;
        $recruteur_entreprise_information->updated_at = Carbon::now();
        $recruteur_entreprise_information->save();

        return response([
            'success' => true,
            'entreprise_information' => $recruteur_entreprise_information
        ]);
    }

    // save recruteur admin information
    public function saveAdminInfo(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'nom'=>'required|max:255',
            'prenom'=>'required|max:255',
            'telephone'=>'required|max:255',
            'email_contact'=>'required|max:255',
            'poste'=>'required|max:255',
            'linkedin'=>'required|max:255'
        ]);
        $recruteur_admin_information = recAdminInfo::find($request->id);
        $recruteur_admin_information->nom = $request->nom;
        $recruteur_admin_information->prenom = $request->prenom;
        $recruteur_admin_information->telephone = $request->telephone;
        $recruteur_admin_information->email_contact = $request->email_contact;
        $recruteur_admin_information->poste = $request->poste;
        $recruteur_admin_information->linkedin = $request->linkedin;
        $recruteur_admin_information->isCompleted = true;
        $recruteur_admin_information->updated_at = Carbon::now();

        $recruteur_admin_information->save();
        return response([
            'success' => true,
            'admin_information' => $recruteur_admin_information
        ]);
    }
}
