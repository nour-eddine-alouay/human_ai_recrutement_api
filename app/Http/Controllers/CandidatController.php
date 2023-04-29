<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Formation;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\canPersonalInfo;
use App\Models\canProfessionalInfo;
use App\Models\canValeursHumaines;
use Exception;

class CandidatController extends Controller
{
    // get candidat personal information
    public function getPersonalInfo($id){
        $user = User::find($id);
        if($user){
            $candidat_personal_information = canPersonalInfo::where("user_id",$user->id)->first();
            return response([
                'success' => true,
                'personal_information' => $candidat_personal_information
            ]);
        }else{
            return response([
                'success' => false
            ]);
        }
    }
    // get num et prenom
    public function getNomEtPrenom($id){
        $user = User::find($id);
        if($user){
            $nomEtPrenom = canPersonalInfo::where("user_id",$user->id)
            ->select('nom','prenom')
            ->first();
            return response([
                'success' => true,
                'nomEtPrenom' => strtoupper($nomEtPrenom->nom).' '.ucfirst($nomEtPrenom->prenom),
            ]);
        }else{
            return response([
                'success' => false
            ]);
        }
    }
    // get candidat professional information
    public function getProfessionalInfo($id){
        $user = User::find($id);
        if($user){
            $candidat_professional_information = canProfessionalInfo::where("user_id",$user->id)->first();
            $candidat_professional_information->competences = json_decode($candidat_professional_information->competences);
            return response([
                'success' => true,
                'professional_information' => $candidat_professional_information
            ]);
        }else{
            return response([
                'success' => false
            ]);
        }
    }

