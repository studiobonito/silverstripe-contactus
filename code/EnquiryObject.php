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
		'Name' => 'Varchar',
		'Company' => 'Varchar',
		'Phone' => 'Varchar',
		'Email' => 'Varchar',
		'Subject' => 'Varchar',
		'Message' => 'Text'
	);
	
	public static $summary_fields = array(
		'Name' => 'Name',
		'Phone' => 'Phone',
		'Email' => 'Email',
		'Created' => 'Date'
	);
	
	public static $searchable_fields = array(
		'Name',
		'Phone',
		'Email'
	);
	
}