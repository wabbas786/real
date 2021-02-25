<?php

namespace TM\Tuffnells\Cron;

use Exception;


class UpdateTuffnells
{

    protected $_logger;

    protected $_productCollectionFactory;

    protected $_resourceConnection;


    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->_logger = $logger;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_resourceConnection = $resourceConnection;
    }

    /**Update tuffnells attribute */
	public function execute()
	{
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();  
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToFilter('custom_height',['gteq' => '1000']);
        $productCollection->addAttributeToFilter('custom_width',['gteq' => '700']);
        $productCollection->addAttributeToFilter('depth',['gteq' => '600']);

        $collection = $productCollection->addAttributeToSelect('*');
        
        foreach ($collection as $product){
    
            $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
            $productObj = $productRepository->get($product->getSku());
            if( intval($productObj->getData('depth')) >= 600 )
            {
                $productObj->setData('tuffnells_attr','1');
                $productObj->setData('dpd_attr','0');
                $productObj->getResource()->saveAttribute($productObj, 'tuffnells_attr');
                $productObj->getResource()->saveAttribute($productObj, 'dpd_attr');
            }
           
        }

        return "Tufnells Cron Job Successfully Done";


    }
}
