<?php

class Peexl_CategoryProduct_Model_Catalog_Resource_Product extends Mage_Catalog_Model_Resource_Product{
    protected function _saveCategories(Varien_Object $object)
    {
        /**
         * If category ids data is not declared we haven't do manipulations
         */
        if (!$object->hasCategoryIds()) {
            return $this;
        }
        $categoryIds = $object->getCategoryIds();
        $oldCategoryIds = $this->getCategoryIds($object);

        $object->setIsChangedCategories(false);

        $insert = array_diff($categoryIds, $oldCategoryIds);
        $delete = array_diff($oldCategoryIds, $categoryIds);

        $write = $this->_getWriteAdapter();
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $categoryId) {
                if (empty($categoryId)) {
                    continue;
                }
                $data[] = array(
                    'category_id' => (int)$categoryId,
                    'product_id'  => (int)$object->getId(),
                    'position'    => 100
                );
            }
            if ($data) {
                $write->insertMultiple($this->_productCategoryTable, $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $categoryId) {
                $where = array(
                    'product_id = ?'  => (int)$object->getId(),
                    'category_id = ?' => (int)$categoryId,
                );

                $write->delete($this->_productCategoryTable, $where);
            }
        }

        if (!empty($insert) || !empty($delete)) {
            $object->setAffectedCategoryIds(array_merge($insert, $delete));
            $object->setIsChangedCategories(true);
        }

        return $this;
    }
}