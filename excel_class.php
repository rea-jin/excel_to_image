<?php

require_once 'vendor\\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XReader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
// use PhpOffice\PhpSpreadsheet\Writer\PNG; // これはないっぽい
// use PhpOffice\PhpSpreadsheet\Writer\Pdf; // 抽象クラス
// use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf; // 別ライブラリ必要
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf; 
// use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use Mpdf\Mpdf;
use tcpdf\TCPDF;

// スプレッドシート作成
// すでにあるテンプレートを使う

/**
 * excelブックを読み込む
 */
class readExcel
{
    /**
     * Excelファイルを読み込みコピーしてシートを返す
     */
    public function readExcelFile() {
        // Excelファイルを読み込む
        $filePath = __DIR__.'/template/template.xlsx';
        $spreadsheet = IOFactory::load($filePath);
        
        // Excelのデフォルトの文字コードを設定
        // \PhpOffice\PhpSpreadsheet\Shared\StringHelper::getUnicodeEncodeOrder('UTF-8');
        // $spreadsheet->getProperties()->setEncoding('UTF-8');

        // Excelファイルのコピーを作成
        $spreadsheetCopy = $spreadsheet->copy();
        
        // 読み込んだExcelファイルのオブジェクトを返す
        return $spreadsheetCopy;
    }

    static $flg;
    // ファイルを読み込んでシートを返す
    public function readExcelSheet()
    {
        // $filePath = '../../../../FTP/template.xlsx';
        $filePath = __DIR__.'/template/template.xlsx';
        $reader = new XReader();
        $spreadsheet = $reader->load($filePath);
        $date = date('Ymd-Hi');
        $worksheet = $spreadsheet->getSheetByName($date);

        // ワークシートが存在しない場合は、新しいワークシートを作成する
        if (!$worksheet) {
            $worksheet = $spreadsheet->createSheet();
            $worksheet->setTitle($date);
        }

        return $worksheet;
    }

    // ふぃいるを複製してファイルオブジェクトを返す
    public function getCopyExcelBook($jdt)
    {
        $filePath = __DIR__.'/template/template.xlsx';

        return $spreadsheet;
    }

    public function setHTLROW($sheet, $pmscd)
    {
        $hitRow = ''; // どの行にデータを入れるか
        // A列の値を取得
        for ($row = 5; $row <= 40; $row++) {
            $celVal = $sheet->getCell('A' . $row)->getValue();
            if ($celVal ==  $pmscd) {
                $hitRow = $row;
                break;
            }
        }
        // $columnAValues = $sheet->getColumnValues('A5:A33');
        // // A列の値と比較する

        // foreach ($columnAValues as $key => $value) {
        //     if ($value ==  $pmscd) {
        //         $row = $key;
        //     }
        // }
        return $hitRow;
    }
}


// --------------------------------------------------------------------------
// GDライブラリを使って画像を作成するための関数
function renderSpreadsheetToImage($spreadsheet)
{
    // 画像の幅と高さを設定します
    // $width = 800;
    // $height = 600;
    // // 新しい画像を作成します
    // $image = imagecreatetruecolor($width, $height);
    // // 背景色を設定します
    // $backgroundColor = imagecolorallocate($image, 255, 255, 255);
    // imagefill($image, 0, 0, $backgroundColor);
    // // セルの内容を画像に描画します
    // $renderer = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
    // $renderer->setWorksheet($spreadsheet->getActiveSheet());
    // $renderer->setImageResource($image);
    // // $renderer->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_PNG);
    // // $renderer->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::QUALITY_HIGH);
    // $renderer->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_PNG);
    // // $renderer->setQuality(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::QUALITY_HIGH);
    // $renderer->setCoordinates('A1'); // 表の開始位置を設定します
    // $renderer->setWidthAndHeight($width, $height);
    // $renderer->render();
    // 画像を返します
    // 画像形式を指定してWriterを取得
    // $writer = new Pdf($spreadsheet);// 抽象クラス
    $m_writer = new Mpdf($spreadsheet);
    // 画像の出力先
    $date = date('YmdHi');
    $pdfPath = "./pdf/{$date}_output.pdf";
    $m_pdfPath = "./pdf/{$date}_output_mpdf.pdf";
    // PDFファイルに書き出す
    $m_writer->save($m_pdfPath);
    // $pdfData = $m_writer->render();
    // file_put_contents($m_pdfPath, $pdfData);

    // 画像ファイルのパスを返す
    return ['mpdf'=>$m_pdfPath];
    // return $image;
}

