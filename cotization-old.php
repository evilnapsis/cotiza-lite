<?php
setlocale(LC_CTYPE, 'es_MX');

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";
include "core/controller/Extra.php";

include "core/app/model/UserData.php";
include "core/app/model/CotizationData.php";
include "core/app/model/CotizationItemData.php";
include "core/app/model/ServiceData.php";
include "core/app/model/SettingData.php";
include "core/app/model/PersonData.php";
include "fpdf/fpdf.php";

session_start();
$symbol = "$";

$logo = SettingData::getByPreffix("general_logo")->val;
$company = SettingData::getByPreffix("general_company")->val;
$address = SettingData::getByPreffix("general_address")->val;
$phone = SettingData::getByPreffix("general_phone")->val;
$rfc = SettingData::getByPreffix("general_rfc")->val;

$sell = CotizationData::getById($_GET["id"]);
$operations = CotizationItemData::getAllBy("cotization_id",$_GET["id"]);


$pdf = new FPDF($orientation='P');



$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20
//$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

//$pdf->SetFont('DejaVu','',8);



//$pdf->setXY(5,0);
$plusforimage =0;
if($logo!=""){
	$src = "storage/configuration/".$logo;
	if(file_exists($src)){
		$pdf->Image($src,12,2,25, 25);		
		$plusforimage=25;
	}
}

$textypos = 35+$plusforimage;
$pdf->setY(2);
$pdf->setX(12);
$pdf->SetFont('Arial','',16);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,strtoupper($company));
//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$textypos+=18;
if($rfc!=""){
$pdf->setX(12);
$pdf->Cell(5,$textypos,"RFC: ".strtoupper($rfc));
}
$textypos+=8;
$pdf->setX(12);
$pdf->Cell(5,$textypos,"DIRECCION: ".strtoupper($address));

$textypos+=8;
if($phone!=""){
$pdf->setX(12);
$pdf->Cell(5,$textypos,"TELEFONO: ".strtoupper($phone));
}
$textypos=$textypos-8-8;
$pdf->setX(102);
$pdf->Cell(5,$textypos,"FOLIO: ".$sell->id);
$pdf->setX(102);
$textypos+=8;
$pdf->Cell(5,$textypos,'FECHA: '.$sell->created_at);
if($sell->person_id!=null){
$textypos+=16;

$pdf->setX(102);
$pdf->Cell(5,$textypos,'DATOS DEL CLIENTE:');

$client=	PersonData::getById($sell->person_id);
$textypos+=8;
$pdf->setX(102);
$pdf->Cell(5,$textypos,"RFC: ".strtoupper($client->rfc));

$textypos+=8;
$pdf->setX(102);
$pdf->Cell(5,$textypos,"NOMBRE: ".strtoupper($client->name.' '.$client->lastname));
$textypos+=8;
$pdf->setX(102);
$pdf->Cell(5,$textypos,"DIRECCION: ".strtoupper($client->address));
$textypos+=8;
$pdf->setX(102);
$pdf->Cell(5,$textypos,"TELEFONO: ".strtoupper($client->phone));

}

$textypos+=16;
$pdf->setX(15);
$pdf->setX(15);
$pdf->setY(80);

        $pdf->Cell(20,7,"CANT",1,0,'C');
        $pdf->Cell(80,7,"PRODUCTO / SERVICIO",1,0,'C');
        $pdf->Cell(40,7,"P.U",1,0,'C');
        $pdf->Cell(40,7,"TOTAL",1,0,'C');
$pdf->Ln();
$total =0;
$off = $textypos+8;
$pdf->setY(87);
$line = 87;
foreach($operations as $op){
	$line+=8;
$product = ServiceData::getById($op->service_id);
$pdf->setX(10);
        $pdf->Cell(20,6,$op->q,'LR');
        $pdf->Cell(80,6,$product->name,'LR');
        $pdf->Cell(40,6,"$ ".number_format($product->amount,2,".",","),'LR',0,'R');
        $pdf->Cell(40,6,"$ ".number_format($product->amount*$op->q,2,".",","),'LR',0,'R');
        $pdf->Ln();









//    ".."  ".number_format($op->q*$product->amount,2,".",","));
$total += $op->q*$product->amount;
$off+=8;
}
$pdf->setX(10);
    $pdf->Cell(180,0,'','T');
$line+=8;
$pdf->setY($line);
$textypos=$off;
//////////////////////////////////////////////
$iva_val = 0;
$pdf->setX(12);
$pdf->Cell(5,6,"SUBTOTAL:  " );
$pdf->setX(80);
$pdf->Cell(5,6,"$symbol ".number_format(($total)/(1 + ($iva_val/100) ),2,".",","),0,0,"R");
$line+=5;
$pdf->setY($line);
$pdf->setX(12);
$pdf->Cell(5,6,"TOTAL: " );
$pdf->setX(80);
$pdf->Cell(5,6,"$symbol ".number_format($total,2,".",","),0,0,"R");


//////////////////////////////////////////
$pdf->setX(12);
//$pdf->Cell(5,$off+5+36,'-------------------------------------------------------------------');
$pdf->setX(12);
//$pdf->Cell(5,$off+5+76,'GRACIAS POR TU COMPRA ');


$pdf->output();
