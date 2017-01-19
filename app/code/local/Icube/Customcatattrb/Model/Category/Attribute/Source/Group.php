<?php
/**
 * Catalog category group
 *
 * @category   Icube
 * @package    Icube_Customcatattrb
 * @author     Po
 */
class Icube_Customcatattrb_Model_Category_Attribute_Source_Group extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	const CAT_ATTR_MAINTENANCE  = 'M';
    const CAT_ATTR_REPAIR  		= 'R';
    const CAT_ATTR_OPERATION    = 'O';
    
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
                    'value' => self::CAT_ATTR_MAINTENANCE,
                    'label' => Mage::helper('catalog')->__('Maintenance'),
                ),
                array(
                    'value' => self::CAT_ATTR_REPAIR,
                    'label' => Mage::helper('catalog')->__('Repair'),
                ),
                array(
                    'value' => self::CAT_ATTR_OPERATION,
                    'label' => Mage::helper('catalog')->__('Operation'),
                )
            );
        }
        return $this->_options;
    }
}