/**
 * 日本語文字化けが全然治せない。
 *  HTMLをPDFに変換することができます。Mpdfは、CSSのサポートが比較的豊富であり、多くのプロジェクトで使用されています。
 */
function changeMpdf($spreadsheet){
    // $options = new \Mpdf\Mpdf([
    //     'default_font' => 'IPAGothic', // 使用するフォントを指定
    //     'mode' => 'utf-8', // UTF-8モードを設定
    // ]);
    // $options = [
    //     'mode' => 'utf-8',
    //     'default_font' => 'ipagp.ttf', // 使用するフォント
    //     // その他のMpdfオプションをここに追加
    // ];
    // Mpdfのオプションを設定
$mpdfOptions = [
    'useAdobeCJK' => true, // Adobe CJKフォントを使用する
    // その他のMpdfオプションをここに追加
    'mode' => 'utf-8',
    'default_font' => 'ipagp.ttf', // 使用するフォント
];

// Mpdfインスタンスを作成
$mpdf = new Mpdf($mpdfOptions);
    // $mpdf->autoScriptToLang = true;
    // $mpdf->autoLangToFont = true;
    
    // $m_writer = new Mpdf($spreadsheet,$options);
    // $m_writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet, $mpdf);
    $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
    $writer->SetFont('kozminproregular', '', 12);
    // $m_writer->SetCharset('UTF-8');
    // $m_writer->autoScriptToLang = true;
    // $m_writer->autoLangToFont = true;
    // 画像の出力先
    $date = date('YmdHi');
    $m_pdfPath = "./pdf/{$date}_output_mpdf.pdf";
    // PDFファイルに書き出す
    $writer->save($m_pdfPath);
    // $pdfData = $m_writer->render();
    // file_put_contents($m_pdfPath, $pdfData);
    // $m_writer->setOutputEncoding('UTF-8');
    // 画像ファイルのパスを返す
    // $m_writer->setOptions($options);
    return ['mpdf'=>$m_pdfPath];
}

/**
 * HTMLをPDFに変換します。DompdfはCSSのサポートがMpdfよりも制限されていますが、軽量でシンプルな使用が可能です。
 */
function changeDompdf($spreadsheet){
    $d_writer = new Dompdf($spreadsheet);
    // 画像の出力先
    $date = date('YmdHi');
    $d_pdfPath = "./pdf/{$date}_output_dompdf.pdf";
    // PDFファイルに書き出す
    $d_writer->save($d_pdfPath);

    // 画像ファイルのパスを返す
    return ['dompdf'=>$d_pdfPath];
}

/**
 * より低レベルなアプローチを取っています。HTMLからPDFに変換する機能はありませんが、テキストや図形を直接PDFに追加することができます。
 * 比較的高速でパフォーマンスが優れていますが、使い方は少し複雑です。
 * いろいろやったが日本語フォントを指定するだけでよかった・
 * https://blog.e2info.co.jp/2019/02/17/phpspreadsheet%E3%81%A8tcpdf%E3%81%A7excel%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB%E3%81%8B%E3%82%89%E6%97%A5%E6%9C%AC%E8%AA%9Epdf%E3%82%92%E4%BD%9C%E6%88%90%E3%81%97%E3%81%A6%E3%81%BF%E3%82%8B/
 * 
 */
function changeTcpdf($spreadsheet){
    // Writer/Pdfクラスを使用してTcpdfを指定してPDFファイルに書き出す
$writer = IOFactory::createWriter($spreadsheet, 'Tcpdf');
// TCPDFインスタンスを取得
// $pdf = $writer->getPhpSpreadsheetPdf();
// TCPDFのフォントを設定
// $writer->SetAutoPageBreak(true, 0); // 自動改ページを有効にする
$writer->SetFont('kozminproregular', '', 12); // 日本語フォントを設定

// // TCPDFオプションを設定
// $tcpdfOptions = [
//     'useAdobeCJK' => true, // Adobe CJKフォントを使用する
//     // その他のTCPDFオプションをここに追加
//     // 例：'default_font' => 'cid0jp',
// ];

// // TCPDFインスタンスを作成
// $tcpdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// $tcpdf->setOptions($tcpdfOptions); // TCPDFオプションを設定
//     // $t_writer = new Tcpdf($spreadsheet);
//     // TCPDFインスタンスを作成
//     $t_writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf($spreadsheet,$tcpdf);
    // TCPDFのオプションを設定
    // $t_writer->setPdfConfig([
    //     'useAdobeCJK' => true, // Adobe CJKフォントを使用する
    //     'default_font' => 'cid0jp', // 使用するフォントを設定（例：cid0jpはゴシック体）
    //     // その他のTCPDFオプションをここに追加
    // ]);
    // 画像の出力先
    $date = date('YmdHi');
    $t_pdfPath = __DIR__ . "/pdf/{$date}_output_tcpdf.pdf";
    // PDFファイルに書き出す
    $writer->save($t_pdfPath);
    // $t_writer->setOutputEncoding('UTF-8');
    // 画像ファイルのパスを返す
    return ['pdf_path'=>$t_pdfPath];
}

