<?php

  global $yatcoConnect_gotten_options;

  class yatcoConnect_Options {

    const OPTION_INSTALLED = '_installed';
 
    const PREFIX = 'yatco_connect_';

    public $defaultOptionValues = [
        'shortcode_styling' => 'yatco',
    ];

    public $FormTypes = [
      'General' => 1,
      'CharterLead' => 3,
      'VesselLead' => 9,
      'NewsLetter' => 13,
    ];

    public function __construct()
    {
        //$this->notifications = new Notifications_Notification();

        $this->is_brokerage_site = $this->getOption('is_brokerage_site') == 'no'?false:true;

        $this->is_euro_site = $this->getOption('is_euro_site') == 'yes'?true:false;

        $this->use_boss_seo = $this->getOption('use_boss_seo') == 'yes'?true:false;
        
    }

    public function getOption($optionName) 
    {
        global $yatcoConnect_gotten_options;

        $prefixedOptionName = SELF::PREFIX.$optionName;

        if (isset($yatcoConnect_gotten_options[ $prefixedOptionName ])) {
          $retVal = $yatcoConnect_gotten_options[$prefixedOptionName];
        }
        else {
          $retVal = get_option($prefixedOptionName);
          $yatcoConnect_gotten_options[$prefixedOptionName] = $retVal;
        }

        return $retVal;
    }

    public function updateOption($optionName, $value) 
    {
        global $yatcoConnect_gotten_options;

        $prefixedOptionName = SELF::PREFIX.$optionName;

        $yatcoConnect_gotten_options[$prefixedOptionName] = $value;
        
        return update_option($prefixedOptionName, $value);
    }

    public function deleteOption($optionName) 
    {
        $prefixedOptionName = SELF::PREFIX.$optionName; 

        return delete_option($prefixedOptionName);
    }

    public function isInstalled() 
    {
      return $this->getOption(self::OPTION_INSTALLED);
    }

    public function markAsInstalled() 
    {
      return $this->updateOption(self::OPTION_INSTALLED, true);
    }

    public function markAsUnInstalled() 
    {
      return $this->deleteOption(self::OPTION_INSTALLED);
    }

    public function get_FormTypes()
    {
      return $this->FormTypes;
    }

    public function get_FormTypeID($formType)
    {
      return $this->FormTypes[$formType] ?? null; // Returns ID or null if not found
    }

  }