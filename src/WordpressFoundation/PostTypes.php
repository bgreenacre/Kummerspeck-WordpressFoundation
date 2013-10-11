<?php namespace WordpressFoundation;
/**
 * WordpressFoundation Utilities
 *
 * @package WordpressFoundation
 * @author Brian Greenacre <bgreenacre42@gmail.com>
 * @version $id$
 */

/**
 *
 * @package WordpressFoundation
 * @author Brian Greenacre <bgreenacre42@gmail.com>
 * @version $id$
 */
class PostTypes {

    use \WordpressFoundation\Traits\ContainerAware;

    /**
     * Holds all post types.
     *
     * @access protected
     * @var array
     */
    protected $types = array();

    public function __construct(array $types)
    {
        $this->setTypes($types);
    }

    public function register()
    {
        foreach ($this->types as $type => $args)
        {
            register_post_type($type, $args);
        }

        return $this;
    }

    public function addType($type, array $args = array(), array $subMenus = null)
    {
        $this->types[$type] = $args;

        if ($subMenus !== null)
        {
            if (is_numeric(key($subMenus)))
            {
                foreach ($subMenus as $subMenu)
                {
                    $this->getProvider('menus')->add($subMenu);
                }
            }
            else
            {
                $this->getProvider('menus')->add($subMenus);
            }
        }

        return $this;
    }

    public function setTypes(array $types)
    {
        foreach ($types as $type)
        {
            $this->addType(
                array_get($type, 'name'),
                array_get($type, 'args'),
                array_get($type, 'sub_menus')
            );
        }

        return $this;
    }

    public function getTypes()
    {
        return $this->types;
    }

}