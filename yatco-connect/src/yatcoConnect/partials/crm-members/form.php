<form action="" class="form-validation form-horizontal" method="get">

    <div class="form-info">
        <h2>Looking for a colleague in our network?</h2>
        <div class="text-block">
            <p>Please use the fields below to search our membership database.</p>
        </div>
    </div>
    <div class="yt-row">    
        
        <div class="yt-row">
            <div class="form-group yt-col-12 yt-col-md-6">
                <div>
                   <label for="">Company</label>
                </div>

                <div>
                    <!-- <input type="input" name="CompanyName" class="yt-input"> -->

                    <?php 
                        $list_of_componies = $apiToPro->membership_companies_list(1045);

                      // var_dump($list_of_componies);
                    ?>

                    <select name="CRMCompanyID" class="yt-input">
                        <option selected="selected" value="0">Please Select...</option>

                        <?php foreach ($list_of_componies as $company) : ?>
                            <option value="<?= $company->CompanyID ?>"><?= $company->CompanyName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>            
            </div>

            <div class="form-group yt-col-12 yt-col-md-6">
                <div>
                   <label>Representative</label>
                </div>

                <div>
                    <!-- <input type="input" name="ContactName" class="yt-input"> -->

                    <?php 
                        $list_of_reps = $apiToPro->membership_reps_list(1045);
                    ?>

                    <select name="CRMContactID" class="yt-input">
                        <option selected="selected" value="0">Please Select...</option>

                        <?php foreach ($list_of_reps as $rep ) : ?>
                            <option value="<?= $rep->ContactID ?>"><?= $rep->ContactName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group yt-col-12 yt-col-md-6">
                <div>
                    <label for="CRMCategoryID">Category</label>
                </div>

                <div><select name="CRMCategoryID" id="CRMCategoryID" class="yt-input"><option selected="selected" value="0">Please Select...</option><option value="182">Accounting Services</option><option value="12">Advertising / Public Relations</option><option value="13">Air Conditioning &amp; Refrigeration</option><option value="14">Apparel / Crew Uniforms</option><option value="15">Associations</option><option value="16">Attorneys</option><option value="17">Audio / Video Systems</option><option value="18">Automation</option><option value="19">Aviation</option><option value="181">Banking Services</option><option value="20">Banks / Finance</option><option value="21">Batteries</option><option value="22">Bilge Maintenance</option><option value="23">Boat Show Production</option><option value="178">Boatyards</option><option value="24">Books &amp; Charts</option><option value="25">Business Development</option><option value="26">Canvas Shop</option><option value="27">Carpentry</option><option value="174">Catering</option><option value="176">Chafe Gear</option><option value="164">Charter Boat Rentals</option><option value="28">Charter Retail / Management</option><option value="29">Clocks</option><option value="30">Communications</option><option value="31">Computer Network Specialists</option><option value="32">Computer Software / Services</option><option value="33">Consultant</option><option value="37">Control Systems / Programming</option><option value="38">Crew Placement / Employment Agencies</option><option value="177">Crew Placement / Management</option><option value="39">Customs Broker</option><option value="40">Desalinators / Watermaker</option><option value="41">Designers</option><option value="42">Detailing</option><option value="43">Diesel Repair</option><option value="167">Dive Equipment &amp; Compressors</option><option value="44">Dive Service - Commercial</option><option value="45">Documentation Services (Yachts)</option><option value="46">Education / Captain &amp; Crew Training</option><option value="47">Electric</option><option value="48">Electronics</option><option value="49">Engine - Repair</option><option value="50">Engine- Repower</option><option value="51">Engineering</option><option value="52">Engineers &amp; Builders</option><option value="53">Engines - Diesel</option><option value="54">Entertainment Systems</option><option value="159">Environmental Consultants</option><option value="55">Environmental Products</option><option value="56">Exhaust Systems</option><option value="57">Fender Covers and Fenders</option><option value="58">Fenderhooks</option><option value="59">Fiberglass Repairs</option><option value="60">Financial Investment / Tax Consultants</option><option value="175">Fine Arts or Model Builder</option><option value="61">Fire Protection Systems</option><option value="152">Florist</option><option value="62">Fuel / Oil Suppliers</option><option value="63">Furniture &amp; Accessories</option><option value="64">Generators</option><option value="65">Glass / Plastics</option><option value="66">Global Yacht Support Agency</option><option value="168">Government</option><option value="67">Graphic Designer</option><option value="68">Harbors</option><option value="69">Healthcare</option><option value="70">Hotels / Restaurants</option><option value="71">Hydraulics, Motion Control</option><option value="72">Hydraulics, Stabilizers</option><option value="73">Insurance</option><option value="74">Insurance - Business</option><option value="75">Insurance - Health</option><option value="156">Insurance - Marine</option><option value="183">Insurance Services</option><option value="76">Interior Design</option><option value="180">Legal Services</option><option value="77">Lighting</option><option value="169">Linens/Softgoods</option><option value="78">Logistics/ Freight Forwarding</option><option value="79">Machine Shop</option><option value="80">Manufacture Accessories</option><option value="145">Manufacturer</option><option value="81">Manufacturer Custom Boats</option><option value="143">Manufacturer Production Boats</option><option value="82">Manufacturer Representative</option><option value="83">Marina Design &amp; Construction</option><option value="84">Marinas / Boatyard Services</option><option value="85">Marine Consultants</option><option value="147">Marine Directory</option><option value="86">Marine Flooring</option><option value="87">Marine Paint Companies</option><option value="88">Marine Sanitation</option><option value="89">Marine Supplies</option><option value="90">Marine Supplies - Distributor</option><option value="91">Marine Supplies - Retail</option><option value="92">Marine Surveys / Surveyors</option><option value="171">Marine Travel </option><option value="93">Maritime Administration</option><option value="94">Maritime Security &amp; Defense Training</option><option value="95">Marketing / Web Design</option><option value="162">Medical / Political Evacuation Services</option><option value="161">Medical Concierge</option><option value="96">Medical Services</option><option value="144">Merchant Services</option><option value="97">Metals Distribution &amp; Fabrication</option><option value="98">Miscellaneous</option><option value="99">Monitoring and Control Systems</option><option value="100">Mooring Products</option><option value="101">Naval Architects / Marine</option><option value="102">Navigation Electronics</option><option value="103">Parade / Event</option><option value="104">Photography / Videography</option><option value="105">Power Systems</option><option value="106">Printers</option><option value="165">Professional Yacht Consultants</option><option value="107">Project Coordination</option><option value="108">Promotional Items</option><option value="109">Propeller Service</option><option value="110">Propulsion Systems</option><option value="111">Publications</option><option value="112">Real Estate / Developer</option><option value="113">Regulatory Compliance</option><option value="114">Restaurant</option><option value="115">Safety / Safety Products</option><option value="116">Satellite Communications</option><option value="173">Seagoing Recruitment</option><option value="117">Security</option><option value="184">Service Providers</option><option value="118">Shipyard/Superyacht Builder</option><option value="119">Skilled Trades / Staffing</option><option value="120">Specialty Products</option><option value="121">Stabilizers</option><option value="172">Superyacht Manufacturer</option><option value="122">Systems Integration</option><option value="123">Teak Decking</option><option value="124">Tender</option><option value="125">Trade Show Displays</option><option value="126">Travel</option><option value="127">Travel &amp; Tourism</option><option value="128">Water / Air Purification</option><option value="170">Waterfront Realty</option><option value="129">Welding / Metal Fabrication</option><option value="130">Woodworking</option><option value="131">Yacht Brokers</option><option value="179">Yacht Builders</option><option value="132">Yacht Charter/ Club</option><option value="133">Yacht Design / Interiors</option><option value="134">Yacht Maintenance</option><option value="135">Yacht Management</option><option value="136">Yacht Provisioning / Catering</option><option value="137">Yacht Refinishing</option><option value="138">Yacht Repair / Refit</option><option value="139">Yacht Services &amp; Concierge</option><option value="140">Yacht Signage</option><option value="160">Yacht Tenders</option><option value="141">Yacht Transport</option><option value="148">Yacht Web Portal</option><option value="142">Yacht/Port Agent</option></select></div>

            </div>
                    
            <div class="form-group yt-col-12 yt-col-md-6">
                <div>
                    <label for="">City</label>
                </div>
                <input type="text" class="yt-input" placeholder="City" name="City">
            </div>
        </div>
        
        <div class="yt-row">
            <div class="form-group yt-col-12 yt-col-md-6">
                <div>
                    <label for="CountryID">Country</label>
                </div>
                <div><select name="CountryID" id="CountryID" class="yt-input" onchange="CountryChanged()"><option selected="selected" value="0">Please Select...</option><option value="1242">United States</option><option value="1343"></option><option value="1593">Afghanistan</option><option value="1598">Albania</option><option value="1600">Algeria</option><option value="1619">American Samoa</option><option value="1621">Andorra</option><option value="1623">Angola</option><option value="1628">Anguilla</option><option value="1629">Antarctica</option><option value="1400">Antigua And Barbuda</option><option value="685">Argentina</option><option value="1630">Armenia</option><option value="1634">Aruba</option><option value="706">Australia</option><option value="715">Austria</option><option value="1635">Azerbaijan</option><option value="724">Bahamas</option><option value="726">Bahrain</option><option value="1640">Bangladesh</option><option value="728">Barbados</option><option value="1647">Belarus</option><option value="730">Belgium</option><option value="1557">Belize</option><option value="1655">Benin</option><option value="738">Bermuda</option><option value="1660">Bhutan</option><option value="1662">Bolivia</option><option value="1670">Bosnia and Herzegovina</option><option value="1673">Botswana</option><option value="1676">Bouvet Island</option><option value="741">Brazil</option><option value="1677">British Indian Ocean Territory</option><option value="722">British Virgin Islands</option><option value="2341">British West Indies</option><option value="1678">Brunei Darussalam</option><option value="1680">Bulgaria</option><option value="1688">Burkina Faso</option><option value="1692">Burundi</option><option value="1694">Cambodia</option><option value="1698">Cameroon</option><option value="769">Canada</option><option value="1705">Cape Verde</option><option value="1340">Cayman Islands</option><option value="1707">Central African Republic</option><option value="1709">Chad</option><option value="786">Chile</option><option value="1447">China</option><option value="1712">Christmas Island</option><option value="1713">Cocos (Keeling) Islands</option><option value="1716">Colombia</option><option value="1740">Comoros</option><option value="1742">Congo</option><option value="1745">Congo, the Democratic Republic of the</option><option value="1756">Cook Islands</option><option value="1445">Costa Rica</option><option value="1758">Cote D'Ivoire</option><option value="1395">Croatia</option><option value="1402">Cuba</option><option value="1531">CYPRUS</option><option value="1764">Czech Republic</option><option value="799">Denmark</option><option value="1772">Djibouti</option><option value="1774">Dominica</option><option value="3503">Dominica </option><option value="1322">Dominican Republic</option><option value="1508">Dutch Caribbean</option><option value="2343">East Timor</option><option value="1581">Ecuador</option><option value="1534">Egypt</option><option value="1776">El Salvador</option><option value="1781">Equatorial Guinea</option><option value="1783">Eritrea</option><option value="1785">Estonia</option><option value="1788">Ethiopia</option><option value="1794">Falkland Islands (Malvinas)</option><option value="1796">Faroe Islands</option><option value="1798">Fiji</option><option value="805">Finland</option><option value="811">France</option><option value="1800">French Guiana</option><option value="1441">French Polynesia</option><option value="1802">French Southern Territories</option><option value="2339">French West Indies</option><option value="1803">Gabon</option><option value="1805">Gambia</option><option value="1808">Georgia</option><option value="832">Germany</option><option value="1814">Ghana</option><option value="1342">Gibraltar</option><option value="849">Greece</option><option value="1819">Greenland</option><option value="1443">Grenada</option><option value="1572">Guadeloupe</option><option value="1821">Guam</option><option value="1822">Guatemala</option><option value="1825">Guinea</option><option value="1827">Guinea-Bissau</option><option value="1829">Guyana</option><option value="1831">Haiti</option><option value="1834">Heard Island and Mcdonald Islands</option><option value="1835">Holy See (Vatican City State)</option><option value="1482">Honduras</option><option value="855">Hong Kong</option><option value="1836">Hungary</option><option value="1846">Iceland</option><option value="1848">India</option><option value="1875">Indonesia</option><option value="1902">Iran, Islamic Republic of</option><option value="1930">Iraq</option><option value="858">Ireland</option><option value="3371">Isle of Man</option><option value="861">Israel</option><option value="867">Italy</option><option value="1517">Jamaica</option><option value="885">Japan</option><option value="1946">Jordan</option><option value="1950">Kazakhstan</option><option value="1966">Kenya</option><option value="1973">Kiribati</option><option value="1975">Korea, Democratic People's Republic of</option><option value="1160">Korea, Republic of</option><option value="933">Kuwait</option><option value="1988">Kyrgyzstan</option><option value="1991">Lao People's Democratic Republic</option><option value="1994">Latvia</option><option value="1479">Lebanon</option><option value="1998">Lesotho</option><option value="2000">Liberia</option><option value="2002">Libyan Arab Jamahiriya</option><option value="2007">Liechtenstein</option><option value="2010">Lithuania</option><option value="936">Luxembourg</option><option value="2016">Macao</option><option value="2018">Macedonia, the Former Yugoslav Republic of</option><option value="2020">Madagascar</option><option value="2025">Malawi</option><option value="938">Malaysia</option><option value="1510">Maldives</option><option value="2028">Mali</option><option value="1421">Malta</option><option value="2030">Marshall Islands</option><option value="1562">Martinique</option><option value="2032">Mauritania</option><option value="1514">Mauritius</option><option value="2035">Mayotte</option><option value="951">Mexico</option><option value="2037">Micronesia, Federated States of</option><option value="2040">Moldova, Republic of</option><option value="985">Monaco</option><option value="2045">Mongolia</option><option value="2047">Montserrat</option><option value="2049">Morocco</option><option value="2063">Mozambique</option><option value="2072">Myanmar</option><option value="2083">Namibia</option><option value="2085">Nauru</option><option value="2086">Nepal</option><option value="986">Netherlands</option><option value="3505">Nevis </option><option value="2090">New Caledonia</option><option value="997">New Zealand</option><option value="2091">Nicaragua</option><option value="2096">Niger</option><option value="2100">Nigeria</option><option value="2121">Niue</option><option value="2122">Norfolk Island</option><option value="2123">Northern Mariana Islands</option><option value="1003">Norway</option><option value="2125">Oman</option><option value="2129">Pakistan</option><option value="2136">Palau</option><option value="2138">Palestinian Territory, Occupied</option><option value="1578">Panama</option><option value="2145">Papua New Guinea</option><option value="2147">Paraguay</option><option value="1009">Peru</option><option value="1424">Philippines</option><option value="2151">Pitcairn</option><option value="1027">Poland</option><option value="1044">Portugal</option><option value="1049">Puerto Rico</option><option value="2152">Qatar</option><option value="2154">Reunion</option><option value="2156">Romania</option><option value="1059">Russian Federation</option><option value="2186">Rwanda</option><option value="2188">Saint Helena</option><option value="2190">Saint Kitts and Nevis</option><option value="1416">Saint Lucia</option><option value="2340">Saint Maarten</option><option value="2338">Saint Martin</option><option value="2192">Saint Pierre and Miquelon</option><option value="2342">Saint Thomas</option><option value="1560">Saint Vincent And The Grenadines</option><option value="2194">Samoa</option><option value="2196">San Marino</option><option value="2199">Sao Tome and Principe</option><option value="1137">Saudi Arabia</option><option value="2201">Senegal</option><option value="2208">Serbia and Montenegro</option><option value="1512">Seychelles</option><option value="2209">Sierra Leone</option><option value="1150">Singapore</option><option value="2211">Slovakia</option><option value="1575">Slovenia</option><option value="2214">Solomon Islands</option><option value="2216">Somalia</option><option value="1151">South Africa</option><option value="2220">South Georgia and the South Sandwich Islands</option><option value="3513">South Korea</option><option value="1176">Spain</option><option value="2221">Sri Lanka</option><option value="3504">St Kitts </option><option value="1520">Sudan</option><option value="2225">Suriname</option><option value="2227">Svalbard and Jan Mayen</option><option value="2229">Swaziland</option><option value="1194">Sweden</option><option value="1206">Switzerland</option><option value="2231">Syrian Arab Republic</option><option value="2242">Tajikistan</option><option value="2245">Tanzania, United Republic of</option><option value="1329">Thailand</option><option value="2256">Timor-Leste</option><option value="2257">Togo</option><option value="2259">Tokelau</option><option value="2261">Tonga</option><option value="1418">Trinidad And Tobago</option><option value="1564">Tunisia</option><option value="1344">Turkey</option><option value="2263">Turkmenistan</option><option value="2268">Turks and Caicos Islands</option><option value="2270">Tuvalu</option><option value="2272">Uganda</option><option value="2274">Ukraine</option><option value="1229">United Arab Emirates</option><option value="1236">United Kingdom</option><option value="2345">United States Minor Outlying Islands</option><option value="2300">Uruguay</option><option value="2302">Uzbekistan</option><option value="2316">Vanuatu</option><option value="1302">Venezuela</option><option value="1486">Viet Nam</option><option value="1234">Virgin Islands, U.S.</option><option value="2318">Wallis and Futuna</option><option value="2320">Western Sahara</option><option value="2322">Yemen</option><option value="2346">Yugoslavia</option><option value="2329">Zambia</option><option value="2333">Zimbabwe</option></select></div>
            </div>
            
            <div class="form-group yt-col-12 yt-col-md-6">
                                    
                <div>
                    <label for="StateID">State</label>
                </div>
                <div>
                    <select class="yt-input" placeholder="State" id="StateID" name="StateID">

                    </select>
                </div>


                <script type="text/javascript">
                    function CountryChanged() {

                        var $select = jQuery('#StateID');
                        $select.empty();
                        var parent = $select.parent();
                        parent.empty();
                        console.log(parent);

                        $select.append('<option value="">Pick A State</option>');

                        jQuery.ajax({
                            type: "get",
                            url: _yatco_wp_url._yatco_wp_rest_url+'yatco/form-data-common?label=Membership_States_List',
                            data: { countryId: jQuery('#CountryID').val() },
                            success: function (list) {

                                list =  list.Membership_States_List;

                                //console.log(list);
                                var first = 0;
                                list.forEach(function (item) {
                                    $select.append("<option " + (first == item.ListItemID ? 'selected="selected"' : '') + " value='" + item.ListItemID + "'>" + item.ListItemDescription + "</option>")
                                });
                                parent.append($select);

                                if (typeof $_REQUEST['StateID'] != 'undefined') {
                                    $select.val($_REQUEST['StateID']);   
                                }
                            }
                        });
                    }
                </script>
            </div>
        </div>
        <div class="yt-row">
            <div class="form-group yt-col-12 yt-col-md-6">

                <div>
                    <label for="CRMRegionID">Region</label>
                </div>
                <div><select name="CRMRegionID" id="CRMRegionID" class="yt-input"><option selected="selected" value="0">Please Select...</option><option value="6">Asia</option><option value="8">Australia</option><option value="9">Caribbean</option><option value="10">Central America</option><option value="11">Europe</option><option value="12">Great Lakes</option><option value="13">Gulf Coast</option><option value="14">Mediterranian</option><option value="15">Mid Atlantic</option><option value="16">Middle East</option><option value="17">Northeast</option><option value="18">Northwest</option><option value="19">Pacific</option><option value="21">South America</option><option value="22">Southeast</option><option value="23">Southwest</option></select></div>
                        
            </div>
        </div>
    </div>
        
    <div class="" style="margin-top: 15px; text-align: center;">
        <input type="submit" class="yt-btn" value="Search">
    </div>
</form>

<script type="text/javascript">
    var $_REQUEST = <?= json_encode($_REQUEST) ?>;

    window.addEventListener('load', function(event) {

        var urlPARAMs=decodeURI(window.location.search)
            .replace('?', '')
            .split('&')
            .map(param => param.split('='))
            .reduce((values, [ key, value ]) => {
                values[ key ] = value;
                return values;
            }, {});

        for (const field_name in urlPARAMs) {
            var field_val=urlPARAMs[field_name];

            jQuery('*[name="'+ field_name +'"]').val( $_REQUEST[field_name] );

            if (field_name == 'CountryID') {
                CountryChanged();
            }
        }
    });

</script>