<?php
namespace Yarri;

class PasswordStrengthAnalyzer {

	protected $coefficients = [];
	
	function analyze($password){
		/*
    case_diff
    numbers
    letters
    symbols

		lower_case
		upper_case
		diacritics

{
    midChar: 2,
    consecAlphaUC: 2,
    consecAlphaLC: 2,
    consecNumber: 2,
    seqAlpha: 3,
    seqNumber: 3,
    seqSymbol: 3,
    length: 4,
    number: 4,
    symbol: 6
}

Each rule element corresponds to the following:

    midChar: The multiplication factor (addition) for middle numbers or symbols.
    consecAlphaUC: The multiplication factor (reduction) for consecutive upper case alphabets.
    consecAlphaLC: The multiplication factor (reduction) for consecutive lower case alphabets.
    consecNumber: The multiplication factor (reduction) for consecutive numbers.
    seqAlpha: The multiplication factor (reduction) for sequential alphabets (3+).
    seqNumber: The multiplication factor (reduction) for sequential numbers (3+).
    seqSymbol: The multiplication factor (reduction) for sequential symbols (3+).
    length: The multiplication factor (addition) for the count of characters.
    number: The multiplication factor (addition) for count of numbers in the input.
    symbol: The multiplication factor (addition) for count of symbols in the input.
		*/

		$password = new \String4($password);
		$chars = $password->chars();

		$unique_chars = [];
		$unique_chars_by_type = [];
		$types_used = [];
		$type_transitions = [];
		$prev_type =  null;

		foreach($chars as $char){
			$type = $this->_classifyChar($char);
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

		// unique_chars
		// 0..2: 0
		// 3: 0.0184
		// 6 ... 0.3957952
		// 8 ... 0.57495424
		// 10 ... 1.0479341056
		// 12 ... 1.729025112064
		
		//																										x														base		multiplier	offset	max
		$coefficients["unique_chars"] =				$this->_calcCoef(sizeof($unique_chars),			1.2,		0.3,				-0.3,		4);
		$coefficients["password_length"] =		$this->_calcCoef(sizeof($chars),						1.18,		0.3,				-0.6,		8);
		$coefficients["types_used"] =					$this->_calcCoef(sizeof($types_used),				1.5,		0.3,				0,			4);
		$coefficients["type_transitions"] =		$this->_calcCoef(sizeof($type_transitions),	1.4,		0.3,				0,			4);

		$this->coefficients = $coefficients;

		foreach($coefficients as $_type => $coefficient){
			//echo "coefficient ($_type): $coefficient\n";
			$score = $score * $coefficient;
		}

		/*
		echo "unique_chars:\n";
		echo join(",",$unique_chars),"\n\n";
		echo "unique_chars_by_type:\n";
		foreach($unique_chars_by_type as $_type => $_chars){
			echo "$_type: ",join(",",$_chars),"\n";
		}
		echo "\n";
		echo "types_used:\n";
		echo join(",",$types_used),"\n\n";
		echo "type_transitions:\n";
		echo print_r($type_transitions),"\n";
		*/

		$score = min(100,round($score));

		//echo "score: $score\n\n";

		return $score;
	}

	function getCoefficients(){
		return $this->coefficients;
	}

	protected function _calcCoef($x,$base,$multiplier,$offset = 0,$max = 2){
		$coefficient = (($multiplier * pow($base,$x)) + $offset);
		$coefficient = max($coefficient, 0);
		$coefficient = min($coefficient, $max);
		return $coefficient;
	}

	protected function _classifyChar($char){
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
		
		$type_ascii = $this->_classifyChar($char->toAscii());
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