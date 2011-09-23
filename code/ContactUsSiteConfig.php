<?php
/**
 * Contact Us Module adds a page type to Silverstripe for creating contact forms and embedding google maps.
 *
 * @package		Silverstripe Contact Us Module
 * @version		1.0
 * @author		Tom Densham <tom.densham at studiobonito.co.uk>
 * @license		Simplified BSD License
 * @copyright	2011 Studio Bonito Ltd.
 * @link		https://github.com/studiobonito/silverstripe-contactus
 */

/**
 * ContactUsSiteConfig Class
 * 
 * @package		Silverstripe Contact Us Module
 */
class ContactUsSiteConfig extends DataObjectDecorator {
	
	public function extraStatics() {
		return array(
			'db' => array(
				'ContactTelephone' => 'Varchar',
				'ContactTelephonePlain' => 'Varchar',
				'ContactEmail' => 'Varchar'
			)
		);
	}

}