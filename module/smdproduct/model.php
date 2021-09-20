<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once '../common/ext/model.php';
require_once 'lib/vendor/autoload.php';

class smdproductModel extends model {
        
    /**
     * Get Meta Field By ID from `meta_field`
     * 
     * Gets a Meta Field row by ID
     * @param int $metaFieldID
     * @return object
     */
    public function getMetaFieldById($metaFieldID = null) {
                
        $metaField = $this->dao->findById((int)$metaFieldID)->from(TABLE_META_FIELDS)->fetch();
        
        if(!$metaField) return false;
        
        return $metaField;
        
    }
    
    /**
     * Get Meta Value By ID
     * 
     * Gets a Meta Value from `meta_field_value`
     * @param int $metaValueID
     * @return object
     */
    public function getMetaValueById($metaValueID = null) {
        
        $metaValue = $this->dao->findById((int)$metaValueID)->from(TABLE_META_FIELD_VALUES)->fetch('metaValue');
        
        if(!$metaValue) return false;
        
        return $metaValue;
    }
    
    /**
     * Get Meta Type By ID
     * 
     * Gets a meta type by its ID
     * @param int $metaTypeID
     * @return object
     */
    public function getMetaTypeById($metaTypeID = null) {
                
        $metaType = $this->dao->findById((int)$metaTypeID)->from(TABLE_META_TYPES)->fetch();
        
        if(!$metaType) return false;
        
        return $metaType;
        
    }
    
    /**
     * Update Meta Field
     * 
     * Upserts a new meta field is `meta_field` depending on whether a 
     * metaFieldID is present
     * 
     * @param int $metaFieldID
     * @return int
     */
    public function updateMetaField($metaFieldID = null) {
        // Filter POST data into a data object
        $metaField = fixer::input('post')
                    ->setDefault('created', date('Y-m-d H:i:s'))
                    ->setDefault('creatorId', $this->app->user->id)
                    ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                    ->remove('uid')
                    ->remove('metaID')
                    ->get();
        
        if($metaFieldID == null) {
            // Insert a new meta field
            $this->dao->insert(TABLE_META_FIELDS)
                      ->data($metaField)
                      ->exec();
            
            return $this->dao->lastInsertID();
            
        } else {
            // Update an existing meta field
            $this->dao->update(TABLE_META_FIELDS)
                      ->data($metaField)
                      ->where('id')->eq($metaFieldID)
                      ->limit(1)
                      ->exec();
            
            return $metaFieldID;
            
        }
        
    }
    
    /**
     * Get Brands
     * 
     * Gets a list of all active brands
     * @return array
     */
    public function getBrands() {
        
        $result = $this->dao->select('*')
                            ->from(TABLE_BRANDS)
                            ->where('active')->eq(1)
                            ->orderBy('name_asc')
                            ->fetchAll();
        
        return $result;
        
    }
    
    /**
     * Get Brand By ID
     * 
     * Gets a single brand from `c_zt_brand` by its ID
     * @param int $brandID
     * @return object
     */
    public function getBrandById($brandID = null) {
                
        $brand = $this->dao->findById((int)$brandID)->from(TABLE_BRANDS)->fetch();
                
        if(!$brand) return false;
        
        return $brand;
        
    }
    
    /**
     * Update Meta Type
     * 
     * Upserts a Meta Type object into the `c_zt_meta_type` table
     */
    public function updateMetaType($metaTypeID = null) {
        // Filter the POST data
        $metaType = fixer::input('post')
                    ->setDefault('created', date('Y-m-d H:i:s'))
                    ->setDefault('creatorId', $this->app->user->id)
                    ->join('fields', ',') // Crush the fields array into a comma separated string
                    ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                    ->remove('uid')
                    ->remove('metaID')
                    ->get();
        
        if($metaTypeID == null) {
            // If the ID doesn't exist, insert
            $this->dao->insert(TABLE_META_TYPES)
                      ->data($metaType)
                      ->exec();
            
            return $this->dao->lastInsertID();
            
        } else {
            // Update the object
            $this->dao->update(TABLE_META_TYPES)
                      ->data($metaType)
                      ->where('id')->eq($metaTypeID)
                      ->limit(1)
                      ->exec();
            
            return $metaTypeID;
            
        }
        
    }
    
    /**
     * Get Products
     * 
     * Gets a list of products
     * 
     * @param int $itemCounts
     * @param string $orderBy
     * @param Pager $pager
     * @return array
     */
    public function getProducts($orderBy = 'order_desc', $pager = null, $search = null) {
                
        $products = $this->dao->select('*')
                              ->from(TABLE_PRODUCTS)->alias('t1')
                              ->where('parentId')->lt('1')  // do not get child products
                              ->orderBy($orderBy)
                              ->page($pager)
                              ->fetchAll();
        
        return $products;
        
    }
    
