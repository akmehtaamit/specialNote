<?php
namespace Mage\SpecialNote\Observer\Sales;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $order= $observer->getData('order');
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            $options = $item->getProductOptions();       
            if (isset($options['additional_options']) && !empty($options['additional_options'])) {        
                foreach ($options['additional_options'] as $option) { 
                    $order->setOrderSpclnote($option['value']);
                    $order->save();
                }
            }
        }
        
    }
}