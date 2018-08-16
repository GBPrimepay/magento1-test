<?php
class Gbprimepay_Gbp_Block_Form extends Mage_Payment_Block_Form {
	protected function _construct() {
		$this->setTemplate ( 'gbp/form.phtml' );
		parent::_construct ();
	} // end function _construct
}//end class Gbprimepay_Gbp_Block_Form
