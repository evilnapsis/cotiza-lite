<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new CreditData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->add();
	Core::alert("Tipo de credito agregada!");
	Core::redir("./?view=credits&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = CreditData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->update();

	Core::alert("Tipo de credito actualizado!");
	Core::redir("./?view=credits&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = CreditData::getById($_GET["id"]);
	$user->del();
	Core::alert("Tipo de credito eliminado!");
	Core::redir("./?view=credits&opt=all");
}



?>