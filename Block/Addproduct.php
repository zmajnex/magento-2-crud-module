<?php


namespace Syncit\Userproducts\Block;

//use Syncit\Userproducts\Block\Productlist; 


class Addproduct extends \Magento\Framework\View\Element\Template
{
 /**
     * @var Session
     */
    public $user_id;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $session,
        array $data
    )
    {
        parent::__construct($context, $data);
        $this->user_id = $session;
    }

protected function _prepareLayout()
{
parent::_prepareLayout();
$this->pageConfig->getTitle()->set(__('Dodajte novi proizvod'));
$pageMainTitle = $this->getLayout()->getBlock('page.main.title');
if ($pageMainTitle) {
$pageMainTitle->setPageTitle(__('Dodajte novi proizvod'));
}
}
    public function showUserForm()
    {
        return 'Forma za dodavanje proizvoda';
    }

   public function setUserId(){
    return $this->user_id->getCustomerId();
   }   
public function getFormAction() {
 return $this->getUrl('userproducts/index/save', ['_secure' => true]);
}
}