<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new FormaData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->add();
	Core::alert("Forma de pago agregada!");
	Core::redir("./?view=formas&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = FormaData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->update();

	Core::alert("Forma de pago actualizado!");
	Core::redir("./?view=formas&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = FormaData::getById($_GET["id"]);
	$user->del();
	Core::alert("Forma de pago eliminado!");
	Core::redir("./?view=formas&opt=all");
}



?>