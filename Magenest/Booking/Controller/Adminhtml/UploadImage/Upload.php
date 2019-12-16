<?php

namespace Magenest\Booking\Controller\Adminhtml\UploadImage;

use Magento\Framework\Controller\ResultFactory;
use Magenest\Booking\Model\ImageUploader;

/**
 * Class Upload
 */
class Upload extends \Magento\Backend\App\Action
{
    protected $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ImageUploader $imageUploader
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Booking::banner_read') ||
            $this->_authorization->isAllowed('Magenest_Booking::banner_create');
    }

    /**
     * Upload file controller action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    public function execute()
    {
        $_files = $_FILES['product'];
        foreach ($_files as $key => $fileItems) {
            foreach ($fileItems['options'] as $optionsKey => $optionsValues) {
                foreach ($optionsValues['values'] as $optionKey => $optionValue) {
                    $_FILES['product'][$key] = $_FILES['product'][$key]['options'][$optionsKey]['values'][$optionKey]['image'];
                }
            }
        }
        $imageId = 'product';
        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);

//            $result['cookie'] = [
//                'name' => $this->_getSession()->getName(),
//                'value' => $this->_getSession()->getSessionId(),
//                'lifetime' => $this->_getSession()->getCookieLifetime(),
//                'path' => $this->_getSession()->getCookiePath(),
//                'domain' => $this->_getSession()->getCookieDomain(),
//            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
