<?php
function uploadFile($file, $filePath = 'files'): string
{
    try {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $filePath, $fileName);
        $loadPath = storage_path('app/public/' . $filePath) . '/' . $fileName;
        $response = [
            'error' => false,
            'loadPath' => $loadPath,
            'fileName' => $fileName,
        ];
        return json_encode($response);
    } catch (Exception $e) {
        return json_encode([
            'error' => true,
            'msg' => $e->getMessage()
        ]);
    }
}

function loadSheet($file, $sheet)
{
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($file);
    $worksheetList = $spreadsheet->getSheetNames();
    $format = false;
    $sheetIndex = 0;
    foreach ($worksheetList as $k => $v) {
        if (strtolower($v) == $sheet) {
            $format = true;
            $sheetIndex = $k;
            break;
        }
    }
    if ($format) {
        $spreadsheet->setActiveSheetIndex($sheetIndex);
        return $spreadsheet;
    }
    die(json_encode([
        'error' => true,
        'msg' => 'Wrong excel format'
    ]));
}

function getResponse($response)
{
    $response = json_decode($response);
    if (empty($response)) {
        return json_encode([
            'error' => true,
            'res' => 'Error'
        ]);
    }
    if (isset($response->cpanelresult->error) && !empty($response->cpanelresult->error)) {
        return json_encode([
            'error' => true,
            'res' => 'Error: ' . $response->cpanelresult->error
        ]);
    }
    if (isset($response->cpanelresult->data[0]->result) && !$response->cpanelresult->data[0]->result) {
        return json_encode([
            'error' => true,
            'res' => 'Error: ' . $response->cpanelresult->data[0]->reason
        ]);
    }
    return json_encode([
        'error' => false
    ]);
}
