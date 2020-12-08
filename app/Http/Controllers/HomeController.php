<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use CloudConvert;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function fileUpload(Request $request)
    {
        $this->validate($request, [
            'doc' => 'mimes:doc,docx',
        ]);


        $doctmp = time();
        $doc = $request->file('doc');
        $input['doc'] = $doctmp.'.'.$doc->getClientOriginalExtension();
        $destinationPath = public_path('/docfile');
        $doc->move($destinationPath, $input['doc']);


        if($doc->getClientOriginalExtension() != "pdf"){
            CloudConvert::file($destinationPath.'/'.$input['doc'])->to('pdf');
            File::delete($destinationPath.'/'. $input['doc']);
            $input['doc'] = $doctmp.'.pdf';
        }


        return back()->with('success','docfile Upload successful');
    }


}

