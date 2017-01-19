<?php
/**
 *
 * @category   Icube
 * @package    Icube_Pim
 * @author     Po
 */

class Icube_Pim_Block_Pager extends Mage_Page_Block_Html_Pager
{
	protected $_availableLimit  = array(10=>10,20=>20,50=>50,100=>100);
    protected $_dispersion      = 3;
    protected $_displayPages    = 10;
    protected $_showPerPage     = true;
}