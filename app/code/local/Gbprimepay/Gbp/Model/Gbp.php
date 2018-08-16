<?php
class Gbprimepay_Gbp_Model_Gbp extends Mage_Payment_Model_Method_Abstract {
	const CGI_URL = 'https://www.globalprimepay.com/gbp/gateway/pay';
	protected $_code = 'gbp';
	protected $_formBlockType = 'gbp/form';
	protected $_allowCurrencyCode = array (
			'THB',
			'USD',
			'EUR',
			'JPY',
			'GBP',
			'AUD',
			'NZD',
			'HKD',
			'SGD',
			'CHF',
			'INR',
			'NOK',
			'DKK',
			'SEK',
			'CAD',
			'MYR',
			'CNY',
			'TWD',
			'MOP',
			'BND',
			'AED',
			'LKP',
			'BDT',
			'SAR',
			'NPR',
			'PKR',
			'ZAR',
			'PHP',
			'QAR',
			'VND',
			'OMR',
			'RUB',
			'KRW',
			'IDR',
			'KWD',
			'BHD' 
	);
	public function getUrl() {
		$url = self::CGI_URL;
		return $url;
	}
	
	// end function getUrl
	public function getSession() {
		return Mage::getSingleton ( 'gbp/gbp_session' );
	}
	
	// end function getSession
	public function getCheckout() {
		return Mage::getSingleton ( 'checkout/session' );
	}
	
	// end function getCheckout
	public function getQuote() {
		return $this->getCheckout ()->getQuote ();
	}
	
	// end function getQuote
	public function getCheckoutFormFields() {
		$order = Mage::getSingleton ( 'sales/order' );
		$order->loadByIncrementId ( $this->getCheckout ()->getLastRealOrderId () );
		
		$currency_code = $order->getBaseCurrencyCode ();
		
		$grandTotalAmount = sprintf ( '%.2f', $order->getGrandTotal () );
		
		switch ($currency_code) {
			case 'THB' :
				$cur = 764;
				break;
			case 'USD' :
				$cur = 840;
				break;
			case 'EUR' :
				$cur = 978;
				break;
			case 'JPY' :
				$cur = 392;
				break;
			case 'GBP' :
				$cur = 826;
				break;
			case 'AUD' :
				$cur = 036;
				break;
			case 'NZD' :
				$cur = 554;
				break;
			case 'HKD' :
				$cur = 344;
				break;
			case 'SGD' :
				$cur = 702;
				break;
			case 'CHF' :
				$cur = 756;
				break;
			case 'INR' :
				$cur = 356;
				break;
			case 'NOK' :
				$cur = 578;
				break;
			case 'DKK' :
				$cur = 208;
				break;
			case 'SEK' :
				$cur = 752;
				break;
			case 'CAD' :
				$cur = 124;
				break;
			case 'MYR' :
				$cur = 458;
				break;
			case 'CNY' :
				$cur = 156;
				break;
			case 'TWD' :
				$cur = 901;
				break;
			case 'MOP' :
				$cur = 446;
				break;
			case 'BND' :
				$cur = 96;
				break;
			case 'AED' :
				$cur = 784;
				break;
			case 'LKR' :
				$cur = 144;
				break;
			case 'BDT' :
				$cur = 050;
				break;
			case 'SAR' :
				$cur = 682;
				break;
			case 'NPR' :
				$cur = 524;
				break;
			case 'PKR' :
				$cur = 586;
				break;
			case 'ZAR' :
				$cur = 710;
				break;
			case 'PHP' :
				$cur = 608;
				break;
			case 'QAR' :
				$cur = 634;
				break;
			case 'VND' :
				$cur = 704;
				break;
			case 'OMR' :
				$cur = 512;
				break;
			case 'RUB' :
				$cur = 643;
				break;
			case 'KRW' :
				$cur = 410;
				break;
			case 'IDR' :
				$cur = 360;
				break;
			case 'KWD' :
				$cur = 414;
				break;
			case 'BHD' :
				$cur = 48;
				break;
			default :
				$cur = 764;
		}
		
		$orderId = $order->getIncrementId ();
		$item_names = array ();
		$items = $order->getItemsCollection ();
		foreach ( $items as $item ) {
			$item_name = $item->getName ();
			$Email = $item->getEmail ();
			$qty = number_format ( $item->getQtyOrdered (), 0, '.', ' ' );
			$item_names [] = $item_name . ' x ' . $qty;
		}
		// Check if any customer is logged in or not
		$customer = Mage::getSingleton ( 'customer/session' )->getCustomer ();
		//
		$gbprimepay_args ['item_name'] = sprintf ( __ ( 'Order %s ' ), $orderId ) . " - " . implode ( ', ', $item_names );
		$orderReferenceValue = $this->getCheckout ()->getLastRealOrderId ();
		$redirectUrl = $this->getConfigData ( 'responseurl' );	
		$token = $this->getConfigData ( 'token' );
		$paytype = $this->getConfigData ( 'paytype' );
		
		$fields = array (
				'token' => $token,
				'amount' => $grandTotalAmount,
				'customerName' => $customer->getCustomerFirstname () . ' ' . $customer->getCustomerLastname (),
				'customerEmail' => $customer->getCustomerEmail (),
				'currencyCode' => $cur,
				'detail' => $gbprimepay_args ['item_name'],
				'referenceNo' => $orderReferenceValue,
				'backgroundUrl' => Mage::getUrl ( 'gbp/gbp/background' ),
				'responseUrl' => Mage::getUrl ( 'gbp/gbp/success' ),
				'payType' => $paytype == '0' ? 'I' : 'F' 
		);
		
// 		$file = fopen ( "debug.json", "w" );
// 		fwrite ( $file, json_encode ( $fields, JSON_UNESCAPED_SLASHES ) );
// 		fclose ( $file );
		
		$filtered_fields = array ();
		foreach ( $fields as $k => $v ) {
			$value = str_replace ( "&", "and", $v );
			$filtered_fields [$k] = $value;
		}
		
		return $filtered_fields;
	}
	
	// end function getCheckoutFormFields
	public function createFormBlock($name) {
		$block = $this->getLayout ()->createBlock ( 'gbp/form', $name )->setMethod ( 'gbp' )->setPayment ( $this->getPayment () )->setTemplate ( 'gbp/form.phtml' );
		
		return $block;
	}
	
	// end function createFormBlock
	public function validate() {
		parent::validate ();
		$currency_code = $this->getQuote ()->getBaseCurrencyCode ();
		if (! in_array ( $currency_code, $this->_allowCurrencyCode )) {
			Mage::throwException ( Mage::helper ( 'gbp' )->__ ( 'Selected currency code (' . $currency_code . ') is not compatabile with GBPrimePay' ) );
		}
		return $this;
	}
	
	// end function validate
	public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment) {
		return $this;
	}
	
	// end function onOrderValidate
	public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment) {
	}
	
	// end function onInvoiceCreate
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl ( 'gbp/gbp/redirect' );
	}
	
	// end function getOrderPlaceRedirectUrl
}

?>
