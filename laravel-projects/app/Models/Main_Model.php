<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Main_Model extends Authenticatable
{

    public function test_insert_data()
    {
        $data = DB::table('ppeum_common_data')->orderBy('sn', 'asc')->get();
//        echo "<pre>"; print_r($data); echo "</pre>"; exit();

        foreach ( $data as $index => $row ) {
            $branch = $row->branch;
            $insertData['keywords'] =  branchHeadData($branch, 'keywords');
            $insertData['description'] =  branchHeadData($branch, 'description');
            $insertData['title'] =  branchHeadData($branch, 'title');
            $insertData['site_name'] =  branchHeadData($branch, 'site_name');
            $insertData['url'] =  branchHeadData($branch, 'url');
            $insertData['image'] =  branchHeadData($branch, 'image');

            $insertData['office_name'] =  branchHeadData($branch, 'office_name');
            $insertData['office_ceo'] =  branchHeadData($branch, 'office_ceo');
            $insertData['office_number'] =  branchHeadData($branch, 'office_number');
            $insertData['office_phone'] =  branchHeadData($branch, 'office_phone');
            $insertData['office_address'] =  branchHeadData($branch, 'office_address');
            $insertData['network_contact'] =  branchHeadData($branch, 'network_contact');

            $insertData['location1_1'] =  branchHeadData($branch, 'location1_1');
            $insertData['location2_1'] =  branchHeadData($branch, 'location2_1');

            $insertData['contact1'] =  branchHeadData($branch, 'contact1');
            $insertData['contact2'] =  branchHeadData($branch, 'contact2');

            $insertData['office_hour1'] =  branchHeadData($branch, 'office_hour1');
            $insertData['office_hour2'] =  branchHeadData($branch, 'office_hour2');
            $insertData['office_hour3'] =  branchHeadData($branch, 'office_hour3');
            $insertData['office_hour4'] =  branchHeadData($branch, 'office_hour4');

            //echo "<pre>"; print_r($insertData); echo "</pre>";

            DB::table('ppeum_common_data')
                ->where('branch', $branch)
                ->update($insertData);
        }

    }


    public function get_main_visual($branch = null, $device = null)
    {
        $data =
            DB::table('ppeum_event_pop_file')
                ->where("type", "visual")
                ->where("branch", $branch)
                ->where("device", $device)
                ->where("use_yn", "y")
                ->whereRaw("UNIX_TIMESTAMP( CONCAT( startDate, ' ', startTime ) ) <= UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') )")
                ->whereRaw("ifnull(UNIX_TIMESTAMP( CONCAT( endDate, ' ', endTime ) ), UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') ) ) >= UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') )")
                ->orderBy('sort', 'asc')
                ->limit(7)
                ->get();

        return $data;
    }


    public function get_main_tv($branch = null, $device = null)
    {
        //DB::enableQueryLog();
        $data =
            DB::table('ppeum_tv')
                ->where("del_yn", "N")
                ->where("use_yn", "Y")
                ->where("branch", $branch)
                ->whereRaw(
                    "(
                        (UNIX_TIMESTAMP( CONCAT( startDate, ' ', startTime ) ) <= UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') )
                        and ifnull(UNIX_TIMESTAMP( CONCAT( endDate, ' ', endTime ) ), UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') ) ) >= UNIX_TIMESTAMP( DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') )
                        ) or ( alltime = 'Y' )
                    )"
                )
                ->orderBy('sort', 'asc')
                ->limit(12)
                ->get();
        //print_r(DB::getQueryLog());

        return $data;
    }

    public function get_main_space()
    {
        //DB::enableQueryLog();
        $data =
            DB::table('ppeum_branch')
                ->where("delFlag", "N")
                ->where("main_img_yn", "Y")
                ->orderBy('sort', 'asc')
                ->get();
        //print_r(DB::getQueryLog());

        return $data;
    }
}
?>
