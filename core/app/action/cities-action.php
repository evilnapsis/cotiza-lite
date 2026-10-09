<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new CityData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->add();
	Core::alert("Ciudad agregada!");
	Core::redir("./?view=cities&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = CityData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));

	$user->update();

	Core::alert("Ciudad actualizado!");
	Core::redir("./?view=cities&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = CityData::getById($_GET["id"]);
	$user->del();
	Core::alert("Ciudad eliminado!");
	Core::redir("./?view=cities&opt=all");
}



?>