<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CV;
use App\Models\Video;
use App\Models\Photo;

class FileController extends Controller
{
    // add file (cv,video)
    public function addFile(Request $request){
        if($request->hasFile('file')){
            $completeFileName = $request->file('file')->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName,PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $uniqueFileName = str_replace(' ','_',$fileNameOnly).'_'.time().'.'.$extension;
            if($request->file_type=='cv'){
                $cv = CV::where("user_id", $request->user_id)->first();
                if($cv){
                    Storage::delete('public/CVs/'.$cv->storage_name);
                    $cv->delete();
                }
                $directory = 'CVs';
                $path = $request->file('file')->storeAs('public/'.$directory,$uniqueFileName);
                $cv = CV::create([
                    'original_name'=>$completeFileName,
                    'storage_name'=>$uniqueFileName,
                    'user_id'=>$request->user_id
                ]);
                return response([
                    'success' => true,
                    'cv' => $cv
                ]);
            }elseif($request->file_type=='video'){
                $video = Video::where("user_id", $request->user_id)->first();
                if($video){
                    Storage::delete('public/Videos/'.$request->user_type.'/'.$video->storage_name);
                    $video->delete();
                }
                $directory = 'Videos/'.$request->user_type;
                $path = $request->file('file')->storeAs('public/'.$directory,$uniqueFileName);
                $video = Video::create([
                    'original_name'=>$completeFileName,
                    'storage_name'=>$uniqueFileName,
                    'user_type'=>$request->user_type,
                    'user_id'=>$request->user_id
                ]);
                return response([
                    'success' => true,
                    'video' => $video
                ]);
            }elseif($request->file_type=='photo'){
                $directory = 'public/Photos/'.$request->user_type;
                $photo = Photo::where("user_id", $request->user_id)
                    ->where('user_type', $request->user_type)
                    ->first();
                if($photo){
                    Storage::delete($directory.'/'.$photo->storage_name);
                    $photo->delete();
                }
                $path = $request->file('file')->storeAs($directory,$uniqueFileName);
                $url = asset('storage/Photos/'.$request->user_type.'/'.$uniqueFileName);
                $photo = Photo::create([
                    'original_name'=>$completeFileName,
                    'storage_name'=>$uniqueFileName,
                    'user_type'=>$request->user_type,
                    'user_id'=>$request->user_id
                ]);
                return response([
                    'success' => true,
                    'url' => $url,
                    'photo' => $photo
                ]);
            }else{
                return response([
                    'success' => false,
                    'message' => "file type not specified"
                ]);
            }
        }else{
            return response([
                'success' => false,
                'message' => "cannot find file"
            ]);
        }
    }
    // get cv
    public function getCV($user_id){
        $cv = CV::where("user_id", $user_id)->first();
        if($cv){
            $url = asset('storage/CVs/'.$cv->storage_name);
            return response([
                'success' => true,
                'url' => $url,
                'cv' => $cv
            ]);
        }else{
            return response([
                'success' => false,
                'message' => "cv pas encore uploadé"
            ]);
        }
    }
    // get video
    public function getVideo(Request $request){
        $validation = $request->validate([
            'user_id'=>'required',
            'user_type'=>'required|in:candidat,entreprise,representant'
        ]);
        $video = Video::where("user_id", $request->user_id)
            ->where('user_type', $request->user_type)
            ->first();
        if($video){
            $url = asset('storage/Videos/'.$request->user_type.'/'.$video->storage_name);
            return response([
                'success' => true,
                'url' => $url,
                'video' => $video
            ]);
        }else{
            return response([
                'success' => false,
                'message' => "video pas encore uploadé"
            ]);
        }
    }
    // get photo
    public function getPhoto(Request $request){
        $validation = $request->validate([
            'user_id'=>'required',
            'user_type'=>'required|in:candidat,entreprise,representant'
        ]);
        $photo = Photo::where("user_id", $request->user_id)
            ->where('user_type', $request->user_type)
            ->first();
        if($photo){
            $url = asset('storage/Photos/'.$request->user_type.'/'.$photo->storage_name);
            return response([
                'success' => true,
                'url' => $url,
                'photo' => $photo
            ]);
        }else{
            return response([
                'success' => false,
                'user_type' => $request->user_type,
                'message' => "photo pas encore uploadé"
            ]);
        }
    }
}
