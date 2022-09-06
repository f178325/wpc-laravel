<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{

    public function getTableHtml($format, $data, $size)
    {
        return $this->$format($data, $size);
    }

    public function emailTable($excelData, $excelSize)
    {
        $resHtml = '';
        for ($i = 1; $i < $excelSize; $i++) {
            $resHtml .= $this->getRow($args);
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

    public function terminateTable($excelData, $excelSize)
    {
        $resHtml = '';
        for ($i = 1; $i < $excelSize; $i++) {
            $server = trim($excelData[$i][0]);
            $username = trim($excelData[$i][1]);
            $password = trim($excelData[$i][2]);
            $account = trim($excelData[$i][3]);
            if (!empty($server) && !empty($username) && !empty($password) && empty(!$account)) {
                $resHtml .= '<tr id="' . $i . '">';
                $resHtml .= '<td>' . $i . '</td>';
                $resHtml .= '<td class="server">' . $server . '</td>';
                $resHtml .= '<td class="username">' . $username . '</td>';
                $resHtml .= '<td class="password">' . $password . '</td>';
                $resHtml .= '<td class="account">' . $account . '</td>';
                $resHtml .= '<td class="status"></td>';
                $resHtml .= '</tr>';
            }
        }
        return $resHtml;
    }

    public function serversTable($excelData, $excelSize)
    {
        $resHtml = '';
        for ($i = 1; $i < $excelSize; $i++) {
            $domain = trim($excelData[$i][0]);
            $username = trim($excelData[$i][1]);
            $password = trim($excelData[$i][2]);
            $type = trim($excelData[$i][3]);
            if (!empty($domain) && !empty($username) && !empty($password) && empty(!$type)) {
                $resHtml .= '<tr id="' . $i . '">';
                $resHtml .= '<td>' . $i . '</td>';
                $resHtml .= '<td class="domain">' . $domain . '</td>';
                $resHtml .= '<td class="username">' . $username . '</td>';
                $resHtml .= '<td class="password">' . $password . '</td>';
                $resHtml .= '<td class="type">' . $type . '</td>';
                $resHtml .= '<td class="status"></td>';
                $resHtml .= '</tr>';
            }
        }
        return $resHtml;
    }
}
