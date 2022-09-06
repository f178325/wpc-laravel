<?php

namespace App\Http\Controllers;

use App\Models\Hosts;
use Exception;
use Illuminate\Http\Request;
use xmlapi;

class HostController extends Controller
{
    public function getHosts()
    {
        $hosts = Hosts::all();
        return view('servers', compact('hosts'));
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
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }

    public function getSubdomains(Request $request)
    {
        try {
            $host = Hosts::find($request['id']);
            $cpanel = new xmlapi($host['name']);
            $cpanel->password_auth($host['username'], $host['password']);
            $cpanel->set_output('json');
            $cpanel->set_port(2083);
            $response = $cpanel->api2_query($host['username'], 'SubDomain', 'listsubdomains');
            $response = json_decode($response);
            $optionHtml = '<option value="public_html">' . $host['name'] . '</option>';
            if (isset($response->cpanelresult->error) && !empty($response->cpanelresult->error)) {
                return json_encode([
                    'error' => true,
                    'msg' => $response->cpanelresult->error
                ]);
            }
            if (count($response->cpanelresult->data) > 1) {
                foreach ($response->cpanelresult->data as $v) {
                    $optionHtml .= '<option value="' . $v->basedir . '">' . $v->domainkey . '</option>';
                }
            } elseif (!empty($response->cpanelresult->data->domainkey)) {
                $optionHtml .= "<option value='" . $response->cpanelresult->data->basedir . "'>" . $response->cpanelresult->data->domainkey . "</option>";
            }
            return json_encode([
                'error' => false,
                'html' => $optionHtml
            ]);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }
}
