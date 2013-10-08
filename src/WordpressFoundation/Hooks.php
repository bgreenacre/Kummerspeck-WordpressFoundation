<?php namespace WordpressFoundation;
/**
 * WordpressFoundation Utilities
 *
 * @package WordpressFoundation
 * @author Brian Greenacre <bgreenacre42@gmail.com>
 * @version $id$
 */

use Pimple;

/**
 * Manage hooks added by plugin.
 *
 * @package WordpressFoundation
 * @author Brian Greenacre <bgreenacre42@gmail.com>
 * @version $id$
 */
class Hooks {

    /**
     * Plugin container object.
     *
     * @access protected
     * @var Pimple
     */
	protected $_container;

	/**
	 * Default priority to set on hook.
	 *
	 * @access protected
	 * @var integer
	 */
	protected $_defaultPriority = 10;

	/**
	 * Construct object and optionally change default priority.
	 *
	 * @access public
	 * @param integer $defaultPriority Default priority to set on hook.
	 * @return void
	 */
	public function __construct(Pimple $container, $defaultPriority = null)
	{
		$this->setContainer($container);

		if ($defaultPriority !== null)
		{
			// Set default priority
			$this->setDefaultPriority($defaultPriority);
		}
	}

	/**
	 * Add a wordpress action hook.
	 *
	 * @access public
	 * @param string               $action   The action tag name.
	 * @param string|array|Closure $callback Callback function.
	 * @param integer              $priority Priority level of added action.
	 * @param integer              $argCount Argument count added action should take.
	 */
	public function addAction($action, $callback, $priority = null, $argCount = 1)
	{
		$c = $this->getContainer();

		if ($priority === null)
		{
			$priority = $this->getDefaultPriority();
		}

		add_action($action, function() use ($c, $callback)
		{
			// Wrap callback in closure so it can
			// take the container as an arg.
			$args = func_get_args();
			array_unshift($args, $c);

			return call_user_func_array($callback, $args);
		}, $priority, $argCount);

		return $this;
	}

	/**
	 * Add a wordpress filter hook.
	 *
	 * @access public
	 * @param string               $filter   The filter tag name.
	 * @param string|array|Closure $callback Callback function.
	 * @param integer              $priority Priority level of added filter.
	 * @param integer              $argCount Argument count added filter should take.
	 */
	public function addFilter($filter, $callback, $priority = null, $argCount = 1)
	{
		$c = $this->getContainer();

		if ($priority === null)
		{
			$priority = $this->getDefaultPriority();
		}

		add_filter($filter, function() use ($c, $callback)
		{
			// Wrap callback in closure so it can
			// take the container as an arg.
			$args = func_get_args();
			array_unshift($args, $c);

			return call_user_func_array($callback, $args);
		}, $priority, $argCount);

		return $this;
	}

	public function activateHook(\Closure $closure)
	{
		$c = $this->getContainer();

		register_activation_hook($c['plugin.filename'], function() use ($c, $closure)
		{
			$args = func_get_args();
			array_unshift($args, $c);

			return call_user_func_array($closure, $args);
		});

		return $this;
	}

	public function deactivateHook(\Closure $closure)
	{
		$c = $this->getContainer();
		register_activation_hook($c['plugin.filename'], function() use ($c, $closure)
		{
			$args = func_get_args();
			array_unshift($args, $c);

			return call_user_func_array($closure, $args);
		});

		return $this;
	}

	/**
	 * Set the default priority level.
	 *
	 * @access public
	 * @param integer $priority Priority level.
	 * @return $this
	 * @throws InvalidArgumentException If priority given is not numeric.
	 */
	public function setDefaultPriority($priority)
	{
		if ( ! ctype_digit($priority))
		{
			throw new \InvalidArgumentException('Invalid priority value for Hooks object.');
		}

		$this->_defaultPriority = (int) $priority;

		return $this;
	}

	/**
	 * Get the default priority level.
	 *
	 * @access public
	 * @return integer Priority level.
	 */
	public function getDefaultPriority()
	{
		return $this->_defaultPriority;
	}

    /**
     * Set container object.
     *
     * @access public
     * @param Pimple $container Plugin container object.
     * @return $this
     */
    public function setContainer(Pimple $container)
    {
        $this->_container = $container;

        return $this;
    }

    /**
     * Get container object.
     *
     * @access public
     * @return Pimple
     */
    public function getContainer()
    {
        return $this->_container;
    }

}