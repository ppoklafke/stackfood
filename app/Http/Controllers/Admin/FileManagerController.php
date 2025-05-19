<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\FileManagerLogic;
use Brian2694\Toastr\Facades\Toastr;
use Madnest\Madzipper\Facades\Madzipper;

class FileManagerController extends Controller
{
    public function index($folder_path = "cHVibGlj", $storage = 'local')
    {
        if ($storage == 's3' && Helpers::getDisk()=='s3'){
            try {
                Storage::disk('s3')->exists($folder_path);
            } catch (\Exception $e){
                Toastr::error(translate('messages.something_went_wrong'));
                return back();
            }

            $folder_path = $folder_path == "cHVibGlj"? "":$folder_path;
            $directory = base64_decode($folder_path).'/';
            $s3 = Storage::disk('s3');
            $file = $directory == '/'?[]:$s3->allFiles($directory);
            $directories = $s3->allDirectories($directory);
        }else{
            $storage = 'local';
            $file = Storage::files(base64_decode($folder_path));
            $directories = Storage::directories(base64_decode($folder_path));

        }
        $folders = FileManagerLogic::format_file_and_folders($directories, 'folder');
        $files = FileManagerLogic::format_file_and_folders($file, 'file');


        $data = array_merge($folders, $files);

        return view('admin-views.file-manager.index', compact('data', 'folder_path','storage'));
    }


    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required_without:file',
            'file' => 'required_without:images',
            'path' => 'required_if:disk,local',
        ]);
        $disk = $request->disk;
        if($disk == 's3' && !$request->path){
            Toastr::warning(translate('messages.To_upload_file_on_s3_bucket_go_to_a_specific_folder'));
            return back();
        }
        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach($images as $image) {
                $name = $image->getClientOriginalName();
                if ($disk === 'local') {
                    Storage::disk($disk)->put($request->path . '/' . $name, file_get_contents($image));
                } elseif ($disk === 's3') {
                    Storage::disk($disk)->putFileAs($request->path, $image, $name);
                }
            }
        }
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            if ($disk === 's3') {
                // Get the contents of the zip file
                $zipContents = file_get_contents($file->path());
                // Extract the zip contents
                $zip = new ZipArchive;
                if ($zip->open($file->path()) === true) {
                    // Loop through each file in the zip
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);

                        if (!$stat['name'] || $this->shouldSkip($stat['name'])) {
                            continue; // Skip directories and unwanted files
                        }

                        $filename = $stat['name'];
                        $fileContent = $zip->getFromIndex($i);
                        $format = pathinfo($filename, PATHINFO_EXTENSION);

                        // Generate image name
                        $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;

                        // Upload each file to S3
                        $s3 = Storage::disk('s3');
                        $s3Path = $request->path . '/' . $imageName;
                        $s3->put($s3Path, $fileContent, 'public');
                    }
                    $zip->close();
                }
            }else{
                Madzipper::make($file)->extractTo('storage/app/'.$request->path);
            }


        }
        Toastr::success(translate('messages.image_uploaded_successfully'));
        return back()->with('success', translate('messages.image_uploaded_successfully'));
    }

    private function shouldSkip($filename) {
        // Add conditions to skip files here
        $skipFiles = [
            '__MACOSX/', // Skip macOS metadata files
            '.DS_Store', // Skip .DS_Store files
            'Thumbs.db', // Skip Thumbs.db files (Windows)
            // Add more conditions as needed
        ];

        foreach ($skipFiles as $skipFile) {
            if (strpos($filename, $skipFile) === 0) {
                return true;
            }
        }

        return false;
    }


    public function download($file_name,$storage='public')
    {
        return Storage::disk($storage)->download(base64_decode($file_name));
    }

    public function destroy($file_path)
    {
        try {
            Storage::disk('local')->delete(base64_decode($file_path));
            Storage::disk('s3')->delete(base64_decode($file_path));
        } catch (\Exception $e){

        }
        Toastr::success(translate('messages.image_deleted_successfully'));
        return back()->with('success', translate('messages.image_deleted_successfully'));
    }
}
