<?php 
	class yatcoConnect_ApiToBossLeads extends yatcoConnect_ApiToBoss {

		public function __construct() {
			
			$this->options = new yatcoConnect_Options();

			//$this->data = new yatcoConnect_ApiToBoss();

		}

		public function xml_contact_cfp($params){
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Contact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></Contact>');
			
				if (isset($params['VesselID'])) {
					$xml->addChild('VesselID', $params['VesselID']);
				}

				$xml->addChild('CompanyID', $params['CompanyID']);
				
				// Dynamically add children from the $params array
				foreach ($params as $key => $value) {
					$xml->addChild($key, htmlspecialchars($value)); // htmlspecialchars() to escape special characters
				}

				$xml->addChild('InquiryType', 1);
				$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');
				$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

			return $xml;
		}

		public function array_contact_cfp($params) {

			return [
				"FirstName" => $params['FirstName'],
				"LastName" => $params['LastName'],
				"Mobile" => $params['Phone'],
				"Email" => $params['Email'],
				"CompanyID" => $params['CompanyID'],
			];

		}

		public function xml_contact($params) {

			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Contact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></Contact>');
			
				if (isset($params['VesselID'])) {
					$xml->addChild('VesselID', $params['VesselID']);
				}

				$xml->addChild('CompanyID', $params['CompanyID']);
				
				$xml->addChild('FromEmail', $params['Email']);
				$xml->addChild('ClientDomain', $_SERVER['HTTP_HOST']);
				
				$xml->addChild('FirstName', $params['FirstName']);
				$xml->addChild('LastName', $params['LastName']);

				$xml->addChild('Email', $params['Email']);
				$xml->addChild('Mobile', $params['Phone']);
				$xml->addChild('Message', $params['Message']);

				$xml->addChild('InquiryType', 1);
				$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');
				$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

				//to bypass default added fields
				$keyArray = array('VesselID', 'CompanyID', 'FromEmail', 'ClientDomain', 'FirstName', 'LastName', 'Email', 'Mobile', 'Message', 'InquiryType', 'isBrokerageSite', 'WP_PLUGIN_SUBMIT');

				foreach($params as $param => $value){
					if (!in_array($param, $keyArray)) {
						$xml->addChild($param, $value);
					}
				}

			return $xml;

		}

		public function array_contact($params) {

			return [
				"FirstName" => $params['FirstName'],
				"LastName" => $params['LastName'],
				"Mobile" => $params['Phone'],
				"Email" => $params['Email'],
				"CompanyID" => $params['CompanyID'],
			];
		}

		public function sendContactFormData($params) {
			
			$vessel = $this->get_vessel_from_vesselid($params['VesselID']);

			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact_cfp($params);
				$xml->addChild('FormName', 'VesselLead');
											
				$xml->addChild('Subject', 'New Lead On Vessel ('. $vessel->MiscInfo->MLSID .')');

				$xml->addChild('OwnerOrIndustry', $params['owner_or_industry']);

				if (isset($params['is_about_full_spec'])) {
					$xml->addChild('isAboutFullSpec', 1);
				}

			$data = [
				'Form' => [
					'FormTypeID' => (isset($params['FormTypeID']) && ! empty($params['FormTypeID']))?$params['FormTypeID']:1,
					'VesselID' => $params['VesselID'],
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact_cfp($params),
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}

			//return $data;//stopped api call before sending to boss

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function sendVesselLead($params) {

			$vessel = $this->get_vessel_from_vesselid($params['VesselID']);

			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact($params);
				$xml->addChild('FormName', 'VesselLead');
											
				$xml->addChild('Subject', 'New Lead On Vessel ('. $vessel->MiscInfo->MLSID .')');

				$xml->addChild('OwnerOrIndustry', $params['owner_or_industry']);

				if (isset($params['is_about_full_spec'])) {
					$xml->addChild('isAboutFullSpec', 1);
				}

			$data = [
				'Form' => [
					'FormTypeID' => (isset($params['FormTypeID']) && ! empty($params['FormTypeID']))?$params['FormTypeID']:1,
					'VesselID' => $params['VesselID'],
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact($params),
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}
			
			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);
		}

		public function sendCharterLead($params) {

			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact($params);
				$xml->addChild('FormName', 'CharterDetailsLead');				
				//$xml->addChild('Subject', '');
				
				$xml->addChild('IdealCheckIn', $params['IdealCheckIn']);
				$xml->addChild('Duration', $params['Duration']);
				$xml->addChild('RateFreq', $params['RateFreq']);

				$xml->addChild('OwnerOrIndustry', $params['owner_or_industry']);
			
			$data = [
				'Form' => [
					'FormTypeID' => (isset($params['FormTypeID']) && ! empty($params['FormTypeID']))?$params['FormTypeID']:1,
					'FormName' => 'CharterDetailsLead',
					
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],
					//'isBrokerageSite' => 0,

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact($params),
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}

			//var_dump(json_encode($data));

			// return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function sendGeneralContact($params) {

			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact($params);
				$xml->addChild('FormName', 'GeneralWebsiteContact');
				$xml->addChild('Subject', 'General Contact Form Submitted');
			
			$data = [
				'Form' => [
					'FormTypeID' => (isset($params['FormTypeID']) && ! empty($params['FormTypeID']))?$params['FormTypeID']:1,
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],
					//'isBrokerageSite' => 0,

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact($params),
			];

			//return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);
		}

		public function sendBrokerContact($params) {
			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact($params);
				$xml->addChild('FormName', 'BrokerContact');

				$xml->addChild('BrokerID', $params['BrokerID']);
				$xml->addChild('BrokerCompanyID', $params['BrokerCompanyID']);
				
				$xml->addChild('Subject', 'General Contact Form Submitted');
				
			$data = [
				'Form' => [
					'FormTypeID' => (isset($params['FormTypeID']) && ! empty($params['FormTypeID']))?$params['FormTypeID']:1,
					
					'CompanyID' => $params['CompanyID'],

					'PageURL' => $params['ReferrerUrl'],
					//'isBrokerageSite' => 0,

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact($params),
			];
			
			// return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function sendServiceContact($params) {
			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = $this->xml_contact($params);
				$xml->addChild('FormName', 'CRM Company Contact');
				$xml->addChild('CRMCompanyID', $params['CRMCompanyID']);
				$xml->addChild('Subject', 'Lead From Yatco.com');
				
			$data = [
				'Form' => [
					'FormTypeID' => 17,
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],
					//'isBrokerageSite' => 0,

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact($params),
			];
			
			// return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function userSignupNewsletter($params) {
			//yatco use only
		
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><SignUpForm xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></SignUpForm>');
			
			$xml->addChild('CompanyID', 1);
			
			$xml->addChild('FirstName', $params['FirstName']);
			$xml->addChild('LastName', $params['LastName']);
			// $xml->addChild('CCRecipientEmails', 'mail@joshuahoffman.me');
			$xml->addChild('Email', $params['Email']);
			$xml->addChild('OwnerOrIndustry', $params['owner_or_industry']);

			$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');
			$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

			$data = [
				'Form' => [
					'FormTypeID' => $this->options->get_FormTypeID('NewsLetter'),
					'CompanyID' => 1,
					'PageURL' => $params['ReferrerUrl'],

					'FormData' => $xml->asXML()
				],

				'Contact' => [
					"FirstName" => $params['FirstName'],
					"LastName" => $params['LastName'],
					"Email" => $params['Email'],
					
					"CompanyID" => 1,
				],
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}
			
			//return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function userClientSignupNewsletter_cfp($params) {
			//to use new form builder newsletter
		
			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><SignUpForm xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></SignUpForm>');
			
			$xml->addChild('CompanyID', $params['CompanyID']);
			
			// Dynamically add children from the $params array
			foreach ($params as $key => $value) {
				$xml->addChild($key, htmlspecialchars($value)); // htmlspecialchars() to escape special characters
			}

			$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');
			$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

			$data = [
				'Form' => [
					'FormTypeID' => $this->options->get_FormTypeID('NewsLetter'),
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],

					'FormData' => $xml->asXML()
				],

				'Contact' => $this->array_contact_cfp($params),
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}
			
			//return $data; //stopped api call before sending to boss

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function userClientSignupNewsletter($params) {
			//yatco use only
		
			if ($this->options->getOption('override_leads_company_id') != '') {
				$params['CompanyID'] = $this->options->getOption('override_leads_company_id');
			}

			if(!isset($params['FirstName'])){
				$params['FirstName'] = ' ';
			}

			if(!isset($params['LastName'])){
				$params['LastName'] = ' ';
			}

			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><SignUpForm xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"></SignUpForm>');
			
			$xml->addChild('CompanyID', $params['CompanyID']);
			
			$xml->addChild('FirstName', $params['FirstName']);
			$xml->addChild('LastName', $params['LastName']);
			// $xml->addChild('CCRecipientEmails', 'mail@joshuahoffman.me');
			//$xml->addChild('Email', $params['Email']);
			$xml->addChild('FromEmail', $params['Email']);
			
			$xml->addChild('OwnerOrIndustry', $params['owner_or_industry']);

			$xml->addChild('isBrokerageSite', ($this->options->is_brokerage_site)?'true':'false');
			$xml->addChild('WP_PLUGIN_SUBMIT', 'true');

			$keyArray = array('CompanyID', 'FirstName', 'LastName', 'Email', 'owner_or_industry', 'isBrokerageSite', 'WP_PLUGIN_SUBMIT');

			foreach($params as $param => $value){
				if (!in_array($param, $keyArray)) {
					$xml->addChild($param, $value);
				}
			}

			$data = [
				'Form' => [
					'FormTypeID' => $this->options->get_FormTypeID('NewsLetter'),
					'CompanyID' => $params['CompanyID'],
					'PageURL' => $params['ReferrerUrl'],

					'FormData' => $xml->asXML()
				],

				'Contact' => [
					"FirstName" => $params['FirstName'],
					"LastName" => $params['LastName'],
					"Email" => $params['Email'],
					
					"CompanyID" => $params['CompanyID'],
				],
			];

			if (isset($params['owner_or_industry'])) {	
				$data['Form']['OwnerOrIndustry'] = $params['owner_or_industry'];
			}
			
			//return $data;

			return $this->call_api( 'POST', '/CRM/Contact/FormSubmission', $data);

		}

		public function yatcoBossProduct_11_2022_Quote($fields) {
			//yatco use only
			
			$message='<html><body>';
				foreach ($fields as $key => $value) {

					$valueL = str_replace ("\n", '<br>' , $value);
					
					$message .= '<div>';
						$message .="<strong>$key</strong><br><p>$valueL</p>";
					$message .= '</div>';

				}
			$message.='</body></html>';

			$emailed=wp_mail( 
				'billing@yatco.com', 
				'YATCO BOSS PRODUCT QUOTE', 
				$message, 
				array( 'Content-Type: text/html; charset=UTF-8' ), 
				[]
			);

			return [
				'success' => 1,
				'emailed' => $emailed,
				'MESSAGE' => str_replace ('\n', '<br>' ,$fields['INVOICE_PACKAGE']), 
			];
		}
	}