<?php

namespace App\Http\Controllers;

use App\Models\Main_Model;

use Illuminate\Http\Request;


class BodyController extends Controller
{
    public function __construct()
    {
        //parent::__construct();

        //$this->middleware('auth', ['except' => ['index', 'show']]);

        //getBranchHeadData();
    }

    public function body01()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 1; $cn = 0; $isMain = 'n';

        return view('contents.body.body01',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body03()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 3; $cn = 0; $isMain = 'n';

        return view('contents.body.body03',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body04()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 4; $cn = 0; $isMain = 'n';

        return view('contents.body.body04',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body05()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 5; $cn = 0; $isMain = 'n';

        return view('contents.body.body05',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body06()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 6; $cn = 0; $isMain = 'n';

        return view('contents.body.body06',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body07()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 7; $cn = 0; $isMain = 'n';

        return view('contents.body.body07',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body09()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 9; $cn = 0; $isMain = 'n';

        return view('contents.body.body09',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body10()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 10; $cn = 0; $isMain = 'n';

        return view('contents.body.body10',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body12()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 12; $cn = 0; $isMain = 'n';

        return view('contents.body.body12',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body15()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 15; $cn = 0; $isMain = 'n';

        return view('contents.body.body15',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body16()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 16; $cn = 0; $isMain = 'n';

        return view('contents.body.body16',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body17()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 17; $cn = 0; $isMain = 'n';

        return view('contents.body.body17',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body19()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 19; $cn = 0; $isMain = 'n';

        return view('contents.body.body19',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }

    public function body20()
    {
        $branch = get_current_branch();

        $mn = 6; $sn = 20; $cn = 0; $isMain = 'n';

        return view('contents.body.body20',
            ['isMain' => $isMain, 'branch' => $branch, 'mn' => $mn, 'sn' => $sn, 'cn' => $cn]
        );
    }


}
?>
