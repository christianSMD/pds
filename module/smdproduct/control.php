<?php

class smdproduct extends control {
    
    public $smdproducts = array();
    
    public function __construct() {
        
        parent::__construct();
        
        $this->smdproduct->install();
        
        if(dao::isError()) die(js::error(dao::getError()));
        
    }
    
    /**
     * Index
     *
     * Main view for all products, shows 20 records per page by default. 
     * Table is searchable and sortable
     *
     * @param boolean $search
     * @param string $orderBy
     * @param integer $recTotal
     * @param integer $recPerPage
     * @param integer $pageID
     * @return void
     */
     public function index($search = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1) {
        // Create pagination manager
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        // Process search data if POSTed
        if($_POST) {
            // Clean the submitted data
            $searchData = fixer::input('post')
                    ->remove('submitSearch')
                    ->get(); 
            // Set the search string
            $search = $searchData->productSearch;            
        }

        // Get products that match the page parameters
        $this->view->products = $this->smdproduct->select($pager, $orderBy, $search);

        // Loop through all products to set children where necessary and define product images
        foreach($this->view->products as $product) {
            // If the product has children...
            if($product->hasChildren == 1) {
                // ...Get the children and reference the image by first child's SKU
                $product->image = $this->smdproduct->getImage('thumb', reset($product->children)->sku, 1);
            } else {
                // ...Get the image by SKU
                $product->image = $this->smdproduct->getImage('thumb', $product->sku, 1);
            }            
        }

        // Set up view parameters to be referenced in the display() function
        $this->view->title      = $this->lang->smdproduct->index;
        $this->view->orderBy    = $orderBy;
        $this->view->search     = $search;
        $this->view->pager      = $pager;
        // Render the view
        $this->display();
    }
    
    public function search($orderBy = 'order_asc', $recTotal = 0, $recPerPage = 50, $pageID = 1) {
        
        $this->smdproduct->select();
        
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        $this->view->title      = $this->lang->smdproduct->index;
        $this->view->products   = $this->smdproduct->getProducts($recPerPage, $orderBy, $pager);
        foreach($this->view->products as $product) {
            $product->children = $this->smdproduct->getChildren($product->id);
            if($product->hasChildren == 0) {
                
                $product->images = $this->smdproduct->getImages('thumb', $product->sku);

            } else {
                
                $product->images = $this->smdproduct->getImages('thumb', reset($product->children)->sku);
            }

        }
                
        $this->view->pager      = $pager;
                
        $this->display();
                
    }
    
    public function createProduct() {
        
        if($_POST) {
            
            $productID = $this->smdproduct->updateProduct();
                        
            if(dao::isError()) die(js::error(dao::getError()));
            
            $this->loadModel('action')->create('smdproduct', $productID, 'created');
            $url = $this->createLink('smdproduct', 'edit', "productID=$productID");            
            
            // header('Location: '.$url);
            die(js::reload('parent.parent'));
            
        }
        
        $metaTypes = $this->smdproduct->getMetaTypes();
        
        $metaTypes = array_filter($metaTypes, function ($var) {
            if($var->parentId == 0) {
                return true;
            }
            
            return false;
        });
        
        
        $this->view->brands     = $this->smdproduct->formatForSelect($this->smdproduct->getBrands());
        $this->view->metaTypes  = $this->smdproduct->formatForSelect($metaTypes);
        $this->view->colours    = $this->smdproduct->formatForSelect($this->smdproduct->getMetaFieldValues(COLOURID));
                                
        $this->display();
        
    }
    
    /**
     * Meta Fields
     * 
     * View and manage Meta Fields
     * @param int $metaID
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     */
    public function metaFields($metaFieldID = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1) {
        
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
                
        $this->view->title      = $this->lang->smdproduct->metaFields;
        $this->view->position[] = $this->lang->smdproduct->metaFields;
        $this->view->metaFields = $this->smdproduct->getMetaFields(50, $orderBy, $pager);
        $this->view->metaField  = $this->smdproduct->getMetaFieldById($metaFieldID);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        
        $this->display();
        
    }
    
