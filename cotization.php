<?php
setlocale(LC_CTYPE, 'es_MX');

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Extra.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/CotizationData.php";
include "core/app/model/CotizationItemData.php";
include "core/app/model/ServiceData.php";
include "core/app/model/SettingData.php";
include "core/app/model/PersonData.php";
include "core/app/model/FormaData.php";
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
$forma = FormaData::getById($sell->forma_id);



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
                $pdf->Image($src,12,2,45, 25);          
                $plusforimage=25;
        }
}

$pdf->SetFont('Arial','B',22);    //Letra Arial, negrita (Bold), tam. 20

/////////////////////////////////////// DATOS FIJOS
$pdf->setY(10);
$pdf->setX(110);
$pdf->Cell(5,0,"COTIZACION");
$pdf->SetFont('Arial','',12);    //Letra Arial, negrita (Bold), tam. 20

$pdf->setY(20);
$pdf->setX(140);
$pdf->Cell(5,0,"FECHA: ");
$pdf->setY(17);
$pdf->setX(160);
$pdf->Cell(30,5,date("d/m/Y",strtotime($sell->created_at)),1);
$pdf->setY(23);
$pdf->setX(126);
$pdf->Cell(5,5,'COTIZACION # ');
$pdf->setY(23);
$pdf->setX(160);
$pdf->Cell(30,5,$sell->id,1);

$pdf->setY(30);
$pdf->setX(126);
$pdf->Cell(65,5,"FORMA DE PAGO",1);
$pdf->setY(35);
$pdf->setX(126);
$pdf->Cell(65,5,strtoupper("$forma->name"),1);

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,0); 
$pdf->setY(45);
$pdf->setX(12);
$pdf->Cell(85, 35, "", 1, 1, 'C');
$pdf->setY(45);
$pdf->setX(12);

//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
$pdf->Cell(85,5,"SALIDA DE MATERIAL",0,1,'C',1); //your cell
$pdf->setY(45);
$pdf->setX(106);
$pdf->Cell(85, 35, "", 1, 1, 'C');
$pdf->setY(45);
$pdf->setX(106);

//$pdf->Cell(85,5,"CLIENTE",1);
$pdf->Cell(85,5,"PROYECTO",0,1,'C',1); //your cell




 $pdf->SetTextColor(0,0,0);
/*
$pdf->setFillColor(0,0,0); 
$pdf->SetTextColor(255,255,255);

$pdf->Cell(0,10,"HOLA MUNDO MUNDANO",0,1,'L',1); //your cell
$pdf->SetTextColor(0,0,0);
*/

//////////////////////////////////////// DATOS FIJOS

$textypos = 35+$plusforimage;
$pdf->setY(2);
$pdf->setX(12);
$pdf->SetFont('Arial','',16);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell(5,$textypos,strtoupper($company));
//$pdf->SetFont('DejaVu','',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$textypos+=18;

$pdf->setY(50);
$pdf->setX(15);
$pdf->Cell(5,5,"Empresa");

$textypos+=8;
$pdf->setY(55);
$pdf->setX(15);
$pdf->Cell(5,5,"Direccion l1");
$pdf->setY(60);
$pdf->setX(15);
$pdf->Cell(5,5,"Colonia");
$pdf->setY(65);
$pdf->setX(15);
$pdf->Cell(5,5,"CP ");
$pdf->setY(70);
$pdf->setX(15);
$pdf->Cell(5,5,"Estado");
$pdf->setY(75);
$pdf->setX(15);
$pdf->Cell(5,5,"Telefono: ");
//////////////////// DAOS DEL CLIENTE / PROYECCTO
$cliente="";
$rfc="";
$direccion="";
$telefono="";
if($sell->person_id){
        $cli = PersonData::getById($sell->person_id);
        $cliente = $cli->name;
        $rfc = $cli->rfc;
        $direccion = $cli->address;
        $telefono= $cli->phone;
}

$pdf->setY(50);
$pdf->setX(110);
$pdf->Cell(5,5,"RFC: $rfc");

$textypos+=8;
$pdf->setY(55);
$pdf->setX(110);
$pdf->Cell(5,5,"CLIENTE: $cliente");
$pdf->setY(60);
$pdf->setX(110);
$pdf->Cell(5,5,"DIRECCION: $address");
$pdf->setY(65);
$pdf->setX(110);
$pdf->Cell(5,5,"TELEFONO: $telefono");

/*

$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,0); 
$pdf->setY(82);
$pdf->setX(12);
//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
$pdf->Cell(179,5,"DESCRIPCION DEL PROYECTO: ".strtoupper($descripcion),0,1,'L',1); //your cel
 $pdf->SetTextColor(0,0,0);


$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
*/
$pdf->setFillColor(0,0,0); 
$textypos+=16;
$pdf->setX(15);
$pdf->setY(90);
$pdf->setX(10);

