<?php

#[\AllowDynamicProperties]
class Extra extends LbModel {

	public $extra_fields;
	public $extra_fields_strings;

	public function __construct(){
		$this->extra_fields = array();
	}

	public function addExtraField($name, $value ){
		$extra = $this->extra_fields;

		$data = array("name"=>$name, "value"=> $value);
		
		$extra[] = $data;
		//array_push($extra, $data);

		$this->extra_fields[] = $extra;

	}

	public function addExtraFieldString($name, $value ){

		$extra = $this->extra_fields_strings;

		$data = array("name"=>$name, "value"=> "\"$value\"");
		$extra[] = $data;
//		array_push($extra, $data);

		$this->extra_fields_strings = $extra;

	}

	public function getExtraFieldNames(){
		$extra_names = array();
		if(is_array($this->extra_fields)){
			foreach($this->extra_fields as $v){
				if(isset($v["name"]) && $v["name"] !== ""){
					$extra_names[] = $v["name"];
				}
			}
		}
		if(is_array($this->extra_fields_strings)){
			foreach($this->extra_fields_strings as $v){
				if(isset($v["name"]) && $v["name"] !== ""){
					$extra_names[] = $v["name"];
				}
			}
		}
		return implode(",", $extra_names);
	}

	public function getExtraFieldValues(){
		$extra_values = array();
		if(is_array($this->extra_fields)){
			foreach($this->extra_fields as $v){
				if(isset($v["value"])){
					$extra_values[] = $v["value"];
				}
			}
		}
		if(is_array($this->extra_fields_strings)){
			foreach($this->extra_fields_strings as $v){
				if(isset($v["value"])){
					$extra_values[] = $v["value"];
				}
			}
		}
		return implode(",", $extra_values);
	}

	public function getExtraFieldforUpdate(){
		$extra_values = array();
		if(is_array($this->extra_fields)){
			foreach($this->extra_fields as $v){
				if(isset($v["name"]) && $v["name"] !== ""){
					$extra_values[] = "$v[name] = $v[value]";
				}
			}
		}
		if(is_array($this->extra_fields_strings)){
			foreach($this->extra_fields_strings as $v){
				if(isset($v["name"]) && $v["name"] !== ""){
					$extra_values[] = "$v[name] = $v[value]";
				}
			}
		}
		return implode(",", $extra_values);
	}


}


?>