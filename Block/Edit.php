<?php

namespace Syncit\Userproducts\Block;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class Edit extends \Magento\Framework\View\Element\Template
{
	
	protected $storeManager;
	protected $formKeyValidator;
    protected $messageManager;
	protected $_filter;
	
	protected $_fileUploaderFactory;
	protected $_filesystem;
	
	private $serializer;
	protected $productFactory;
	protected $productRepository;
	protected $stockRegistry;
	public $user_id;
	protected $_sanitize;
	protected $_request;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
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
		 \Magento\Framework\Escaper $escapeHtml,
		 \Magento\Framework\App\Request\Http $request

	)
	{
		
		
$this->storeManager = $storeManager;
$this->formKeyValidator = $formKeyValidator;
$this->_filter = $filter;
$this->_fileUploaderFactory = $fileUploaderFactory;
$this->_filesystem = $filesystem;
$this->serializer = $serializer;
$this->productFactory = $productFactory;
$this->productRepository = $productRepository;
$this->stockRegistry = $stockRegistry;
$this->user_id = $session;
$this->_sanitize = $escapeHtml;
$this->_request= $request;

parent::__construct($context);
	}

	public function editProduct()
	{
		return __('Edit product');
	}
	
	public function getId(){
		
		$urlId = $this->_request->getParam('id');
		return $urlId;
	}

	public function getProduct($fieldName){

		$product = $this->productFactory->create()->load($this->getId());
		 $name = $product->getData($fieldName);
		 return $name;
	}
     public function getFormAction()
     {    
	 	return $this->getUrl('userproducts/index/update', ['_secure' => true]);
		
	 }



}