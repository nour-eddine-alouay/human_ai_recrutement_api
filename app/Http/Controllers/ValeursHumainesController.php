<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ValeursHumaines;

class ValeursHumainesController extends Controller
{

    // get valeurs humaines par user id
    public function getValeursHumaines($id){
        $valeurs_humaines = ValeursHumaines::where("user_id",$id)->first();
        if($valeurs_humaines){
            return response([
                'success' => true,
                'valeurs_humaines' => $valeurs_humaines
            ]);
        }else{
            return response([
                'success' => false,
                "message" => "pas de valeurs humaines pour cet étulisateur"
            ]);
        }
    }
    // toggle une valeur humaine pour un user
    public function toggleValeurHumaine(Request $request){
        $validation = $request->validate([
            'valeur' => 'required',
            'user_id' => 'required'
        ]);
        $user = User::find($request->user_id);
        if($user){
            $valeurs_humaines = ValeursHumaines::where("user_id",$user->id)->first();
            switch($request->valeur){
                case('agilite'):
                    $valeurs_humaines->agilite = !$valeurs_humaines->agilite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->agilite;
                    break;
                case('apprentissage_continu'):
                    $valeurs_humaines->apprentissage_continu = !$valeurs_humaines->apprentissage_continu;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->apprentissage_continu;
                    break;
                case('authenticite'):
                    $valeurs_humaines->authenticite = !$valeurs_humaines->authenticite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->authenticite;
                    break;
                case('efficacite'):
                    $valeurs_humaines->efficacite = !$valeurs_humaines->efficacite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->efficacite;
                    break;
                case('empathie'):
                    $valeurs_humaines->empathie = !$valeurs_humaines->empathie;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->empathie;
                    break;
                case('engagement_envers_client'):
                    $valeurs_humaines->engagement_envers_client = !$valeurs_humaines->engagement_envers_client;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->engagement_envers_client;
                    break;
                case('excellence'):
                    $valeurs_humaines->excellence = !$valeurs_humaines->excellence;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->excellence;
                    break;
                case('humilite'):
                    $valeurs_humaines->humilite = !$valeurs_humaines->humilite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->humilite;
                    break;
                case('innovation'):
                    $valeurs_humaines->innovation = !$valeurs_humaines->innovation;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->innovation;
                    break;
                case('integrite'):
                    $valeurs_humaines->integrite = !$valeurs_humaines->integrite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->integrite;
                    break;
                case('orientationeclient'):
                    $valeurs_humaines->orientationeclient = !$valeurs_humaines->orientationeclient;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->orientationeclient;
                    break;
                case('orientation_resultats'):
                    $valeurs_humaines->orientation_resultats = !$valeurs_humaines->orientation_resultats;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->orientation_resultats;
                    break;
                case('ouverture_esprit'):
                    $valeurs_humaines->ouverture_esprit = !$valeurs_humaines->ouverture_esprit;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->ouverture_esprit;
                    break;
                case('collaboration_travail_equipe'):
                    $valeurs_humaines->collaboration_travail_equipe = !$valeurs_humaines->collaboration_travail_equipe;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->collaboration_travail_equipe;
                    break;
                case('communication'):
                    $valeurs_humaines->communication = !$valeurs_humaines->communication;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->communication;
                    break;
                case('confiance'):
                    $valeurs_humaines->confiance = !$valeurs_humaines->confiance;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->confiance;
                    break;
                case('confiance_en_soi'):
                    $valeurs_humaines->confiance_en_soi = !$valeurs_humaines->confiance_en_soi;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->confiance_en_soi;
                    break;
                case('confidentialite'):
                    $valeurs_humaines->confidentialite = !$valeurs_humaines->confidentialite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->confidentialite;
                    break;
                case('creativite'):
                    $valeurs_humaines->creativite = !$valeurs_humaines->creativite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->creativite;
                    break;
                case('croissance'):
                    $valeurs_humaines->croissance = !$valeurs_humaines->croissance;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->croissance;
                    break;
                case('croissance_personnelle'):
                    $valeurs_humaines->croissance_personnelle = !$valeurs_humaines->croissance_personnelle;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->croissance_personnelle;
                    break;
                case('culture_apprentissage'):
                    $valeurs_humaines->culture_apprentissage = !$valeurs_humaines->culture_apprentissage;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->culture_apprentissage;
                    break;
                case('diversite_inclusion'):
                    $valeurs_humaines->diversite_inclusion = !$valeurs_humaines->diversite_inclusion;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->diversite_inclusion;
                    break;
                case('durabilite'):
                    $valeurs_humaines->durabilite = !$valeurs_humaines->durabilite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->durabilite;
                    break;
                case('flexibilite'):
                    $valeurs_humaines->flexibilite = !$valeurs_humaines->flexibilite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->flexibilite;
                    break;
                case('flexibilite_horaire'):
                    $valeurs_humaines->flexibilite_horaire = !$valeurs_humaines->flexibilite_horaire;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->flexibilite_horaire;
                    break;
                case('gestion_temps'):
                    $valeurs_humaines->gestion_temps = !$valeurs_humaines->gestion_temps;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->gestion_temps;
                    break;
                case('loyaute'):
                    $valeurs_humaines->loyaute = !$valeurs_humaines->loyaute;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->loyaute;
                    break;
                case('passion'):
                    $valeurs_humaines->passion = !$valeurs_humaines->passion;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->passion;
                    break;
                case('qualite'):
                    $valeurs_humaines->qualite = !$valeurs_humaines->qualite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->qualite;
                    break;
                case('qualite_vie_travail'):
                    $valeurs_humaines->qualite_vie_travail = !$valeurs_humaines->qualite_vie_travail;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->qualite_vie_travail;
                    break;
                case('reactivite'):
                    $valeurs_humaines->reactivite = !$valeurs_humaines->reactivite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->reactivite;
                    break;
                case('reconnaissance'):
                    $valeurs_humaines->reconnaissance = !$valeurs_humaines->reconnaissance;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->reconnaissance;
                    break;
                case('resilience'):
                    $valeurs_humaines->resilience = !$valeurs_humaines->resilience;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->resilience;
                    break;
                case('resolution_problemes'):
                    $valeurs_humaines->resolution_problemes = !$valeurs_humaines->resolution_problemes;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->resolution_problemes;
                    break;
                case('responsabilite_environnementale'):
                    $valeurs_humaines->responsabilite_environnementale = !$valeurs_humaines->responsabilite_environnementale;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->responsabilite_environnementale;
                    break;
                case('responsabilite_personnelle'):
                    $valeurs_humaines->responsabilite_personnelle = !$valeurs_humaines->responsabilite_personnelle;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->responsabilite_personnelle;
                    break;
                case('responsabilite_sociale'):
                    $valeurs_humaines->responsabilite_sociale = !$valeurs_humaines->responsabilite_sociale;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->responsabilite_sociale;
                    break;
                case('securite'):
                    $valeurs_humaines->securite = !$valeurs_humaines->securite;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->securite;
                    break;
                case('securite_financiere'):
                    $valeurs_humaines->securite_financiere = !$valeurs_humaines->securite_financiere;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->securite_financiere;
                    break;
                case('transparence'):
                    $valeurs_humaines->transparence = !$valeurs_humaines->transparence;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->transparence;
                    break;
                case('volonte_prendre_risques'):
                    $valeurs_humaines->volonte_prendre_risques = !$valeurs_humaines->volonte_prendre_risques;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->volonte_prendre_risques;
                    break;
                case('respect'):
                    $valeurs_humaines->respect = !$valeurs_humaines->respect;
                    $valeurs_humaines->updated_at = Carbon::now();
                    $valeurs_humaines->save();
                    $status = $valeurs_humaines->respect;
                    break;
            }
            return response([
                'success' => true,
                'valeur' => $request->valeur,
                'status' => $status,
            ]);
        }else{
            return response([
                'success' => false
            ]);
        }
    }
}
