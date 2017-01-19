<?php
class Icube_Pim_Block_Category_Attribute_Disable_Text extends Varien_Data_Form_Element_Text
{
    public function getAfterElementHtml()
    {
        $html = parent::getAfterElementHtml();
        return $html."  <script>
        				$('".$this->getHtmlId()."').disable();
        				</script>";
    }
 
}