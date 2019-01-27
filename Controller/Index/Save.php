<?php


namespace Syncit\Userproducts\Controller\Index;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;


class Save extends \Magento\Framework\App\Action\Action
{

protected $storeManager;
protected $formKeyValidator;
protected $_filter;
protected $_storeManager;
protected $_fileUploaderFactory;
protected $_filesystem;
protected $_errors= null;
private $serializer;
protected $productFactory;
protected $productRepository;
protected $stockRegistry;
public $user_id;
protected $_sanitize;

public function __construct(       
    \Magento\Framework\App\Action\Context $context,
	\Magento\Ui\Component\MassAction\Filter $filter,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
    \Magento\Framework\Filesystem $filesystem,
     SerializerInterface $serializer,

     \Magento\Catalog\Api\Data\ProductInterfaceFactory $productFactory, 
     \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, 
     \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
     \Magento\Customer\Model\Session $session,
     \Magento\Framework\Escaper $escapeHtml

         
)
{

$this->storeManager = $storeManager;
$this->formKeyValidator = $formKeyValidator;
$this->messageManager = $context->getMessageManager();
$this->_productFactory = $productFactory;
$this->_filter = $filter;
$this->_storeManager = $storeManager;
$this->_fileUploaderFactory = $fileUploaderFactory;
$this->_filesystem = $filesystem;
$this->serializer = $serializer;

$this->productFactory = $productFactory;
$this->productRepository = $productRepository;
$this->stockRegistry = $stockRegistry;
$this->user_id = $session;
$this->_sanitize = $escapeHtml;
parent::__construct($context);
}
public function execute()
{
//sanitize with allowed tags
$availableTags="<p>,</p>,<h1>,</h1>,<b>,</b>,<h2>,</h2>,<h3>,</h3>,<pre>,</pre>,<span>,</span>,
<blockquote>,</blockquote>,<ul>,</ul>,<ol>,</ol>,<li>,</li>,<s>,</s>,<em>,</em>,<a>,</a>,<table>,
</table>,<tbody>,</tbody>,<tr>,</tr>,<td>,</td>";

$resultRedirect = $this->resultRedirectFactory->create();
$name =$this->_sanitize->escapeHtml($this->getRequest()->getParam('name'));
$price = $this->getRequest()->getParam('price');
$content = $this->getRequest()->getParam('content');
$image = $this->getRequest()->getFiles()->image['name'];
$content = strip_tags($content,$availableTags);
// var_dump($content);
// die();
$userId=$this->user_id->getCustomerId();
$sku = time();
$product = $this->productFactory->create();

if(!empty($image)){
    $imgArray=explode('.',$image);
    $fileName=$imgArray[0];
    $fileExt=$imgArray[1];
    $image = $fileName . '_' . time() . '.' . $fileExt; 
    $stripped = preg_replace('/\s/', '_', $image);
    $image = $stripped;
    // upload in images folder
    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png','jp2']);
    $uploader->setAllowRenameFiles(true);
    $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('images/');
    $result = $uploader->save($path,$image);
}
    $product->setSku($sku); // Set your sku here
    $product->setName($name); // Name of Product
    $product->setDescription($content);
    $product->setAttributeSetId(11); // Attribute set id
    $product->setStatus(1); // Status on product enabled/ disabled 1/0
    $product->setWeight(10); // weight of product
    $product->setVisibility(4); // visibilty of product (catalog / search / catalog, search / Not visible individually)
    $product->setTaxClassId(0); // Tax class id
    $product->setTypeId('simple'); // type of product (simple/virtual/downloadable/configurable)
    $product->setPrice($price); // price of product

    $product->setData('added_by_customer_id',$userId);
    $product->save();
     if(!empty($image)){
  $imagePath =  $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('images/').$image; // path of the image
  $product->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);
  $product->save();
     } 


$this->messageManager->addSuccess(__('Uspešno ste dodali proizvod.'));  
return $resultRedirect->setPath('*/*/index');
}
}