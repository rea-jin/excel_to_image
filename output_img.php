<?php
// 関数読み込み
require_once __DIR__ . '/excel_class.php';
require_once __DIR__ . '/common_lib.php';

use Spatie\PdfToImage\Pdf;

//自分自身のファイル名取得 ".PHP" のぞき
$pro_name = basename(__FILE__, ".php");
$dir = dirname(__FILE__) . '/logs/logs_' . $pro_name;
LOG::debug($dir, "start");

// 送信データ取得
$request_json = file_get_contents('php://input');
$request_data = json_decode($request_json, true);
if(json_last_error() !== JSON_ERROR_NONE){
    // JSON形式でない場合
    $response = LOG::error($dir,400, "JSON_ERR", "JSON形式で送信してください");
    echo $response;
}
LOG::debug($dir, $request_data);

$pdf_path_arr = setDataExcel($request_data);
LOG::debug($dir, $pdf_path_arr);

// pdf to img 
$pdf = new Pdf($pdf_path_arr['pdf_path']);

// 画像保存先
$date = date('YmdHis');
$img_name = $date."_output.png";
$img_path = __DIR__ . '\\images\\'.$img_name;
// $pdf->setOutputFormat('png')
// ->saveImage($img_path);

// echo $img_path;

// php8.2はpeclでimagickを使えないので直接実行
// PDFからJPGに変換する例
$pdfFile = $pdf_path_arr['pdf_path'];
$jpgFile = $img_path;

$command = sprintf("C:\imagick\bin\convert.exe -density 300 %s %s", 
    escapeshellarg($pdfFile),
    escapeshellarg($jpgFile)
);

LOG::debug($dir, $command);

exec($command, $output, $returnValue);
if ($returnValue === 0) {
    $msg =  "PDF から JPG への変換が完了しました";
    $result = array(
        'result' => 'success',
        'img_path' => $img_path,
        'msg' => $msg
    );
} else {
    $msg = "エラーが発生しました: " . implode("\n", $output);
    $result = array(
        'result' => 'error',
        'msg' => $msg
    );
}

LOG::debug($dir, $result);
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

