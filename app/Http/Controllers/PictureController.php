<?php

namespace App\Http\Controllers;

use App\Picture;
use Croppa;
use File;
use FileUpload;
use Illuminate\Http\Request;

class PictureController extends Controller
{
    public $folder = '/uploads/staffs/'; // add slashes for better url handling

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get all pictures
        $pictures = Picture::where('staff_central_id', $request->id)->get();

        // add properties to pictures
        $pictures->map(function ($picture) {
            $picture['size'] = File::size(public_path($picture['url']));
            $picture['thumbnailUrl'] = \URL::to(Croppa::url($picture['url'], 80, 80, ['resize']));
            $picture['deleteType'] = 'DELETE';
            $picture['id'] = $picture['id'];
            $picture['extension'] = strtolower(\File::extension($picture['url']));
            $picture['deleteUrl'] = route('pictures.destroy', $picture->id);
            //return $picture;
        });

        // show all pictures
        return response()->json(['files' => $pictures]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create upload path if it does not exist
        $path = public_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        // Simple validation (max file size 2MB and only two allowed mime types)
        $validator = new FileUpload\Validator\Simple('5M', ['image/png', 'image/jpg', 'image/jpeg','application/pdf']);

        // Simple path resolver, where uploads will be put
        $pathresolver = new FileUpload\PathResolver\Simple($path);

        // The machine's filesystem
        $filesystem = new FileUpload\FileSystem\Simple();
        //var_dump(($_FILES['files']));
        // FileUploader itself
        $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
        $slugGenerator = new FileUpload\FileNameGenerator\Slug();

        // Adding it all together. Note that you can use multiple validators or none at all
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);
        $fileupload->setFileNameGenerator($slugGenerator);
//        var_dump(($_FILES['files']));
        // Doing the deed
        list($files, $headers) = $fileupload->processAll();
//        var_dump($files);

        // Outputting it, for example like this
        foreach($headers as $header => $value) {
            header($header . ': ' . $value);
        }

        foreach($files as $file){
            //var_dump($file);
            //Remember to check if the upload was completed
            if ($file->completed) {

                // set some data
                $filename = $file->getFilename();
                $url = $this->folder . $filename;

                // save data
                $picture = Picture::create([
                    'name' => $filename,
                    'url' => $this->folder . $filename,
                ]);


                // prepare response
                $data[] = [
                    'size' => $file->size,
                    'id' => $picture->id,
                    'name' => $filename,
                    'url' => \URL::to($url),
                    'thumbnailUrl' => \URL::to(Croppa::url($url, 80, 80, ['resize'])),
                    'extension' => strtolower(\File::extension($url)),
                    'deleteType' => 'DELETE',
                    'deleteUrl' => route('pictures.destroy', $picture->id),
                ];

                // output uploaded file response
                return response()->json(['files' => $data]);
            }
        }
        // errors, no uploaded file
        return response()->json(['files' => $files]);
    }
 /*   public function store(Request $request)
    {
        // create upload path if it does not exist
        $path = public_path($this->folder);
        if(!File::exists($path)) {
            File::makeDirectory($path);
        };

        // Simple validation (max file size 2MB and only two allowed mime types)
        $validator = new FileUpload\Validator\Simple('2M', ['image/png', 'image/jpg', 'image/jpeg']);

        // Simple path resolver, where uploads will be put
        $pathresolver = new FileUpload\PathResolver\Simple($path);

        // The machine's filesystem
        $filesystem = new FileUpload\FileSystem\Simple();
//        dd(($_FILES['files']));
        // FileUploader itself
        $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);
        $slugGenerator = new FileUpload\FileNameGenerator\Slug();

        // Adding it all together. Note that you can use multiple validators or none at all
       $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);
        $fileupload->setFileNameGenerator($slugGenerator);

        // Doing the deed
        list($files, $headers) = $fileupload->processAll();

        // Outputting it, for example like this
        foreach($headers as $header => $value) {
            header($header . ': ' . $value);
        }

        foreach($files as $file){
            //Remember to check if the upload was completed
            if ($file->completed) {

                // set some data
                $filename = $file->getFilename();
                $url = $this->folder . $filename;

                // save data
                $picture = Picture::create([
                    'name' => $filename,
                    'url' => $this->folder . $filename,
                ]);

                // prepare response
                $data[] = [
                    'size' => $file->size,
                    'name' => $filename,
                    'url' => $url,
                    'thumbnailUrl' => \URL::to(Croppa::url($url, 80, 80, ['resize'])),
                    'deleteType' => 'DELETE',
                    'deleteUrl' => route('pictures.destroy', $picture->id),
                ];

                // output uploaded file response
                return response()->json(['files' => $data]);
            }
        }
        // errors, no uploaded file
        return response()->json(['files' => $files]);
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        Croppa::delete($picture->url); // delete file and thumbnail(s)
        $picture->delete(); // delete db record
        return response()->json([$picture->url]);
    }
}
