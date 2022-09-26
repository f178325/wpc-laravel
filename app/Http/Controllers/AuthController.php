<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JsonException;

class AuthController extends Controller
{
    public function getLogin()
    {
        if (auth()->check()) {
            return redirect()->route('getDashboard');
        }
        return view('auth.login');
    }

    /**
     * @throws JsonException
     */
    public function postLogin(Request $request)
    {
        $user = User::where('username', $request['username'])->first();
        if (Hash::check($request['password'], $user['password'])) {
            auth()->login($user);
            return json_encode([
                'error' => false,
                'msg' => 'Login successful',
            ]);
        }
        return json_encode([
            'error' => true,
            'msg' => 'Invalid Username/Password',
        ]);
    }

    public function postLogout()
    {
        auth()->logout();
        return redirect()->route('getLogin');
    }

    public function convert()
    {
        try {
            $newFile = storage_path('app/public/files/numbers.xlsx');
            $spreadsheet = loadSheet($newFile, 'numbers');
            $excelData = $spreadsheet->getActiveSheet()->toArray();
            $excelSize = count($excelData);
            $domainArr = [];
            for ($i = 1; $i < $excelSize; $i++) {
                $domain = strtolower($excelData[$i][13]);
                $state = $excelData[$i][6];
                if (isset($domainArr[$domain])) {
                    if ($domainArr[$domain]['state'] != $state) {
                        $domainArr[$domain]['count']++;
                    }
                } else {
                    $domainArr[$domain] = [
                        'state' => $state,
                        'count' => 1
                    ];
                }
            }
            $finalArr = [];
            foreach ($domainArr as $k => $v) {
                if ($v['count'] > 1)
                    $finalArr[] = [
                        'domain' => $k,
                        'count' => $v['count']
                    ];
            }
            file_put_contents(storage_path('app/public/files/filter.json'), json_encode($finalArr));
            dd($finalArr[0]);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
