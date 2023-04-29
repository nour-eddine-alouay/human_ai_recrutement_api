<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\recEntrepriseInfo;
use App\Models\canPersonalInfo;
use App\Models\Offre;
use App\Models\Photo;

class StatisticsController extends Controller
{
    // getCountEntreprises
    public function getCountEntreprises(){
        $count = recEntrepriseInfo::where('isCompleted', '=', true)->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    // getCountCandidatCountryEntreprises
    public function getCountCandidatCountryEntreprises($user_id){
        $candidat = canPersonalInfo::where('user_id','=',$user_id)->select('pays')->first();
        $count = recEntrepriseInfo::where('pays','=',$candidat->pays)->count();
        return response()->json([
            'success' => true,
            'count' => $count,
            'pays' => $candidat->pays
        ]);
    }
    // getCountCandidats
    public function getCountCandidats(){
        $count = canPersonalInfo::where('isCompleted', '=', true)->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    // getCountCandidatAtSameCountry
    public function getCountCandidatAtSameCountry($user_id){
        $candidat = canPersonalInfo::where('user_id','=',$user_id)->select('pays')->first();
        $count = canPersonalInfo::where('pays','=',$candidat->pays)->count();
        return response()->json([
            'success' => true,
            'count' => $count,
            'pays' => $candidat->pays
        ]);
    }
    // getCountActiveOffres
    public function getCountActiveOffres(){
        $count = Offre::where('status', '=', 'active')->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    // getCountPostulesOfActiveOffres
    public function getCountPostulesOfActiveOffres(){
        $count = Offre::join('postules', 'offres.id', '=', 'postules.offre_id')
                   ->where('offres.status', '=', 'active')
                   ->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    // getCountEnSelectionOffres
    public function getCountEnSelectionOffres(){
        $count = Offre::where('status', '=', 'en selection')->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
    // getCountPostulesOfEnSelectionOffres
    public function getCountPostulesOfEnSelectionOffres(){
        $count = Offre::join('postules', 'offres.id', '=', 'postules.offre_id')
                   ->where('offres.status', '=', 'en selection')
                   ->count();
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}