    /**
     * Get Products List
     * 
     * Gets a list of product ids and names for quick reference
     * @return array
     */
    public function getProductsList() {
        
        $products = $this->dao->select('id, CONCAT(sku, " ", name) AS name')
                              ->from(TABLE_PRODUCTS)
                              ->fetchAll();
        
        return $this->formatForSelect($products);
        
    }
    
    /**
     * Get Meta Fields
     * 
     * Gets a list of meta fields
     * 
     * @param int $branch
     * @param int $itemCounts
     * @param string $orderBy
     * @param Pager $pager
     * @return array
     */
    public function getMetaFields($itemCounts = 50, $orderBy = 'order_asc', $pager = null) {
                
        $metaFields = $this->dao->select('*')
                                ->from(TABLE_META_FIELDS)->alias('t1')
                                ->where('`default`')->eq(0)
                                ->orderBy('order_asc')
                                ->page($pager)
                                ->fetchAll();
        
        return $metaFields;
        
    }
    
    /**
     * Format for Select
     * 
     * Formats an array of objects into an array of IDs and names
     * @param array $metaFields
     * @return array
     */
    public function formatForSelect($arr) {
                       
        $data = array('');
                
        foreach($arr as $a) {
            $data[$a->id] = $a->name;
        }
                
        return $data;
    }
    
    /**
     * Get Meta Types
     * 
     * Gets a list of meta types
     * 
     * @param int $branch
     * @param int $itemCounts
     * @param string $orderBy
     * @param Pager $pager
     * @return array
     */
    public function getMetaTypes($itemCounts = 50, $orderBy = 'order_desc', $pager = null) {
        // Ordering fix from https://stackoverflow.com/questions/13382380/mysql-order-by-parent-and-child
        $metaTypes = $this->dao->select("*, CONCAT(IF(parentId = 0,'',CONCAT('/',parentId)),'/',id) AS gOrder")
                                ->from(TABLE_META_TYPES)->alias('t1')
                                ->orderBy('gOrder_asc')
                                ->page($pager)
                                ->fetchAll('id');
        
        return $metaTypes;
        
    }
    
    /**
     * Get Child Meta Types
     * 
     * Gets all of the child meta types for a particular product
     */
    public function getChildMetaTypes($data) {
        
        $metaTypeId = $data['metaTypeId'];
                        
        $metaTypeChildren = $this->dao->select('*')
                                      ->from(TABLE_META_TYPES)->alias('t1')
                                      ->where('parentId')->eq($metaTypeId)
                                      ->fetchAll('id');
                
        return $metaTypeChildren;
                
        
    }
    
    /**
     * Get Meta Type Field IDs
     * 
     * Gets all meta field IDs associated with a particular type
     * @param int $metaTypeID
     * @return array
     */
    public function getMetaTypeFieldIds($metaTypeID = null) {

        $metaFields = $this->dao->findById((int)$metaTypeID)->from(TABLE_META_TYPES)->fetch('fields');
                
        return explode(',', $metaFields);
        
    }
    
    /**
     * Get Meta Type Fields
     * 
     * Gets all meta fields associated with a particular type
     * @param int $metaTypeID
     * @return array
     */
    public function getMetaTypeFields($metaTypeID) {
        
        $metaFieldIds = $this->getMetaTypeFieldIds($metaTypeID);
        
        $metaTypeFields = $this->dao->select('id, name, `order`')
                                    ->from(TABLE_META_FIELDS)
                                    ->where('id')->in($metaFieldIds)
                                    ->orderBy('order')
                                    ->fetchAll();
        
        return $metaTypeFields;
        
    }
    
    /**
     * Get All Meta Field Descriptions
     * 
     * Gets key value pairs for all meta field descriptions. Used for tooltips
     * @return array
     */
    public function getAllMetaFieldDescriptions() {
        
        $metaFieldDescs = $this->dao->select('id, `desc` AS name')
                                    ->from(TABLE_META_FIELDS)
                                    ->fetchAll();
        
        return $metaFieldDescs;
        
    }
    
    /**
     * Get Meta Field Values
     * 
     * Gets all active meta field values for a particular meta ID.
     * @param int $metaFieldID
     * @return array
     */
    public function getMetaFieldValues($metaFieldId) {
        
        $result = $this->dao->select('id, metaFieldId, metaValue AS name')
                            ->from(TABLE_META_FIELD_VALUES)
                            ->where('active')->eq(1)
                            ->andWhere('metaFieldId')->eq($metaFieldId)
                            ->fetchAll();
                
        return $result;
        
    }
    
