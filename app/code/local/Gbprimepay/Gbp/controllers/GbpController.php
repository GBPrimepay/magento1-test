<?php
class Gbprimepay_Gbp_GbpController extends Mage_Core_Controller_Front_Action {
	public function redirectAction() {
		$session = Mage::getSingleton ( 'checkout/session' );
		$session->setGbpQuoteId ( $session->getQuoteId () );
		$this->getResponse ()->setBody ( $this->getLayout ()->createBlock ( 'gbp/redirect' )->toHtml () );
		$session->unsQuoteId ();
	}
	public function cancelAction() {
		$session = Mage::getSingleton ( 'checkout/session' );
		$session->setQuoteId ( $session->getPGbpQuoteId ( true ) );
		
		if ($session->getLastRealOrderId ()) {
			$order = Mage::getModel ( 'sales/order' )->loadByIncrementId ( $session->getLastRealOrderId () );
			if ($order->getId ()) {
				$order->cancel ()->save ();
			}
		}
		$this->_redirect ( 'checkout/cart' );
	}
	public function backgroundAction() {
		$session = Mage::getSingleton ( 'checkout/session' );
		$session->setQuoteId ( $session->getGbpQuoteId ( true ) );
		
		Mage::getSingleton ( 'checkout/session' )->getQuote ()->setIsActive ( false )->save ();
		
		$order = Mage::getModel ( 'sales/order' );
		$order->load ( Mage::getSingleton ( 'checkout/session' )->getLastOrderId () );
		
		$order->save ();
		
		if ($order->getId ()) {
			$order->sendNewOrderEmail ();
		}
	}
	public function successAction() {
		$session = Mage::getSingleton ( 'checkout/session' );
		$session->setQuoteId ( $session->getGbpQuoteId ( true ) );
		
		Mage::getSingleton ( 'checkout/session' )->getQuote ()->setIsActive ( false )->save ();
		
		$order = Mage::getModel ( 'sales/order' );
		$order->load ( Mage::getSingleton ( 'checkout/session' )->getLastOrderId () );
		
		$order->save ();
		Mage::getSingleton ( 'checkout/session' )->unsQuoteId ();
		
		if ($order->getId ()) {
			$order->sendNewOrderEmail ();
		}
		$this->_redirect ( 'checkout/onepage/success' );
	}
	public function failureAction() {
		$session = Mage::getSingleton ( 'checkout/session' );
		$session->setQuoteId ( $session->getGbpQuoteId ( true ) );
		
		if ($session->getLastRealOrderId ()) {
			$order = Mage::getModel ( 'sales/order' )->loadByIncrementId ( $session->getLastRealOrderId () );
			if ($order->getId ()) {
				$order->cancel ()->save ();
			}
		}
		$this->_redirect ( 'checkout/onepage/failure' );
	}
}
