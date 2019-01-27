<?php


namespace Syncit\Userproducts\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Catalog\Model\Product\Gallery\Entry;

class Delete extends \Magento\Framework\App\Action\Action
{

protected $storeManager;
protected $formKeyValidator;
//protected $_productFactory;
protected $_filter;
protected $_storeManager;
protected $_fileUploaderFactory;
protected $_filesystem;
protected $_errors= null;
private $serializer;
protected $_file;
protected $productFactory;
protected $productRepository;
protected $stockRegistry;
protected $_registry;

public function __construct(       
    \Magento\Framework\App\Action\Context $context,
//	\Syncit\Userproducts\Model\ProductFactory $productFactory,
	\Magento\Ui\Component\MassAction\Filter $filter,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
    \Magento\Framework\Filesystem $filesystem,
     SerializerInterface $serializer,
     \Magento\Framework\Filesystem\Driver\File $file,
     \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory, 
     \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, 
     \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
     \Magento\Framework\Registry $registry


)
{

$this->storeManager = $storeManager;
$this->formKeyValidator = $formKeyValidator;
$this->messageManager = $context->getMessageManager();
//$this->_productFactory = $productFactory;
$this->_filter = $filter;
$this->_storeManager = $storeManager;
$this->_fileUploaderFactory = $fileUploaderFactory;
$this->_filesystem = $filesystem;
$this->serializer = $serializer;
$this->_file = $file;
$this->productFactory = $productFactory;
$this->productRepository = $productRepository;
$this->stockRegistry = $stockRegistry;
$this->_registry = $registry;

parent::__construct($context);
}
public function execute()
{
    $resultRedirect = $this->resultRedirectFactory->create();
    //Get image path from media/images folder
    $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('images/');
    $corePath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('catalog/product');
    $productId = $this->getRequest()->getParam('id');

    // Load image name from DB
    $product = $this->productFactory->create()->load($productId);
    
    $imageName = $product->getData('image');
    $this->_registry->unregister('isSecureArea');
    $this->_registry->register('isSecureArea', true);

    $coreImgName =$imageName;

    // Check if file exists 
    if (!empty($imageName)) {
        $imgArray =explode('/',$imageName);
        $imageName=$imgArray[3];
       
        $this->_file->deleteFile($path.$imageName);
        $this->_file->deleteFile($corePath.$coreImgName); 
                      
    }      			
    $product->delete();
    
    $this->messageManager->addSuccess(__('Proizvod je uspešno obrisan'));  
    return $resultRedirect->setPath('*/*/index');

}
}