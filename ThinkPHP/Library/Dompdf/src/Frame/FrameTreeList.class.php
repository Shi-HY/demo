<?php
namespace Dompdf\src\Frame;

use IteratorAggregate;
use Dompdf\src\Frame;

/**
 * Pre-order IteratorAggregate
 *
 * @access private
 * @package dompdf
 */
class FrameTreeList implements IteratorAggregate
{
    /**
     * @var \Dompdf\src\Frame
     */
    protected $_root;

    /**
     * @param \Dompdf\src\Frame $root
     */
    public function __construct(Frame $root)
    {
        $this->_root = $root;
    }

    /**
     * @return FrameTreeIterator
     */
    public function getIterator()
    {
        return new FrameTreeIterator($this->_root);
    }
}
