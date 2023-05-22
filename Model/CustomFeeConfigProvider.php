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

namespace Aimsinfosoft\InsuranceFees\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Quote\Model\Quote;

class CustomFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Aimsinfosoft\InsuranceFees\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
	
	protected $taxHelper;

    /**
     * @param \Aimsinfosoft\InsuranceFees\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
	 * @param \Aimsinfosoft\InsuranceFees\Helper\Tax $helperTax
     */
    public function __construct(
        \Aimsinfosoft\InsuranceFees\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $customFeeConfig = [];
		
        $enabled = $this->dataHelper->isModuleEnabled();
        $customFeeConfig['fee_label'] = $this->dataHelper->getFeeLabel();
		
        $quote = $this->checkoutSession->getQuote();
        $subtotal = $quote->getSubtotal();
		$customFeeConfig['custom_fee_amount_per'] = $this->dataHelper->getCustomFee();
        $customFeeConfig['custom_fee_amount'] = ($this->dataHelper->getCustomFee()/100) * $subtotal;



        $customFeeConfig['show_hide_customfee_block'] = ($enabled && $quote->getFee()) ? true : false;
        $customFeeConfig['show_hide_customfee_shipblock'] = ($enabled) ? true : false;
        return $customFeeConfig;
    }


	protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}