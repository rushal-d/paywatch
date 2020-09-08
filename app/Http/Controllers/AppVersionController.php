<?php

namespace App\Http\Controllers;

use App\AppVersion;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

class AppVersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if ($this->user->email == 'bmp@bmpinfology.com') {
                return $next($request);
            }
            return redirect()->route('dashboard');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : Config::get('constants.records_per_page');
        $search_term = $request->search;
        $appVersions = AppVersion::search($search_term)->paginate(config('constants.records_per_page'));
        $records_per_page_options = config('constants.records_per_page_options');
        $title = 'All App Version';
        return view('appversion.index', [
            'title' => $title,
            'appVersions' => $appVersions,
            'records_per_page_options' => $records_per_page_options,
            'records_per_page' => $records_per_page
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create App Version';
        return view('appversion.create', [
            'title' => $title
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status_mesg = false;
        if (empty($request->file('zip_file'))) {
            return redirect()->route('appversion.create')
                ->withInput()
                ->withErrors(['Zip file is required']);
        }

        $uploadedExtension = $request->file('zip_file')->getClientOriginalExtension();

        if (!in_array($uploadedExtension, ['zip', 'rar'])) {
            return redirect()->route('appversion.create')
                ->withInput()
                ->withErrors(['.' . $uploadedExtension . ' extension is not supported. Please upload either zip file or rar file']);
        }

        $validator = \Validator::make($request->all(), [
            'app_version_name' => 'required|unique:app_versions,app_version_name',
        ],
            [
                'app_version_name.required' => 'You must enter the app_version_name!',
                'zip_file' => 'required|file',
                'app_version_name.unique' => 'The app version name already exists'
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('appversion.create')
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $appVersion = new AppVersion();
                $appVersion->app_version_name = $request->app_version_name;
                $appVersion->description = $request->description;
                $appVersion->created_by = Auth::id();
                $pathDirectory = config('constants.app_version_uploaded_dir') . DIRECTORY_SEPARATOR . $request->app_version_name;

                $pathName = $request->file('zip_file')->store($pathDirectory . $appVersion->name);

                $appVersion->path_name = $pathName;

                if ($appVersion->save()) {
                    $status_mesg = true;
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('appversion.create')->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Display the specified resource.
     *
     * @param \App\AppVersion $appVersion
     * @return \Illuminate\Http\Response
     */
    public function show(AppVersion $appVersion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appVersion = AppVersion::find($id);
        return view('appversion.edit', [
            'title' => 'Edit App Version',
            'appVersion' => $appVersion
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appVersion = AppVersion::where('id', $id)->firstOrFail();

        $status_mesg = false;
        if (!empty($request->file('zip_file'))) {
            $uploadedExtension = $request->file('zip_file')->getClientOriginalExtension();

            if (!in_array($uploadedExtension, ['zip', 'rar'])) {
                return redirect()->route('appversion.create')
                    ->withInput()
                    ->withErrors(['.' . $uploadedExtension . ' extension is not supported. Please upload either zip file or rar file']);
            }
        }


        $validator = \Validator::make($request->all(), [
            'app_version_name' => 'required|unique:app_versions,app_version_name,' . $id,
        ],
            [
                'app_version_name.required' => 'You must enter the app_version_name!',
                'zip_file' => 'required|file'
            ]
        );
        if ($validator->fails()) {
            return redirect()->route('appversion.edit', $id)
                ->withInput()
                ->withErrors($validator);
        } else {
            //start transaction to save the data
            try {
                //start transaction for rolling back if some problem occurs
                DB::beginTransaction();
                $appVersion->app_version_name = $request->app_version_name;
                $appVersion->description = $request->description;
                $appVersion->updated_by = Auth::id();

                if (!empty($request->file('zip_file'))) {
                    Storage::delete($appVersion->path_name);

                    $pathDirectory = config('constants.app_version_uploaded_dir') . DIRECTORY_SEPARATOR . $request->app_version_name;
                    $pathName = $request->file('zip_file')->store($pathDirectory . $appVersion->name);
                    $appVersion->path_name = $pathName;
                }

                if ($appVersion->save()) {
                    $status_mesg = true;
                }
            } catch (Exception $e) {
                DB::rollback();
                $status_mesg = false;
            }
        }

        if ($status_mesg) {
            DB::commit();
        }
        $status = ($status_mesg) ? 'success' : 'error';
        $mesg = ($status_mesg) ? 'Added Successfully' : 'Error Occured! Try Again!';
        return redirect()->route('appversion.edit', [$appVersion->id])->with('flash', array('status' => $status, 'mesg' => $mesg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            //only soft delete
            $appVersion = AppVersion::find($request->id);
            $appVersion->deleted_by = Auth::id();
            $appVersion->save();
            Storage::delete($appVersion->path_name);
            if ($appVersion->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request)
    {
        $status_mesg = false;
        if (!empty($request->ids)) {
            $ids = $request->ids;
            //only soft delete
            try {
                //start transaction to prevent unsuccessful deletion
                $exception = DB::transaction(function () use ($ids) {
                    foreach ($ids as $id) {
                        $appVersion = AppVersion::find($id);
                        $appVersion->deleted_by = Auth::id();
                        $appVersion->save();
                        Storage::delete($appVersion->path_name);
                        $appVersion->delete();
                    }
                });
                $status_mesg = is_null($exception) ? true : $exception;
            } catch (Exception $e) {
                $status_mesg = false;
            }
        }
        $mesg = ($status_mesg) ? 'Successfully Deleted' : 'Error deleting!';
        echo $mesg;
    }


    public function downloadZip($id)
    {
        $appVersion = AppVersion::findOrFail($id);

        if (empty($appVersion->path_name)) {
            abort('403', 'This app version does not contain zip file');
        }

        if (!Storage::exists($appVersion->path_name)) {
            abort('403', 'File not found of this version');
        }

        return Storage::download($appVersion->path_name, $appVersion->app_version_name . ' Update');
    }
}
