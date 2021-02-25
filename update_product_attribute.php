<?php
use Magento\Framework\App\Bootstrap;

require '../../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('global');

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$objectManager->get('Magento\Framework\Registry')->register('isSecureArea', true);

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create();
$collection = $productCollection->addAttributeToSelect('*');
foreach ($collection as $product){
    
    $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
    $productObj = $productRepository->get($product->getSku());
    $productObj->setData('tuffnells_attr','0');
    $productObj->getResource()->saveAttribute($productObj, 'tuffnells_attr'); 
}
