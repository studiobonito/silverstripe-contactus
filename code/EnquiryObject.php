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
		'Phone' => 'Varchar',
		'Email' => 'Varchar',
		'Comment' => 'Text'
	);
	
	public static $summary_fields = array(
		'Name' => 'Name',
		'Phone' => 'Phone',
		'Email' => 'Email',
		'Created' => 'Request date'
	);
	
	public static $searchable_fields = array(
		'Name',
		'Phone',
		'Email'
	);

	public function getCMSFieldsForPopup() {
		$fields = new FieldSet();
		$fields->push(new TextField('Name'));
		$fields->push(new TextField('Phone'));
		$fields->push(new TextField('Email'));
		$fields->push(new TextareaField('Comment'));
		return $fields;
	}
	
}