<?php
if(isset($_GET["opt"]) && $_GET["opt"]=="add"){
if(count($_POST)>0){



	$user = new PersonData();

	$user->addExtraFieldString("name", htmlentities($_POST["name"]));
	$user->addExtraFieldString("lastname", htmlentities($_POST["lastname"]));
	$user->addExtraFieldString("email", htmlentities($_POST["email"]));
	$user->addExtraFieldString("phone", htmlentities($_POST["phone"]));
	$user->addExtraFieldString("address", htmlentities($_POST["address"]));
	$user->addExtraFieldString("contact", htmlentities($_POST["contact"]));
	$user->addExtraFieldString("code", htmlentities($_POST["code"]));
	$user->addExtraFieldString("rfc", htmlentities($_POST["rfc"]));
	$user->addExtraFieldString("city_id", htmlentities($_POST["city_id"]));
	$user->addExtraFieldString("credit_id", htmlentities($_POST["credit_id"]));
	$user->addExtraFieldString("comments", htmlentities($_POST["comments"]));

	$user->add();
	Core::alert("Contacto agregada!");
	Core::redir("./?view=persons&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="update"){
if(count($_POST)>0){

	$user = PersonData::getById($_POST["_id"]);
	$user->addExtraFieldString("name", htmlentities($_POST["name"]));
	$user->addExtraFieldString("lastname", htmlentities($_POST["lastname"]));
	$user->addExtraFieldString("email", htmlentities($_POST["email"]));
	$user->addExtraFieldString("phone", htmlentities($_POST["phone"]));
	$user->addExtraFieldString("address", htmlentities($_POST["address"]));
	$user->addExtraFieldString("contact", htmlentities($_POST["contact"]));
	$user->addExtraFieldString("code", htmlentities($_POST["code"]));
	$user->addExtraFieldString("rfc", htmlentities($_POST["rfc"]));
	$user->addExtraFieldString("city_id", htmlentities($_POST["city_id"]));
	$user->addExtraFieldString("credit_id", htmlentities($_POST["credit_id"]));
	$user->addExtraFieldString("comments", htmlentities($_POST["comments"]));

	$user->update();

	Core::alert("Contacto actualizado!");
	Core::redir("./?view=persons&opt=all");
}
}
else if(isset($_GET["opt"]) && $_GET["opt"]=="del"){
	$user = PersonData::getById($_GET["id"]);
	$user->del();
	Core::alert("Contacto eliminado!");
	Core::redir("./?view=persons&opt=all");
}



?>