<?php
class yatcoConnect_TemplateLiterals {
    //separate placeholders for listing page
    public function display_listing_literals($type = ''){
        if( $type === 'charter'){

            $array = [
                "fLOA",
                "LOAFeetFormat",
                "LOAMetersFormat",
                "LOAOutput",
                "fBeam",
                "fRange",
                "fDraft",
                "VesselName",
                "LOA",
                "HighRate",
                "GrossTonnagePounds",
                "MinDraft",
                "MaxDraft",
                "Beam",
                "ModelYear",
                "RefitYear",
                "CompanyName",
                "NumberOfCrew",
                "NumberOfGuests",
                "TotalStateRooms",
                "TotalHeads",
                "BuilderName",
                "tags",
                "Category",
                "SubCategory",
                "NumberOfSleeps",
                "LOAFeet",
                "LOAMeters",
                "MaxCruise",
                "MaxSleeps",
                "MaxQuayside",
                "LOAMeters",
                "LOAMeters",
                "Brokers"=>array(
                    "FirstName",
                    "LastName",
                    "Phone",
                    "Email",
                    "FullName",
                    "CompanyName",
                    "CompanyAddress1",
                    "CompanyAddress2",
                    "CompanyCity",
                    "CompanyCountry",
                    "CompanyState",
                    "VesselLocationState",
                    "VesselLocationCountry",
                ),
                "Rate"=>array(
                    "HighRate",
                    "LowRate",
                    "PriceOnApplication",
                    "BasePort",
                    "RateFrequencyName",
                    "SeasonName",
                    "HighRate_USD",
                    "LowRate_USD",
                    "fHighRateNoCurrency",
                    "fLowRateNoCurrency",
                    "fHighRate",
                    "fLowRate",
                    "fHighRateEuro",
                    "fLowRateEuro",
                    "fSeasonRate",
                ),
            ];

        }else if( $type === 'yacht' ){

            $array = [
                "VesselStatusText",
                "VesselConditionText",
                "Year",
                "VesselTypeText",
                "YearBuilt",
                "StateRooms",
                "LoaFormat",
                "LOAFeet",
                "Length",
                "Beam",
                "MinDraft",
                "MaxDraft",
                "MSRPPrice",
                "BuilderName",
                "WeightUnitType",
                "LOAMeters",
                "BeamFeet",
                "BeamMeters",
                "DraftMinFeet",
                "DraftMinMeters",
                "DraftMaxFeet",
                "DraftMaxMeters",
                "GrossTonnage",
                "GrossTonnagePounds",
                "GrossTonnageConverted",
                "LengthUnit",
                "WeightUnit",
                "VolumeUnit",
                "tags",
                "new",
                "AskingPrice",
                "AskingPriceCurrency",
                "PriceOnApplication",
                "IsStartingPrice",
                "VesselName",
                "AskingPriceFormatted",
                "AskingPriceFormattedNoCurrency",
                "AskingPriceCompare",
                "MSRPPriceFormatted",
                "MSRPPriceFormattedNoCurrency",
                "BuilderName",
                "BrokerTeaser",
                "City",
                "LocationCountry",
                "HullMaterial",
                "LocationState",
                "LocationCity",
                "LocationSubRegion",
                "Model",
                "DaysOnMarket",
                "DaysTillExpiration",
                "RecordAgeHours",
                "Brokers"=>array(
                    "FirstName",
                    "LastName",
                    "BrokerName",
                    "BrokerPhone",
                    "BrokerEmail",
                    "BrokerPhotoUrlSmall",
                    "CompanyName",
                    "CompanyAddress1",
                    "CompanyZip",
                    "CompanyPhotoUrl",
                    "Company_A_URL",
                    "a_url",
                ),
                "AssistantList",
            ];

        }else{

        }
    
        return $array;
    }
    //separate placeholders for single page
    public function display_single_literals( $type = '' ){

        if( $type === 'charter' ){

            $array = [
                "VesselID",
                "VesselName",
                "LOAMeters",                
                "LOAFeet",
                "LOA",                
                "LOAdisplay",
                "ModelYear",                
                "MaxSpeed",
                "BuilderName",                
                "VesselType",
                "VesselCategoryText",                
                "VesselSubCategoryText",
                "NumberOfCrew",                
                "NumberOfGuests",
                "TotalStateRooms",
                "TotalHeads",
                "City", 
                "State", 
                "Country", 
                "RegionName",
                "CompanyName", 
                "VesselID", 
                "VesselID", 
                "VesselID",
                "lowCost",
                "highCost",
                "frequency",
                "RatesList" => array(
                    "LowRateFormat",
                    "HighRateFormat"
                ),
                "Description",
            ];

        }else if( $type === 'yacht' ){

            $array = [
                "Description",
                "shortDescription",
                "VD" => array(
                    "VesselDescriptionShortDescriptionStripped",
                    "VesselDescriptionShortDescriptionNoStyles"
                ),
                "MainCategoryText",
                "shareButtons",
                "VesselStatusText",
                "VesselCondition",
                "Year",
                "VesselTypeText",
                "YearBuilt",
                "StateRooms",
                "LOAFeet",
                "Length",
                "Beam",
                "MinDraft",
                "MaxDraft",
                "MSRPPrice",
                "MSRPPriceCurrencyID",
                "BuilderName",
                "BuilderID",
                "WeightUnitType",
                "LOAMeters",
                "BeamFeet",
                "BeamMeters",
                "DraftMinFeet",
                "DraftMinMeters",
                "DraftMaxFeet",
                "DraftMaxMeters",
                "GrossTonnage",
                "GrossTonnagePounds",
                "GrossTonnageConverted",
                "LengthUnit",
                "WeightUnit",
                "VolumeUnit",
                "tags",
                "AskingPrice",
                "AskingPriceCurrency",
                "PriceOnApplication",
                "IsStartingPrice",
                "VesselName",
                "AskingPriceFormatted",
                "AskingPriceFormattedNoCurrency",
                "AskingPriceCompare",
                "MSRPPriceFormatted",
                "MSRPPriceFormattedNoCurrency",
                "BuilderName",
                "BrokerTeaser",
                "City",
                "LocationCountry",
                "HullMaterial",
                "HasVideo",
                "LocationState",
                "LocationCity",
                "LocationSubRegion",
                "Model",
                "IsModel",
                "SoldSecret",
                "DaysOnMarket",
                "DaysTillExpiration",
                "EngineNos",
                "AllEnginesHours",
                "Brokers"=>array(
                    "FirstName",
                    "LastName",
                    "BrokerName",
                    "BrokerPhone",
                    "BrokerEmail",
                    "BrokerPhotoUrlSmall",
                    "CompanyName",
                    "CompanyAddress1",
                    "CompanyZip",
                    "CompanyPhotoUrl",
                    "Company_A_URL",
                    "a_url",
                ),
                "Dimensions"=>array(
                    "LOA",
                    "LOAFeet",
                    "LOAMeter",
                ),
                "BasicInfo"=>array(
                    "VesselCondition",
                    "Builder",
                    "BoatName",
                    "MainCategory",
                    "SubCategory",
                    "Model",
                    "YearBuilt",
                    "ModelYear",
                    "RefitYear",
                    "LocationCountry",
                    "LocationCity",
                    "LocationState",
                    "StateRooms",
                    "Sleeps",
                    "CrewQuarters",
                    "CrewBerths",
                    "CrewSleeps",
                    "Flag",
                    "Currency",
                    "AskingPrice",
                    "AskingPriceUSD",
                    "AskingPriceFormatted",
                    "VesselCondition",
                    "VesselTypeTextISS",
                    "VesselTypeText",
                    "LocationCustom",
                    "LocationCustomRJC",
                    "Country",
                    "City",
                    "State",
                    "LocationSubRegion",
                    "Region",
                    "AskingPriceFormattedEuro",
                ),
                "AssistantList",
                "DataSource",
                "SearchedCurrency",
                "SearchedVolumeUnit",
                "MaxDraftFormat",
                "MinDraftFormat",
                "BeamFormat",
                "AcceptsCrypto",
                "ShowOnCustomerSite",
                "ShowOnOurWebsite",
                "ShowOnThirdPartySite",
                "ShowOnPro",
                "short_title",
                "State",
                "a_url",
                "Country",
                "LocationBlurb",
                "Berths",
                "Heads",
                "HeadRoom",
                "Sleeps",
                "StateRooms",
                "CrewQuarters",
                "CrewBerths",
                "CrewSleeps",
                "CrewHeads",
                "CaptainQuarters",
                "RegistrationNumber",
                "SpeedUnit",
                "WeightUnit",
                "VolumeUnit",
                "CruiseSpeedValue",
                "CruiseSpeed",
                "CruiseSpeedKnots",
                "CruiseSpeedMPH",
                "CruiseSpeedRPMValue",
                "CruiseSpeedRPM",
                "CruiseSpeedRangeValue",
                "CruiseSpeedRange",
                "MaxSpeedValue",
                "MaxSpeed",
                "MaxSpeedKnots",
                "MaxSpeedMPH",
                "MaxSpeedRPMValue",
                "MaxSpeedRPM",
                "MaxSpeedRangeValue",
                "MaxSpeedRange",
                "DisplacementValue",
                "Displacement",
                "DisplacementLBS",
                "DisplacementKG",
                "GrossTonnageValue",
                "GrossTonnage",
                "WaterCapacityValue",
                "WaterCapacity",
                "WaterCapacityGal",
                "WaterCapacityLiters",
                "HoldingTankValue",
                "HoldingTank",
                "HoldingTankGal",
                "HoldingTankLiters",
                "FuelCapacityValue",
                "FuelCapacity",
                "FuelCapacityGal",
                "FuelCapacityLiters",
                "FuelConsumptionValue",
                "FuelConsumption",
                "FuelConsumptionGal",
                "FuelConsumptionLiters",
                "HullMaterial",
                "HullColor",
                "ExteriorDesigner",
                "Engines"=>array(
                    "Manufacturer",
                    "Model",
                    "Year",
                    "EngineType",
                    "FuelType",
                    "PropulsionType",
                    "Horsepower",
                    "AppoxHours",
                    "toString",
                    "ReportToString",
                ),
                "LOA",
                "LOAFeet",
                "LOAMeter",
                "LengthUnit",
                "Length",
                "LWLValue",
                "LWL",
                "LWLFeet",
                "LWLMeter",
                "LODValue",
                "LOD",
                "LODFeet",
                "LODMeter",
                "BeamValue",
                "Beam",
                "BeamFeet",
                "BeamMeter",
                "MinDraftValue",
                "MinDraft",
                "MinDraftFeet",
                "MinDraftMeter",
                "MaxDraftValue",
                "MaxDraft",
                "MaxDraftFeet",
                "MaxDraftMeter",
                "ClearanceValue",
                "Clearance",
                "ClearanceFeet",
                "ClearanceMeter",
                "MFGLengthValue",
                "MFGLength",
                "MFGLengthFeet",
                "MFGLengthMeter",
                "DraftFeet",
                "DraftMeter",
                "VesselType"
            ];

        }else{

        }
        
        return $array;
    }

