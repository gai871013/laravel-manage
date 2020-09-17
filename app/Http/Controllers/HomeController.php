<?php

namespace App\Http\Controllers;

use App\Models\News;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('sendSms', 'getIndex', 'NotifyBoc');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * 网站首页 2017-7-29 11:03:41 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $news = News::orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('welcome', compact('news'));
    }

    public function NotifyBoc($id)
    {
        \Log::info($id);
        \Log::info('request:', request()->all());
        \Log::info(file_get_contents('php://input'));

        $client = new Client();
        $response = $client->request('POST', 'https://pay.bzh001.com/notify/boc/' . $id, [
            'form_params' => request()->all()
        ]);

        \Log::info($response->getBody()->getContents());
    }
}
