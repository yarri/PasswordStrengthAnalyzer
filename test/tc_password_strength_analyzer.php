<?php
class TcPasswordStrengthAnalyzer extends TcBase {
	
	function test(){
		$analyzer = new Yarri\PasswordStrengthAnalyzer();

		$score1 = $analyzer->analyze('thisipassword');
		$score2 = $analyzer->analyze('thisISpassword');
		$score3 = $analyzer->analyze('thisISpassword8!');

		$this->assertTrue($score1>0);
		$this->assertTrue($score2>0);
		$this->assertTrue($score3>0);

		$this->assertTrue($score2>$score1);
		$this->assertTrue($score3>$score2);
	}

	function test_simplicity_factor(){
		$analyzer = new Yarri\PasswordStrengthAnalyzer();

		$score1 = $analyzer->analyze('hiWORLDhiWORLD');
		$coeficients1 = $analyzer->getCoefficients();

		$score2 = $analyzer->analyze('hiWORLDhiWZRLD');
		$coeficients2 = $analyzer->getCoefficients();

		$this->assertTrue($score1 < $score2);
		$this->assertTrue(1.0 > $coeficients1["simplicity_factor"]);
		$this->assertEquals(1.0,$coeficients2["simplicity_factor"]);
	}

	function test__simplifyPassword(){
		$analyzer = new Yarri\PasswordStrengthAnalyzer();

		$this->assertEquals("hiWorld",$analyzer->_simplifyPassword("hiWorld"));
		$this->assertEquals("Helo#1",$analyzer->_simplifyPassword("Heeello#11"));
		$this->assertEquals("",$analyzer->_simplifyPassword(""));

		$this->assertEquals("ab",$analyzer->_simplifyPassword("ab"));
		$this->assertEquals("ab",$analyzer->_simplifyPassword("abc"));
		$this->assertEquals("xabx",$analyzer->_simplifyPassword("xabcdefx"));

		$this->assertEquals("ba",$analyzer->_simplifyPassword("ba"));
		$this->assertEquals("cb",$analyzer->_simplifyPassword("cba"));
		$this->assertEquals("xfex",$analyzer->_simplifyPassword("xfedcbax"));

		$this->assertEquals("hiWorld",$analyzer->_simplifyPassword("hiWorld"));
		$this->assertEquals("Secret12",$analyzer->_simplifyPassword("Secret12"));
		$this->assertEquals("Secret12",$analyzer->_simplifyPassword("Secret123"));
		$this->assertEquals("Secret12",$analyzer->_simplifyPassword("Secret1234"));
	}
}
