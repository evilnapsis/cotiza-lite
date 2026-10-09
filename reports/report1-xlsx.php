<?php

/** Error reporting */
$debug= true;

if($debug){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}else{
error_reporting(0);

}

include "../core/autoload.php";
include "../core/app/model/ServiceData.php";
include "../core/app/model/StatusData.php";
include "../core/app/model/CotizationData.php";
include "../core/app/model/CotizationItemData.php";

include "../vendor/autoload.php";
session_start();

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
//require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//require __DIR__ . '/../Header.php';

//$spreadsheet = new Spreadsheet();
// Create new PHPExcel object
$objPHPExcel = new Spreadsheet();

//$products = $_SESSION['data_report']; //PersonData::getClients();
$products = CotizationData::getAllByRange($_GET["start"], $_GET['finish']);
// Set document properties
$objPHPExcel->getProperties()->setCreator("Evilnapsis")
							 ->setLastModifiedBy("Evilnapsis")
							 ->setTitle("Reportes")
							 ->setSubject("Reportes Geniales")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(30);



$sheet->getStyle('A5:E5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
//$sheet->getStyle('A5:E5')
  //  ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('A5:E5')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:E5')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:E5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:E5')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:E5')
    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A5:E5')
    ->getFill()->getStartColor()->setARGB('FF00AA55');


$sheet->setCellValue('B2', 'Cotizaciones');

$sheet->setCellValue('A5', 'Id')
->setCellValue('B5', 'Total')
->setCellValue('C5', 'Cliente')
->setCellValue('D5', 'Estado')
->setCellValue('E5', 'Fecha');

$start = 6;
$tags = StatusData::getAll();
foreach($products as $product){
$gat = "";

$items = CotizationItemData::getAllBy("cotization_id", $product->id);

$total = 0;
foreach ($items as $it){
$serv = ServiceData::getById($it->service_id);
$total+=($it->q*$serv->amount);
}


foreach($tags as $tag){
  if($tag->id==$product->status_id){ $gat = $tag->name; }
}

$client = "";
if($product->person_id!=null){ $client = PersonData::getById($product->person_id)->name; }
$sheet->setCellValue('A'.$start, $product->id)
->setCellValue('B'.$start, $total)
->setCellValue('C'.$start, $client)
->setCellValue('D'.$start, $gat)
->setCellValue('E'.$start, $product->created_at);


$sheet->getStyle('A'.$start.':E'.$start.'')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A'.$start.':E'.$start.'')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A'.$start.':E'.$start.'')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A'.$start.':E'.$start.'')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//$sheet->setCellValue('A5', 'Hello World !');
////////////////////////////////////////////////////
  // Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="report1-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//////////////////////////////////////////////////////
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');