    /**
     * Update Product
     * 
     * Upserts a product into the database depending on whether a $productID is
     * present
     * @return int
     */
    public function updateProduct($productID = null) {
                
        $product = fixer::input('post')
                    ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                    ->remove('uid')
                    ->get();
        
        $product = $this->cleanTags($product);
                
        if($productID == null) {
            // Set the created date
            $product->created = date('Y-m-d H:i:s');
            $product->creatorId = $this->app->user->id;
            
            // Determine if product has children or not
            if(count($product->{'meta-' . COLOURID}) > 1) {
                $product->hasChildren = 1;
            } else {
                $product->hasChildren = 0;
                $product->colourId = $product->{'meta-' . COLOURID}[0];
            }
            
            // Create / update colour variations
            $childColours = $product->{'meta-' . COLOURID};
            unset($product->{'meta-' . COLOURID});
                                   
            $this->dao->insert(TABLE_PRODUCTS)
                      ->data($product)
                      ->exec();
            
            $insert_id = $this->dao->lastInsertID();
            
            if($product->hasChildren == 1) {
                $this->createChildren($insert_id, $childColours);
            }
            
            return $insert_id;
            
        } else {
            
            $productMeta = new stdClass();
            $productDefault = new stdClass();
            
            $product->modified = date('Y-m-d H:i:s');
            $product->modifierId = $this->app->user->id;
            
            // Extract the meta and default fields from the product object
            // so that they can each be processed separately
            foreach($product as $k => $p) {
                
                if(strpos($k, 'meta-') !== false) {
                    $productMeta->$k = $p;
                    unset($product->$k);
                }
                // There are no default fields when a product is initially
                // created so this only runs on update
                if(strpos($k, 'default-') !== false) {
                    $productDefault->$k = $p;
                    unset($product->$k);
                }
            }
            
            // Extract child product fields from product object
            if(isset($product->child)) {
                $children = $product->child;
                unset($product->child);
            }
            
            $product->complete = $this->checkIfComplete($productID, $productMeta);
            
            // Update the product
            $this->dao->update(TABLE_PRODUCTS)
                      ->data($product)
                      ->where('id')->eq($productID)
                      ->limit(1)
                      ->exec();
            // Update the product's meta and defaults
            $this->updateProductMeta($productID, $productMeta);
            $this->updateProductDefault($productID, $productDefault);
            // If a product has children, update the children
            if($product->hasChildren == 1) {
                $this->updateChildren($children);
            }
            
            return $productID;
            
        }
        
    }
    
    /**
     * Check If Complete
     * 
     * Determines whether a product is complete or not
     * @param object $productID
     */
    
    private function checkIfComplete($productID, $meta) {
        
        $metaFlat = array();
        $valuesTotal = 0;
        
        foreach($meta as $k => $m) {
            $key = str_replace('meta-', '', $k);
            
            $valuesTotal += count($m);
            $keys[] = $key;
        }
        
        $result = $this->dao->select('id')
                            ->from(TABLE_PRODUCTS_META)
                            ->where('productId')->eq($productID)
                            ->andWhere('metaFieldId')->in($keys)
                            ->fetchAll();
                
        // Product is not complete
        if(count($keys) != count($result)) {
            return 0;
        }
        
        return 1;
        
    }

    /**
     * Create Child
     * 
     * Creates a single new child for a parent product
     * @param object $child
     * @return int
     */
    public function createChild($child) {

        $this->dao->insert(TABLE_PRODUCTS)
                      ->data($child)
                      ->exec();
                     
        $insert_id = $this->dao->lastInsertID();

        return $insert_id;
    }
    
    /**
     * Create Children
     * 
     * If multiple colours are selected on a product, create child products
     * @param int $productID
     * @param array $childColours
     * @return void
     */
    private function createChildren($productID, $childColours) {

        foreach($childColours as $colourID) {
            
            // 0 is always submitted as a value for some reason
            if($colourID == 0) {
                continue;
            }
            // Checks whether the child object exists in the DB already
            $child = $this->getChild($productID, $colourID);
                        
            // Child does not exist, create new entry
            if($child == '') {
                // Create child object
                $childProduct = new stdClass();
                $childProduct->colourId = $colourID;
                $childProduct->parentId = $productID;
                $childProduct->created = date('Y-m-d H:i:s');
                $childProduct->creatorId = $this->app->user->id;
                                
                $this->dao->insert(TABLE_PRODUCTS)
                      ->data($childProduct)
                      ->exec();
                     
                $insert_id = $this->dao->lastInsertID();
                
            }
            
        }
        
    }
    
    /**
     * Update Children
     * 
     * Loops through all submitted children and updates their information
     * @param array $children
     */
    private function updateChildren($children) {
        
        foreach($children as $key => $child) {
            
            $childProductID = $key;
            // Create a child object from the data provided
            $childproduct = new stdClass();
            
            $childproduct->sku = $child['sku'];
            $childproduct->barcode = $child['barcode'];
            $childproduct->modified = date('Y-m-d H:i:s');
            $childproduct->modifierId = $this->app->user->id;
            
            $this->dao->update(TABLE_PRODUCTS)
                      ->data($childproduct)
                      ->where('id')->eq($childProductID)
                      ->limit(1)
                      ->exec();
            
        }
    }
    
