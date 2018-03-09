<?php
/**
 * \brief test whether the phpunit is work
 * @author Administrator
 */
class unitTest extends CTestCase
{
    public function testEmpty()
    {
        $stack = array();
        $this->assertTrue(empty($stack));
 
        return $stack;
    }
 
    /**
     * @depends testEmpty
     */
    public function testPush(array $stack)
    {
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertFalse(empty($stack));
 
        return $stack;
    }
 
    /**
     * @depends testPush
     */
    public function testPop(array $stack)
    {
        $this->assertEquals('foo', array_pop($stack));
        $this->assertTrue(empty($stack));
    }
}
?>