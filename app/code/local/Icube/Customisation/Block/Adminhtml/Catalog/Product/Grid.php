<?php
class Icube_Customisation_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected function _prepareCollection()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id')
            ->addAttributeToSelect('vendor_id')
            ->addAttributeToSelect('vendor_sku')
            ->addAttributeToSelect('brand')
            ->addAttributeToSelect('approval_status')
            ->addAttributeToSelect('updated_at');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        $this->setCollection($collection);

        Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    protected function _prepareColumns()
    {

        $this->addColumn('vendor_sku', array(
            'header' => Mage::helper('catalog')->__('Vendor SKU'),
            'align' => 'left',
            'index' => 'vendor_sku',
            'width' => '70'
        ));

        $this->addColumn('vendor_id', array(
            'header' => Mage::helper('catalog')->__('Vendor Name'),
            'align' => 'left',
            'index' => 'vendor_id',
            'width' => '70',
            'renderer'  => 'Icube_Customisation_Block_Adminhtml_Catalog_Product_Renderer_Name'
        ));

        $this->addColumn('brand', array(
            'header' => Mage::helper('catalog')->__('Brand'),
            'align' => 'left',
            'index' => 'brand',
            'type' => 'options',
            'width' => '70',
            'options' => $this->_getProductAttributeOptions('brand'),
            'renderer'  => 'Icube_Customisation_Block_Adminhtml_Catalog_Product_Renderer_Brand'
        ));

        $this->addColumn('approval_status', array(
            'header' => Mage::helper('catalog')->__('Approval Status'),
            'align' => 'left',
            'index' => 'approval_status',
            'type' => 'options',
            'width' => '70',
            'options' => $this->_getProductAttributeOptions('approval_status'),
            'renderer'  => 'Icube_Customisation_Block_Adminhtml_Catalog_Product_Renderer_Approval'
        ));

        $this->addColumn('updated_at', array(
                   'header'    => Mage::helper('catalog')->__('Updated At'),
                   'index'     => 'updated_at',
                   'type'      => 'datetime',
        ));

        $this->addColumnsOrder('vendor_sku', 'sku');
        $this->addColumnsOrder('vendor_id', 'status');
        $this->addColumnsOrder('brand', 'visibility');
        $this->addColumnsOrder('approval_status', 'vendor_id');
        $this->addColumnsOrder('updated_at', 'entity_id');

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('set_as_approved', array(
            'label'        => Mage::helper('catalog')->__('Set as approved'),
            'url'          => $this->getUrl('*/grid/setAsApproved')
        ));

        $this->getMassactionBlock()->addItem('set_as_notapproved', array(
            'label'        => Mage::helper('catalog')->__('Set as not approved'),
            'url'          => $this->getUrl('*/grid/setAsNotApproved'),
            'additional'   => array(
                'reason'    => array(
                    'name'     => 'reason',
                    'type'     => 'text',
                    'class'    => 'required-entry',
                    'label'    => Mage::helper('catalog')->__('Reason')
                )
            )
        ));

        return parent::_prepareMassaction();
    }

    protected function _getProductAttributeOptions($attributeCode) {
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product',$attributeCode);
        /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
        $attributeOptions = $attribute->getSource()->getAllOptions();
        $options = array();
        foreach ($attributeOptions as $option) {
            $options[number_format(intval($option['value']), 4, '.', '')] = $option['label'];
        }

        return $options;
    }
}
