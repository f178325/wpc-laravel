<?php

namespace App\Http\Controllers;

use App\Models\Hosts;
use ErrorException;
use Illuminate\Http\Request;
use Mockery\Exception;

class HostController extends Controller
{
    public function getHosts()
    {
        return view('servers');
    }

    public function postHosts(Request $request)
    {
        try {
            $find = array('http://', 'https://', 'ftp://', '//', '/');
            $host = str_replace($find, '', $request['domain']);
            $record = Hosts::where('name', $host)->first();
            if ($record) {
                return json_encode([
                    'error' => true,
                    'msg' => 'Host already exist'
                ]);
            }

            $ftp = ftp_connect($host);
            if (@ftp_login($ftp, $request['username'], $request['password'])) {
                $insert = Hosts::create([
                    'name' => $host,
                    'username' => $request['username'],
                    'password' => $request['password'],
                    'type' => $request['type']
                ]);
                if ($insert) {
                    return json_encode([
                        'error' => false,
                        'msg' => 'Host added successfully'
                    ]);
                } else {
                    return json_encode([
                        'error' => true,
                        'msg' => 'Something went wrong'
                    ]);
                }
            } else {
                return json_encode([
                    'error' => true,
                    'msg' => 'Invalid credentials'
                ]);
            }
        } catch (Exception|ErrorException $e) {
            return json_encode([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
