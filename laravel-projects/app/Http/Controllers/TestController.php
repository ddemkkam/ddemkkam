<?php

namespace App\Http\Controllers;

use App\Models\Test_Model;

use Illuminate\Http\Request;


class TestController extends Controller
{
    public function __construct()
    {
        //parent::__construct();

        //$this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        echo "asdf";
//        $tasks = Test_Model::get_test();
//        dd($tasks);

        //return view('contents.test',['tasks' => $tasks, 'aaa' => 'aaa2']);
//        return view('contents.test',
//            [
//                'isMain' => $isMain
//                , 'mn' => $mn
//                , 'sn' => $sn
//                , 'cn' => $cn
//            ]
//        );
    }
}
?>