////////////////////////////////////////////////////////

$pdf->setFillColor(0,0,0); 
$pdf->Cell(20,5,"CANTIDAD",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(30);
$pdf->Cell(120,5,"PRODUCTO",1,1,1,'C');
//$pdf->setY(90);
//$pdf->setX(150);
//$pdf->Cell(20,5,"P.U",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(150);
$pdf->Cell(40,5,"COSTO TOTAL",1,1,1,'C');
$pdf->setY(90);
$pdf->setX(150);

$pdf->Ln();
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR
$total =0;
$off = $textypos+8;
$pdf->setY(95);
$line = 87;
$ypos_static = 95;
foreach($operations as $op){
$line+=8;
$ypos_static +=5;
$product = ServiceData::getById($op->service_id);
$pdf->setX(10);
$pdf->Cell(20,5,$op->q,'LR');
$pdf->Cell(120,5,$product->name,'LR');
//$pdf->Cell(40,5,"$ ".number_format($product->price_out,2,".",","),'LR',0,'R');
$pdf->Cell(40,5,"$ ".number_format($product->amount*$op->q,2,".",","),'LR',0,'R');
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
$ypos_static+=5;
//$iva_val = 0;
//$pdf->setY($ypos_static);
//$pdf->setX(150);
//$pdf->Cell(5,6,"SUBTOTAL:  " );
//$pdf->setY($ypos_static);
//$pdf->setX(187);
//$pdf->Cell(5,6,"$symbol ".number_format(($total)/(1 + ($iva_val/100) ),2,".",","),0,0,"R");
$line+=5;
$pdf->setY($ypos_static);
$pdf->setX(150);
$pdf->Cell(5,6,"TOTAL: " );
$pdf->setY($ypos_static);
$pdf->setX(187);
$pdf->Cell(5,6,"$symbol ".number_format($total,2,".",","),0,0,"R");

//////////////////////////////////////////

$ypos_static+=5;
//$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
//$pdf->setFillColor(0,0,0); 
//$pdf->setY($ypos_static);
//$pdf->setX(12);
//$pdf->Cell(130, 32, "", 1, 1, 'C');
//$pdf->setY($ypos_static);
//$pdf->setX(12);
//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
//$pdf->Cell(130,5,"TERMINOS Y CONDICIONES",0,1,'C',1); //your cell
//$pdf->setY($ypos_static);
//$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Tiempo de Entrega acordado con el cliente");

//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"La vigencia de la presente cotizacion es por 15 dias.");
//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Los costos son en moneda nacional");
//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Los costos no incluyen IVA.");
//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"La informacion contenida en este documento es confidencial.");

//////////////////////////////////////////

//$ypos_static+=10;
//$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
//$pdf->setFillColor(0,0,0); 
//$pdf->setY($ypos_static);
//$pdf->setX(12);
//$pdf->Cell(130, 22, "", 1, 1, 'C');
//$pdf->setY($ypos_static);
//$pdf->setX(12);
//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
//$pdf->Cell(130,5,"NOTAS",0,1,'C',1); //your cell
//$pdf->setY($ypos_static);
//$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Chaleco Rojo en todo proceso de Instalacion.");

//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Equipo de Seguridad Requerido por Planta.");
//$ypos_static+=5;
//$pdf->setY($ypos_static);
//$pdf->setX(15);
//$pdf->Cell(5,5,"Mano de Obra certificada con DC3 para trabajos de Alto Riesgo.");

//////////////////////////////////////////

$ypos_static+=5;
$pdf->SetTextColor(255,255,255); // CAMBIO DE COLOR
$pdf->setFillColor(0,0,0); 
$pdf->setY($ypos_static);
$pdf->setX(12);
$pdf->Cell(130, 22, "", 1, 1, 'C');
$pdf->setY($ypos_static);
$pdf->setX(12);
//$pdf->Cell(85,5,"PROVEEDOR DE SERVICIOS",1);
$pdf->Cell(130,5,"FIRMA Y FECHA DE ENTREGA",0,1,'C',1); //your cell
$pdf->setY($ypos_static);
$pdf->SetTextColor(0,0,0); // CAMBIO DE COLOR

$ypos_static+=5;

$ypos_static+=5;
$ypos_static+=5;
$pdf->setY($ypos_static);
$pdf->setX(15);
$pdf->Cell(5,5,"Se recibe herramienta/productos completa.");

$pdf->setX(12);
//$pdf->Cell(5,$off+5+36,'-------------------------------------------------------------------');
$pdf->setX(12);
//$pdf->Cell(5,$off+5+76,'GRACIAS POR TU COMPRA ');


$pdf->output();
