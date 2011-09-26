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
 * EnquiryObject Class
 * 
 * @package		Silverstripe Contact Us Module
 */
class EnquiryObject extends DataObject {
	
	public static $singular_name = 'Enquiry';
	
	public static $plural_name = 'Enquiries';
	
	public static $db = array(
		'EnquiryName' => 'Varchar',
		'EnquiryCompany' => 'Varchar',
		'EnquiryPhone' => 'Varchar',
		'EnquiryEmail' => 'Varchar',
		'EnquirySubject' => 'Varchar',
		'EnquiryMessage' => 'Text'
	);
	
	public static $summary_fields = array(
		'EnquiryName' => 'Name',
		'EnquiryPhone' => 'Phone',
		'EnquiryEmail' => 'Email',
		'EnquiryCreated' => 'Date'
	);
	
	public static $searchable_fields = array(
		'EnquiryName' => array('title' => 'Name'),
		'EnquiryPhone' => array('title' => 'Phone'),
		'EnquiryEmail' => array('title' => 'Email')
	);
	
}