    /**
     * Get Children
     * 
     * Gets all children for a particular product ID
     * @param int $productID
     * @return array
     */
    public function getChildren($productID) {
        
        $children = $this->dao->select('id')
                              ->from(TABLE_PRODUCTS)
                              ->where('parentId')->eq($productID)
                              ->fetchAll('id');
                
        foreach($children as $k => $child) {
            
            $children[$k] = $this->getById($child->id);
            
        }
        
        return $children;
        
    }
    
    /**
     * Get Child
     * 
     * Gets a single child based on a productID and colourID
     * 
     * @param int $productID
     * @param int $colourID
     * @return string
     */
    public function getChild($productID, $colourID) {
        
        $child = $this->dao->select('id')
                              ->from(TABLE_PRODUCTS)
                              ->where('parentId')->eq($productID)
                              ->andWhere('colourId')->eq($colourID)
                              ->limit(1)
                              ->fetch('id');
        
        return $child;
        
    }
    
    /**
     * Update Product Meta
     * 
     * Loops through all submitted Product Meta fields and upserts to the
     * `c_zt_product_meta` table
     * @param int $productID
     * @param array $meta
     */
    public function updateProductMeta($productID, $meta) {
        
        foreach($meta as $metaField => $metaValueID) {
            
            $metaFieldID = (int)str_replace('meta-', '', $metaField);
            
            $data = new stdClass();
            $data->metaFieldValueId = $metaValueID;
            
            $metaID = $this->getProductMetaId($productID, $metaFieldID);
            
            if($metaID == '') {
                
                $data->productID = $productID;
                $data->metaFieldID = $metaFieldID;
                
                $this->dao->insert(TABLE_PRODUCTS_META)
                          ->data($data)
                          ->exec();
                
            } else {
            
                $this->dao->update(TABLE_PRODUCTS_META)
                          ->data($data)
                          ->where('id')->eq($metaID)
                          ->limit(1)
                          ->exec();
            }
            
        }
        
    }
    
    /**
     * Get Product Meta ID
     * 
     * Gets the ID of a particular product meta value by product ID and field ID
     * @param int $productID
     * @param int $metaFieldID
     * @return int
     */
    public function getProductMetaId($productID, $metaFieldID) {
        
        $metaID = $this->dao->select('id')
                            ->from(TABLE_PRODUCTS_META)
                            ->where('productId')->eq($productID)
                            ->andWhere('metaFieldId')->eq($metaFieldID)
                            ->fetch('id');
                
        return $metaID;
        
    }
    
        
    /**
     * Get By ID
     * 
     * Gets a single product by its ID from the `c_zt_product` table
     * @param int $productID
     * @return object
     */
     public function getById($productID) {
        
        $product = $this->dao->findById((int)$productID)->from(TABLE_PRODUCTS)->fetch();
        
        if(!$product) return false;
        
        $product->brand = $this->getBrandById($product->brandId)->name;
        $product->metaType = $this->getMetaTypeById($product->metaTypeId)->name;
        $product->metaTypeChild = $this->getMetaTypeById($product->metaTypeChildId)->name;
        
        if($product->hasChildren == 1) {
            $product->children = $this->getChildren($productID);
        }
                
        return $product;
        
    }
    
    /**
     * Select
     * Selects a collection of products based on the pager, order and search params
     * 
     * @param Pager $pager
     * @param String $orderBy
     * @param String $search
     * @return Array
     */
     public function select($pager, $orderBy, $search = false) {
        // Get a collection of product IDs
        $result = $this->dao->select('id')
                            ->from(TABLE_PRODUCTS)
                            // Ignore child products with a parentID > 0
                            ->where('parentId')->lt(1)
                            // Search the name and sku fields for the string if $search is not false
                            ->beginIF($search != false)
                            ->andWhere('`name`', true)
                            ->like("%{$search}%")
                            ->orWhere('sku')
                            ->like("%{$search}%")
                            ->markRight(1)->fi()
                            // Set the ordering
                            ->orderBy($orderBy)
                            // Paginate with the Pager object
                            ->page($pager)
                            ->fetchAll();
        // Loop through each ID and get the product's data
        // This seems inefficient, but it negates the need for "getChildren" on the Index page
        foreach($result as $key => $product) {
            $result[$key] = $this->getById($product->id);
        }
        return $result;
    }
        
    /**
     * Get All Meta Values For Type
     * 
     * Gets all available values for the fields associated with a Meta type
     * for insertion into the dropdowns on product edit pages
     * @param int $metaTypeId
     * @return array
     */
    public function getAllMetaValuesForType($metaTypeId) {
        
        $metaFields = $this->getMetaTypeFieldIds($metaTypeId);
        
        $metaValues = $this->dao->select('*')
                                ->from(TABLE_META_FIELD_VALUES)
                                ->where('metaFieldId')->in($metaFields)
                                ->orderBy('metaValue')
                                ->fetchAll();
                
        $allMetaValues = array();
        
        foreach($metaValues as $mv) {
            
            $data = new stdClass();
            $data->id = $mv->id;
            $data->name = $mv->metaValue;
                       
            $allMetaValues[$mv->metaFieldId][] = $data;
            
        }
        
        return $allMetaValues;
        
    }
    
