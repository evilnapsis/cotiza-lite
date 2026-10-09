<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_GET)>0){
	$user = new CotizationData();

//	$user->addExtraFieldString("name", htmlentities($_POST["name"]));
	$user->addExtraFieldString("user_id", htmlentities($_SESSION["user_id"]));
	$user->addExtraFieldString("status_id", htmlentities(1));
	$user->addExtraField("created_at", "NOW()");

	$da = $user->add();
//	Core::alert("Categoria agregada!");
	Core::redir("./?view=cotizations&opt=edit&id=".$da[1]);
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){
print_r($_POST);
	$user = CotizationData::getById($_POST["_id"]);
	$user->addExtraField("person_id", htmlentities($_POST["person_id"]!=""?$_POST["person_id"]:"NULL"));
	$user->addExtraFieldString("status_id", htmlentities($_POST["status_id"]));

	$user->update();

	Core::alert("Cotizacion actualizado!");
	Core::redir("./?view=cotizations&opt=edit&id=".$_POST["_id"]);
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="updatenotes"){
if(count($_POST)>0){
print_r($_POST);
	$user = CotizationData::getById($_POST["_id"]);
	$user->addExtraFieldString("forma_id", htmlentities($_POST["forma_id"]));
	$user->addExtraFieldString("notes", htmlentities($_POST["notes"]));

	$user->update();

	Core::alert("Cotizacion actualizado!");
	Core::redir("./?view=cotizations&opt=edit&id=".$_POST["_id"]);
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="additem"){
if(count($_POST)>0){
	$citem = new CotizationItemData();
	$citem->addExtraFieldString("cotization_id", htmlentities($_POST["_id"]));
	$citem->addExtraFieldString("q", htmlentities($_POST["q"]));
	$citem->addExtraFieldString("service_id", htmlentities($_POST["service_id"]));
	$citem->add(); 

	Core::redir("./?view=cotizations&opt=edit&id=".$_POST['_id']);
}
}

else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = CotizationData::getById($_GET["id"]);
	$cotizationitems = CotizationItemData::getAllBy("cotization_id",$user->id);
	foreach($cotizationitems as $citem){ $citem->del(); }
	$user->del();
	Core::alert("Cotizacion eliminado!");
	Core::redir("./?view=cotizations&opt=all");
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="delitem"){
	$user = CotizationItemData::getById($_GET["item_id"]);
	$user->del();
	Core::redir("./?view=cotizations&opt=edit&id=".$_GET['cotization_id']);
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="sendmail"){
	$cotization = CotizationData::getById($_GET["id"]);
	if($cotization->person_id!=null){
		$person = PersonData::getById($cotization->person_id);
		if($person->email!=""){
			//echo "Si";
$to = $person->email;
$headers = "From: myplace@example.com\r\n";
$headers .= "Reply-To: myplace2@example.com\r\n";
$subject = "Cotization #".$_GET["id"];
$message = "<body><h1>Has Recibido una Cotizacion #$_GET[id]</h1>Has recibido una cotizacion puedes ver el resumen en el siguiente enlace <a href='https://masipro.com.mx/cotizaciones/cotization.php?id=$_GET[id]'>Ver Cotizacion #$_GET[id]<a></body>";
if ( mail($to,$subject,$message,$headers) ) {
   Core::alert("Enviado!");
   } else {
   Core::alert("No se ha podido enviar!");
   }
		}else{
			Core::alert("El cliente no tiene asignado email!");
		}

	}else{
		Core::alert("La cotizacion no tiene asinada un cliente!");
	}
Core::redir("./?view=cotizations&opt=edit&id=".$_GET["id"]);


}



?>