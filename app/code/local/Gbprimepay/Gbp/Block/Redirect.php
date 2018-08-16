<?php
class Gbprimepay_Gbp_Block_Redirect extends Mage_Core_Block_Abstract {
	protected function _toHtml() {
		$gbp = Mage::getModel ( 'gbp/gbp' );
		
		$form = new Varien_Data_Form ();
		$form->setAction ( $gbp->getUrl () )->setId ( 'gbp_checkout' )->setName ( 'gbp_checkout' )->setMethod ( 'post' )->setUseContainer ( true );
		foreach ( $gbp->getCheckoutFormFields () as $field => $value ) {
			$form->addField ( $field, 'hidden', array (
					'name' => $field,
					'value' => $value 
			) );
		}
		$html = '<html><body>';
		$html .= $this->__ ( 'You will be redirected to GBPrimePay in a few seconds.' );
		$html .= $form->toHtml ();
		$html .= '<script type="text/javascript">document.getElementById("gbp_checkout").submit();</script>';
		$html .= '</body></html>';
		
		return $html;
	} // end function _toHtml
}//end class Gbprimepay_Gbp_Block_Redirect
