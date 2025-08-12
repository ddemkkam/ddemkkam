<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Test_Model extends Authenticatable
{
    public function get_test()
    {
        $data = DB::table('CRM2_CALL_ANSWER')->limit(10)->get();

        return $data;
    }
}
?>
