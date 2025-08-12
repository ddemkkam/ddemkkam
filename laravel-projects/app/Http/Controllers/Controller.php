<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function changeConnection($connection, $table)
    {
        if ($connection == 'ppeum_dev') {
            $conn = 'mysql';
        } else if ($connection == 'ppeum09') {
            $conn = 'mysql_ppeum_09';
        } else if ($connection == 'ppeum20') {
            $conn = 'mysql_ppeum_20';
        } else if ($connection == 'ppeum920') {
            $conn = 'mysql_ppeum_920';
        } else if ($connection == 'ppeum30') {
            $conn = 'mysql_ppeum_30';
        } else if ($connection == 'ppeum01') {
            $conn = 'mysql_ppeum_01';
        } else if ($connection == 'ppeum916') {
            $conn = 'mysql_ppeum_916';
        } else if ($connection == 'ppeum27') {
            $conn = 'mysql_ppeum_27';
        } else if ($connection == 'ppeum37') {
            $conn = 'mysql_ppeum_37';
        } else if ($connection == 'ppeum931') {
            $conn = 'mysql_ppeum_931';
        } else if ($connection == 'ppeumtest') {
            $conn = 'mysql_ppeumtest';
        }

        $table->setConnection($conn);

        return $table;
    }

    public function setLocale($request)
    {
        return $request->input('lo', 'ko');
    }

    public function changeLocaleYoil($country)
    {
        switch ($country) {
            case 'en':
                $result = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                break;
            case 'cn':
                $result = ['周日','周一','周二','周三','周四','周五','周六'];
                break;
            case 'jp':
                $result = ['日','月','火','水','木','金','土'];
                break;
            case 'ru':
                $result = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];
                break;
            case 'th':
                $result = ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'];
                break;
            case 'vi':
                $result = ['','','','','','',''];
                break;
            case 'tw':
                $result = ['週日','週一','週二','週三','週四','週五','週六'];
                break;
            default:
                $result = ['일','월','화','수','목','금','토'];
        }

        return $result;
    }

    public function changeLocaleRemain($country)
    {
        switch ($country) {
            case 'en':
                $result = 'Remaining Treatments';
                break;
            case 'cn':
                $result = '剩餘施術';
                break;
            case 'jp':
                $result = '残りの施術';
                break;
            case 'ru':
                $result = 'Оставшиеся процедуры';
                break;
            case 'th':
                $result = 'บริการที่เหลืออยู่';
                break;
            case 'vi':
                $result = '';
                break;
            case 'tw':
                $result = '剩余施术';
                break;
            default:
                $result = '잔여시술';
        }

        return $result;
    }

    public function changeLocaleEvent($country)
    {
        switch ($country) {
            case 'en':
                $result = 'Promotion';
                break;
            case 'cn':
                $result = '活動';
                break;
            case 'jp':
                $result = 'イベント';
                break;
            case 'ru':
                $result = 'Акции';
                break;
            case 'th':
                $result = 'อีเวนต์';
                break;
            case 'vi':
                $result = '';
                break;
            case 'tw':
                $result = '活动';
                break;
            default:
                $result = '이벤트';
        }

        return $result;
    }

    public function changeLocaleColumn($country): string
    {
        switch ($country) {
            case 'en':
                $result = 'TI.TI_CONTENT_EN_SQ';
                break;
            case 'cn':
                $result = 'TI.TI_CONTENT_CN_SQ';
                break;
            case 'jp':
                $result = 'TI.TI_CONTENT_JP_SQ';
                break;
            case 'ru':
                $result = 'TI.TI_CONTENT_RU_SQ';
                break;
            case 'th':
                $result = 'TI.TI_CONTENT_TH_SQ';
                break;
            case 'vi':
                $result = 'TI.TI_CONTENT_VI_SQ';
                break;
            case 'tw':
                $result = 'TI.TI_CONTENT_TW_SQ';
                break;
            default:
                $result = 'TI.TI_CONTENT_KO_SQ';
        }

        return $result;
    }

    public function getCouponLocaleColumn($country)
    {
        switch ($country) {
            case 'en':
                $result = 'CPC_NAME_EN';
                break;
            case 'cn':
                $result = 'CPC_NAME_CN';
                break;
            case 'jp':
                $result = 'CPC_NAME_JP';
                break;
            case 'ru':
                $result = 'CPC_NAME_RU';
                break;
            case 'th':
                $result = 'CPC_NAME_TH';
                break;
            case 'vi':
                $result = 'CPC_NAME_VI';
                break;
            case 'tw':
                $result = 'CPC_NAME_TW';
                break;
            default:
                $result = 'CPC_NAME_KO';
        }

        return $result;
    }

    public function getTSLocaleColumn($country)
    {
        switch ($country) {
            case 'en':
                $result = 'TS_NM_EN';
                break;
            case 'cn':
                $result = 'TS_NM_CN';
                break;
            case 'jp':
                $result = 'TS_NM_JP';
                break;
            case 'ru':
                $result = 'TS_NM_RU';
                break;
            case 'th':
                $result = 'TS_NM_TH';
                break;
            case 'vi':
                $result = 'TS_NM_VI';
                break;
            case 'tw':
                $result = 'TS_NM_TW';
                break;
            default:
                $result = 'TS_NM_KO';
        }

        return $result;
    }

    public function getCateLocaleColumn($country)
    {
        switch ($country) {
            case 'en':
                $result = 'CB_NM_EN';
                break;
            case 'cn':
                $result = 'CB_NM_CN';
                break;
            case 'jp':
                $result = 'CB_NM_JP';
                break;
            case 'ru':
                $result = 'CB_NM_RU';
                break;
            case 'th':
                $result = 'CB_NM_TH';
                break;
            case 'vi':
                $result = 'CB_NM_VI';
                break;
            case 'tw':
                $result = 'CB_NM_TW';
                break;
            default:
                $result = 'CB_NM';
        }

        return $result;
    }

    public function getTSELocaleColumn($country)
    {
        switch ($country) {
            case 'en':
                $result = 'TSE_SUBJECT_EN';
                break;
            case 'cn':
                $result = 'TSE_SUBJECT_CN';
                break;
            case 'jp':
                $result = 'TSE_SUBJECT_JP';
                break;
            case 'ru':
                $result = 'TSE_SUBJECT_RU';
                break;
            case 'th':
                $result = 'TSE_SUBJECT_TH';
                break;
            case 'vi':
                $result = 'TSE_SUBJECT_VI';
                break;
            case 'tw':
                $result = 'TSE_SUBJECT_TW';
                break;
            default:
                $result = 'TSE_SUBJECT_KO';
        }

        return $result;
    }

    public function getCPLocaleColumn($country)
    {
        switch ($country) {
            case 'en':
                $result = 'CP_NAME_EN';
                break;
            case 'cn':
                $result = 'CP_NAME_CN';
                break;
            case 'jp':
                $result = 'CP_NAME_JP';
                break;
            case 'ru':
                $result = 'CP_NAME_RU';
                break;
            case 'th':
                $result = 'CP_NAME_TH';
                break;
            case 'vi':
                $result = 'CP_NAME_VI';
                break;
            case 'tw':
                $result = 'CP_NAME_TW';
                break;
            default:
                $result = 'CP_NAME_KO';
        }

        return $result;
    }

    public function changeEventName($country): string
    {
        switch ($country) {
            case 'en':
                $result = 'EVENT';
                break;
            case 'cn':
                $result = '事件';
                break;
            case 'jp':
                $result = 'イベント';
                break;
            case 'ru':
                $result = 'событие';
                break;
            case 'th':
            case 'vi':
                $result = 'sự kiện';
                break;
            default:
                $result = '이벤트';
        }

        return $result;
    }

    public function setDate($country)
    {
        switch ($country) {
            case 'en':
            case 'cn':
            case 'jp':
            case 'ru':
            case 'th':
            case 'vi':
                $result = ['sun', '월', '화', '수', '목', '금', '토'];
                break;
            default:
                $result = ['일', '월', '화', '수', '목', '금', '토'];
        }

        return $result;
    }

    public function setTime($country, $time)
    {
        switch ($country) {
            case 'en':
            case 'cn':
            case 'jp':
            case 'ru':
            case 'th':
            case 'vi':
                $result = $time > 12 ? 'PM' : 'AM';
                break;
            default:
                $result = $time > 12 ? '오후' : '오전';
        }

        return $result;
    }
}
