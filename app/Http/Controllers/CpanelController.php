<?php

namespace App\Http\Controllers;

use App\Models\Hosts;
use App\Models\Servers;
use App\Models\Tables;
use Exception;
use Illuminate\Http\Request;
use xmlapi;

class CpanelController extends Controller
{
    public function getEmails()
    {
        return view('bulk-emails');
    }

    public function postEmails(Request $request)
    {
        try {
            $host = Hosts::where('name', $request['domain'])->first();
            $cpanel = new xmlapi($host['name']);
            $cpanel->password_auth($host['username'], $host['password']);
            $cpanel->set_output('json');
            $cpanel->set_port(2083);
            $response = $cpanel->api2_query($host['username'], "Email", "addpop", array(
                "domain" => $host['name'],
                "email" => $request['username'],
                "password" => $request['password'],
                "quota" => '50'));
            return getResponse($response);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }

    public function getEmailF()
    {
        return view('bulk-email-forwarder');
    }

    public function postForwarder(Request $request)
    {
        try {
            $host = Hosts::where('name', $request['domain'])->first();
            $cpanel = new xmlapi($host['name']);
            $cpanel->password_auth($host['username'], $host['password']);
            $cpanel->set_output('json');
            $cpanel->set_port(2083);
            $response = $cpanel->api2_query($host['username'], "Email", "addforward", [
                'fwdopt' => 'fwd',
                'domain' => $host['name'],
                'email' => $request['email'],
                'fwdemail' => $request['fwdEmail']
            ]);
            return getResponse($response);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage(),
            ]);
        }
    }

    public function getRepository()
    {
        return view('repository');
    }

    public function getRepoList(Request $request)
    {
        try {
            $server = Servers::first();
//            $conn_id = ftp_connect($server['name']);
//            ftp_login($conn_id, $server['username'], $server['password']);
//            ftp_pasv($conn_id, true);
            if ($request['dir']) {
                $repoPath = '';
            } else {
                $repoPath = asset($server['path']);
            }
            dd($repoPath);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function getTable(Request $request)
    {
        try {
            $file = $request->file('file');
            $response = json_decode(uploadFile($file), true);
            if ($response['error']) {
                return json_encode($response);
            }
            $spreadsheet = loadSheet($response['loadPath'], 'data');
            unlink($response['loadPath']);
            $excelData = $spreadsheet->getActiveSheet()->toArray();
            $excelSize = count($excelData);
            $table = new Tables();
            $resHtml = $table->getTableHtml($request['format'], $excelData, $excelSize);
            return json_encode([
                'error' => false,
                'html' => $resHtml,
            ]);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function getTerminate()
    {
        return view('terminate');
    }

    public function postTerminate(Request $request)
    {
        try {
            $whm = new xmlapi($request['server']);
            $whm->set_output('json');
            $whm->set_port(2087);
            $whm->password_auth($request['username'], $request['password']);
            $response = $whm->removeacct($request['account']);
            return getResponse($response);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }

    public function getReconcile()
    {
        $hosts = Hosts::all();
        return view('reconcile-db', compact('hosts'));
    }

    public function getDatabase(Request $request)
    {
        try {
            $host = Hosts::find($request['id']);
            $cpanel = new xmlapi($host['name']);
            $cpanel->password_auth($host['username'], $host['password']);
            $cpanel->set_output('json');
            $cpanel->set_port(2083);
            $res = $cpanel->api2_query($host['username'], 'MysqlFE', 'listdbsbackup');
            $res = json_decode($res);
            if (isset($res->cpanelresult->error) && !empty($res->cpanelresult->error))
                return null;
            $dbArr = [];
            if (count($res->cpanelresult->data) > 0) {
                foreach ($res->cpanelresult->data as $v) {
                    $dbArr[$v->db] = $v->db;
                }
                $response = $cpanel->api2_query($host['username'], 'SubDomain', 'listsubdomains');
                $response = json_decode($response);
                if (count($response->cpanelresult->data) > 0) {
                    $ftp_conn = ftp_connect($host['name']);
                    ftp_login($ftp_conn, $host['username'], $host['password']);
                    ftp_pasv($ftp_conn, true);
                    foreach ($response->cpanelresult->data as $v) {
                        $status = 0;
                        $localFile = $_SERVER['DOCUMENT_ROOT'] . "/local.php";
                        $connFile = $v->basedir . "/connection.php";
                        if (ftp_get($ftp_conn, $localFile, $connFile)) {
                            $db = file($localFile)[4];
                            $db = explode('DB_NAME', $db)[1];
                            $db = preg_replace('/[\',);\n]+/', "", $db);
                            if (array_search($db, $dbArr)) {
                                $status = 1;
                            }
                            $dbArr[$db] = [
                                'domain' => $v->subdomain,
                                'status' => $status
                            ];
                        }
                    }
                }
            }
            $resHtml = '';
            foreach ($dbArr as $k => $v) {
                $status = isset($v['domain']);
                $resHtml .= '<tr id="' . (($status) ? $v['domain'] : $k) . '" class="' . (($status) ? 'bg-soft-success' : 'bg-soft-danger') . '">';
                $resHtml .= '<td class="domain"><div class="form-check"><input class="form-check-input fs-15 checkBox" type="checkbox" value="' . (($status) ? $v['domain'] : $k) . '" name="domain" ' . (($status) ? 'disabled' : '') . '></div></td>';
                $resHtml .= '<td>' . $k . '</td>';
                $resHtml .= '<td>' . (($status) ? $v['domain'] : '') . '</td>';
                $resHtml .= '<td class="status"></td>';
                $resHtml .= '</tr>';
            }
            return json_encode([
                'error' => false,
                'html' => $resHtml
            ]);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }

    public function postReconcile(Request $request)
    {
        try {
            $host = Hosts::find($request['id']);
            $cpanel = new xmlapi($host['name']);
            $cpanel->password_auth($host['username'], $host['password']);
            $cpanel->set_output('json');
            $cpanel->set_port(2083);
            $response = $cpanel->api2_query($host['username'], 'MysqlFE', 'listusersindb', array(
                'db' => $request['db']
            ));
            $response = json_decode($response);
            if (isset($response->cpanelresult->data) && count($response->cpanelresult->data) > 0) {
                foreach ($response->cpanelresult->data as $v) {
                    $cpanel->api2_query($host['username'], 'MysqlFE', 'deletedbuser', array(
                        'dbuser' => $v->user
                    ));
                }
            }
            $response = $cpanel->api2_query($host['username'], 'MysqlFE', 'deletedb', array(
                'db' => $request['db']
            ));
            return getResponse($response);
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'res' => $e->getMessage()
            ]);
        }
    }

    public function getBackup()
    {
        $hosts = Hosts::all();
        return view('domain-backup', compact('hosts'));
    }
}
