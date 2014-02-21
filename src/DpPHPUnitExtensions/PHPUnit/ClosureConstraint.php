<?php
/**
 * User: dpeuscher
 * Date: 01.03.13
 */
namespace DpPHPUnitExtensions\PHPUnit;

// Framework usage
use PHPUnit_Framework_Constraint;
// PHP usage
use Closure;

class ClosureConstraint extends PHPUnit_Framework_Constraint
{
	protected $_closure;
	public function __construct(Closure $closure) {
		$this->_closure = $closure;
	}
	/**
	 * @param mixed $test
	 * @return bool
	 */
	public function matches($test)
	{
		return call_user_func($this->_closure,$test) === true;
	}

	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString()
	{
		return 'results in true';
	}
}?>
