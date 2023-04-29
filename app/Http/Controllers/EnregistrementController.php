<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\Enregistrement;
use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\Photo;

class EnregistrementController extends Controller
{
    public function toggleOffreEnregistrement(Request $request){
        $validation = $request->validate([
            'offre_id' => 'required',
            'user_id' => 'required'
        ]);
        $enregistrement = Enregistrement::where('offre_id',$request->offre_id)
        ->where('user_id',$request->user_id);
        if($enregistrement->exists()){
            $enregistrement->delete();
            return response()->json([
                'success' => true,
                'message' => "offre supprimé des enregistrements."
            ]);
        }else{
            $enregistrement = Enregistrement::create([
                'offre_id' => $request->offre_id,
                'user_id' => $request->user_id
            ]);
            return response()->json([
                'success' => true,
                'message' => "offre ajouté aux enregistrements."
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "une erreur s'est produite."
        ]);
    }

    public function index(Request $request)
    {
        $enregistrements = Enregistrement::with('offre')->get();
        return response()->json([
            'success' => true,
            'data' => $enregistrements,
        ]);
    }

    public function delete($id)
    {
        $enregistrement = Enregistrement::where('offre_id', $id);

        if ($enregistrement) {
            $enregistrement->delete();
            return response()->json([
                'success' => true,
                'message' => 'Offre supprimée des enregistrements.'
            ]);
        }
    }

    public function offreExists($id){
        $exists = Enregistrement::where('offre_id', $id)->exists();
        if ($exists) {
            return response()->json([
                'exists' => true
            ]);
        }
        return response()->json([
            'exists' => false
        ]);
    }

    public function filterOffers(Request $request){
        $data = $request->validate([
            'pays' => 'required',
            'contrat' => 'required',
            'modeTravail' => 'required',
            'domaine' => 'required',
            'min_salaire' => 'required',
            'max_salaire' => 'required',
        ]);
        $user_id = auth()->user()->id;
        $enregistrements = Offre::leftJoin('photos', function ($join) {
            $join->on('offres.user_id', '=', 'photos.user_id')
                ->where('photos.user_type','entreprise');
        })
        ->join('enregistrements', function ($join) use($user_id){
            $join->on('offres.id', '=', 'enregistrements.offre_id')
            ->where('enregistrements.user_id','=',$user_id);
        })
        ->whereIn('offres.status', ['active', 'en sélection'])
        ->select(
            'offres.id',
            'offres.status',
            'offres.titre',
            'offres.contrat',
            'offres.pays',
            'offres.adresse',
            'offres.mode_travail',
            'offres.hide_salaire',
            'offres.min_salaire',
            'offres.max_salaire',
            DB::raw('CONCAT("' . asset('storage/Photos/entreprise/') . '","/", photos.storage_name) as image_url'),
        )
        ->groupBy(
            'offres.id',
            'offres.status',
            'offres.titre',
            'offres.contrat',
            'offres.pays',
            'offres.adresse',
            'offres.mode_travail',
            'offres.hide_salaire',
            'offres.min_salaire',
            'offres.max_salaire',
            'photos.storage_name',
            'enregistrements.id'
        )
        ->orderBy('offres.id', 'desc');

        $keywords = $request->input('keywords', $request->keywords);
        if(isset($request->keywords)){
            $enregistrements = $enregistrements->where('titre', 'like', '%'.$request->keywords.'%');
        }
        $pays = $request->input('pays', $request->pays);
        if($request->pays!="Pays"&&$request->pays!="Tout"){
            $enregistrements->where('pays', $request->pays);
        }
        $contrat = $request->input('contrat', $request->contrat);
        if($request->contrat!="Contrat"&&$request->contrat!="Tout"){
            $enregistrements->where('contrat', $request->contrat);
        }
        $domaine = $request->input('domaine', $request->domaine);
        if($request->domaine!="Domaine"&&$request->domaine!="Tout"){
            $enregistrements->where('domaine', $request->domaine);
        }
        $modeTravail = $request->input('modeTravail', $request->modeTravail);
        if($modeTravail!="Mode de travail"&&$modeTravail!="Tout"){
            $enregistrements->where('mode_travail', $modeTravail);
        }
        $min_salaire = $request->input('min_salaire', $request->min_salaire);
        if($min_salaire!=0){
            $enregistrements->where('min_salaire','>=', $min_salaire);
            $enregistrements->where('hide_salaire', false);
        }
        $max_salaire = $request->input('max_salaire', $request->max_salaire);
        if($max_salaire!=0){
            $enregistrements->where('max_salaire','<=', $max_salaire);
            $enregistrements->where('hide_salaire', false);
        }


        $enregistrements = $enregistrements->paginate(5)->appends($request->except('page'));
        return response()->json([
            'success' => true,
            'enregistrements' => $enregistrements
        ]);
    }
}
