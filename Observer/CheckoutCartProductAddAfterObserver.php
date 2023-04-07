<?php

namespace Mage\SpecialNote\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;

class CheckoutCartProductAddAfterObserver implements ObserverInterface
{

    protected $_request;
    private $serializer;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request, 
        SerializerInterface $serializer
    ){
            $this->_request = $request;
            $this->serializer = $serializer;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        /* @var \Magento\Quote\Model\Quote\Item $item */
        $item = $observer->getQuoteItem();

        $additionalOptions = array();

        if ($additionalOption = $item->getOptionByCode('additional_options')){
            $additionalOptions = (array) $this->serializer->unserialize($additionalOption->getValue());
        }

        $post = $this->_request->getParam('spclnote');

        $additionalOptions[] = [
            'label' => "Special Note:",
            'value' => $post
        ];
        if(count($additionalOptions) > 0){
            $item->addOption(array(
                'code' => 'additional_options',
                'value' => $this->serializer->serialize($additionalOptions)
            ));
        }

    }

}
