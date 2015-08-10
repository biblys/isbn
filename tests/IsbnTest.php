<?php 

require_once __DIR__ . '/../ISBN.class.php';

class testEuroTax extends PHPUnit_Framework_TestCase
{
    protected $isbn;
    
    public function setUp()
    {
        $this->isbn = new ISBN('9782207258040');
    }
    
    public function testIsValid()
    {
        $this->assertTrue($this->isbn->isValid());
    }
    
    public function testIsNotValid()
    {
        $invalid = new ISBN('5780AAC728440');
        $this->assertFalse($invalid->isValid());
    }
    
    public function testFormatIsbn13()
    {
        $this->assertEquals($this->isbn->format('ISBN-13'), "978-2-207-25804-0");
    }
    
    public function testFormatIsbn10()
    {
        $this->assertEquals($this->isbn->format('ISBN-10'), "2-207-25804-1");
    }
}
