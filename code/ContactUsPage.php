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
 * ContactUsPage Class
 * 
 * @package		Silverstripe Contact Us Module
 */
class ContactUsPage extends Page implements Mappable {
	
	public static $icon = array('contactus/images/contact',"file");
	
	public static $db = array(
		'ContactTelephone' => 'Varchar',
		'ContactEmail' => 'Varchar',
		'LocationTitle' => 'Varchar',
		'LocationAddress1' => 'Text',
		'LocationAddress2' => 'Text',
		'LocationTownCity' => 'Text',
		'LocationCounty' => 'Text',
		'LocationCountry' => 'Text',
		'LocationPostcode' => 'Varchar',
		'Lat' => 'Varchar',
		'Lng' => 'Varchar',
		'MapAPIKey' => 'Text',
		'MapHeight' => 'Int',
		'MapWidth' => 'Int',
		'MapZoom' => 'Int',
		'MapIconSize' => 'Int'
	);
	
	public static $has_one = array(
		'MapIcon' => 'Image'
	);
	
	public static $defaults = array(
		'MapHeight' => '300',
		'MapWidth' => '400',
		'MapZoom' => '16',
		'MapIconSize' => '32'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationTitle', 'Location Title'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationAddress1', 'Address Line 1'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationAddress2', 'Address Line 2'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationTownCity', 'Town/City'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationCounty', 'County'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new CountryDropdownField('LocationCountry', 'Country'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('LocationPostcode', 'Postcode'));
		
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('ContactTelephone', 'Telephone Number'));
		$fields->addFieldToTab("Root.Content.ContactDetails", new TextField('ContactEmail', 'Email Address'));

		$fields->addFieldToTab("Root.Content.MapSettings", new TextField('MapAPIKey', 'Map API Key (GoogleMaps)'));
		$fields->addFieldToTab("Root.Content.MapSettings", new NumericField('MapHeight', 'Map Height (px)'));
		$fields->addFieldToTab("Root.Content.MapSettings", new NumericField('MapWidth', 'Map Width (px)'));
		$fields->addFieldToTab("Root.Content.MapSettings", new DropdownField('MapZoom', 'Map Zoom Level (default)', array('16' => '10 (Zoomed In)', '15' => '9', '14' => '8', '13' => '7', '12' => '6', '11' => '5', '10' => '4', '9' => '3', '8' => '2', '7' => '1 (Zoomed Out)')));
		$fields->addFieldToTab("Root.Content.MapSettings", new DropdownField('MapIconSize', 'Map Pin Size', array('48' => 'Large', '32' => 'Medium', '24' => 'Small', '16' => 'Tiny')));
		$fields->addFieldToTab("Root.Content.MapSettings", new ImageField('MapIcon', 'Map Pin'));

