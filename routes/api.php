<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\EnregistrementController;
use App\Http\Controllers\RecruteurController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\ValeursHumainesController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PostuleController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register/candidat',[AuthController::class,'registerCandidat']);
Route::post('/register/recruteur',[AuthController::class,'registerRecruteur']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/isEmailUnique',[AuthController::class,'isEmailUnique']);
Route::delete('/user/delete/{id}',[AuthController::class,'deleteUser']);

Auth::routes(['verify' => true]);













Route::post('/forgot',[ForgotPasswordController::class,'forgot']);
Route::post('/reset',[ForgotPasswordController::class,'reset']);

Route::middleware('auth:api')->group(function(){
    // settings commun routes
    Route::post('/logout',[AuthController::class,'logout']);
    Route::put('/user/change-password',[AuthController::class,'changePassword']);
    // file commun routes
    Route::post('/file', [FileController::class, 'addFile']);
    Route::post('/photo', [FileController::class, 'getPhoto']);
    Route::post('/video', [FileController::class, 'getVideo']);
    // valeurs humaines
    Route::prefix('valeurs')->group(function () {
        Route::post('/{user_id}', [ValeursHumainesController::class, 'getValeursHumaines']);
        Route::put('/', [ValeursHumainesController::class, 'toggleValeurHumaine']);
    });
    // candidat ----------------------------------
    Route::prefix('candidat')->group(function () {
        Route::get('/fullname/{user_id}', [CandidatController::class, 'getNomEtPrenom']);
        Route::prefix('personal')->group(function () {
            Route::post('/{user_id}', [CandidatController::class, 'getPersonalInfo']);
            Route::put('/save', [CandidatController::class, 'savePersonalInfo']);
        });
        Route::prefix('professional')->group(function () {
            Route::post('/{user_id}', [CandidatController::class, 'getProfessionalInfo']);
            Route::put('/save', [CandidatController::class, 'saveProfessionalInfo']);
        });
        Route::prefix('formation')->group(function () {
            Route::post('/{user_id}', [CandidatController::class, 'getAllFormations']);
            Route::post('/', [CandidatController::class, 'addFormation']);
            Route::put('/', [CandidatController::class, 'editFormation']);
            Route::post('/get/{id}', [CandidatController::class, 'getFormation']);
            Route::post('/delete/{id}', [CandidatController::class, 'deleteFormation']);
        });
        Route::prefix('experience')->group(function () {
            Route::post('/{user_id}', [CandidatController::class, 'getAllExperiences']);
            Route::post('/', [CandidatController::class, 'addExperience']);
            Route::put('/', [CandidatController::class, 'editExperience']);
            Route::post('/get/{id}', [CandidatController::class, 'getExperience']);
            Route::post('/delete/{id}', [CandidatController::class, 'deleteExperience']);
        });
        Route::prefix('certification')->group(function () {
            Route::post('/{user_id}', [CandidatController::class, 'getAllCertifications']);
            Route::post('/', [CandidatController::class, 'addCertification']);
            Route::put('/', [CandidatController::class, 'editCertification']);
            Route::post('/get/{id}', [CandidatController::class, 'getCertification']);
            Route::post('/delete/{id}', [CandidatController::class, 'deleteCertification']);
        });
        Route::prefix('file')->group(function () {
            Route::post('/cv/{user_id}', [FileController::class, 'getCV']);
        });
        Route::prefix('offres')->group(function () {
            Route::post('/search', [OffreController::class, 'filterOffers']);
            Route::get('/{id}', [OffreController::class, 'getOffreDetails']);
            Route::get('/recentes/{user_id}', [OffreController::class, 'getOffresRecentes']);
            Route::post('/domaine', [OffreController::class, 'getOffresSimilaires']);
        });
        Route::prefix('entreprise')->group(function () {
            Route::get('/{id}', [OffreController::class, 'getEntrepriseDetails']);
            Route::post('/search', [EntrepriseController::class, 'filterEntreprises']);
        });
        Route::prefix('postules')->group(function () {
            Route::delete('/{id}', [PostuleController::class, 'deletePostule']);
            Route::post('/search', [PostuleController::class, 'filterPostules']);
            Route::post('/', [PostuleController::class, 'addPostule']);
        });
        Route::prefix('enregistrements')->group(function () {
            Route::post('/', [EnregistrementController::class, 'toggleOffreEnregistrement']);
            Route::post('/search', [EnregistrementController::class, 'filterOffers']);
            Route::delete('/{id}', [EnregistrementController::class, 'delete']);
            Route::get('/offre/{id}', [EnregistrementController::class, 'offreExists']);
        });
        Route::prefix('statistics')->group(function () {
            Route::get('/offres/active/count', [StatisticsController::class, 'getCountActiveOffres']);
            Route::get('/offres/active/postules/count', [StatisticsController::class, 'getCountPostulesOfActiveOffres']);
            Route::get('/offres/en-selection/count', [StatisticsController::class, 'getCountEnSelectionOffres']);
            Route::get('/offres/en-selection/postules/count', [StatisticsController::class, 'getCountPostulesOfEnSelectionOffres']);
            Route::get('/entreprise/count', [StatisticsController::class, 'getCountEntreprises']);
            Route::get('/candidat/count', [StatisticsController::class, 'getCountCandidats']);
            Route::get('/pays/entreprise/count/{candidat_id}', [StatisticsController::class, 'getCountCandidatCountryEntreprises']);
            Route::get('/pays/candidats/count/{candidat_id}', [StatisticsController::class, 'getCountCandidatAtSameCountry']);
        });
    });
    // recruteur ----------------------------------
    Route::prefix('recruteur')->group(function () {
        Route::prefix('entreprise')->group(function () {
            Route::post('/{user_id}', [RecruteurController::class, 'getEntrepriseInfo']);
            Route::put('/', [RecruteurController::class, 'saveEntrepriseInfo']);
        });
        Route::prefix('admin')->group(function () {
            Route::post('/{user_id}', [RecruteurController::class, 'getAdminInfo']);
            Route::put('/', [RecruteurController::class, 'saveAdminInfo']);
        });
        Route::prefix('offres')->group(function () {
            Route::post('/', [OffreController::class, 'store']);
            Route::get('/{user_id}', [OffreController::class, 'index']);
            Route::get('/offre/{id}', [OffreController::class, 'show']);
            Route::patch('/{id}', [OffreController::class, 'update']);
            Route::delete('/{id}', [OffreController::class, 'delete']);
            Route::patch('status/{id}', [OffreController::class, 'updateStatus']);
        });
    });
});