    /**
     * Get Product Meta Field Value ID
     * 
     * Gets a single ID of the value associated with a particular meta field
     * @param int $metaFieldId
     * @param int $productId
     * @return int
     */
    public function getProductMetaFieldValueId($metaFieldId, $productId) {
        
        $metaValue = $this->dao->select('metaFieldValueId')
                               ->from(TABLE_PRODUCTS_META)
                               ->where('productId')->eq($productId)
                               ->andWhere('metaFieldId')->eq($metaFieldId)
                               ->fetch('metaFieldValueId');
        
        return $metaValue;
        
    }
    
    /**
     * Get Product Meta Field Value
     * 
     * Gets a single Product meta field value from `c_zt_meta_field_value` by ID
     * @param int $metaFieldValueId
     * @return string
     */
    public function getProductMetaFieldValue($metaFieldValueId) {
                
        if($metaFieldValueId == null) {
            return false;
        }
        
        $metaValue = $this->dao->select('metaValue')
                               ->from(TABLE_META_FIELD_VALUES)
                               ->where('id')->eq($metaFieldValueId)
                               ->limit(1)
                               ->fetch('metaValue');
        
        return $metaValue;
        
    }
    
    /**
     * Get Product Meta Field Value ID By Value
     * 
     * Gets the value ID from `c_zt_meta_field_values` based on the field ID and
     * the value string. Reverse lookup
     * 
     * @param int $metaFieldId
     * @param string $metaValue
     * @return int
     */
    public function getProductMetaFieldValueIdByValue($metaFieldId, $metaValue) {

        $metaValue = $this->dao->select('id')
                               ->from(TABLE_META_FIELD_VALUES)
                               ->where('metaFieldId')->eq($metaFieldId)
                               ->andWhere('metaValue')->eq($metaValue)
                               ->limit(1)
                               ->fetch('id');
        
        return $metaValue;
        
    }
    
    /**
     * Update Product Default
     * 
     * Almost all products have specific default attributes. These are upserted
     * into a product as meta but are treated as non-essentials in comparison
     * to specs and fabs.
     * 
     * @param int $productID
     * @param array $meta
     */
    public function updateProductDefault($productID, $meta) {
        
        foreach($meta as $metaField => $metaValue) {
            
            $metaFieldID = (int)str_replace('default-', '', $metaField);
            $data = new stdClass();
            // Get the Value ID 
            $metaValue = (string)$metaValue;
            $metaFieldValueID = $this->getProductMetaFieldValueIdByValue($metaFieldID, $metaValue);
            // If the value ID does not exist, create a new value for that meta field
            if($metaFieldValueID == '') {
                
                $metaFieldValueID = $this->setMetaFieldValue(array('metaFieldId' => $metaFieldID, 'metaValue' => $metaValue));
                
            } 
            
            // Create the data object to be inserted/updated
            $data->productId = $productID;
            $data->metaFieldId = $metaFieldID;
            $data->metaFieldValueId = $metaFieldValueID;
            // Get the product's meta ID
            $metaID = $this->getProductMetaId($productID, $metaFieldID);
            
            if($metaID == '') {
                // Meta value does not exist, insert if the value isn't empty
                if($metaValue != '') {
                
                    $this->dao->insert(TABLE_PRODUCTS_META)
                              ->data($data)
                              ->exec();
                }
                
            } else {
                // Set the meta value ID to zero if string is empty to prevent
                // deleting and reinserting every time
                if($metaValue == '' || $metaValue == null || $metaValue == false) {
                    
                    $data->metaFieldValueId = 0;
                    
                }
                
                // Update the meta value to its new data
                $this->dao->update(TABLE_PRODUCTS_META)
                          ->data($data)
                          ->where('id')->eq($metaID)
                          ->limit(1)
                          ->exec();
                
            }
            
        }
        
    }
    
    /**
     * Set Meta Field Value
     * 
     * Inserts a new meta value for a particular meta field. Due to the nature 
     * of the data model, multiple fields can have the same value at different 
     * ID points. 
     * 
     * @param array $data
     * @return int
     */
    public function setMetaFieldValue($data) {
                        
        $metaFieldValue = new stdClass();
        $metaFieldValue->metaFieldId = trim($data['metaFieldId']);
        $metaFieldValue->metaValue = trim($data['metaValue']);
        
        // Check that this value doesn't already exist for the field
        $metaFieldValueId = $this->isMetaFieldValue($metaFieldValue->metaFieldId, $metaFieldValue->metaValue);
                        
        if(!$metaFieldValueId) {
            // Create a new value if it doesn't exist
            $this->dao->insert(TABLE_META_FIELD_VALUES)
                      ->data($metaFieldValue)
                      ->exec();
            
            return $this->dao->lastInsertID();

        } else {
            // Return the current meta value
            return $metaFieldValueId;

        }        
    }
    
