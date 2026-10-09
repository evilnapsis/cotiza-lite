<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new CategoryData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->add();
	Core::alert("Categoria agregada!");
	Core::redir("./?view=categories&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = CategoryData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->update();

	Core::alert("Categoria actualizado!");
	Core::redir("./?view=categories&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = CategoryData::getById($_GET["id"]);
	$user->del();
	Core::alert("Categoria eliminado!");
	Core::redir("./?view=categories&opt=all");
}



?>