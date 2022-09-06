<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getDashboard()
    {
        return view('dashboard');
    }

    public function postDelete(Request $request)
    {
        try {
            $deleteArr = explode(',', $request['deleteId']);
            foreach ($deleteArr as $v) {
                DB::table($request['tableName'])
                    ->where('id', $v)
                    ->delete();
            }
            return json_encode([
                'error' => false,
                'msg' => 'Record deleted successfully',
            ]);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
