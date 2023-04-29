<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\recEntrepriseInfo;
use App\Models\recAdminInfo;
use App\Models\Offre;
use App\Models\Photo;
use App\Models\Video;
use App\Models\ValeursHumaines;

class OffreController extends Controller
{
    // Recruteur Functions
    public function store(Request $request){
        $data = $request->validate([
            'titre' => 'required',
            'contrat' => 'required',
            'domaine' => 'required',
            'status' => 'required',
            'description' => 'required',
            'pays' => 'required',
            'adresse' => 'required',
            'profil' => 'required',
            'competences' => 'required|array',
            'max_salaire' => 'required',
            'min_salaire' => 'required',
            'hide_salaire' => 'required',
            'mode_travail' => 'required',
            'niveau_etude' => 'required',
            'niveau_experience' => 'required',
            'user_id' => 'required'
        ]);
        if(isset($data['competences'])){
            $data['competences'] = json_encode($data['competences']);
        }
        $offre = Offre::create($data);
        return response()->json([
            'success' => true,
            'message' => "offre creée"
        ]);
    }
    public function index($user_id){
        $offres = Offre::where('user_id',$user_id)->get();
        if($offres){
            foreach ($offres as $offre) {
                $offre->competences = json_decode($offre->competences);
            }
            return response()->json([
                'success' => true,
                'offres' => $offres,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function update(Request $request, $id){
        $offre = Offre::find($id);
        $data = $request->validate([
            'titre' => 'required',
            'contrat' => 'required',
            'domaine' => 'required',
            'status' => 'required',
            'description' => 'required',
            'pays' => 'required',
            'adresse' => 'required',
            'profil' => 'required',
            'competences' => 'required|array',
            'max_salaire' => 'required',
            'min_salaire' => 'required',
            'hide_salaire' => 'required',
            'mode_travail' => 'required',
            'niveau_etude' => 'required',
            'niveau_experience' => 'required',
            'user_id' => 'required'
        ]);

        if ($offre) {
            $offre->update($data);
            return response()->json([
                'success' => true,
                'message' => "offre modifiée"
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function updateStatus(Request $request, $id){
        $offre = Offre::find($id);
        $data = $request->validate([
            'status' => 'required'
        ]);
        if ($offre) {
            $offre->status = $data['status'];
            $offre->update();
            return response()->json([
                'success' => true,
                'message' => "Statut de l'offre modifié",
                'offre' => $offre
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function delete($id){
        $offre = Offre::find($id);

        if ($offre) {
            $offre->delete();
            return response()->json([
                'success' => true,
                'message' => 'offre supprimé'
            ]);
        }
    }

    // Candidat Functions
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
        $offres = Offre::leftJoin('photos', function ($join) {
            $join->on('offres.user_id', '=', 'photos.user_id')
                ->where('photos.user_type','entreprise');
        })
        ->leftJoin('enregistrements', function ($join) use($user_id){
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
            // DB::raw('count(enregistrements.id) as countSaved'),
            DB::raw('IF(count(enregistrements.id) > 0, true, false) as isSaved')
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
            $offres = $offres->where('titre', 'like', '%'.$request->keywords.'%');
        }
        $pays = $request->input('pays', $request->pays);
        if($request->pays!="Pays"&&$request->pays!="Tout"){
            $offres->where('pays', $request->pays);
        }
        $contrat = $request->input('contrat', $request->contrat);
        if($request->contrat!="Contrat"&&$request->contrat!="Tout"){
            $offres->where('contrat', $request->contrat);
        }
        $domaine = $request->input('domaine', $request->domaine);
        if($request->domaine!="Domaine"&&$request->domaine!="Tout"){
            $offres->where('domaine', $request->domaine);
        }
        $modeTravail = $request->input('modeTravail', $request->modeTravail);
        if($modeTravail!="Mode de travail"&&$modeTravail!="Tout"){
            $offres->where('mode_travail', $modeTravail);
        }
        $min_salaire = $request->input('min_salaire', $request->min_salaire);
        if($min_salaire!=0){
            $offres->where('min_salaire','>=', $min_salaire);
            $offres->where('hide_salaire', false);
        }
        $max_salaire = $request->input('max_salaire', $request->max_salaire);
        if($max_salaire!=0){
            $offres->where('max_salaire','<=', $max_salaire);
            $offres->where('hide_salaire', false);
        }


        $offres = $offres->paginate(5)->appends($request->except('page'));
        return response()->json([
            'success' => true,
            'offres' => $offres
        ]);
    }
    public function getOffreDetails(Request $request, $id){
        // offre
        $offre = Offre::find($id);
        $offre->competences = json_decode($offre->competences);
        // image_url
        $image = Photo::where("user_id", $offre->user_id)->where('user_type', 'entreprise')->first();
        $image_url = $image?asset('storage/Photos/entreprise/'.$image->storage_name):null;
        // entreprise
        $entreprise = recEntrepriseInfo::where("user_id", $offre->user_id)
        ->select("id","nom","pays","slogan")
        ->first();

        if($offre && $entreprise){
            return response()->json([
                'success' => true,
                'offre' => $offre,
                'image_url' => $image_url,
                'entreprise' => $entreprise
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'offre or entreprise not found'
            ]);
        }
    }
    public function getEntrepriseDetails($id){
        $entreprise = recEntrepriseInfo::find($id);
        $admin = recAdminInfo::where("user_id",$entreprise->user_id)->first();
        // entreprise_image_url
        $image = Photo::where("user_id", $entreprise->user_id)->where('user_type', 'entreprise')->first();
        $entreprise_image_url = $image?asset('storage/Photos/entreprise/'.$image->storage_name):null;
        // admin_image_url
        $image = Photo::where("user_id", $entreprise->user_id)->where('user_type', 'representant')->first();
        $admin_image_url = $image?asset('storage/Photos/representant/'.$image->storage_name):null;
        // video  d'entreprise
        $video = Video::where("user_id", $entreprise->user_id)->where('user_type', 'entreprise')->first();
        $video_url = $video?asset('storage/Videos/entreprise/'.$video->storage_name):null;
        // Valeurs humaines
        $valeurs_humaines = ValeursHumaines::where("user_id",$entreprise->user_id)->first();

        return response([
            'success' => true,
            'entreprise' => $entreprise,
            'admin' => $admin,
            'valeurs_humaines' => $valeurs_humaines,
            'entreprise_image_url' => $entreprise_image_url,
            'admin_image_url' => $admin_image_url,
            'video_url' => $video_url
        ]);
    }
    public function getOffresSimilaires(Request $request){
        $validation = $request->validate([
            'domaine' => 'required',
            'id' => 'required',
        ]);
        $offres = Offre::leftJoin('photos', function ($join) use ($request) {
            $join->on('offres.user_id', '=', 'photos.user_id')
            ->where('photos.user_type','entreprise');
        })
        ->whereIn('offres.status', ['active', 'en sélection'])
        ->where('offres.id', '!=',$request->id)
        ->select(
            'offres.id',
            'offres.titre',
            'offres.pays',
            'offres.adresse',
            'offres.mode_travail',
            'offres.hide_salaire',
            'offres.min_salaire',
            'offres.max_salaire',
            DB::raw('CONCAT("' . asset('storage/Photos/entreprise/') . '","/", photos.storage_name) as image_url')
        )->inRandomOrder()->limit(4)->get();
        if($offres){
            return response()->json([
                'success' => true,
                'offres' => $offres,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function getOffresRecentes($user_id){
        $offres = Offre::where('offres.status','active')
        ->where('user_id',$user_id)
        ->whereIn('offres.status', ['active', 'en sélection'])
        ->limit(3)
        ->select(
            'id',
            'titre',
            'pays',
            'adresse',
            'mode_travail',
            'hide_salaire',
            'min_salaire',
            'max_salaire'
        )->orderBy('id','desc')->get();
        if($offres){
            return response()->json([
                'success' => true,
                'offres' => $offres,
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }


    // Commun functions
    public function show(Request $request, $id){
        $offre = Offre::leftJoin('photos', function ($join) {
            $join->on('offres.user_id', '=', 'photos.user_id')
                ->where('photos.user_type','entreprise');
        })
        ->leftJoin('recruteur_entreprise_information', function ($join) {
            $join->on('offres.user_id', '=', 'recruteur_entreprise_information.user_id');
        })
        ->select(
            'offres.*',
            'recruteur_entreprise_information.nom as nom_entreprise',
            DB::raw('CONCAT("' . asset('storage/Photos/entreprise/') . '","/", photos.storage_name) as image_url')
        )
        ->find($id);

        if($offre){
            $offre->competences = json_decode($offre->competences);
            return response()->json([
                'success' => true,
                'data' => $offre
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'offre not found'
            ]);
        }
    }
}
