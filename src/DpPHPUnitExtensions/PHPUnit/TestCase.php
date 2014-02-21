<?php
namespace DpPHPUnitExtensions\PHPUnit;

// Framework usage
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_MockObject_Matcher_Invocation;
use PHPUnit_Framework_Exception;
use SebastianBergmann\Exporter\Exception;
// PHP usage
use ReflectionClass;

/*
 * Test-case with minor improvements to the default test-case
 */
class TestCase extends PHPUnit_Framework_TestCase {
	const SUT = "UNKNOWN";
	/** @var $_allValidator \DpZFExtensions\Validator\IChangeValidator */
	protected $_allValidator;
	public function setUp() {
		$this->_allValidator = $this->getMock('DpZFExtensions\Validator\IChangeValidator');
		$this->_allValidator->expects($this->any())->method('isValid')->will($this->returnValue(true));
		$this->_allValidator->expects($this->any())->method('isValidChange')->will($this->returnValue(true));
	}
	private function _getSut() {
		$class = get_called_class();
		return $class::SUT;
	}
	public function __construct($name = NULL, array $data = array(), $dataName = '') {
		if ($this->_getSut() === "UNKNOWN")
			throw new Exception("System Under Test not set (const SUT)");
		parent::__construct($name,$data,$dataName);
	}
	protected function _mustBeCalled(PHPUnit_Framework_MockObject_Matcher_Invocation $inv = null) {
		$object = $this->getMock('stdClass',array('called'));
		$object->expects(is_null($inv)?$this->atLeastOnce():$inv)->method('called');
		return $object;
	}
    /**
     * @param object $object
     * @param string $name
     * @return mixed
     */
    protected function _getPrivateProperty($object,$name) {
        $class = new ReflectionClass($this->_getSut());
        $method = $class->getProperty($name);
        $method->setAccessible(true);
        return $method->getValue($object);
    }

    /**
     * @param object $object
     * @param string $name
     * @param mixed $value
     */
    protected function _setPrivateProperty($object,$name,$value = null) {
        $class = new ReflectionClass($this->_getSut());
        $method = $class->getProperty($name);
        $method->setAccessible(true);
        $method->setValue($object,$value);
    }

    /**
     * @param object|string $object
     * @param string $name
     * @param array $args
     * @return mixed
     */
    protected function _getPrivateMethod($object,$name,$args = array()) {
	    $className = $this->_getSut();
	    if (is_string($object)) {
		    $className = $object;
		    $object = null;
	    }
	    elseif (!$object instanceof $className)
		    $className = get_class($object);
        $class = new ReflectionClass($className);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($object,$args);
    }
	/**
	 * Asserts that a condition is true.
	 *
	 * @param  Closure $callback
	 * @param  object $object
	 * @param  string  $message
	 * @throws PHPUnit_Framework_AssertionFailedError
	 */
	public static function assertCallback($callback,$object, $message = '')
	{
		self::assertThat($object, self::checkCallback($callback), $message);
	}

	// ...

	/**
	 * Returns a PHPUnit_Framework_Constraint_IsTrue matcher object.
	 *
	 * @param Closure $closure
	 * @return PHPUnit_Framework_Constraint_IsTrue
	 * @since  Method available since Release 3.3.0
	 */
	public static function checkCallback($closure)
	{
		return new ClosureConstraint($closure);
	}
	/**
     * Returns a mock object for the specified class.
     *
     * @param  string  $originalClassName
     * @param  array   $methods
     * @param  array   $arguments
     * @param  string  $mockClassName
     * @param  boolean $callOriginalConstructor
     * @param  boolean $callOriginalClone
     * @param  boolean $callAutoload
     * @param  boolean $cloneArguments
     * @return PHPUnit_Framework_MockObject_MockObject
     * @throws PHPUnit_Framework_Exception
     * @since  Method available since Release 3.0.0
     */
    public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false)
    {
    	if (!class_exists($originalClassName) && !interface_exists($originalClassName))
            $this->fail("Could not find Class or Interface: ".$originalClassName);
    	return parent::getMock($originalClassName, $methods, $arguments , $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $cloneArguments);
    }
    /**
     * Returns a mock object for the specified class.
     *
     * @param  string  $originalClassName
     * @param  array   $methods
     * @param  array   $arguments
     * @param  string  $mockClassName
     * @param  boolean $callOriginalConstructor
     * @param  boolean $callOriginalClone
     * @param  boolean $callAutoload
     * @param  boolean $cloneArguments
     * @return PHPUnit_Framework_MockObject_MockObject
     * @throws PHPUnit_Framework_Exception
     * @since  Method available since Release 3.0.0
     */
    public function getNewMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false) {
    	return parent::getMock($originalClassName, $methods, $arguments , $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $cloneArguments);
    }
}