    public function isMetaFieldValue($metaFieldId, $metaValue) {
        
        $metaField = $this->dao->select('id')
                        ->from(TABLE_META_FIELD_VALUES)
                        ->where('metaFieldId')->eq($metaFieldId)
                        ->andWhere('metaValue')->eq($metaValue)
                        ->limit(1)->fetch('id');
                
        if(strlen($metaField) < 1) {
            return false;
        } else {
            return $metaField;
        }
        
    }
    
    function convertMetaValue($convert, $metaFieldID, $productID, $decimals = 0, $symbol = true) {
                
        // Check that the method exists for the conversion
        if(!method_exists($this, $convert)) {
            
            return false;
            
        }
        
        // Get the meta value from the field ID and product ID
        $metaValueID = $this->getProductMetaFieldValueId($metaFieldID, $productID);
        $metaValue = $this->getMetaValueById($metaValueID);
                
        return $this->convert($convert, $metaValue, $decimals, $symbol);
                
    }
    
    /**
     * Convert
     * 
     * Strips out any non-scalar characters from the value and runs the 
     * conversion formula
     * 
     * @param string $function
     * @param string $value
     * @return string
     */    
    function convert($function, $value, $decimals, $symbol) {
        
        // Remove all non-numeric chars
        $numericValue = preg_replace("/[^0-9.]/", "", $value );
        
        // Idiot check to make sure no null values get converted
        if(method_exists($this, $function) && $numericValue > null) {

            return $this->$function($numericValue, $decimals, $symbol);

        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * mToFt
     * 
     * Converts meters to feet
     * @param float $value
     * @return string
     */
    function mToFt($value, $decimals, $symbol) {
        
        $feet = $value / 0.3048;
        if($symbol == true) {
            $sym = ' ft';
        } else {
            $sym = null;
        }
                
        return round($feet, $decimals, PHP_ROUND_HALF_UP) . $sym;
        
    }
    
    /**
     * mmToIn
     * 
     * Converts millimeters to inches
     * @param float $value
     * @param int $decimals
     * @return float
     */
    public function mmToIn($value, $decimals = 0, $symbol = true) {
        
        $inches = $value / 25.4;
        if($symbol == true) {
            $sym = ' in';
        } else {
            $sym = null;
        }
        
        return round($inches, $decimals, PHP_ROUND_HALF_UP) . $sym;
        
    }
    
    /**
     * gToOx
     * 
     * Converts grams to ounces
     * @param float $value
     * @return string
     */
    function gToOz($value, $decimals = 2, $symbol = true) {
        
        $ounces = $value * 0.03527396195;
        
        if($symbol == true) {
            $sym = ' oz';
        } else {
            $sym = null;
        }
        
        // If ounces exceeds 1lb, return lbs rather
        if($ounces >= 16) {
            return $this->ozToLbs($ounces);
        }
        
        return round($ounces, $decimals) . $sym;
        
    }
    
    function ozToLbs($value, $decimals = 2, $symbol = true) {
        
        $lbs = $value * 0.0625;
        
        if($symbol == true) {
            $sym = ' lbs';
        } else {
            $sym = null;
        }
        
        return round($lbs, $decimals) . $sym;
        
    }
    
    function kgToLbs($value, $decimals = 2, $symbol = true) {
                
        return $this->gToOz($value * 1000);
        
    }    
    
    
    /**
     * Needs Convert
     * 
     * Checks whether the field requires conversion or not
     * @param int $metaFieldID
     * @return string
     */
    function needsConvert($metaFieldID) {
                
        $result = $this->dao->select('conversion')
                            ->from(TABLE_META_FIELDS)
                            ->where('id')->eq($metaFieldID)
                            ->limit(1)
                            ->fetch();
        
        if(!is_null($result->conversion)) {
            return $result->conversion;
        } else {
            return false;
        }
        
    }
    
    /**
     * Install
     * 
     * Installs the necessary tables. Keep this updated if the table structure
     * changes
     */
    public function install() {
        $result = $this->dao->query("CREATE TABLE IF NOT EXISTS `c_{$this->config->db->prefix}meta_field` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) DEFAULT NULL,
                  `desc` text,
                  `active` tinyint(1) DEFAULT '1',
                  `order` int(11) DEFAULT NULL,
                  `default` tinyint(1) DEFAULT '0',
                  `conversion` varchar(16) DEFAULT NULL,
                  `creatorId` int(11) DEFAULT NULL,
                  `created` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
                CREATE TABLE IF NOT EXISTS `c_{$this->config->db->prefix}meta_field_value` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `metaFieldId` int(11) DEFAULT NULL,
                  `metaValue` varchar(255) DEFAULT NULL,
                  `active` tinyint(1) DEFAULT '1',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
                CREATE TABLE IF NOT EXISTS `c_{$this->config->db->prefix}meta_type` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `parentId` int(11) DEFAULT '0',
                  `name` varchar(255) DEFAULT NULL,
                  `desc` text,
                  `fields` text,
                  `active` tinyint(1) DEFAULT '1',
                  `order` int(11) DEFAULT NULL,
                  `creatorId` int(11) DEFAULT NULL,
                  `created` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
                CREATE TABLE  IF NOT EXISTS `c_{$this->config->db->prefix}product` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) DEFAULT NULL,
                  `sku` varchar(32) DEFAULT NULL,
                  `xca` varchar(32) DEFAULT NULL,
                  `barcode` varchar(32) DEFAULT NULL,
                  `description` text,
                  `metaTypeId` int(11) DEFAULT NULL,
                  `metaTypeChildId` int(11) DEFAULT NULL,
                  `brandId` int(11) DEFAULT NULL,
                  `hasChildren` tinyint(1) DEFAULT '0',
                  `parentId` int(11) DEFAULT '0',
                  `colourId` int(11) DEFAULT NULL,
                  `normalFabs` text,
                  `shoutoutFabs` text,
                  `extendedFabs` text,
                  `customMeta` text,
                  `active` tinyint(1) DEFAULT '1',
                  `complete` tinyint(1) DEFAULT '0',
                  `order` int(11) DEFAULT NULL,
                  `modifierId` int(11) DEFAULT NULL,
                  `modified` datetime DEFAULT NULL,
                  `creatorId` int(11) DEFAULT NULL,
                  `created` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
                CREATE TABLE IF NOT EXISTS `c_{$this->config->db->prefix}product_meta` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `productId` int(11) DEFAULT NULL,
                  `metaFieldId` int(11) DEFAULT NULL,
                  `metaFieldValueId` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");    
    }
    
    public function getChildMetaFieldOptions($data) {
        
        $parentId = $data['metaParentId'];
        
        $allMetaFields = $this->getMetaFields(0,0);
        
        if($parentId == 0) {
            
            $this->formatForSelect($allMetaFields);
            return $allMetaFields;
            
        } else {
        
            $parentMetaFields = $this->getMetaTypeFields($parentId);
            $filterableParentMetaFields = $this->formatForSelect($parentMetaFields);
            
            foreach($allMetaFields as $key => $allMetaField) {
                if(in_array($allMetaField->name, $filterableParentMetaFields)) {
                    
                    unset($allMetaFields[$key]);
                    
                }
            }
            
            return array_values($allMetaFields);
            
        }
                
    }
    
    public function getImages($size, $sku) {

        $sizes = array(
            'thumb' => 3,
            'medium' => 6
        );

        $url = "http://images.smdtechnologies.co.za/api?sku={$sku}&type={$sizes[$size]}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $resp = curl_exec($curl);

        if ($resp === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        return $resp;
        
    }
    
    public function renderImage($path) {
        
        $http_path = str_replace('https://', 'http://', $path);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $http_path);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $resp = curl_exec($curl);

        return $resp;
        
    }
    
    /**
     * Select Brands
     * 
     * Get ALL products from `c_zt_brands`
     * @return array
     */
    public function selectBrands($pager, $orderBy) {
                
        $result = $this->dao->select(TABLE_BRANDS . ".id, " . TABLE_BRANDS . ".name")
                            ->from(TABLE_BRANDS)
                            ->orderBy($orderBy)
                            ->page($pager)
                            ->fetchAll();
                        
        return $result;
        
    }
    
    
    /**
     * Update Brand
     * 
     * Creates or edits a brand
     * @
     */
    public function updateBrand($brandID = null) {
        // Filter the POST data
        $brand = fixer::input('post')
                    ->setDefault('created', date('Y-m-d H:i:s'))
                    ->setDefault('active', 1)
                    ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                    ->remove('uid')
                    ->remove('brandID')
                    ->get();
        
        
        if($brandID == null) {
            
            // If the ID doesn't exist, insert
            $this->dao->insert(TABLE_BRANDS)
                      ->data($brand)
                      ->exec();
            
            return $this->dao->lastInsertID();
            
        } else {
            echo 'update';
            // Update the object
            $this->dao->update(TABLE_BRANDS)
                      ->data($brand)
                      ->where('id')->eq($brandID)
                      ->limit(1)
                      ->exec();
            
            return $brandID;
            
        }
    }
    
    /**
     * Clean Tags
     * 
     * Strips a bunch of unnecessary tags out of the WYSIWYG editor. Configured 
     * inside the config.php file
     * 
     * @param object $brief
     * @return object
     */
    private function cleanTags($product) {
                        
        foreach($this->config->smdproduct->cleanFields as $field) {
            $product->$field      = trim(strip_tags(html_entity_decode($product->$field), $this->config->smdproduct->myAllowedTags));
        }
                        
        return $product;
    }
    
    public function getCategoryName($metaTypeId, $metaTypeChildId) {
        
        $parent = $this->getMetaTypeById($metaTypeId);
        $child = $this->getMetaTypeById($metaTypeChildId);
        $name = '';
        
        if(isset($parent->name)) {
            $name .= $parent->name;
        }
        
        if(isset($child->name)) {
            $name .= ', ' . $child->name;
        }
                
        return $name;
        
    }
    
    function notifyNewProduct($productID, $html) {
                
        $file = $this->loadModel('file')->saveUpload('smdproduct', $productID);
                
        $product = $this->getById($productID);
          
        $data = fixer::input('post')
                ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
                ->remove('files')
                ->remove('labels')
                ->get();
        
        // Append comment to the email text
        $html .= "\r\n\r\n{$data->comment}\r\n";
        
        foreach($file as $fileID => $f) {
            
            $attachments[] = $this->loadModel('file')->getById($fileID);
            
        }
        
        $cc = $this->loadModel('group')->getUserPairs(NEWPRODUCTGROUPID);
        $subject = "New Product: {$product->sku} {$product->name}";
        $from = 'pds@smdtechnologies.co.za';
        
        $this->my_sendMail($cc, $subject, $html, $from, $attachments);
        
        // Update the object
        $data = array('sent' => 1);
        
        $this->dao->update(TABLE_PRODUCTS)
                  ->data($data)
                  ->where('id')->eq($productID)
                  ->limit(1)
                  ->exec();
        
        exit;
        
        
        
        /*$brief = $this->getById(22);
        $teamMembers = $this->getTeamMembers(22);
        $teamAccounts = $this->my_arrayColumn($teamMembers, 'account');      
        $teamNamesEmails = $this->loadModel('user')->getRealNameAndEmails($teamAccounts);
        $emails = $this->my_arrayColumn($teamNamesEmails, 'email'); */
        // array('username' => 'username')
        
        //echo $html;
                
        
        //print_r($file);
        
        
    }
    
    private function my_sendMail($toList, $subject, $content, $from = '', $attachments = array()) {
        
        // Set From
        $from = $from;
        $fromName = 'PDS';
        
        // Filter Addresses
        $users = $this->loadModel('user')->getPairs('nodeleted|all');
        foreach($toList as $key => $to):
            if(!isset($users[trim($key)])):
                unset($toList[$key]);
            else:
                $toList[$key] = $key;
            endif;
        endforeach;
        // There must be a more efficient way of doing this
        $toList = join(',', $toList);        
        $emails = $this->loadModel('user')->getRealNameAndEmails(str_replace(' ', '', $toList . ',' . $ccList));
        $toList = explode(',', $toList);
                
        // Doing this with PHP mailer because native mailer does not support 
        // attachments
        $mail = new PHPMailer();
        
        $to = array();

        foreach($toList as $user) {

            if($emails[$user]->email != '') {
            
                $to[] = $emails[$user]->email;
                $mail->addAddress($emails[$user]->email);

            }
        }

        $to = join(';', $to);
        
        $mail->setFrom($from, $fromName);
        $mail->Subject  = $subject;
        $mail->Body     = $content;
        
        foreach($attachments as $attachment) {
            $mail->addAttachment('../../www' . $attachment->webPath, $attachment->title);
        }
        
        // Do a DB update
        
        echo json_encode($mail->send());
               
    }
    
    /**
     * Get team members.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTeamMembers($briefID)
    {
        
        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$briefID)
            ->andWhere('t1.type')->eq('brief')
            ->fetchAll('account');
        
    }
    
    function my_arrayColumn($array,$column_name)
    {

        return array_map(function($element) use($column_name){return $element->$column_name;}, $array);

    }

    function deleteProduct($productID) {

        return $this->dao->delete()
                         ->from(TABLE_PRODUCTS)
                         ->where('id')->eq($productID)->exec();

    }

    function deleteProductMeta($productID) {

        return $this->dao->delete()
                         ->from(TABLE_PRODUCTS_META)
                         ->where('productId')->eq($productID)->exec();

    }

    function updateP($product) {

        if(isset($product->id)) {
            // Product has an ID, so update
            $this->dao->update(TABLE_PRODUCTS)
                      ->data($product)
                      ->where('id')->eq($product->id)
                      ->limit(1)
                      ->exec();                      
        } else {
            // Product does not have an ID, so insert
            $this->dao->insert(TABLE_PRODUCTS)
                      ->data($product)
                      ->exec();
        }

    }

    /**
     * Get Image
     *
     * Gets a product image by size, sku and key vai the images api
     * @param String $size
     * @param String $sku
     * @param Int $key
     * @return void
     */
     public function getImage($size, $sku, $key) {
        // Clean SKU of any odd characters
        $sku = preg_replace('[^A-Za-z0-9-]', '-', $sku);      
        $url = "https://images.smdtechnologies.co.za/api/{$size}/{$sku}/{$key}.jpg";
        return $url;
    }

}