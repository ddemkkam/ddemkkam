<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Category_Model;
use App\Models\Holiday_Model;
use App\Models\Homepage_Model;

use Illuminate\Http\Request;


class HolidayController extends Controller
{
    public function __construct()
    {
        //parent::__construct();

        //$this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function test()
    {
        $basketData = Homepage_Model::getHomeBasketDataList('3444000270', 'ppeum9');
        dd($basketData);
    }


    public function getHoliday($branch = null)
    {
//        Log::error("asdf - ".$publicCi);

        $dataResult = Holiday_Model::getHolidayData($branch);

        return response()->json(['result' => $dataResult]);
    }

}

?>
