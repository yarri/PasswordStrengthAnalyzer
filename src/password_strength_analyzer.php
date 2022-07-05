<?php
namespace Yarri;

class PasswordStrengthAnalyzer {

	protected $coefficients = [];
	
	function analyze($password){
		$password = new \String4($password);
		$chars = $password->chars(["stringify" => false]);

		// Treatment for older versions of String4
		foreach($chars as $k => $char){
			if(is_object($char)){ break; } // \String4
			$chars[$k] = new \String4($char);
		}

		$unique_chars = [];
		$unique_chars_by_type = [];
		$types_used = [];
		$type_transitions = [];
		$prev_type =  null;

		foreach($chars as $char){
			$type = $this->_classifyCharType($char);
			$unique_chars["$char"] = $char;
			if(!isset($unique_chars_by_type[$type])){
				$unique_chars_by_type[$type] = []; 
			}
			$unique_chars_by_type[$type]["$char"] = $char;
			$types_used[$type] = $type;
			if($prev_type && $type!==$prev_type){
				$type_transitions[] = [$prev_type,$type];
			}

			$prev_type = $type;
		}

		$score = 50.0;

		$coefficients = [];

		//																										 x															base		multiplier	offset	max
		$coefficients["unique_chars"] =				$this->_calcCoef(sizeof($unique_chars),					1.2,		0.3,				-0.3,		4);
		$coefficients["password_length"] =		$this->_calcCoef(sizeof($chars),								1.15,		0.3,				-0.6,		8);
		$coefficients["types_used"] =					$this->_calcCoef(sizeof($types_used)-1,					1.5,		0.3,				0,			4);
		$coefficients["type_transitions"] =		$this->_calcCoef(sizeof($type_transitions)-1,		1.3,		0.3,				0,			4);

		$this->coefficients = $coefficients;

		foreach($coefficients as $_type => $coefficient){
			$score = $score * $coefficient;
		}

		$score = min(100,round($score));

		return $score;
	}

	function getCoefficients(){
		return $this->coefficients;
	}

	protected function _calcCoef($x,$base,$multiplier,$offset = 0,$max = 2){
		$coefficient = (($multiplier * pow($base,$x)) + $offset);
		$coefficient = max($coefficient, 0);
		$coefficient = min($coefficient, $max);
		return round($coefficient,4);
	}

	protected function _classifyCharType($char){
		foreach([
			"[a-z]" => "lower_case",
			"[A-Z]" => "upper_case",
			"[0-9]" => "number",
			'\s' => "white",
		] as $pattern => $type){
			if($char->match("/^$pattern+$/")){ return $type; }
		}

		if($char->toAscii()->toString()===$char->toString()){
			return "symbol";
		}
		
		$type_ascii = $this->_classifyCharType($char->toAscii());
		if($type_ascii==="lower_case"){
			return "lower_case_diacritics";
		}
		if($type_ascii==="upper_case"){
			return "upper_case_diacritics";
		}

		// maybe some Chinese :)
		return "special";
	}
}
