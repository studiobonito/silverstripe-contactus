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
		'MapAPIKey' => 'Varchar',
		'MapHeight' => 'Int',
		'MapWidth' => 'Int'
	);
	
	public static $has_one = array(
		'MapPin' => 'Image'
	);
	
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
        return $this->MapPin()->URL;
    }

	/* end Mappable interface */

	function getCMSFields() {
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
		$fields->addFieldToTab("Root.Content.MapSettings", new ImageField('MapPin', 'Map Pin'));

		return $fields;
	}
	
	protected function onBeforeWrite() {
		parent::onBeforeWrite();
		$address = preg_replace('/\s\s+/', '+', "{$this->LocationAddress1} {$this->LocationAddress2} {$this->LocationTownCity} {$this->LocationCounty} {$this->LocationCountry} {$this->LocationPostcode}");
		if($json = @file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($address))) {
				$response = Convert::json2array($json);
				$location = $response['results'][0]->geometry->location;
		}
		$this->Lat = $location->lat;
		$this->Lng = $location->lng;
		
		GoogleMapUtil::set_api_key($this->MapAPIKey);
		
		$config = SiteConfig::current_site_config();
		$config->ContactTelephone = $this->ContactTelephone;
		$config->ContactEmail = $this->ContactEmail;
		$config->write();
	}
	
	public function ContactMap() {
		$gmap = GoogleMapUtil::get_map(new DataObjectSet($this));
		$gmap->setSize($this->MapHeight, $this->MapWidth);
		$gmap->setEnableAutomaticCenterZoom(false);
		$gmap->setZoom(14);
		$gmap->setIconSize(32, 32);
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
	}
	
}