    // save candidat personal information
    public function savePersonalInfo(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'nom'=>'required|max:255',
            'prenom'=>'required|max:255',
            'civilite'=>'required|max:255|in:Monsieur,Madame',
            'etat_civilite'=>'required|max:255|in:Marié,Célibataire',
            'date_naissance'=>'required',
            'telephone'=>'required|max:255',
            'pays'=>'required|max:255',
            'ville'=>'required|max:255',
            'linkedin'=>'required|max:255',
            'skype'=>'max:255'
        ]);

        $candidat_personal_information = canPersonalInfo::find($request->id);
        $candidat_personal_information->nom = $request->nom;
        $candidat_personal_information->prenom = $request->prenom;
        $candidat_personal_information->civilite = $request->civilite;
        $candidat_personal_information->etat_civilite = $request->etat_civilite;
        $candidat_personal_information->date_naissance = $request->date_naissance;
        $candidat_personal_information->telephone = $request->telephone;
        $candidat_personal_information->pays = $request->pays;
        $candidat_personal_information->ville = $request->ville;
        $candidat_personal_information->linkedin = $request->linkedin;
        $candidat_personal_information->skype = $request->skype;
        $candidat_personal_information->isCompleted = true;
        $candidat_personal_information->updated_at = Carbon::now();
        $candidat_personal_information->save();

        return response([
            'success' => true,
            'personal_information' => $candidat_personal_information
        ]);
    }

    // save candidat professional information
    public function saveProfessionalInfo(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'domaine'=>'required|max:255',
            'profession'=>'required|max:255',
            'competences' => 'required|array',
            'apercu'=>'required',
            'niveau_etude'=>'required|max:255',
            'niveau_experience'=>'required|max:255'
        ]);
        if(isset($request->competences)){
            $request->competences = json_encode($request->competences);
        }
        $candidat_professional_information = canProfessionalInfo::find($request->id);
        $candidat_professional_information->domaine = $request->domaine;
        $candidat_professional_information->profession = $request->profession;
        $candidat_professional_information->competences = $request->competences;
        $candidat_professional_information->apercu = $request->apercu;
        $candidat_professional_information->niveau_etude = $request->niveau_etude;
        $candidat_professional_information->niveau_experience = $request->niveau_experience;
        $candidat_professional_information->isCompleted = true;
        $candidat_professional_information->updated_at = Carbon::now();

        $candidat_professional_information->save();
        return response([
            'success' => true,
            'professional_information' => $candidat_professional_information
        ]);
    }

    // -------------------------------------------------------------------------------------
    // add formation
    public function addFormation(Request $request){
        $validation = $request->validate([
            'date_debut' => 'required',
            'date_fin' => 'required',
            'nom' => 'required',
            'diplome' => 'required',
            'etat' => 'required|in:en_cours,valide,non_valide',
            'etablissement' => 'required',
            'user_id' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $formation = Formation::create([
                'date_debut'=>$request->date_debut,
                'date_fin'=>$request->date_fin,
                'nom'=>$request->nom,
                'diplome'=>$request->diplome,
                'etat'=>$request->etat,
                'etablissement'=>$request->etablissement,
                'description'=>$request->description,
                'user_id'=>$request->user_id
            ]);
            DB::commit();
            return response([
                'success' => true,
                'formation' => $formation
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'success' => false,
            ]);
        }
    }

    // get all formations
    public function getAllFormations($user_id){
        $formations = Formation::where("user_id", $user_id)->get();
        return response()->json($formations);
    }

    // delete formation by id
    public function deleteFormation($id){
        $formation = Formation::find($id);
        if ($formation) {
            $formation->delete();
            return response([
                'success' => true,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // get formation by id
    public function getFormation($id){
        $formation = Formation::find($id);
        if ($formation) {
            return response([
                'success' => true,
                'formation' => $formation,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // edit formation by id
    public function editFormation(Request $request){
        $validation = $request->validate([
            'id' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'nom' => 'required',
            'diplome' => 'required',
            'etat' => 'required|in:en_cours,valide,non_valide',
            'etablissement' => 'required'
        ]);
        $formation = Formation::find($request->id);
        if ($formation) {
            try{
                DB::beginTransaction();
                $formation->date_debut = $request->date_debut;
                $formation->date_fin = $request->date_fin;
                $formation->nom = $request->nom;
                $formation->diplome = $request->diplome;
                $formation->etat = $request->etat;
                $formation->etablissement = $request->etablissement;
                $formation->description = $request->description;
                $formation->updated_at = Carbon::now();
                $formation->save();
                DB::commit();
                return response([
                    'success' => true,
                    'formation' => $formation
                ]);
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    'success' => false,
                ]);
            }
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // -------------------------------------------------------------------------------------
    // add experience
    public function addExperience(Request $request){
        $validation = $request->validate([
            'date_debut' => 'required',
            'date_fin' => 'required',
            'poste' => 'required',
            'entreprise' => 'required',
            'etat' => 'required|in:en_cours,complete,non_complete',
            'user_id' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $experience = Experience::create([
                'date_debut'=>$request->date_debut,
                'date_fin'=>$request->date_fin,
                'poste'=>$request->poste,
                'entreprise'=>$request->entreprise,
                'etat'=>$request->etat,
                'description'=>$request->description,
                'user_id'=>$request->user_id
            ]);
            DB::commit();
            return response([
                'success' => true,
                'experience' => $experience
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'success' => false,
            ]);
        }
    }

    // get all experiences
    public function getAllExperiences($user_id){
        $experiences = Experience::where("user_id", $user_id)->get();
        return response()->json($experiences);
    }

    // delete experience by id
    public function deleteExperience($id){
        $experience = Experience::find($id);
        if ($experience) {
            $experience->delete();
            return response([
                'success' => true,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // get experience by id
    public function getExperience($id){
        $experience = Experience::find($id);
        if ($experience) {
            return response([
                'success' => true,
                'experience' => $experience,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // edit experience by id
    public function editExperience(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'date_debut'=>'required',
            'date_fin'=>'required',
            'poste'=>'required',
            'entreprise'=>'required',
            'etat'=>'required|in:en_cours,complete,non_complete'
        ]);
        $experience = Experience::find($request->id);
        if ($experience) {
            try{
                DB::beginTransaction();
                $experience->date_debut = $request->date_debut;
                $experience->date_fin = $request->date_fin;
                $experience->poste = $request->poste;
                $experience->entreprise = $request->entreprise;
                $experience->etat = $request->etat;
                $experience->description = $request->description;
                $experience->updated_at = Carbon::now();
                $experience->save();
                DB::commit();
                return response([
                    'success' => true,
                    'experience' => $experience
                ]);
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    'success' => false,
                ]);
            }
        }else{
            return response([
                'success' => false,
            ]);
        }
    }
    // -------------------------------------------------------------------------------------
    // add certification Certification
    public function addCertification(Request $request){
        $validation = $request->validate([
            'date_debut' => 'required',
            'date_fin' => 'required',
            'nom' => 'required',
            'autorite' => 'required',
            'user_id' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $certification = Certification::create([
                'date_debut'=>$request->date_debut,
                'date_fin'=>$request->date_fin,
                'nom'=>$request->nom,
                'autorite'=>$request->autorite,
                'licence'=>$request->licence,
                'user_id'=>$request->user_id
            ]);
            DB::commit();
            return response([
                'success' => true,
                'certification' => $certification
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'success' => false,
            ]);
        }
    }

    // get all certifications
    public function getAllCertifications($user_id){
        $certifications = Certification::where("user_id", $user_id)->get();
        return response()->json($certifications);
    }

    // delete certification by id
    public function deleteCertification($id){
        $certification = Certification::find($id);
        if ($certification) {
            $certification->delete();
            return response([
                'success' => true,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // get certification by id
    public function getCertification($id){
        $certification = Certification::find($id);
        if ($certification) {
            return response([
                'success' => true,
                'certification' => $certification,
            ]);
        }else{
            return response([
                'success' => false,
            ]);
        }
    }

    // edit certification by id
    public function editCertification(Request $request){
        $validation = $request->validate([
            'id'=>'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'nom' => 'required',
            'autorite' => 'required',
        ]);
        $certification = Certification::find($request->id);
        if ($certification) {
            try{
                DB::beginTransaction();
                $certification->date_debut = $request->date_debut;
                $certification->date_fin = $request->date_fin;
                $certification->nom = $request->nom;
                $certification->autorite = $request->autorite;
                $certification->licence = $request->licence;
                $certification->updated_at = Carbon::now();
                $certification->save();
                DB::commit();
                return response([
                    'success' => true,
                    'certification' => $certification
                ]);
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    'success' => false,
                ]);
            }
        }else{
            return response([
                'success' => false,
            ]);
        }
    }
}