    /**
     * Meta Types
     * 
     * View and Manage Meta Types
     */
    public function metaTypes($metaTypeID = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1) {
        
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
                
        $this->view->title      = $this->lang->smdproduct->metaTypes;
        $this->view->position[] = $this->lang->smdproduct->metaTypes;
        $this->view->metaTypes  = $this->smdproduct->getMetaTypes(50, $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        
        $this->display();
        
    }
    
    
    /**
     * Edit Meta Field
     * 
     * Creates or edits an existing Meta Field.
     * @param int $metaFieldID
     */
    public function editMetaField($metaFieldID = null) {
        
        
        if($_POST) {
            
            $this->smdproduct->updateMetaField($metaFieldID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $this->view->metaField = $this->smdproduct->getMetaFieldById($metaFieldID);
                
        $this->display();
        
    }
    
    public function editMetaType($metaTypeID = null) {
        
        if($_POST) {
            
            $this->smdproduct->updateMetaType($metaTypeID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
        }
        
        $metaTypes = $this->smdproduct->getMetaTypes();
        
        $metaTypes = array_filter($metaTypes, function ($var) {
            if($var->parentId == 0) {
                return true;
            }
            
            return false;
        });
        
        $this->view->metaTypes  = $this->smdproduct->formatForSelect($metaTypes);
        $this->view->metaFields = $this->smdproduct->formatForSelect($this->smdproduct->getMetaFields(0,0));
        $this->view->metaType   = $this->smdproduct->getMetaTypeById($metaTypeID);
                
        $this->display();
        
    }
    
    public function view($productID = null) {
        // Pre-select data
        $product = $this->smdproduct->getById($productID);
        $metaTypeFields         = $this->smdproduct->getMetaTypeFields($product->metaTypeId);
        $metaTypeChildFields    = $this->smdproduct->getMetaTypeFields($product->metaTypeChildId);
        $metaFieldValues        = $this->smdproduct->getAllMetaValuesForType($product->metaTypeId);
        $metaFieldChildValues   = $this->smdproduct->getAllMetaValuesForType($product->metaTypeChildId);
        
        $this->view->title              = $product->name;
        //$product->meta              = $this->smdproduct->getProductMeta($productID, $product->metaTypeId);
        $this->view->metaTypes          = $this->smdproduct->formatForSelect($this->smdproduct->getMetaTypes());
        $this->view->metaTypeFields     = array_merge($metaTypeFields, $metaTypeChildFields);
        $this->view->metaFieldValues    = $metaFieldValues + $metaFieldChildValues;
        
        // Sort meta fields
        usort($this->view->metaTypeFields, array($this, 'cmp'));        
        $this->view->metaTypeFields     = $this->smdproduct->formatForSelect($this->view->metaTypeFields);
        
        $this->view->product            = $product;
        $this->view->actions            = $this->loadModel('action')->getList('smdproduct', $productID);
                
        // 
        if($product->hasChildren == 0) {
            $this->view->images             = $this->smdproduct->getImages('thumb', $product->sku);
        } else {
            $images = array();
            
            foreach($product->children as $child) {
                $childImages = (array)json_decode($this->smdproduct->getImages('thumb', $child->sku));
                
                foreach($childImages as $img) {
                    $images[] = $img;
                }
                
            }
                        
            $this->view->images = json_encode($images);
                        
        }
                
        $this->display();
        
    }
    
    function cmp($a, $b, $key = 'order')
    {
        return strcmp($a->$key, $b->$key);
    }

    
    public function edit($productID) {
                
        if(!empty($_POST))
        {
            $productID = $this->smdproduct->updateProduct($productID);
            
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            
            $actionID = $this->loadModel('action')->create('smdproduct', $productID, 'edited');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "productID=$productID")));
            
        }
        
        $product = $this->smdproduct->getById($productID);
        $metaTypeFields = $this->smdproduct->getMetaTypeFields($product->metaTypeId);
        $metaTypeChildFields = $this->smdproduct->getMetaTypeFields($product->metaTypeChildId);
        $metaFieldValues = $this->smdproduct->getAllMetaValuesForType($product->metaTypeId);
        $metaFieldChildValues = $this->smdproduct->getAllMetaValuesForType($product->metaTypeChildId);
        
        $metaFieldDescs                 = $this->smdproduct->getAllMetaFieldDescriptions();
        
        $this->view->title              = $this->lang->smdproduct->edit;
        $this->view->product            = $product;
        $this->view->metaTypes          = $this->smdproduct->formatForSelect($this->smdproduct->getMetaTypes());
        $this->view->metaTypeFields     = array_merge($metaTypeFields, $metaTypeChildFields);
        $this->view->metaFieldValues    = $metaFieldValues + $metaFieldChildValues; // lmao,how is php even real
        $this->view->brands             = $this->smdproduct->formatForSelect($this->smdproduct->getBrands());
        $this->view->colours            = $this->smdproduct->formatForSelect($this->smdproduct->getMetaFieldValues(COLOURID));
        $this->view->metaFieldDescs     = $this->smdproduct->formatForSelect($metaFieldDescs);
        
        // Sort meta fields 
        usort($this->view->metaTypeFields, array($this, 'cmp'));        
        $this->view->metaTypeFields     = $this->smdproduct->formatForSelect($this->view->metaTypeFields);
                                                
        $this->display();
    }
    
    public function ajax() {
        
        $response = new stdClass();
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
                
        if($requestMethod == 'post') {
           $modelMethod = filter_input(INPUT_POST, 'method');
           $data = $_POST;
        } elseif($requestMethod == 'get') {
           // GET requests are busted for whatever reason; have to break REST 
           // patterns to get done
           $modelMethod = $this->get->method;
           $data = $_GET; 
        } else {
            http_response_code(405);
            echo json_encode('Method not allowed');
            exit;
        }
                
        $response->data = $this->smdproduct->$modelMethod($data);
        $response->status = 200;
        
        if(dao::isError()) $response->status = 400;
        
        http_response_code($response->status);
        echo json_encode($response->data);
        exit;
        
    }
    
    public function img() {
        
        $path = $_POST['path'];
        echo json_encode($this->smdproduct->renderImage($path));
        
    }
    
    public function brands($orderBy = 'name_asc', $recTotal = 0, $recPerPage = 50, $pageID = 1) {
        
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        
        $brands                 = $this->smdproduct->selectBrands($pager, $orderBy);
        
        $this->view->title      = $this->lang->smdproduct->brands;
        $this->view->brands     = $brands;
        $this->view->pager      = $pager;
                
        $this->display();
        
    }
    
    /**
     * Edit Brand
     * 
     * Creates or edits an existing Brand.
     * @param int $brandID
     */
    public function editBrand($brandID = null) {
        
        
        if($_POST) {
            
            $this->smdproduct->updateBrand($brandID);
            
            if(dao::isError()) die(js::error(dao::getError()));
            
            die(js::reload('parent.parent'));
            
        }
        
        $this->view->brand = $this->smdproduct->getBrandById($brandID);
                
        $this->display();
        
    }
    
    public function download($productID = null, $outputStream = true) {
        
        $product                        = $this->smdproduct->getById($productID);
        $metaTypeFields                 = $this->smdproduct->getMetaTypeFields($product->metaTypeId);
        $metaTypeChildFields            = $this->smdproduct->getMetaTypeFields($product->metaTypeChildId);
        $metaFieldValues                = $this->smdproduct->getAllMetaValuesForType($product->metaTypeId);
        $metaFieldChildValues           = $this->smdproduct->getAllMetaValuesForType($product->metaTypeChildId);
        
        $this->view->title              = $product->name;
        $this->view->metaTypes          = $this->smdproduct->formatForSelect($this->smdproduct->getMetaTypes());
        $this->view->metaTypeFields     = array_merge($metaTypeFields, $metaTypeChildFields);
        $this->view->metaFieldValues    = $metaFieldValues + $metaFieldChildValues;
        $this->view->product            = $product;
        $this->view->category           = $this->smdproduct->getCategoryName($product->metaTypeId, $product->metaTypeChildId);
        
        // Sort meta fields 
        usort($this->view->metaTypeFields, array($this, 'cmp'));        
        $this->view->metaTypeFields     = $this->smdproduct->formatForSelect($this->view->metaTypeFields);
        
        $output = $this->fetch();
        
        if($outputStream == true) {
        
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=$product->sku.txt");

            echo $output;

            exit;
            
        } else {
            return $output;
        }
    }
    
    public function sendtoteam($productID) {
                
        $product                        = $this->smdproduct->getById($productID);
        $metaTypeFields                 = $this->smdproduct->getMetaTypeFields($product->metaTypeId);
        $metaTypeChildFields            = $this->smdproduct->getMetaTypeFields($product->metaTypeChildId);
        $metaFieldValues                = $this->smdproduct->getAllMetaValuesForType($product->metaTypeId);
        $metaFieldChildValues           = $this->smdproduct->getAllMetaValuesForType($product->metaTypeChildId);
                        
        $this->view->title              = $product->name;
        $this->view->metaTypes          = $this->smdproduct->formatForSelect($this->smdproduct->getMetaTypes());
        $this->view->metaTypeFields     = array_merge($metaTypeFields, $metaTypeChildFields);
        $this->view->metaFieldValues    = $metaFieldValues + $metaFieldChildValues;
        $this->view->product            = $product;
        $this->view->category           = $this->smdproduct->getCategoryName($product->metaTypeId, $product->metaTypeChildId);
        
        // Sort meta fields 
        usort($this->view->metaTypeFields, array($this, 'cmp'));        
        $this->view->metaTypeFields     = $this->smdproduct->formatForSelect($this->view->metaTypeFields);
        
        // Fixed spec bug where spec names weren't being loaded properly
        $metaTypeFields                 = $this->view->metaTypeFields;
        
        if(!empty($_POST))
        {
            $modulePath = $this->app->getModulePath($appName = '', 'smdproduct');
            $viewFile = $modulePath . 'view/download.html.php';

            ob_start();
            include $viewFile;
            $output = ob_get_contents();
            ob_end_clean();
            
            $productID = $this->smdproduct->notifyNewProduct($productID, $output);
            
            if(dao::isError()) die(js::error(dao::getError()));
                        
            $this->loadModel('action')->create('smdproduct', $product->id, 'sent');
                        
            die(js::reload('parent.parent'));
            
        } 
        
        $this->display();        
                
    }

    /**
     * Delete
     * 
     * Delete's a child product from a parent. Deletes the child object from
     * the c_product table and any meta values it may have
     * @param int $productID
     * @return void
     */
    public function deletechild($productID) {

        $product = $this->smdproduct->getById($productID);
        $parentID = $product->parentId;
        
        if($parentID == 0) {
            $parentID = $productID;
        }

        $this->smdproduct->deleteProduct($productID);
        $this->smdproduct->deleteProductMeta($productID);

        if(dao::isError()) die(js::error(dao::getError()));

        $this->loadModel('action')->create('smdproduct', $parentID, $product->sku . ' deleted');

        header('Location: ' . helper::createLink('smdproduct', 'edit', "productID=$parentID"));

    }

    /**
     * Add Child
     * 
     * Interface to add a new child product to a particular parent SKU
     *
     * @param int $productID
     * @return void
     */
    public function addchild($productID) {

        $product = $this->smdproduct->getById($productID);
        $colours = $this->smdproduct->getMetaFieldValues(25);

        if(!empty($_POST)) {

            $child = new stdClass();
            $child->colourId = filter_input(INPUT_POST, 'colourId');
            $child->barcode = filter_input(INPUT_POST, 'barcode');
            $child->sku = filter_input(INPUT_POST, 'sku');
            $child->parentId = $productID;
            $child->created = date('Y-m-d H:i:s');
            $child->creatorId = $this->app->user->id;

            $this->smdproduct->createChild($child);

            if(dao::isError()) die(js::error(dao::getError()));

            $this->loadModel('action')->create('smdproduct', $productID, $child->sku . ' created');

            die(js::reload('parent.parent'));
        }

        usort($colours, function($a, $b) {
            return strcmp($a->name, $b->name);
        });

        $this->view->sku = $product->sku;
        $this->view->name = $product->name;
        $this->view->colours = $this->smdproduct->formatForSelect($colours); // 25 is the key for colours
        
        $this->display();

    }

    public function deleteproduct($productID) {
        // Get the product and any associated children
        $product[] = $this->smdproduct->getById($productID);
        $children = $this->smdproduct->getChildren($productID);
        // Merge arrays for easy looping
        $products = array_merge($product, $children);
        $product_ids = [];
        foreach($products as $product) {
            $this->smdproduct->deleteProduct($product->id);
            $this->smdproduct->deleteProductMeta($product->id);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('smdproduct', $productID, $product->sku . ' deleted');
        }
        header('Location: ' . helper::createLink('smdproduct', 'index'));
    }

    public function enablechildren($productID) {
        $product = $this->smdproduct->getById($productID);
        if($product->hasChildren == 1 || (isset($product->children) && count($product->children) > 0)) {
            js::error('This product already has children');
            $this->view($productID);
            exit();
        }
        $child = new stdClass();
        $child->sku = $product->sku;
        $child->barcode = $product->barcode;
        $child->parentId = $product->id;
        $child->colourId = $product->colourId;

        $updatedProduct = new stdClass();
        $updatedProduct->id = $product->id;
        $updatedProduct->sku = null;
        $updatedProduct->barcode = '';
        $updatedProduct->parentId = '';
        $updatedProduct->colourId = '';
        $updatedProduct->hasChildren = 1;
        // Custom Product Update function, I don't have the energy to
        // refactor the updateProduct() function to utilize either POST
        // or parameter data
        $this->smdproduct->updateP($updatedProduct);
        $this->smdproduct->updateP($child);

        header('Location: ' . helper::createLink('smdproduct', 'view', "productID=$product->id"));
    }

    public function disablechildren($productID) {

        $product = $this->smdproduct->getById($productID);
        if($product->hasChildren == 0) {
            js::error('This product has no children');
            $this->view($productID);
            exit();
        }
        
        

    }
}