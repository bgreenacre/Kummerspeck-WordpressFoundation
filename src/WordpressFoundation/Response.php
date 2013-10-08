<?php namespace WordpressFoundation;

class Response {

    /**
     * Plugin container object.
     *
     * @access protected
     * @var PluginContainer
     */
    protected $_container;

    /**
     * HTTP status code
     *
     * @access protected
     * @var integer
     */
    protected $_status = 200;

    /**
     * Construct object.
     *
     * @access public
     * @param PluginContainer $container Plugins container object.
     * @return void
     */
    public function __construct(PluginContainer $container)
    {
        $this->setContainer($container);
    }

    /**
     * Render a response string when object is casted to string or echoed.
     *
     * @access public
     * @return string Response string.
     */
    public function __toString()
    {
    	return (string) $this->render();
    }

    /**
     * Render a response and send out headers.
     *
     * @access public
     * @return string Response.
     */
    public function render()
    {
    	if ( ! headers_sent())
    	{
    		header('Content-Length: ' . strlen($this->_body));
    		header('Status: ' . $this->getStatus());

    		foreach ($this->_headers as $header)
    		{
    			header($header, true);
    		}
    	}

    	return $this->_body;
    }

    /**
     * Add a header to be sent during render.
     *
     * @access public
     * @param string $header HTTP header value.
     * @return $this
     */
    public function addHeader($header)
    {
    	$this->_headers[] = $header;

    	return $this;
    }

    /**
     * Set the status code.
     *
     * @access public
     * @param integer $status Valid http status code.
     * @return $this
     * @throws InvalidArgumentException If status is not a number or invalid http code.
     */
    public function setStatus($status)
    {
    	if ( ! is_numeric($status))
    	{
    		throw new \InvalidArgumentException('Status code must be a number.');
    	}

    	$this->_status = (int) $status;

    	return $this;
    }

    /**
     * Get the status code of the response object.
     *
     * @access public
     * @return integer HTTP status code.
     */
    public function getStatus()
    {
    	return $this->_status;
    }

    /**
     * Set container object.
     *
     * @access public
     * @param PluginContainer $container Plugin container object.
     * @return $this
     */
    public function setContainer(PluginContainer $container)
    {
        $this->_container = $container;

        return $this;
    }

    /**
     * Get container object.
     *
     * @access public
     * @return PluginContainer
     */
    public function getContainer()
    {
        return $this->_container;
    }

}