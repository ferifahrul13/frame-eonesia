<?php

namespace App\Http\Controllers\b;

use App\Http\Controllers\b\BackendController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Background;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class BackgroundController extends BackendController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bcrum = $this->bcrum('Create Background', route('background.index'), 'Background');
        return view('backend.background.create', compact('bcrum'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->handleRequest($request);
        $create = Background::create($data);
        if ($create) {
            Session::flash('flash_notification', [
                'title'   => 'Successful!',
                'level'   => 'success',
                'message' => 'Background successfully added.'
            ]);

            $redirect = ($request->has('stay')) ? 'background.create' : 'background.index';

            return redirect()->route($redirect);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function handleRequest($request)
    {
        $data        = $request->all();
        $size        = [
            'width' => '1920',
            'height' => '1080',
            'dpi' => '72',
        ];
        $slug        = Str::slug($request->nama_bg);
        if ($request->has('background')) {
            $background       = $request->file('background');
            $extension   = $background->guessClientExtension();
            $fileName    = 'bg_' . $slug . '_' . date('dmy_His') . '.' . $extension;
            $thumbName   = "thumb_" . $fileName;
            $destination = public_path() . '/img/bg';

            Image::make($background->getRealPath())
                ->resize($size['width'], $size['height'])
                ->save($destination . "/" . $fileName);

            Image::make($background->getRealPath())
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('png', $size['dpi'])
                ->save($destination . "/" . $thumbName);

            $data['path_bg']       = $fileName;
            $data['path_bg_thumb'] = $thumbName;
        }
        return $data;
    }

    public function preview(Request $request)
    {
        if ($request->ajax()) {
            $background = DB::table('backgrounds')->where('id', $request->id)->first();
            if ($background) {
                $result = [
                    'result' => 'success',
                    'code' => '200',
                    'image' => URL::asset($this->pathBackground . DIRECTORY_SEPARATOR . $background->path_bg)
                ];
            } else {
                $result = ['result' => 'error', 'code' => '400', 'message' => 'Wrong ID'];
            }
            return $result;
        }
    }
}