    public function dummy_charter_staterooms() {
        return [
            (object)[
                'AccTypeName' => 'Master Suite',
                'BedTypeName' => 'King',
                'HeadLocationName' => 'Ensuite',
                'VesselID' => 3408,
                'VesselAccommodationID' => 10001,
                'NumBed' => 1,
                'TTLGuest' => 2,
                'StateroomDimLengthUnit' => 'meters',
            ],
            (object)[
                'AccTypeName' => 'VIP Stateroom',
                'BedTypeName' => 'Queen',
                'HeadLocationName' => 'Ensuite',
                'VesselID' => 3408,
                'VesselAccommodationID' => 10002,
                'NumBed' => 1,
                'TTLGuest' => 2,
                'StateroomDimLengthUnit' => 'meters',
            ]
        ];
    }

    public function dummy_charter_amenities() {
        $groups = [
            [
                'AmenityGroupName' => 'ENTERTAINMENT / ELECTRONICS',
                'Items' => [
                    ['AmenityName' => 'Apple TV'],
                    ['AmenityName' => 'Game Console']
                ]
            ],
            [
                'AmenityGroupName' => 'EXTERIOR FEATURES',
                'Items' => [
                    ['AmenityName' => 'At-anchor Stabilizers'],
                    ['AmenityName' => 'Hydraulic Gangway']
                ]
            ],
            [
                'AmenityGroupName' => 'INSIDE EQUIPMENT',
                'Items' => [
                    ['AmenityName' => 'Air Compressor'],
                    ['AmenityName' => 'Electrical Bilge Pump']
                ]
            ],
            [
                'AmenityGroupName' => 'FITNESS AND WELLNESS',
                'Items' => [
                    ['AmenityName' => 'Beach Club'],
                    ['AmenityName' => 'Hot Tub / Jacuzzi']
                ]
            ],
            [
                'AmenityGroupName' => 'CONFIGURATIONS',
                'Items' => [
                    ['AmenityName' => 'Crew Passageway'],
                    ['AmenityName' => 'Fireplace']
                ]
            ],
            [
                'AmenityGroupName' => 'OUTDOOR ENTERTAINING',
                'Items' => [
                    ['AmenityName' => 'BBQ'],
                    ['AmenityName' => 'Golf']
                ]
            ],
            [
                'AmenityGroupName' => 'HELICOPTER / SEAPLANE',
                'Items' => [
                    ['AmenityName' => 'Helicopter'],
                    ['AmenityName' => 'Seaplane']
                ]
            ],
            [
                'AmenityGroupName' => 'COVERS',
                'Items' => [
                    ['AmenityName' => 'Bimini Top'],
                    ['AmenityName' => 'Lazy Bag']
                ]
            ],
            [
                'AmenityGroupName' => 'SPECIAL ACCESS',
                'Items' => [
                    ['AmenityName' => 'Elevator / Lift'],
                    ['AmenityName' => 'Wheelchair Friendly']
                ]
            ],
            [
                'AmenityGroupName' => 'ADDITIONAL EQUIPMENT',
                'Items' => [
                    ['AmenityName' => 'Beaching Legs'],
                    ['AmenityName' => 'Inverter']
                ]
            ]
        ];
    
        return (object)[ 'Groups' => $groups ];
    }
    
    public function dummy_charter_toys(){
        $array = [
            "Diving Equipment",
            "Flyboard",
            "Glass Bottom Kayaks",
            "Jet Ski",
            "Kayaks",
            "Paddle-Boards",
            "Seabobs",
            "Snorkelling Equipment",
            "Wakeboard",
            "Water-Skis",
        ];
        return $array;
    }

    public function dummy_charter_tenders(){
        $array = [
            "9m Windy Custom Limo",
            "10.40m Scorpion Rib Jet Tender",
            "7.80m Scorpion Rib Serket Inboard",
            "7.80m Custom Tender",
            "4.50m Bombard Commando C5",
        ];
        return $array;
    }

    public function dummy_charter_ToysDetails(){
        $array = [
            "Floating Noodles",
            "Snorkel gear - mask and fins",
        ];
        return $array;
    }
    
}
?>