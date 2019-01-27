<?php
namespace Syncit\Userproducts\Block;

use Magento\Framework\App\Filesystem\DirectoryList;
 
class Productlist extends \Magento\Framework\View\Element\Template
{
    /**
* {@inheritdoc}
*/
// User id 

    /**
     * @var Session
     */
     public $user_id;
     protected $productFactory;
     protected $_productCollectionFactory;
     protected $_storeManager;
     protected $_filesystem;
     protected $_sanitize;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $session,
        array $data,
        \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,       
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Escaper $escapeHtml
    )
    {
        parent::__construct($context, $data);
        $this->user_id = $session;
        $this->productFactory = $productFactory;
        $this->_productCollectionFactory =  $productCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_filesystem = $filesystem;
        $this->_sanitize = $escapeHtml;
    }

protected function _prepareLayout(){
  
     parent::_prepareLayout();
     $this->pageConfig->getTitle()->set(__('Products list'));
     $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
       
     if ($pageMainTitle) {
         $pageMainTitle->setPageTitle(__('Products list'));
     }
}
     public function getAllProducts()
    {
        //get product collection
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToFilter('added_by_customer_id',$this->getUserId());
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(100); // fetching 100 products
        return $collection;
        
    }

    public function getImagePath(){
		
		$imagePath = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $imagePath.'catalog/product'; 
		
	}

   public function getUserId(){
       return $this->user_id->getCustomerId();
   }

public function getFormAction()
{    
    return $this->getUrl('userproducts/index/delete', ['_secure' => true]);
   
    
}
public function clearUrl($param){
    return $this->_sanitize->escapeUrl($param);
}
}