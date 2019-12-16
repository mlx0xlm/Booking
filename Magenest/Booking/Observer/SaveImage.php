<?php

namespace Magenest\Booking\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magenest\Booking\Model\ImageUploader;

class SaveImage implements ObserverInterface
{
    protected $imageUploader, $storeManager;
    protected $_options;
    protected $_customerRepositoryInterface;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ImageUploader $imageUploader,
        \Magento\Catalog\Model\Product\Option $options,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    )
    {
        $this->storeManager = $storeManager;
        $this->imageUploader = $imageUploader;
        $this->_options = $options;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $basePath= $this->imageUploader->getBasePath();
        $product = $observer->getEvent()->getProduct();
        $productOptions = $product->getOptions();
        foreach ($productOptions as $key => $productOption) {
            if (isset($productOption->getData()['values'])) {
                $values = $productOption->getData()['values'];
                foreach ($values as $keys => $value) {
                    if (isset($value['image'])) {
                        $image = $value['image'];
                        if ($image[0]['name']) {
                            $this->imageUploader->moveFileFromTmp($image[0]['name']);
                            $url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $basePath . $image[0]['name'];
                            $image[0]['url'] = $url;
                        }
                        $values[$keys]['image'] = json_encode($image);
                    } else {
                        $values[$keys]['image'] = '';
                    }
                }
                $productOption->setData('values', $values);
            }
        }
    }
}