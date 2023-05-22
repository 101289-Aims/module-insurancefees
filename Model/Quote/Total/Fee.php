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

namespace Aimsinfosoft\InsuranceFees\Model\Quote\Total;

use Magento\Store\Model\ScopeInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\Total;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
	protected $quoteValidator = null;
	protected $_priceCurrency;
    protected $helperData;
	private $productMetadata;
    /**
     * Collect grand total address amount
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function __construct(
    	\Magento\Quote\Model\QuoteValidator $quoteValidator,
    	\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Aimsinfosoft\InsuranceFees\Helper\Data $helperData,
		\Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Aimsinfosoft\InsuranceFees\Model\CustomFeeConfigProvider $customFeeConfigProvider
	) {
        $this->quoteValidator = $quoteValidator;
		$this->_priceCurrency = $priceCurrency;
        $this->helperData = $helperData;
		$this->productMetadata = $productMetadata;
		$this->customFeeConfigProvider = $customFeeConfigProvider;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $enabled = $this->helperData->isModuleEnabled();
        $subtotal = $total->getTotalAmount('subtotal');

        if ($enabled && $quote->getGiftFeeCheck() > 0 ) {
            $fee = ($this->helperData->getCustomFee()/100)*$subtotal;//$fee_arr['custom_fee_amount_total'];
            //Try to test with sample value
            //$fee=50;

            $total->setTotalAmount('fee', $fee);
            $total->setBaseTotalAmount('fee', $fee);
            $total->setFee($fee);
			$quote->setFee($fee);
			
            $version = (float)$this->getMagentoVersion();
			
			if($version > 2.1)
			{
				//$total->setGrandTotal($total->getGrandTotal() + $fee);
			}
			else
			{
				$total->setGrandTotal($total->getGrandTotal() + $fee);
			}

        }
        return $this;
    }

	public function getMagentoVersion()
	{
	    return $this->productMetadata->getVersion();
	}
	
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(
    	\Magento\Quote\Model\Quote $quote,
    	\Magento\Quote\Model\Quote\Address\Total $total
	) {

        $enabled = $this->helperData->isModuleEnabled();
        $subtotal = $quote->getSubtotal();
        $fee = $quote->getFee();

        $result = [];
        if ($enabled && $fee) {
            $result = [
                'code' => 'fee',
                'title' => $this->helperData->getFeeLabel(),
                'value' => $fee
            ];
        }
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Insurance Fee');
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);

    }
	protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

}
