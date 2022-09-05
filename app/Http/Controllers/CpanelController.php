<?php

namespace App\Http\Controllers;

use App\Models\Hosts;
use App\Models\Servers;
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
                'msg' => $e->getMessage()
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
                'msg' => $e->getMessage(),
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
            $resHtml = $this->callAction($request['format'], [$excelData, $excelSize]);
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

    public function emailTable($excelData, $excelSize)
    {
        $resHtml = '';
        for ($i = 1; $i < $excelSize; $i++) {
            $root = trim($excelData[$i][0]);
            $subdomain = trim($excelData[$i][1]);
            $username = trim($excelData[$i][2]);
            $password = trim($excelData[$i][3]);
            if (!empty($root) && !empty($username) && !empty($password)) {
                $host = Hosts::where('name', $root)->first();
                if ($host) {
                    $resHtml .= '<tr id="' . $i . '">';
                } else {
                    $resHtml .= '<tr id="' . $i . '" class="bg-soft-danger" disabled>';
                }
                $resHtml .= '<td>' . $i . '</td>';
                $resHtml .= '<td class="domain">' . $root . '</td>';
                $resHtml .= '<td class="subdomain">' . $subdomain . '</td>';
                $resHtml .= '<td class="username">' . $username . '</td>';
                $resHtml .= '<td class="password">' . $password . '</td>';
                if ($host) {
                    $resHtml .= '<td class="status"></td>';
                } else {
                    $resHtml .= '<td class="status"><span class="badge bg-danger">No host found</span></td>';
                }
                $resHtml .= '</tr>';
            }
        }
        return $resHtml;
    }

    public function emailFTable($excelData, $excelSize)
    {
        $resHtml = '';
        for ($i = 1; $i < $excelSize; $i++) {
            $root = trim($excelData[$i][0]);
            $subdomain = trim($excelData[$i][1]);
            $email = trim($excelData[$i][2]);
            $fwdEmail = trim($excelData[$i][3]);
            if (!empty($root) && !empty($email) && !empty($fwdEmail)) {
                $host = Hosts::where('name', $root)->first();
                if ($host) {
                    $resHtml .= '<tr id="' . $i . '">';
                } else {
                    $resHtml .= '<tr id="' . $i . '" class="bg-soft-danger" disabled>';
                }
                $resHtml .= '<td>' . $i . '</td>';
                $resHtml .= '<td class="domain">' . $root . '</td>';
                $resHtml .= '<td class="subdomain">' . $subdomain . '</td>';
                $resHtml .= '<td class="email">' . $email . '</td>';
                $resHtml .= '<td class="fwdEmail">' . $fwdEmail . '</td>';
                if ($host) {
                    $resHtml .= '<td class="status"></td>';
                } else {
                    $resHtml .= '<td class="status"><span class="badge bg-danger">No host found</span></td>';
                }
                $resHtml .= '</tr>';
            }
        }
        return $resHtml;
    }

//    public function convertJson()
//    {
//        $countFile = storage_path('app/public/files/raw_count.xlsx');
//        $spreadsheet = loadSheet($countFile, 'raw_count');
//        $excelData = $spreadsheet->getActiveSheet()->toArray();
//        $excelSize = count($excelData);
//        $countArr = [];
//        for ($i = 1; $i < $excelSize; $i++) {
//            $domain = strtolower(trim($excelData[$i][0]));
//            $countArr[$domain] = 1;
//        }
//        $file = storage_path('app/public/files/latest.xlsx');
//        $spreadsheet = loadSheet($file, 'db_csv');
//        $excelData = $spreadsheet->getActiveSheet()->toArray();
//        $excelSize = count($excelData);
//        for ($i = 1; $i < $excelSize; $i++) {
//            $domain = strtolower(trim($excelData[$i][13]));
//            if (isset($countArr[$domain])) {
//                $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 1) . ':P' . ($i + 1))->applyFromArray([
//                    'fill' => [
//                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
//                        'color' => array('rgb' => '6C757D')
//                    ],
//                ]);
//            }
//        }
//        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
//        $writer->save(storage_path('app/public/files/updated.xlsx'));
//        dd('DONE');
//    }
}
