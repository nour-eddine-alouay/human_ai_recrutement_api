<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\recEntrepriseInfo;
use App\Models\Photo;

class EntrepriseController extends Controller
{
    //
    public function filterEntreprises(Request $request){
        $data = $request->validate([
            'pays' => 'required',
            'type' => 'required',
            'domaine' => 'required',
        ]);

        $entreprises = recEntrepriseInfo::leftJoin('photos', function ($join) {
            $join->on('recruteur_entreprise_information.user_id', '=', 'photos.user_id')
                ->where('photos.user_type','entreprise')
                ->where('recruteur_entreprise_information.isCompleted',true);
        })
        ->leftJoin('offres', function ($join) {
            $join->on('recruteur_entreprise_information.user_id', '=', 'offres.user_id')
                ->where('offres.status','active');
        })
        ->select(
            'recruteur_entreprise_information.id',
            'recruteur_entreprise_information.nom',
            'recruteur_entreprise_information.type',
            'recruteur_entreprise_information.domaine',
            'recruteur_entreprise_information.pays',
            DB::raw('COUNT(offres.id) as nombre_offres'),
            DB::raw('CONCAT("' . asset('storage/Photos/entreprise/') . '","/", photos.storage_name) as image_url')
        )
        ->groupBy(
            'recruteur_entreprise_information.id',
            'recruteur_entreprise_information.nom',
            'recruteur_entreprise_information.type',
            'recruteur_entreprise_information.domaine',
            'recruteur_entreprise_information.pays',
            'photos.storage_name',
        )
        ->orderBy('recruteur_entreprise_information.id', 'desc');

        $keywords = $request->input('keywords', $request->keywords);
        if(isset($request->keywords)){
            $entreprises = $entreprises->where('titre', 'like', '%'.$request->keywords.'%');
        }
        $pays = $request->input('pays', $request->pays);
        if($request->pays!="Tout"){
            $entreprises->where('pays', $request->pays);
        }
        $type = $request->input('type', $request->type);
        if($request->type!="Tout"){
            $entreprises->where('type', $request->type);
        }
        $domaine = $request->input('domaine', $request->domaine);
        if($request->domaine!="Tout"){
            $entreprises->where('domaine', $request->domaine);
        }
        $entreprises = $entreprises->paginate(5)->appends($request->except('page'));
        return response()->json([
            'success' => true,
            'entreprises' => $entreprises,
        ]);
    }


}
