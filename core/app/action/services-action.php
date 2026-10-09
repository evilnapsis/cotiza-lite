<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new ServiceData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));
	$user->addExtraFieldString("code", htmlentities($_POST["code"]));
	$user->addExtraFieldString("kind", htmlentities($_POST["kind"]));
	$user->addExtraFieldString("amount", htmlentities($_POST["amount"]));
	$service = $_POST["service_id"]!=""?$_POST['service_id']:"NULL";
	$user->addExtraField("service_id", $service);

	$user->add();
	Core::alert("Servicio agregada!");
	Core::redir("./?view=services&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = ServiceData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));
	$user->addExtraFieldString("code", htmlentities($_POST["code"]));
	$user->addExtraFieldString("kind", htmlentities($_POST["kind"]));
	$user->addExtraFieldString("amount", htmlentities($_POST["amount"]));
	$service = $_POST["service_id"]!=""?$_POST['service_id']:"NULL";
	$user->addExtraField("service_id", $service);

	$user->update();

	Core::alert("Servicio actualizado!");
	Core::redir("./?view=services&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = ServiceData::getById($_GET["id"]);
	$user->del();
	Core::alert("Servicio eliminado!");
	Core::redir("./?view=services&opt=all");
}



?>