function setDataExcel($request_data){

// excelに書き出すデータを取得する
// 特にこのままでいいが、数えるか。
$hotel_data = $request_data['hotels'];
$cost_1 = $request_data['cost_1'];
$cost_2 = $request_data['cost_2'];

if(count($hotel_data) === 0){
    $response = LOG::error($dir, 400, "NO_DATA", "ホテルデータがありません");
    echo $response;
}
if(count($cost_1) === 0){
    $response = LOG::error($dir, 400, "NO_DATA", "コスト1データがありません");
    echo $response;
}
if(count($cost_2) === 0){
    $response = LOG::error($dir, 400, "NO_DATA", "コスト2データがありません");
    echo $response;
}
if(count($hotel_data) != count($cost_1) || count($cost_1) != count($cost_2)){
    $response = LOG::error($dir, 400, "NO_DATA", "データ数が一致しません");
    echo $response;
}

// エクセルを読み込む
$read_class = new readExcel();
$spreadSheet = $read_class->readExcelFile(); // コピーしたスプレッドシートを返す
// $newSheet = $read_class->readExcel();
// $spreadSheet->setCodeSheetEncoding('UTF-8'); 
$workSheet = $spreadSheet->getActiveSheet(); // シートを取得

// エクセルに転写、エクセルを保存
$month_1 = explode('-',$request_data['date1'])[1];
$month_2 = explode('-',$request_data['date2'])[1];
$day_1 = explode('-',$request_data['date1'])[2];
$day_2 = explode('-',$request_data['date2'])[2];
$date_1 = $month_1.'月'.$day_1.'日';
$date_2 = $month_2.'月'.$day_2.'日';

// title
$tilte = "{$date_1}日と{$date_2}ではこんなに違う！";
$workSheet->setCellValue('B5', $tilte);

// diff cost
$diff_cost = $request_data['diff_cost'];
$sub_title = "価格差：{$diff_cost}円";
$workSheet->setCellValue('B8', $sub_title);

// date1
$workSheet->setCellValue('B10',$date_1);

// date2
$workSheet->setCellValue('B26',$date_2);

// hotel_data
$cell = 11;
$startAddress = 'B';
$startCol = Coordinate::columnIndexFromString($startAddress);
for($i=0;$i<count($hotel_data);$i++){
    $cellAddress = Coordinate::stringFromColumnIndex($startCol) . ($cell+2*$i);
    $workSheet->setCellValue($cellAddress, $hotel_data[$i]['name']); 
}
$cell = 27;
for($i=0;$i<count($hotel_data);$i++){
    $cellAddress = Coordinate::stringFromColumnIndex($startCol) . ($cell+2*$i);
    $workSheet->setCellValue($cellAddress, $hotel_data[$i]['name']); 
}

// cost_1
$cell = 11;
$startAddress='E';
$startCol = Coordinate::columnIndexFromString($startAddress);
for($i=0;$i<count($cost_1);$i++){
    $cellAddress = Coordinate::stringFromColumnIndex($startCol) . ($cell+2*$i);
    $workSheet->setCellValue($cellAddress, $cost_1[$i]['value']); 
}
// cost_2
$cell = 27;
for($i=0;$i<count($cost_2);$i++){
    $cellAddress = Coordinate::stringFromColumnIndex($startCol) . ($cell+2*$i);
    $workSheet->setCellValue($cellAddress, $cost_2[$i]['value']); 
}

// コピーしたExcelファイルを保存
$today = date('YmdH');
$new_file_path = __DIR__."/op_excel/{$today}_modified_file.xlsx";
// $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
$writer = new XWriter($spreadSheet);
$writer->save($new_file_path);

// エクセルを読み込み、画像を生成
// $image = renderSpreadsheetToImage($spreadSheet);
// $m_pdf_path = changeMpdf($spreadSheet);
// $dom_pdf_path = changeDompdf($spreadSheet);// セルの線が出る
$tc_pdf_path = changeTcpdf($spreadSheet);
// 画像を出力、保存
// imagepng($image, 'spreadsheet_image.png');
// imagedestroy($image);
return $tc_pdf_path;
}