<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Postule;

class PostuleController extends Controller
{
    public function addPostule(Request $request){
        $validation = $request->validate([
            "lettre" => "required",
            "offre_id" => "required",
            "candidat_id" => "required"
        ]);
        $postuleCheck = Postule::where('offre_id',$request->offre_id)
        ->where('candidat_id',$request->candidat_id)->get();
        if($postuleCheck->count()>0){
            return response()->json([
                'success' => false,
                'message' => "Vous avez déjà postuler à cette offre."
            ]);
        }else{
            $postule = Postule::create([
                "lettre" => $request->lettre,
                "offre_id" => $request->offre_id,
                "candidat_id" => $request->candidat_id
            ]);
            if($postule){
                return response()->json([
                    'success' => true,
                    'message' => "Postule envoyée avec succès."
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Une erreur s'est produite lors d'envoie de postule."
            ]);
        }
    }

    public function filterPostules(Request $request){
        $valdidation = $request->validate([
            'candidat_id' => 'required',
        ]);
        $postules = Postule::join('offres','postules.offre_id', '=', 'offres.id')
        ->join('recruteur_entreprise_information','recruteur_entreprise_information.user_id', '=', 'offres.user_id')
        ->join('photos','photos.user_id', '=', 'offres.user_id')
        ->where('postules.candidat_id',$request->candidat_id)
        ->where('photos.user_type','entreprise')
        ->select(
            'postules.id as id_postule',
            'postules.status as status_postule',
            'postules.created_at as date_postule',
            'offres.id as id_offre',
            'offres.titre as titre_offre',
            'recruteur_entreprise_information.nom as nom_entreprise',
            'recruteur_entreprise_information.pays as pays_entreprise',
            DB::raw('CONCAT("' . asset('storage/Photos/entreprise/') . '","/", photos.storage_name) as image_url')
        )
        ->distinct('postules.id')
        ->orderBy('date_postule', 'desc');

        $keywords = $request->input('keywords', $request->keywords);
        if(isset($request->keywords)){
            $postules = $postules->where('offres.titre', 'like', '%'.$request->keywords.'%');
        }

        $postules = $postules->paginate(8)->appends($request->except('page'));
        return response()->json([
            'success' => true,
            'postules' => $postules,
        ]);
    }

    public function deletePostule($id){
        $postule = Postule::find($id);
        if($postule->delete()){
            return response()->json([
                'success' => true,
                'message' => 'candidature supprimée avec succès.',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de suppression de candidature.',
            ]);
        }
    }
}
