 <?php
	class Gbprimepay_Gbp_Model_Gbp_PayType extends Varien_Object {
		/**
		 * Options getter
		 *
		 * @return array
		 */
		public function toOptionArray() {
			return array (
					array (
							'value' => 0,
							'label' => Mage::helper ( 'adminhtml' )->__ ( 'Full Payment' ) 
					),
					array (
							'value' => 1,
							'label' => Mage::helper ( 'adminhtml' )->__ ( 'Installment' ) 
					) 
			);
		}
		
		/**
		 * Get options in "key-value" format
		 *
		 * @return array
		 */
		public function toArray() {
			return array (
					0 => Mage::helper ( 'adminhtml' )->__ ( 'Data1' ),
					1 => Mage::helper ( 'adminhtml' )->__ ( 'Data2' ) 
			);
		}
	}//end class Gbprimepay_Gbp_Model_Gbp_PayType
