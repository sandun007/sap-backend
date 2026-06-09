<?php

namespace App\Http\Controllers;

use App\Models\InvPara;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general_setting()
    {
        $inv_data = InvPara::query()->first();
        return view('settings.general_setting',compact('inv_data'));
    }
}
