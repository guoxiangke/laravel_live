<?php

namespace App\Http\Controllers;

use App\Models\Live;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class LiveController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('lives.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Live  $live
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Live $live)
    {
        $user = Auth::user()->load('socials');
        $live->load('messages.user.socials');

        $isLive = $request->query('live');//$live->is_live;
        $vid = $request->query('vid')?:date('Ymd');
        $preset = $request->query('preset')?:3;
        $hls = $request->query('hls')?:0;
        if($hls) {
            $domain = "/hls/index.html";
            // $domain = "https://livelss.cdn.bcebos.com/index.html";
        }else{
            $domain= '/rtmp/index.html';
        }
        // dd($domain);
        $live->hls = $hls;
        $live->m3u = $domain."?stream=classroom&vid=" . $vid .  "&preset=L" . $preset . '&live=' .  $isLive. '&hls=' .  $hls;
        activity()
           ->causedBy($user)
           ->performedOn($live)
           ->log('viewed');
        $viewed = Activity::forSubject($live)->where('description','viewed')->get()->count();
        return view('lives.show', ['live' => $live, 'user'=>$user, 'viewed'=>$viewed]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Live  $live
     * @return \Illuminate\Http\Response
     */
    public function edit(Live $live)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Live  $live
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Live $live)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Live  $live
     * @return \Illuminate\Http\Response
     */
    public function destroy(Live $live)
    {
        //
    }
}
