<?php
require(__DIR__ . "/../vendor/autoload.php");

echo "password: ";

$stdin = fopen("php://stdin", "r");
stream_set_blocking($stdin, 0);
system("stty cbreak -echo");

$password = "";
while (1) {
  $keypress = fgets($stdin);
  if (strlen($keypress)) {
		if($keypress === "\177"){ // backspace
			$password = String4::ToObject($password)->substr(0,-1)->toString();
		}elseif($keypress === "\e"){ // escape
			system("stty cbreak echo");
			exit;
		}else{
			$password .= $keypress;
		}

		echo "\n";
		echo "\n";

		echo "coefficients:\n";
		$detector = new Yarri\PasswordStrengthAnalyzer();
		$score = $detector->analyze($password);
		foreach($detector->getCoefficients() as $key => $coefficient){
			echo "* $key: $coefficient\n";
		}
		echo "SCORE: $score%\n";
		echo "password: $password";
  }

	usleep(50000);
}
