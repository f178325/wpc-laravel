<?php

namespace App\Http\Controllers;

use App\Models\Hosts;
use cpanelAPI;
use Exception;
use Illuminate\Http\Request;
use xmlapi;

class CpanelController extends Controller
{
    public function getEmails()
    {
        return view('bulk-emails');
    }

    public function getEmailTable(Request $request)
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
            $resHtml = '';
            for ($i = 1; $i < $excelSize; $i++) {
                $root = trim($excelData[$i][0]);
                $subdomain = trim($excelData[$i][1]);
                $username = trim($excelData[$i][2]);
                $password = trim($excelData[$i][3]);
                if (!empty($root) && !empty($username) && !empty($password)) {
                    $resHtml .= '<tr id="' . $i . '">';
                    $resHtml .= '<td>' . $i . '</td>';
                    $resHtml .= '<td class="domain">' . $root . '</td>';
                    $resHtml .= '<td class="subdomain">' . $subdomain . '</td>';
                    $resHtml .= '<td class="username">' . $username . '</td>';
                    $resHtml .= '<td class="password">' . $password . '</td>';
                    $host = Hosts::where('name', $root)->first();
                    $resHtml .= '<td class="status"></td>';
                    $resHtml .= '</tr>';
                }
            }
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

    public function postEmails(Request $request)
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            $host = Hosts::where('name', $request['domain'])->first();
            if ($host) {
                $cpanel = new xmlapi($host['name']);
                $cpanel->password_auth($host['username'], $host['password']);
                $cpanel->set_output('json');
                $cpanel->set_port(2083);
                $response = $cpanel->api2_query($host['username'], "Email", "addpop", array(
                    "domain" => $host['name'],
                    "email" => $request['username'],
                    "password" => $request['password'],
                    "quota" => '50'));
                $response = json_decode($response);
                if (!$response->cpanelresult->data[0]->result) {
                    return json_encode([
                        'error' => true,
                        'res' => 'Error: ' . $response->cpanelresult->data[0]->reason
                    ]);
                }
                return json_encode([
                    'error' => false
                ]);
            } else {
                return json_encode([
                    'error' => true,
                    'res' => 'Error: No host found'
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function convertJson()
    {
        $countFile = storage_path('app/public/files/raw_count.xlsx');
        $spreadsheet = loadSheet($countFile, 'raw_count');
        $excelData = $spreadsheet->getActiveSheet()->toArray();
        $excelSize = count($excelData);
        $countArr = [];
        for ($i = 1; $i < $excelSize; $i++) {
            $domain = strtolower(trim($excelData[$i][0]));
            $countArr[$domain] = 1;
        }
        $file = storage_path('app/public/files/latest.xlsx');
        $spreadsheet = loadSheet($file, 'db_csv');
        $excelData = $spreadsheet->getActiveSheet()->toArray();
        $excelSize = count($excelData);
        for ($i = 1; $i < $excelSize; $i++) {
            $domain = strtolower(trim($excelData[$i][13]));
            if (isset($countArr[$domain])) {
                $spreadsheet->getActiveSheet()->getStyle('A' . ($i + 1) . ':P' . ($i + 1))->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => array('rgb' => '6C757D')
                    ],
                ]);
            }
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/files/updated.xlsx'));
        dd('DONE');
    }
}