		return $fields;
	}
	
	/* Mappable interface requirements */

    public function getLatitude() {
        return $this->Lat;
    }

    public function getLongitude() {
        return $this->Lng;
    }

    public function getMapContent() {
        return GoogleMapUtil::sanitize($this->renderWith('MapBubble'));
    }

    public function getMapCategory() {
        return 'ContactUs';
    }

    public function getMapPin() {
        return $this->MapIcon()->SetRatioSize($this->MapIconSize, $this->MapIconSize)->URL;
    }

	/* end Mappable interface */
	
	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		$address = preg_replace('/\s\s+/', '+', "{$this->LocationAddress1} {$this->LocationAddress2} {$this->LocationTownCity} {$this->LocationCounty} {$this->LocationCountry} {$this->LocationPostcode}");
		if($json = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($address))) {
				$response = Convert::json2array($json);
				$location = $response['results'][0]->geometry->location;
		}
		$this->Lat = $location->lat;
		$this->Lng = $location->lng;
		
		$this->ContactTelephone = preg_replace('/[^0-9\+\(\)\s]/', '', $this->ContactTelephone);
		
		$site_config = SiteConfig::current_site_config();
		$site_config->ContactTelephone = $this->ContactTelephone;
		$site_config->ContactTelephonePlain = $this->getContactTelephonePlain();
		$site_config->ContactEmail = $this->ContactEmail;
		$site_config->write();
	}
	
	public function getContactTelephonePlain() {
		return preg_replace('/[(0)]|[^0-9\+]/', '', $this->ContactTelephone);
	}
	
	public function getLocationCountryName() {
		return GeoIP::countryCode2name($this->LocationCountry);
	}


	public function getContactMap() {
		$gmap = GoogleMapUtil::get_map(new DataObjectSet($this));
		$gmap->setSize($this->MapWidth, $this->MapHeight);
		$gmap->setEnableAutomaticCenterZoom(false);
		$gmap->setZoom($this->MapZoom);
		$gmap->setIconSize($this->MapIconSize, $this->MapIconSize);
		$gmap->setLatLongCenter(array(
			'200',
			'4',
			$this->getLatitude(),
			$this->getLongitude()
		));
		
		return $gmap;
	}

}
class ContactUsPage_Controller extends Page_Controller {

	public function init() {
		parent::init();
		GoogleMapUtil::set_api_key($this->MapAPIKey);
	}
	
	public function processEnquiryForm($data) {
		$enquiry = new EnquiryObject();
		
		$enquiry->Name = isset($data['EnquiryName']) ? $data['EnquiryName'] : null;
		$enquiry->Company = isset($data['EnquiryCompany']) ? $data['EnquiryCompany'] : null;
		$enquiry->Phone = isset($data['EnquiryPhone']) ? $data['EnquiryPhone'] : null;
		$enquiry->Email = isset($data['EnquiryEmail']) ? $data['EnquiryEmail'] : null;
		$enquiry->Subject = isset($data['EnquirySubject']) ? $data['EnquirySubject'] : null;
		$enquiry->Message = isset($data['EnquiryMessage']) ? $data['EnquiryMessage'] : null;
		
		if(empty($enquiry->Phone) && empty($enquiry->Email)) {
			return $this->render(array('ErrorMessage' => 'Please provide either a phone number or an email address!'));
		}
		
		$enquiry->write();
		
		if(!empty($this->ContactEmail)) {
			$email = new Email($this->ContactEmail, $this->ContactEmail, 'Website Enquiry');
			$email->setTemplate('EnquiryEmail');
			$email->populateTemplate(array(
				'Name' => $enquiry->Name,
				'Company' => $enquiry->Company,
				'Phone' => $enquiry->Phone,
				'Email' => $enquiry->Email,
				'Subject' => $enquiry->Subject,
				'Message' => $enquiry->Message
			));
			$email->send();
		}
		
		return $this->render(array('SuccessMessage' => 'Thank you for your enquiry.'));
	}
	
	public function EnquiryForm() {
		$fields = new FieldSet();
		
		$fields->push(new TextField('EnquiryName', 'Name'));
		$fields->push(new TextField('EnquiryCompany', 'Company'));
		$fields->push(new TextField('EnquiryPhone', 'Phone'));
		$fields->push(new TextField('EnquiryEmail', 'Email'));
		$fields->push(new TextField('EnquirySubject', 'Subject'));
		$fields->push(new TextareaField('EnquiryMessage', 'Message'));
		
		$actions = new FieldSet();
		
		$actions->push(new FormAction('processEnquiryForm', 'Send', null, null, 'submit'));
		
		$form = new Form($this, 'EnquiryForm', $fields, $actions);
		
		$this->extend('updateEnquiryForm', $form);
		
		return $form;
	}
	
	public function ContactTelephonePlain() {
		return $this->getContactTelephonePlain();
	}
	
	public function LocationCountryName() {
		return $this->getLocationCountryName();
	}
	
	public function ContactMap() {
		return $this->getContactMap();
	}
	
}