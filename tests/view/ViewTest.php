<?php
/**
 * 
 */
class View_Kohana_ViewTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provider for test_instaniate
	 *
	 * @return array
	 */
	public function provider_instantiate()
	{
		return array(
			array('Test', FALSE),
			array('Welcome', FALSE),
			array('doesnt_exist', TRUE),
		);
	}

	/**
	 * Tests that we can instantiate a view file
	 * 
	 * @test
	 * @dataProvider provider_instantiate
	 *
	 * @return null
	 */
	public function test_instantiate($view, $expects_exception)
	{
		try
		{
			$view = View::factory($view);
			$this->assertFalse($expects_exception);
		}
		catch (Exception $e)
		{
			$this->assertTrue($expects_exception);
		}
	}
}

class View_Test extends View {}