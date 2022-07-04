<?php
class TcPasswordStrengthAnalyzer extends TcBase {
	
	function test(){
		$analyzer = new Yarri\PasswordStrengthAnalyzer();

		$score1 = $analyzer->analyze('thisipassword');
		$score2 = $analyzer->analyze('thisISpasword');
		$score3 = $analyzer->analyze('thisISpasword8!');

		$this->assertTrue($score1>0);
		$this->assertTrue($score2>0);
		$this->assertTrue($score3>0);

		$this->assertTrue($score2>$score1);
		$this->assertTrue($score3>$score2);
	}
}
