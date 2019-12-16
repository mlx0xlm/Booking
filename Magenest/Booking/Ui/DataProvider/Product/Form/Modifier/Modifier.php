<?php

namespace Magenest\Booking\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;
use \Magento\Catalog\Model\Locator\LocatorInterface;

class Modifier extends AbstractModifier
{
    protected $locator;
    public function __construct(LocatorInterface $locator)
    {
        $this->locator = $locator;
    }
    public function modifyMeta(array $meta)
    {
        $a=1;
        return $meta;
    }

    public function modifyData(array $data)
    {
        $ListOption   = $this->locator->getProduct()->getOptions();
        if(isset($ListOption)){
        foreach ($ListOption as $options)
        {
            $listOptions= $options->getValues();
            if(isset($listOptions))
            {
                foreach ($listOptions as $key => $optionValues)
                {
                    $image=$optionValues->getData()['image'];
                    $listOptions[$key]['image']=json_decode($image);
                }
            }

        }
        }
        return $data;
    }
}