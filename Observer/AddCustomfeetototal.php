<?php
/**
 * Aimsinfosoft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the aimsinfosoft.com license that is
 * available through the world-wide-web at this URL:
 * https://www.aimsinfosoft.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_InsuranceFees
 * @copyright   Copyright (c) Aimsinfosoft (https://www.aimsinfosoft.com)
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */

namespace Aimsinfosoft\InsuranceFees\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Checkout\Model\Session;
use Aimsinfosoft\InsuranceFees\Helper\Data;

class AddCustomfeetototal implements ObserverInterface
{
    protected $checkout;
    protected $helper;

    public function __construct(
    	Session $checkout,
		Data $helper
    )
    {
        $this->checkout = $checkout;
        $this->helper = $helper;
    }

    public function execute(Observer $observer)
    {
        if(!$this->helper->isModuleEnabled()){
            return $this;
        }
        $cart = $observer->getEvent()->getCart();
        $quote = $this->checkout->getQuote();
        $customAmount = $quote->getFee();
        $label = $this->helper->getFeeLabel();
        if($customAmount) {
            $cart->addCustomItem($label, 1, $customAmount, $label);
        }
    }
}