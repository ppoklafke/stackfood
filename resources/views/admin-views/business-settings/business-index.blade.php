@extends('layouts.admin.app')

@section('title', translate('Settings'))

@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ dynamicAsset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business_setup') }}
                    </span>
                </h1>
            <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                <div class="blinkings active">
                    <i class="tio-info-outined"></i>
                    <div class="business-notes">
                        <h6><img src="{{dynamicAsset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                        <div>
                            {{translate('Don’t_forget_to_click_the_‘Save_Information’_button_below_to_save_changes.')}}
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="business-setup">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="maintainance-mode-toggle-bar d-flex flex-wrap justify-content-between border blue-border rounded align-items-center">
                            @php($config = \App\CentralLogics\Helpers::get_business_settings('maintenance_mode'))
                            <h5 class="card-title text-capitalize mr-3 m-0 text--info">
                                <span class="card-header-icon">
                                    <i class="tio-settings-outlined"></i>
                                </span>
                                <span>
                                    {{ translate('messages.maintenance_mode') }}
                                </span>
                            </h5>

                            <label class="toggle-switch toggle-switch-sm">
                                <input type="checkbox"
                                id="maintenance_mode"

                                class="status toggle-switch-input  {{ isset($config) && $config ?   'turn_off_maintenance_mode' : 'maintenance-mode' }} "
                                    {{ isset($config) && $config ? 'checked' : '' }}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>


                <?php
                    $maintenance_mode_data=   \App\Models\DataSetting::where('type','maintenance_mode')->whereIn('key' ,['maintenance_system_setup' ,'maintenance_duration_setup','maintenance_message_setup'])->pluck('value','key')
                    ->map(function ($value) {
                        return json_decode($value, true);
                    })
                    ->toArray();
                            $selectedMaintenanceSystem      =  data_get($maintenance_mode_data,'maintenance_system_setup',[]);
                            $selectedMaintenanceDuration    =  data_get($maintenance_mode_data,'maintenance_duration_setup',[]);
                            $selectedMaintenanceMessage     = data_get($maintenance_mode_data,'maintenance_message_setup',[]);
                            $maintenanceMode                = (int) ($config ?? 0);

                    if (isset($selectedMaintenanceDuration['start_date']) && isset($selectedMaintenanceDuration['end_date'])) {
                        $startDate = new DateTime($selectedMaintenanceDuration['start_date']);
                        $endDate = new DateTime($selectedMaintenanceDuration['end_date']);
                    } else {
                        $startDate = null;
                        $endDate = null;
                    }
                ?>


                @if($config)
                    <div class="mt-3 border rounded p-3 p-md-4">
                                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                    <p class="fz-12 mb-0">
                                        {{ translate('Your maintenance mode is activated') }}
                                        @if(isset($selectedMaintenanceDuration['maintenance_duration']) && $selectedMaintenanceDuration['maintenance_duration'] == 'until_change')
                                            {{ translate(' until I change') }}
                                        @endif
                                        @if($startDate && $endDate)
                                        {{translate('from ')}}<strong>{{ $startDate->format('m/d/Y, h:i A') }}</strong> {{ translate('to') }} <strong>{{ $endDate->format('m/d/Y, h:i A') }}</strong>.
                                        @endif
                                    </p>
                                    <button class="c1 btn  btn-outline-primary edit maintenance-mode-show maintenance-mode" href="#"> <i class="tio-edit" ></i> {{ translate('edit') }}</button>
                                </div>
                            <h6 class="mb-0">
                                {{ translate('Selected Systems') }}
                            </h6>
                                <div class="d-flex flex-wrap gap-3 mt-3 align-items-center">
                                    <ul class="selected-systems d-flex gap-4 flex-wrap bg-soft-dark px-5 py-1 mb-0 rounded">
                                        @foreach($selectedMaintenanceSystem as $system)
                                            <li>{{ ucwords(str_replace('_', ' ', $system)) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                    </div>
                @else
                    <p class="mt-2 mb-0">
                        {{ translate('*Turn on the') }}  <strong> {{ translate('Maintenance') }} </strong>    {{  translate('mode will temporarily deactivate your selected systems as of your chosen date and time.') }}
                    </p>
                @endif

            </div>
            </div>







                <form action="{{ route('admin.business-settings.update-setup') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <h4 class="card-title mb-3 mt-1">
                        <img src="{{dynamicAsset('/public/assets/admin/img/company.png')}}" class="card-header-icon mr-2" alt="">
                        <span>{{ translate('Company_Information') }}</span>
                    </h4>
                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.company_name') }}
                                            &nbsp;
                                        <span class="line--limit-1"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Write_the_Company_Name.') }}"><img
                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}">
                                    </span>
                                        </label>
                                        <input type="text" name="restaurant_name" maxlength="191" value="{{ $name->value ?? '' }}" class="form-control"
                                            placeholder="{{ translate('messages.Ex :') }} ABC Company" required>
                                    </div>
                                </div>
                                @php($email = \App\Models\BusinessSetting::where('key', 'email_address')->first())
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" value="{{ $email->value ?? '' }}" name="email"
                                            class="form-control" placeholder="{{ translate('messages.Ex :') }} contact@company.com" required>
                                    </div>
                                </div>
                                @php($phone = \App\Models\BusinessSetting::where('key', 'phone')->first())
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                        <input type="tel" value="{{ $phone->value ?? '' }}" name="phone"
                                            class="form-control" placeholder="{{ translate('messages.Ex :') }} +9XXX-XXX-XXXX" required>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="country">{{ translate('messages.country') }} &nbsp;
                                            <span class="line--limit-1"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Choose_your_country_from_the_drop-down_menu.') }}"><img
                                                src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}">
                                        </span>

                                        </label>
                                        <select id="country" name="country" class="form-control  js-select2-custom">
                                            <option value="AF">Afghanistan</option>
                                            <option value="AX">Åland Islands</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia, Plurinational State of</option>
                                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="BA">Bosnia and Herzegovina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo, the Democratic Republic of the</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="HR">Croatia</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CW">Curaçao</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GG">Guernsey</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard Island and McDonald Islands</option>
                                            <option value="VA">Holy See (Vatican City State)</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran, Islamic Republic of</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IM">Isle of Man</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JE">Jersey</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea, Democratic People's Republic of</option>
                                            <option value="KR">Korea, Republic of</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macao</option>
                                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia, Federated States of</option>
                                            <option value="MD">Moldova, Republic of</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="ME">Montenegro</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PS">Palestinian Territory, Occupied</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Réunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="BL">Saint Barthélemy</option>
                                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                            <option value="KN">Saint Kitts and Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="MF">Saint Martin (French part)</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="RS">Serbia</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SX">Sint Maarten (Dutch part)</option>
                                            <option value="SK">Slovakia</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="SS">South Sudan</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan, Province of China</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TL">Timor-Leste</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands, British</option>
                                            <option value="VI">Virgin Islands, U.S.</option>
                                            <option value="WF">Wallis and Futuna</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    @php($address = \App\Models\BusinessSetting::where('key', 'address')->first())
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.address') }}</label>
                                        <textarea type="text" id="address" name="address" class="form-control h--90px" placeholder="{{ translate('messages.Ex :') }} House#94, Road#8, Abc City" rows="1"
                                            required>{{ $address->value ?? '' }}</textarea>
                                    </div>
                                    @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                    @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="latitude">{{ translate('messages.latitude') }}<span class="input-label-secondary"
                                                            data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.Click_on_the_map_to_see_your_location’s_latitude') }}"><img
                                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.Click_on_the_map_to_see_your_location’s_latitude') }}"></span></label>
                                                <input type="text" id="latitude" name="latitude" class="form-control d-inline"
                                                    placeholder="{{ translate('messages.Ex :') }} -94.22213"
                                                    value="{{ $default_location ? $default_location['lat'] : 0 }}" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="longitude">{{ translate('messages.longitude') }}<span class="input-label-secondary"
                                                            data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.Click_on_the_map_to_see_your_location’s_longitude') }}"><img
                                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.Click_on_the_map_to_see_your_location’s_longitude') }}"></span></label>
                                                <input type="text" name="longitude" class="form-control" placeholder="{{ translate('messages.Ex :') }} 103.344322"
                                                    id="longitude" value="{{ $default_location ? $default_location['lng'] : 0 }}"
                                                    required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex __gap-12px mt-4">
                                        <label class="__custom-upload-img mr-lg-5">
                                            @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                            <label class="form-label">
                                                {{ translate('logo') }} <span class="text--primary">({{ translate('3:1') }})</span>
                                            </label>
                                            <div class="text-center">
                                                    <img class="img--vertical onerror-image"   id="viewer"
                                                    src="{{\App\CentralLogics\Helpers::get_full_url('business', $logo?->value?? '', $logo?->storage[0]?->value ?? 'public','upload_image')}}" alt="image">

                                            </div>
                                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>

                                        <label class="__custom-upload-img">
                                            @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                            <label class="form-label">
                                                {{ translate('Favicon') }}  <span class="text--primary">({{ translate('1:1') }})</span>
                                            </label>
                                            <div class="text-center">
                                                    <img class="img--110 onerror-image"   id="iconViewer"
                                                    src="{{\App\CentralLogics\Helpers::get_full_url('business', $icon?->value?? '', $icon?->storage[0]?->value ?? 'public','upload_image')}}"
                                                    data-onerror-image="{{ dynamicAsset('public/assets/admin/img/upload-img.png') }}" alt="Fav icon">
                                            </div>
                                            <input type="file" name="icon" id="favIconUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-3 rounded border border-success">
                                        <div class="d-flex mb-3 fs-12">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V11H13V17ZM13 9H11V7H13V9Z" fill="#039D55"></path>
                                            </svg>
                                            <div class="w-0 flex-grow pl-2">
                                                Clicking on the map will set Latitude and Longitude automatically
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <input id="pac-input" class="controls rounded overflow-hidden initial-9 mt-1"
                                                title="{{ translate('messages.search_your_location_here') }}" type="text"
                                                placeholder="{{ translate('messages.search_here') }}" />
                                            <div id="location_map_canvas" class="overflow-hidden rounded height-285px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="card-title mb-3 pt-2">
                        <span class="card-header-icon mr-2">
                            <i class="tio-settings-outlined"></i>
                        </span>
                        <span>{{ translate('General_Settings') }}</span>
                    </h4>

                    <div class="card mb-3">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    @php($tz = \App\Models\BusinessSetting::where('key', 'timezone')->first())
                                    @php($tz = $tz ? $tz->value : 0)
                                    <div class="form-group">
                                        <label
                                            class="input-label text-capitalize d-flex alig-items-center">{{ translate('messages.time_zone') }}</label>
                                        <select name="timezone" class="form-control js-select2-custom">
                                            <option value="UTC" {{ $tz ? ($tz == '' ? 'selected' : '') : '' }}>UTC</option>
                                            <option value="Etc/GMT+12" {{ $tz ? ($tz == 'Etc/GMT+12' ? 'selected' : '') : '' }}>(GMT-12:00) International Date Line West</option>
                                            <option value="Pacific/Midway" {{ $tz ? ($tz == 'Pacific/Midway' ? 'selected' : '') : '' }}> (GMT-11:00) Midway Island, Samoa</option>
                                            <option value="Pacific/Honolulu" {{ $tz ? ($tz == 'Pacific/Honolulu' ? 'selected' : '') : '' }}> (GMT-10:00) Hawaii</option>
                                            <option value="US/Alaska" {{ $tz ? ($tz == 'US/Alaska' ? 'selected' : '') : '' }}> (GMT-09:00) Alaska</option>
                                            <option value="America/Los_Angeles" {{ $tz ? ($tz == 'America/Los_Angeles' ? 'selected' : '') : '' }}>(GMT-08:00) Pacific Time (US & Canada)</option>
                                            <option value="America/Tijuana" {{ $tz ? ($tz == 'America/Tijuana' ? 'selected' : '') : '' }}> (GMT-08:00) Tijuana, Baja California</option>
                                            <option value="US/Arizona" {{ $tz ? ($tz == 'US/Arizona' ? 'selected' : '') : '' }}> (GMT-07:00) Arizona</option>
                                            <option value="America/Chihuahua" {{ $tz ? ($tz == 'America/Chihuahua' ? 'selected' : '') : '' }}>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                            <option value="US/Mountain" {{ $tz ? ($tz == 'US/Mountain' ? 'selected' : '') : '' }}>(GMT-07:00) Mountain Time (US & Canada)</option>
                                            <option value="America/Managua" {{ $tz ? ($tz == 'America/Managua' ? 'selected' : '') : '' }}> (GMT-06:00) Central America</option>
                                            <option value="US/Central" {{ $tz ? ($tz == 'US/Central' ? 'selected' : '') : '' }}> (GMT-06:00) Central Time (US & Canada)</option>
                                            <option value="America/Mexico_City" {{ $tz ? ($tz == 'America/Mexico_City' ? 'selected' : '') : '' }}>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                            <option value="Canada/Saskatchewan" {{ $tz ? ($tz == 'Canada/Saskatchewan' ? 'selected' : '') : '' }}>(GMT-06:00) Saskatchewan
                                            </option>
                                            <option value="America/Bogota" {{ $tz ? ($tz == 'America/Bogota' ? 'selected' : '') : '' }}> (GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                            <option value="US/Eastern" {{ $tz ? ($tz == 'US/Eastern' ? 'selected' : '') : '' }}> (GMT-05:00) Eastern Time (US & Canada)</option>
                                            <option value="US/East-Indiana" {{ $tz ? ($tz == 'US/East-Indiana' ? 'selected' : '') : '' }}> (GMT-05:00) Indiana (East)</option>
                                            <option value="Canada/Atlantic" {{ $tz ? ($tz == 'Canada/Atlantic' ? 'selected' : '') : '' }}> (GMT-04:00) Atlantic Time (Canada)</option>
                                            <option value="America/Caracas" {{ $tz ? ($tz == 'America/Caracas' ? 'selected' : '') : '' }}> (GMT-04:00) Caracas, La Paz</option>
                                            <option value="America/Manaus" {{ $tz ? ($tz == 'America/Manaus' ? 'selected' : '') : '' }}> (GMT-04:00) Manaus</option>
                                            <option value="America/Santiago" {{ $tz ? ($tz == 'America/Santiago' ? 'selected' : '') : '' }}> (GMT-04:00) Santiago</option>
                                            <option value="Canada/Newfoundland" {{ $tz ? ($tz == 'Canada/Newfoundland' ? 'selected' : '') : '' }}>(GMT-03:30) Newfoundland
                                            </option>
                                            <option value="America/Sao_Paulo" {{ $tz ? ($tz == 'America/Sao_Paulo' ? 'selected' : '') : '' }}>(GMT-03:00) Brasilia</option>
                                            <option value="America/Argentina/Buenos_Aires" {{ $tz ? ($tz == 'America/Argentina/Buenos_Aires' ? 'selected' : '') : '' }}> (GMT-03:00) Buenos Aires, Georgetown</option>
                                            <option value="America/Godthab" {{ $tz ? ($tz == 'America/Godthab' ? 'selected' : '') : '' }}> (GMT-03:00) Greenland</option>
                                            <option value="America/Montevideo" {{ $tz ? ($tz == 'America/Montevideo' ? 'selected' : '') : '' }}>(GMT-03:00) Montevideo
                                            </option>
                                            <option value="America/Noronha" {{ $tz ? ($tz == 'America/Noronha' ? 'selected' : '') : '' }}> (GMT-02:00) Mid-Atlantic</option>
                                            <option value="Atlantic/Cape_Verde" {{ $tz ? ($tz == 'Atlantic/Cape_Verde' ? 'selected' : '') : '' }}>(GMT-01:00) Cape Verde Is.
                                            </option>
                                            <option value="Atlantic/Azores" {{ $tz ? ($tz == 'Atlantic/Azores' ? 'selected' : '') : '' }}> (GMT-01:00) Azores</option>
                                            <option value="Africa/Casablanca" {{ $tz ? ($tz == 'Africa/Casablanca' ? 'selected' : '') : '' }}>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                            <option value="Etc/Greenwich" {{ $tz ? ($tz == 'Etc/Greenwich' ? 'selected' : '') : '' }}> (GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon</option>
                                            <option value="Europe/London" {{ $tz ? ($tz == 'Europe/London' ? 'selected' : '') : '' }}> (GMT+00:00) London</option>

                                            <option value="Europe/Amsterdam" {{ $tz ? ($tz == 'Europe/Amsterdam' ? 'selected' : '') : '' }}> (GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                            <option value="Europe/Belgrade" {{ $tz ? ($tz == 'Europe/Belgrade' ? 'selected' : '') : '' }}> (GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                            <option value="Europe/Brussels" {{ $tz ? ($tz == 'Europe/Brussels' ? 'selected' : '') : '' }}> (GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                            <option value="Europe/Sarajevo" {{ $tz ? ($tz == 'Europe/Sarajevo' ? 'selected' : '') : '' }}> (GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                            <option value="Africa/Lagos" {{ $tz ? ($tz == 'Africa/Lagos' ? 'selected' : '') : '' }}> (GMT+01:00) West Central Africa</option>
                                            <option value="Asia/Amman" {{ $tz ? ($tz == 'Asia/Amman' ? 'selected' : '') : '' }}> (GMT+02:00) Amman</option>
                                            <option value="Europe/Athens" {{ $tz ? ($tz == 'Europe/Athens' ? 'selected' : '') : '' }}> (GMT+02:00) Athens, Bucharest, Istanbul</option>
                                            <option value="Asia/Beirut" {{ $tz ? ($tz == 'Asia/Beirut' ? 'selected' : '') : '' }}>(GMT+02:00) Beirut</option>
                                            <option value="Africa/Cairo" {{ $tz ? ($tz == 'Africa/Cairo' ? 'selected' : '') : '' }}> (GMT+02:00) Cairo</option>
                                            <option value="Africa/Harare" {{ $tz ? ($tz == 'Africa/Harare' ? 'selected' : '') : '' }}> (GMT+02:00) Harare, Pretoria</option>
                                            <option value="Europe/Helsinki" {{ $tz ? ($tz == 'Europe/Helsinki' ? 'selected' : '') : '' }}> (GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                            <option value="Asia/Jerusalem" {{ $tz ? ($tz == 'Asia/Jerusalem' ? 'selected' : '') : '' }}> (GMT+02:00) Jerusalem</option>
                                            <option value="Europe/Minsk" {{ $tz ? ($tz == 'Europe/Minsk' ? 'selected' : '') : '' }}> (GMT+02:00) Minsk</option>
                                            <option value="Africa/Windhoek" {{ $tz ? ($tz == 'Africa/Windhoek' ? 'selected' : '') : '' }}> (GMT+02:00) Windhoek</option>
                                            <option value="Asia/Kuwait" {{ $tz ? ($tz == 'Asia/Kuwait' ? 'selected' : '') : '' }}>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                            <option value="Europe/Moscow" {{ $tz ? ($tz == 'Europe/Moscow' ? 'selected' : '') : '' }}> (GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                            <option value="Africa/Nairobi" {{ $tz ? ($tz == 'Africa/Nairobi' ? 'selected' : '') : '' }}> (GMT+03:00) Nairobi</option>
                                            <option value="Asia/Tbilisi" {{ $tz ? ($tz == 'Asia/Tbilisi' ? 'selected' : '') : '' }}> (GMT+03:00) Tbilisi</option>
                                            <option value="Asia/Tehran" {{ $tz ? ($tz == 'Asia/Tehran' ? 'selected' : '') : '' }}>(GMT+03:30) Tehran</option>
                                            <option value="Asia/Muscat" {{ $tz ? ($tz == 'Asia/Muscat' ? 'selected' : '') : '' }}>(GMT+04:00) Abu Dhabi, Muscat</option>
                                            <option value="Asia/Baku" {{ $tz ? ($tz == 'Asia/Baku' ? 'selected' : '') : '' }}> (GMT+04:00) Baku</option>
                                            <option value="Asia/Yerevan" {{ $tz ? ($tz == 'Asia/Yerevan' ? 'selected' : '') : '' }}> (GMT+04:00) Yerevan</option>
                                            <option value="Asia/Kabul" {{ $tz ? ($tz == 'Asia/Kabul' ? 'selected' : '') : '' }}> (GMT+04:30) Kabul</option>
                                            <option value="Asia/Yekaterinburg" {{ $tz ? ($tz == 'Asia/Yekaterinburg' ? 'selected' : '') : '' }}>(GMT+05:00) Yekaterinburg
                                            </option>
                                            <option value="Asia/Karachi" {{ $tz ? ($tz == 'Asia/Karachi' ? 'selected' : '') : '' }}> (GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                            <option value="Asia/Calcutta" {{ $tz ? ($tz == 'Asia/Calcutta' ? 'selected' : '') : '' }}> (GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                            <option value="Asia/Colombo"  {{ $tz ? ($tz == 'Asia/Colombo' ? 'selected' : '') : '' }}>(GMT+05:30) Sri Jayawardenapura</option>
                                            <option value="Asia/Katmandu" {{ $tz ? ($tz == 'Asia/Katmandu' ? 'selected' : '') : '' }}> (GMT+05:45) Kathmandu</option>
                                            <option value="Asia/Almaty" {{ $tz ? ($tz == 'Asia/Almaty' ? 'selected' : '') : '' }}>(GMT+06:00) Almaty, Novosibirsk</option>
                                            <option value="Asia/Dhaka" {{ $tz ? ($tz == 'Asia/Dhaka' ? 'selected' : '') : '' }}> (GMT+06:00) Astana, Dhaka</option>
                                            <option value="Asia/Rangoon" {{ $tz ? ($tz == 'Asia/Rangoon' ? 'selected' : '') : '' }}> (GMT+06:30) Yangon (Rangoon)</option>
                                            <option value="Asia/Bangkok" {{ $tz ? ($tz == 'Asia/Bangkok' ? 'selected' : '') : '' }}> (GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                            <option value="Asia/Krasnoyarsk" {{ $tz ? ($tz == 'Asia/Krasnoyarsk' ? 'selected' : '') : '' }}> (GMT+07:00) Krasnoyarsk</option>
                                            <option value="Asia/Hong_Kong" {{ $tz ? ($tz == 'Asia/Hong_Kong' ? 'selected' : '') : '' }}> (GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                            <option value="Asia/Kuala_Lumpur" {{ $tz ? ($tz == 'Asia/Kuala_Lumpur' ? 'selected' : '') : '' }}>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                            <option value="Asia/Irkutsk" {{ $tz ? ($tz == 'Asia/Irkutsk' ? 'selected' : '') : '' }}> (GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                            <option value="Australia/Perth" {{ $tz ? ($tz == 'Australia/Perth' ? 'selected' : '') : '' }}> (GMT+08:00) Perth</option>
                                            <option value="Asia/Taipei" {{ $tz ? ($tz == 'Asia/Taipei' ? 'selected' : '') : '' }}>(GMT+08:00) Taipei</option>
                                            <option value="Asia/Tokyo" {{ $tz ? ($tz == 'Asia/Tokyo' ? 'selected' : '') : '' }}> (GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                            <option value="Asia/Seoul" {{ $tz ? ($tz == 'Asia/Seoul' ? 'selected' : '') : '' }}> (GMT+09:00) Seoul</option>
                                            <option value="Asia/Yakutsk" {{ $tz ? ($tz == 'Asia/Yakutsk' ? 'selected' : '') : '' }}> (GMT+09:00) Yakutsk</option>
                                            <option value="Australia/Adelaide" {{ $tz ? ($tz == 'Australia/Adelaide' ? 'selected' : '') : '' }}>(GMT+09:30) Adelaide
                                            </option>
                                            <option value="Australia/Darwin" {{ $tz ? ($tz == 'Australia/Darwin' ? 'selected' : '') : '' }}> (GMT+09:30) Darwin</option>
                                            <option value="Australia/Brisbane" {{ $tz ? ($tz == 'Australia/Brisbane' ? 'selected' : '') : '' }}>(GMT+10:00) Brisbane
                                            </option>
                                            <option value="Australia/Canberra" {{ $tz ? ($tz == 'Australia/Canberra' ? 'selected' : '') : '' }}>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                            <option value="Australia/Hobart" {{ $tz ? ($tz == 'Australia/Hobart' ? 'selected' : '') : '' }}> (GMT+10:00) Hobart</option>
                                            <option value="Pacific/Guam" {{ $tz ? ($tz == 'Pacific/Guam' ? 'selected' : '') : '' }}> (GMT+10:00) Guam, Port Moresby</option>
                                            <option value="Asia/Vladivostok" {{ $tz ? ($tz == 'Asia/Vladivostok' ? 'selected' : '') : '' }}> (GMT+10:00) Vladivostok</option>
                                            <option value="Asia/Magadan" {{ $tz ? ($tz == 'Asia/Magadan' ? 'selected' : '') : '' }}> (GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                            <option value="Pacific/Auckland" {{ $tz ? ($tz == 'Pacific/Auckland' ? 'selected' : '') : '' }}> (GMT+12:00) Auckland, Wellington</option>
                                            <option value="Pacific/Fiji" {{ $tz ? ($tz == 'Pacific/Fiji' ? 'selected' : '') : '' }}> (GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                            <option value="Pacific/Tongatapu" {{ $tz ? ($tz == 'Pacific/Tongatapu' ? 'selected' : '') : '' }}>(GMT+13:00) Nuku'alofa
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($tf = \App\Models\BusinessSetting::where('key', 'timeformat')->first())
                                    @php($tf = $tf ? $tf->value : '24')
                                    <div class="form-group">
                                        <label
                                            class="input-label text-capitalize d-flex alig-items-center">{{ translate('messages.time_format') }}</label>
                                        <select name="time_format" class="form-control">
                                            <option value="12" {{ $tf == '12' ? 'selected' : '' }}>
                                                {{ translate('messages.12_hour') }}
                                            </option>
                                            <option value="24" {{ $tf == '24' ? 'selected' : '' }}>
                                                {{ translate('messages.24_hour') }}
                                            </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($currency_code = \App\Models\BusinessSetting::where('key', 'currency')->first())
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.currency') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                                        <select id="change_currency" name="currency" class="form-control js-select2-custom">
                                            @foreach (\App\Models\Currency::orderBy('currency_code')->get() as $currency)
                                                <option value="{{ $currency['currency_code'] }}"
                                                    {{ $currency_code ? ($currency_code->value == $currency['currency_code'] ? 'selected' : '') : '' }}>
                                                    {{ $currency['currency_code'] }} ( {{ $currency['currency_symbol'] }} )
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @php($currency_symbol_position = \App\Models\BusinessSetting::where('key', 'currency_symbol_position')->first())
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="currency_symbol_position">{{ translate('messages.currency_symbol_positon') }}</label>
                                        <select name="currency_symbol_position" class="form-control js-select2-custom"
                                            id="currency_symbol_position">
                                            <option value="left"
                                                {{ $currency_symbol_position ? ($currency_symbol_position->value == 'left' ? 'selected' : '') : '' }}>
                                                {{ translate('messages.left') }}
                                                ({{ \App\CentralLogics\Helpers::currency_symbol() }}123)
                                            </option>
                                            <option value="right"
                                                {{ $currency_symbol_position ? ($currency_symbol_position->value == 'right' ? 'selected' : '') : '' }}>
                                                {{ translate('messages.right') }}
                                                (123{{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-2">
                                    @php($digit_after_decimal_point = \App\Models\BusinessSetting::where('key', 'digit_after_decimal_point')->first())
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="digit_after_decimal_point">{{ translate('messages.Digit after decimal point') }}</label>
                                        <input type="number" name="digit_after_decimal_point" class="form-control"
                                            id="digit_after_decimal_point"
                                            value="{{ $digit_after_decimal_point ? $digit_after_decimal_point->value : 0 }}"
                                            min="0" max="4" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5">
                                    @php($footer_text = \App\Models\BusinessSetting::where('key', 'footer_text')->first())
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('copy_right_text') }} <img src="{{dynamicAsset('/public/assets/admin/img/info-circle.svg')}}" title="{{ translate('make_visitors_aware_of_your_business‘s_rights_&_legal_information') }}" data-toggle="tooltip" alt=""> </label>
                                        <textarea type="text" value="" name="footer_text" class="form-control" placeholder="" rows="3"
                                            required>{{ $footer_text->value ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-5">
                                    @php($cookies_text = \App\Models\BusinessSetting::where('key', 'cookies_text')->first())
                                    <div class="form-group">
                                        <label class="input-label">{{ translate('Cookies_Text') }} <img src="{{dynamicAsset('/public/assets/admin/img/info-circle.svg')}}" data-original-title="{{ translate('messages.make_visitors_aware_of_your_business‘s_rights_&_legal_information.') }}" data-toggle="tooltip" alt=""> </label>
                                        <textarea type="text" value="" name="cookies_text" class="form-control"
                                            placeholder="{{ translate('messages.Ex_:_Cookies_Text') }} " rows="3" required>{{ $cookies_text->value ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="card-title mb-3 pt-2">
                        <span class="card-header-icon mr-2">
                            <i class="tio-settings-outlined"></i>
                        </span>
                        <span>{{translate('messages.business_Rule_setting')}}</span>
                    </h4>

                    <div class="card">
                        <div class="card-body">

                            <div class="row">

                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($admin_commission = \App\Models\BusinessSetting::where('key', 'admin_commission')->first())
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.Default_commission') }} (%)
                                            &nbsp;
                                        <span class="line--limit-1"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Set_up_Default_Commission_on_evrey_order._Admin_can_set_restaurant_wise_different_commission_rates_from_respective_restaurant_settings.') }}">
                                        <img
                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}">
                                    </span>
                                        </label>
                                        <input type="number" name="admin_commission" class="form-control" id="admin_commission"
                                        value="{{ $admin_commission ? $admin_commission?->value : 0 }}" min="0" max="100" step="0.001"
                                        required>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($delivery_charge_comission = \App\Models\BusinessSetting::where('key', 'delivery_charge_comission')->first())
                                    <div class="form-group">
                                        <label class="form-label" for="exampleFormControlInput1">{{ translate('messages.Commission_on_Delivery_Charge') }} (%)
                                            &nbsp;
                                        <span class="line--limit-1"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{ translate('Set_a_default_Commission_Rate_for_freelance_deliverymen_(under_admin)_on_every_deliveryman') }}"><img
                                            src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}">
                                    </span>
                                        </label>
                                        <input type="number" name="admin_comission_in_delivery_charge" class="form-control" id="admin_comission_in_delivery_charge"
                                            min="0" max="100" step="0.001" value="{{ $delivery_charge_comission ? $delivery_charge_comission?->value: 0 }}">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($free_delivery_over = \App\Models\BusinessSetting::where('key', 'free_delivery_over')->first())
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                                <span class="line--limit-1">{{ translate('messages.free_delivery_over') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</span>
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If the order amount exceeds this amount the delivery fee will be free and the delivery fee will be deducted from the admin’s commission.')}}" class="input-label-secondary"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Set_a_minimum_order_value_for_free_delivery._If_someone’s_order_value_reaches_this_point,_he_will_get_a_free_delivery,_and_the_delivery_fee_will_be_deducted_from_Admin’s_commission_and_added_to_the_Admin’s_expense') }}"></span>
                                            </label>
                                            <label class="toggle-switch toggle-switch-sm" data-toggle="modal" data-target="#free-delivery-modal">
                                                <input type="checkbox" name="free_delivery_over_status"
                                                    id="free_delivery_over_status"


                                                    data-id="free_delivery_over_status"
                                                    data-type="toggle"
                                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/tax-on.png') }}"
                                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/tax-off.png') }}"
                                                    data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('Free_Delivery') }}</strong> {{ translate('on_Minimum_Orders') }} ?"
                                                    data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('Free_Delivery') }} {{ translate('on_Minimum_Orders') }}</strong> ?"
                                                    data-text-on="<p>{{ translate('If_enabled,_customers_can_get_FREE_Delivery_by_ordering_above_the_minimum_order_requirement') }}</p>"
                                                    data-text-off="<p>{{ translate('If_disabled,_the_FREE_Delivery_option_will_be_hidden_from_the_Customer_App_or_Website.') }}</p>"
                                                    class="status toggle-switch-input dynamic-checkbox-toggle"

                                                    value="1"

                                                    {{ isset($free_delivery_over->value) ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="number" name="free_delivery_over" class="form-control"
                                            id="free_delivery_over"
                                            value="{{ $free_delivery_over ? $free_delivery_over->value : 0 }}" min="0"
                                            step=".01" placeholder="{{ translate('messages.Ex :') }} 100" required {{ isset($free_delivery_over->value) ? '' : 'readonly' }}>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($vnv = \App\Models\BusinessSetting::where('key', 'toggle_veg_non_veg')->first())
                                    @php($vnv = $vnv ? $vnv->value : 0)
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" >
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Veg_/_Non_Veg_Option') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_enabled,_customers_can_filter_food_according_to_their_preference_from_the_Customer_App_or_Website.')}}">
                                            <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" id="veg_nonveg"  value="1" name="vnv"
                                            data-id="veg_nonveg"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/veg-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/veg-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Veg/Non_Veg') }}</strong> {{ translate('Option') }} ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Veg/Non_Veg') }} {{ translate('Option') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_customers_can_filter_food_items_by_choosing_food_from_the_Veg/Non-Veg_option') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_Veg/Non-Veg_option_will_be_hidden_in_both_the_Restaurant_panel_&_App_and_Website') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"
                                            {{ $vnv == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($business_model = \App\Models\BusinessSetting::where('key', 'business_model')->first())
                                    @php($business_model = $business_model->value ? json_decode($business_model->value, true) : 0)
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Commission Base Business Model') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{translate('If_enabled,_the_commission_based_business_model_option_will_be_available_for_restaurants.')}}">
                                        <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox" value="1" name="commission" id="commission_id"
                                            data-id="commission_id"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Commission_Base') }}</strong> {{ translate('Business_Model') }} ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Commission_Base') }} {{ translate('Business_Model') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_the_commission_based_restaurant_business_model_option_will_be_available_for_this_restaurant') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_commission_based_restaurant_business_model_option_will_be_hidden_from_this_restaurant_panel._And_it_can_only_use_the_subscription_based_business_model') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"

                                            {{  $business_model['commission'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('Subscription Base Business Model ') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right"
                                        data-original-title="{{translate('If_enabled,_the_package_based_subscription_business_model_option_will_be_available_for_restaurants')}}">
                                        <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox"  value="1" name="subscription" id='subscription_id'
                                            data-id="subscription_id"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                            data-title-on="{{ translate('Want_to_enable_the') }} <strong>{{ translate('Subscription_Base') }}</strong> {{ translate('Business_Model') }} ?"
                                            data-title-off="{{ translate('Want_to_disable_the') }} <strong>{{ translate('Subscription_Base') }} {{ translate('Business_Model') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_the_subscription_based_restaurant_business_model_option_will_be_available_in_this_restaurant') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_subscription_based_restaurant_business_model_option_will_be_hidden_from_this_restaurant_panel._The_existing_subscribed_restaurants’_subscriptions_will_be_valid_till_the_packages_expire') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"

                                            {{  $business_model['subscription'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($tax_included = \App\Models\BusinessSetting::where('key', 'tax_included')->first())
                                    @php($tax_included = $tax_included ? $tax_included->value : 0)
                                    <div class="form-group">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control" >
                                        <span class="pr-2 d-flex align-items-center"><span class="line--limit-1">{{ translate('messages.Include_TAX_Amount') }}</span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.If_enabled,_the_customer_will_see_the_total_food_price,_including_VAT/TAX._If_it’s_disabled,_the_VAT/TAX_will_be_calculated_separately_from_the_total_cost_of_the_food,_and_he_can_see_a_separate_VAT/TAX_amount_on_the_invoice')}}">
                                            <i class="tio-info-outined"></i>
                                            </span></span>
                                            <input type="checkbox"  value="1" id="tax_included"
                                            data-id="tax_included"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/tax-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/tax-off.png') }}"
                                            data-title-on="{{ translate('Want_to_Enable') }} <strong>{{ translate('Tax_Amount') }} </strong> ?"
                                            data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('Tax_Amount') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_customers_will_see_the_food_Price_added_with_Tax') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_customers_will_see_the_food_price_without_Tax') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"
                                            name="tax_included"
                                            {{ $tax_included == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>





                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($admin_order_notification = \App\Models\BusinessSetting::where('key', 'admin_order_notification')->first())
                                    @php($admin_order_notification = $admin_order_notification ? $admin_order_notification?->value : 0)
                                    <div class="form-group">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                                        <span class="pr-2 d-flex align-items-center">
                                            <span class="line--limit-1">{{translate('Order_Notification_for_Admin')}}</span><span class="input-label-secondary text--title" data-original-title="{{ translate('Admin_will_get_a_pop-up_notification_with_sounds_for_every_order_placed_by_customers') }}" data-toggle="tooltip" data-placement="right">
                                            <i class="tio-info-outined"></i>
                                            </span>
                                        </span>
                                            <input type="checkbox" id="admin_notification"
                                            value="1"
                                            name="admin_order_notification"
                                            {{ $admin_order_notification == 1 ? 'checked' : '' }}
                                            data-id="admin_notification"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/order-notification-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/order-notification-off.png') }}"
                                            data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('Order_Notification_for_Admin') }}</strong> ?"
                                            data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('Order_Notification_for_Admin') }}</strong> ?"
                                            data-text-on="<p>{{ translate('If_enabled,_the_Admin_will_receive_a_Notification_for_every_order_placed_by_customers') }}</p>"
                                            data-text-off="<p>{{ translate('If_disabled,_the_Admin_will_NOT_receive_any_Notification_for_any_order_placed_by_customers') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"  >
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>




                                <div class="col-sm-6 col-lg-4">
                                    @php($order_notification_type = \App\Models\BusinessSetting::where('key', 'order_notification_type')->first())
                                    <div class="form-group mb-0">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="order_notification_type">{{ translate('Order_Notification_Type') }}
                                           <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('For_Firebase_a_single_real-time_notification_will_be_sent_upon_order_placement_with_no_repetition._For_the_Manual_option_notifications_will_appear_at_10-second_intervals_until_the_order_is_viewed.')}}">
                                                <i class="tio-info-outined"></i>
                                                </span>
                                        </label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="firebase" name="order_notification_type" {{ $order_notification_type ? ($order_notification_type->value == 'firebase' ? 'checked' : '') : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('firebase')}}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="manual" name="order_notification_type" {{ $order_notification_type ? ($order_notification_type->value == 'manual' ? 'checked' : '') : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('manual')}}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-6 col-lg-4">
                                    @php($additional_charge_status = \App\Models\BusinessSetting::where('key', 'additional_charge_status')->first())
                                    @php($additional_charge_status = $additional_charge_status ? $additional_charge_status->value : 0)
                                    <div class="form-group mb-0">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.If_enabled,_customers_need_to_pay_an_extra_charge_while_checking_out_orders.')}}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.customer_varification_toggle') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="additional_charge_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                            data-title-on="<strong>{{ translate('messages.Want_to_enable_additional_charge?') }}</strong>"
                                            data-title-off="<strong>{{ translate('messages.Want_to_disable_additional_charge?') }}</strong>"
                                            data-text-on="<p>{{ translate('messages.If_you_enable_this,_additional_charge_will_be_added_with_order_amount,_it_will_be_added_in_admin_wallet') }}</p>"
                                            data-text-off="<p>{{ translate('messages.If_you_disable_this,_additional_charge_will_not_be_added_with_order_amount.') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"
                                            value="1"
                                                name="additional_charge_status" id="additional_charge_status"
                                                {{ $additional_charge_status == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    @php($additional_charge_name = \App\Models\BusinessSetting::where('key', 'additional_charge_name')->first())
                                    <div class="form-group ">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="additional_charge_name">
                                            <span class="line--limit-1">{{ translate('messages.additional_charge_name') }}
                                                <small
                                                class="text-danger"><span class="form-label-secondary"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.Set_a_name_for_the_additional_charge,_e.g._“Processing_Fee”.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.free_over_delivery_message') }}"></span>
                                                *</small></span>
                                        </label>

                                        <input type="text" name="additional_charge_name" class="form-control"
                                            id="additional_charge_name"  placeholder="{{ translate('messages.Ex:_Processing_Fee') }}"
                                            value="{{ $additional_charge_name ? $additional_charge_name->value : '' }}" {{ isset($additional_charge_status) ? ' required' : 'readonly' }}>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    @php($additional_charge = \App\Models\BusinessSetting::where('key', 'additional_charge')->first())
                                    <div class="form-group ">
                                        <label class="input-label text-capitalize d-flex alig-items-center"
                                            for="additional_charge">
                                            <span class="line--limit-1">{{ translate('messages.charge_amount') }}
                                                ({{ \App\CentralLogics\Helpers::currency_symbol() }}) <small
                                                class="text-danger"><span class="form-label-secondary"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.Set_the_value_(amount)_customers_need_to_pay_as_additional_charge.') }}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.free_over_delivery_message') }}"></span>
                                                *</small></span>
                                        </label>

                                        <input type="number" name="additional_charge" class="form-control"
                                            id="additional_charge"  placeholder="{{ translate('messages.Ex:_10') }}"
                                            value="{{ $additional_charge ? $additional_charge->value : 0 }}"
                                            min="0" step=".01" {{ isset($additional_charge_status) ? 'required ' : 'readonly' }}>
                                    </div>
                                </div>


                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($partial_payment = \App\Models\BusinessSetting::where('key', 'partial_payment_status')->first())
                                    @php($partial_payment = $partial_payment ? $partial_payment->value : 0)
                                    <div class="form-group">
                                        <label class="form-label d-none d-sm-block">&nbsp;</label>
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.partial_payment') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.If_enabled,_customers_can_make_partial_payments._For_example,_a_customer_can_pay_$20_initially_out_of_their_$50_payment_&_use_other_payment_methods_for_the_rest._Partial_payments_must_be_made_through_their_wallets.')}}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.customer_varification_toggle') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="partial_payment"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/schedule-off.png') }}"
                                            data-title-on="{{ translate('messages.Want_to_enable') }} <strong>{{ translate('messages.partial_payment_?') }}</strong>"
                                            data-title-off="{{ translate('messages.Want_to_disable') }} <strong>{{ translate('messages.partial_payment_?') }}</strong>"
                                            data-text-on="<p>{{ translate('messages.If_you_enable_this,_customers_can_choose_partial_payment_during_checkout.') }}</p>"
                                            data-text-off="<p>{{ translate('messages.If_you_disable_this,_the_partial_payment_feature_will_be_hidden.') }}</p>"
                                            class="toggle-switch-input dynamic-checkbox-toggle"
                                            value="1"
                                                name="partial_payment_status" id="partial_payment"
                                                {{ $partial_payment == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($partial_payment_method = \App\Models\BusinessSetting::where('key', 'partial_payment_method')->first())
                                    <div class="form-group">

                                        <label class="input-label text-capitalize d-flex alig-items-center"><span
                                            class="line--limit-1">{{ translate('Can_Pay_the_Rest_Amount_using') }}
                                        <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.Set_the_method(s)_that_customers_can_pay_the_remainder_after_partial_payment.') }}" alt="">
                                            <img src="{{dynamicAsset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                        </span>
                                        </span>
                                    </label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="cod" name="partial_payment_method" {{ $partial_payment_method ? ($partial_payment_method->value == 'cod' ? 'checked' : '') : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('cod')}}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="digital_payment" name="partial_payment_method" {{ $partial_payment_method ? ($partial_payment_method->value == 'digital_payment' ? 'checked' : '') : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('digital_payment')}}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="both" name="partial_payment_method" {{ $partial_payment_method ? ($partial_payment_method->value == 'both' ? 'checked' : '') : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('both')}}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-6">
                                    @php($free_delivery_distance = \App\Models\BusinessSetting::where('key', 'free_delivery_distance')->first())
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                                <span class="line--limit-1">{{ translate('messages.free_delivery_distance') }} (km)</span>
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Within_this_distance_the_delivery_fee_will_be_free_and_the_delivery_fee_will_be_deducted_from_the_admin’s_commission.')}}" class="input-label-secondary"><img src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.Enter_the_distance_for_free_delivery._Charges_will_apply_after_exceeding_that_distance.') }}"></span>
                                            </label>
                                            <label class="toggle-switch toggle-switch-sm" data-toggle="modal" data-target="#free-delivery-modal">
                                                <input type="checkbox" name="free_delivery_distance_status"
                                                    id="free_delivery_distance_status"
                                                    data-id="free_delivery_distance_status"
                                                    data-type="toggle"
                                                    data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/free-delivery-on.png') }}"
                                                    data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/free-delivery-off.png') }}"
                                                    data-title-on="{{ translate('Want_to_enable') }} <strong>{{ translate('Free_Delivery') }}</strong> {{ translate('on_Minimum_Distance') }} ?"
                                                    data-title-off="{{ translate('Want_to_disable') }} <strong>{{ translate('Free_Delivery') }} {{ translate('on_Minimum_Distance') }}</strong> ?"
                                                    data-text-on="<p>{{ translate('If_enabled,_customers_can_get_FREE_Delivery_by_ordering_above_the_minimum_order_requirement') }}</p>"
                                                    data-text-off="<p>{{ translate('If_disabled,_the_FREE_Delivery_option_will_be_hidden_from_the_Customer_App_or_Website.') }}</p>"
                                                    class="status toggle-switch-input dynamic-checkbox-toggle"
                                                    value="1"
                                                    {{ isset($free_delivery_distance->value) ? 'checked' : '' }}>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="number" name="free_delivery_distance" class="form-control"
                                            id="free_delivery_distance"
                                            value="{{ $free_delivery_distance ? $free_delivery_distance->value : 0 }}" min="0" max="999999999"
                                            step=".001" placeholder="{{ translate('messages.Ex :') }} 100" required {{ isset($free_delivery_distance->value) ? '' : 'readonly' }}>
                                    </div>
                                </div>




                                <div class="col-sm-6 col-lg-4">
                                    @php($guest_checkout_status = \App\Models\BusinessSetting::where('key', 'guest_checkout_status')->first())
                                    @php($guest_checkout_status = $guest_checkout_status ? $guest_checkout_status->value : 0)
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{translate('messages.guest_checkout') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.If_enabled,_customers_do_not_have_to_login_while_checking_out_orders.')}}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.customer_varification_toggle') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="guest_checkout_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-on.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/dm-tips-off.png') }}"
                                            data-title-on="<strong>{{ translate('messages.Want_to_enable_guest_checkout?') }}</strong>"
                                            data-title-off="<strong>{{ translate('messages.Want_to_disable_guest_checkout?') }}</strong>"
                                            data-text-on="<p>{{ translate('messages.If_you_enable_this,_guest_checkout_will_be_visible_when_customer_is_not_logged_in.') }}</p>"
                                            data-text-off="<p>{{ translate('messages.If_you_disable_this,_guest_checkout_will_not_be_visible_when_customer_is_not_logged_in.') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"
                                            value="1"
                                                name="guest_checkout_status" id="guest_checkout_status"
                                                {{ $guest_checkout_status == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    @php($country_picker_status = \App\Models\BusinessSetting::where('key', 'country_picker_status')->first())
                                    @php($country_picker_status = $country_picker_status ? $country_picker_status->value : 0)
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{translate('messages.country_picker') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('messages.If_you_enable_this_option,_in_all_phone_no_field_will_show_a_country_picker_list.')}}"><img
                                                        src="{{ dynamicAsset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.customer_varification_toggle') }}">
                                                </span>
                                            </span>
                                            <input type="checkbox"
                                            data-id="country_picker_status"
                                            data-type="toggle"
                                            data-image-on="{{ dynamicAsset('/public/assets/admin/img/modal/mail-success.png') }}"
                                            data-image-off="{{ dynamicAsset('/public/assets/admin/img/modal/mail-warning.png') }}"
                                            data-title-on="<strong>{{ translate('messages.Want_to_enable_country_picker?') }}</strong>"
                                            data-title-off="<strong>{{ translate('messages.Want_to_disable_country_picker?') }}</strong>"
                                            data-text-on="<p>{{ translate('messages.If_you_enable_this,_user_can_select_country_from_country_picker') }}</p>"
                                            data-text-off="<p>{{ translate('messages.If_you_disable_this,_user_can_not_select_country_from_country_picker,_default_country_will_be_selected') }}</p>"
                                            class="status toggle-switch-input dynamic-checkbox-toggle"
                                            value="1"
                                                name="country_picker_status" id="country_picker_status"
                                                {{ $country_picker_status == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn--reset location-reload">{{ translate('messages.Reset') }} </button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                class="btn btn--primary call-demo"><i class="tio-save-outlined mr-2"></i>{{ translate('messages.save_info')}}</button>
                            </div>
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>


    <div class="modal fade" id="currency-warning-modal">
        <div class="modal-dialog modal-dialog-centered status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img width="80" src="{{  dynamicAsset('public/assets/admin/img/modal/currency.png') }}" class="mb-20">
                                <h5 class="modal-title"></h5>
                            </div>
                            <div class="text-center" >
                                <h3 > {{ translate('Are_you_sure_to_change_the_currency_?') }}</h3>
                                <div > <p>{{ translate('If_you_enable_this_currency,_you_must_active_at_least_one_digital_payment_method_that_supports_this_currency._Otherwise_customers_cannot_pay_via_digital_payments_from_the_app_and_websites._And_Also_restaurants_cannot_pay_you_digitally') }}</h3></p></div>
                            </div>

                            <div class="text-center mb-4" >
                                <a class="text--underline" href="{{ route('admin.business-settings.payment-method') }}"> {{ translate('Go_to_payment_method_settings.') }}</a>
                            </div>
                            </div>

                        <div class="btn--container justify-content-center">
                            <button data-dismiss="modal" id="confirm-currency-change" class="btn btn--cancel min-w-120" >{{translate("Cancel")}}</button>
                            <button data-dismiss="modal"   type="button"  class="btn btn--primary min-w-120">{{translate('OK')}}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="maintenance-off-mode-modal">
        <div class="modal-dialog modal-dialog-centered status-warning-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <form method="post" action="{{route('admin.maintenance-mode')}}">
                    @csrf
                    <input type="hidden" name="maintenance_mode_off" value="1">
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img width="80" src="{{  dynamicAsset('public/assets/admin/img/modal/maintenance-off.png') }}" class="mb-20">
                                <h5 class="modal-title">{{ translate('Are you sure you?') }}</h5>
                            </div>
                            <div class="text-center" >
                                {{-- <h3 > {{ translate('Are_you_sure_to_change_the_currency_?') }}</h3> --}}
                                <div > <p>{{ translate('Do you want to turn off Maintenance mode? Turning it off will activate all systems that were deactivated.') }}</h3></p></div>
                            </div>


                            </div>

                        <div class="btn--container justify-content-center">
                            <button data-dismiss="modal" type="button" class="btn btn--cancel min-w-120" >{{translate("Cancel")}}</button>
                            <button  type="submit"  class="btn btn--primary min-w-120">{{translate('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>





    <div class="modal fade" id="maintenance-mode-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pt-3">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <form method="post" action="{{route('admin.maintenance-mode')}}">
                    @csrf
                    <div class="modal-body pt-3 px-0">
                        @csrf
                        <div class="d-flex flex-column gap-4">
                            <div class="px-4">
                                <div class="row mb-4">
                                    <div class="col-xl-4">
                                        <h5 class="mb-2">{{ translate('Select System') }}</h5>
                                        <p>{{ translate('Select the systems you want to temporarily deactivate for maintenance') }}</p>
                                    </div>
                                    <div class="col-xl-8">
                                        <div class="border p-3 p-sm-4 rounded">
                                            <div class="d-flex flex-wrap gap-4">
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="all_system" type="checkbox"
                                                        {{ in_array('admin_panel', $selectedMaintenanceSystem) &&
                                                                in_array('restaurant_panel', $selectedMaintenanceSystem) &&
                                                                in_array('user_mobile_app', $selectedMaintenanceSystem) &&
                                                                in_array('user_web_app', $selectedMaintenanceSystem) &&
                                                                in_array('react_website', $selectedMaintenanceSystem) &&
                                                                in_array('deliveryman_app', $selectedMaintenanceSystem) &&
                                                                in_array('restaurant_app', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="allSystem">
                                                    <label class="form-check-label" for="allSystem">{{ translate('All System') }}</label>
                                                </div>

                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="restaurant_panel" type="checkbox"
                                                        {{ in_array('restaurant_panel', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="restaurant_panel">
                                                    <label class="form-check-label" for="restaurant_panel">{{ translate('Restaurant Panel') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="user_mobile_app" type="checkbox"
                                                        {{ in_array('user_mobile_app', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="user_mobile_app">
                                                    <label class="form-check-label" for="user_mobile_app">{{ translate('User Mobile App') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="user_web_app" type="checkbox"
                                                        {{ in_array('user_web_app', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="user_web_app">
                                                    <label class="form-check-label" for="user_web_app">{{ translate('User Website') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="react_website" type="checkbox"
                                                        {{ in_array('react_website', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="react_website">
                                                    <label class="form-check-label" for="react_website">{{ translate('React Website') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="deliveryman_app" type="checkbox"
                                                        {{ in_array('deliveryman_app', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="deliveryman_app">
                                                    <label class="form-check-label" for="deliveryman_app">{{ translate('Deliveryman App') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input system-checkbox" name="restaurant_app" type="checkbox"
                                                        {{ in_array('restaurant_app', $selectedMaintenanceSystem) ? 'checked' :'' }}
                                                        id="restaurant_app">
                                                    <label class="form-check-label" for="restaurant_app">{{ translate('Restaurant App') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-xl-4">
                                        <h5 class="mb-2">{{ translate('Maintenance Date') }} & {{ translate('Time') }}</h5>
                                        <p>{{ translate('Choose the maintenance mode duration for your selected system.') }}</p>
                                    </div>
                                    <div class="col-xl-8">
                                        <div class="border p-3 p-sm-4 rounded">
                                            <div class="d-flex flex-wrap gap-4 mb-3">
                                                <div class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="maintenance_duration"
                                                        {{ isset($selectedMaintenanceDuration['maintenance_duration']) && $selectedMaintenanceDuration['maintenance_duration'] == 'one_day' ? 'checked' : '' }}
                                                        value="one_day" id="one_day">
                                                    <label class="form-check-label opacity-100" for="one_day">{{ translate('For 24 Hours') }}</label>
                                                </div>
                                                <div class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="maintenance_duration"
                                                        {{ isset($selectedMaintenanceDuration['maintenance_duration']) && $selectedMaintenanceDuration['maintenance_duration'] == 'one_week' ? 'checked' : '' }}
                                                        value="one_week" id="one_week">
                                                    <label class="form-check-label opacity-100" for="one_week">{{ translate('For 1 Week') }}</label>
                                                </div>
                                                <div class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="maintenance_duration"
                                                        {{ isset($selectedMaintenanceDuration['maintenance_duration']) && $selectedMaintenanceDuration['maintenance_duration'] == 'until_change' ? 'checked' : '' }}
                                                        value="until_change" id="until_change">
                                                    <label class="form-check-label opacity-100" for="until_change">{{ translate('Until I change') }}</label>
                                                </div>
                                                <div class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="maintenance_duration"
                                                        {{ isset($selectedMaintenanceDuration['maintenance_duration']) && $selectedMaintenanceDuration['maintenance_duration'] == 'customize' ? 'checked' : '' }}
                                                        value="customize" id="customize">
                                                    <label class="form-check-label opacity-100" for="customize">{{ translate('Customize') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ translate('Start Date') }}</label>
                                                    <input type="datetime-local" class="form-control h-40" name="start_date" id="startDate"
                                                        value="{{ old('start_date', $selectedMaintenanceDuration['start_date'] ?? '') }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ translate('End Date') }}</label>
                                                    <input type="datetime-local" class="form-control h-40" name="end_date" id="endDate"
                                                        value="{{ old('end_date', $selectedMaintenanceDuration['end_date'] ?? '') }}" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <small id="dateError" class="form-text text-danger" style="display: none;">{{ translate('Start date cannot be greater than end date.') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="advanceFeatureButtonDiv">
                            <div class="px-4">
                                <a type="button" href="#" id="advanceFeatureToggle" class="text--base fw-semibold d-block maintenance-advance-feature-button">{{ translate('Advance Feature') }} <i class="tio-arrow-drop-down-circle-outlined"></i> </a>
                            </div>
                        </div>
                        <div class="px-4">
                            <div class="row" id="advanceFeatureSection" style="display: none;">
                                <div class="col-xl-4">
                                    <h5 class="mb-2">{{ translate('Maintenance Massage') }}</h5>
                                    <p>{{ translate('Select & type what massage you want to see your selected system when maintenance mode is active.') }}</p>
                                </div>
                                <div class="col-xl-8">
                                    <div class="border rounded p-3">
                                        <div class="form-group">
                                            <label class="form-label">{{ translate('Contact us through') }}</label>
                                            <div class="d-flex flex-wrap gap-5 mb-3">
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input" type="checkbox" name="business_number"
                                                        {{ isset($selectedMaintenanceMessage['business_number']) && $selectedMaintenanceMessage['business_number'] == 1 ? 'checked' : '' }}
                                                        id="businessNumber">
                                                    <label class="form-check-label" for="businessNumber">{{ translate('Business Number') }}</label>
                                                </div>
                                                <div class="form-check form--check m-0">
                                                    <input class="form-check-input" type="checkbox" name="business_email"
                                                        {{ isset($selectedMaintenanceMessage['business_email']) && $selectedMaintenanceMessage['business_email'] == 1 ? 'checked' : '' }}
                                                        id="businessEmail">
                                                    <label class="form-check-label" for="businessEmail">{{ translate('Business Email') }}</label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{ translate('Message Title') }}</label>
                                            <input type="text" class="form-control h-40" name="maintenance_message" placeholder="{{ translate('We are Working On Something Special!') }}"
                                                maxlength="200" value="{{ $selectedMaintenanceMessage['maintenance_message'] ?? '' }}">
                                        </div>
                                        <div class="form-group mt-3">
                                            <label class="form-label">{{ translate('Message Details') }}</label>
                                            <input type="text" class="form-control h-40" name="message_body" placeholder="{{ translate('We are Working On Something Special!') }}"
                                            maxlength="200" value="{{ $selectedMaintenanceMessage['message_body'] ?? '' }}">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex ml-5 mt-4">
                                        <a type="button" href="#" id="seeLessToggle" class="text--base fw-semibold d-block mb-3 maintenance-advance-feature-button">{{ translate('Advance Feature') }} <i class="tio-arrow-drop-up-circle-outlined" ></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex flex-wrap gap-3 justify-content-end">
                            <button data-dismiss="modal" class="btn btn--cancel" data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary {{env('APP_MODE') =='demo'? 'demo_check':''}}">{{ translate('Active') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>











@endsection

@push('script_2')
<script
src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&v=3.45.8">
</script>
    <script>
        "use strict";


        $(document).on('click', '.demo_check', function (event) {
            toastr.warning('{{ translate('Sorry! You can not enable maintenance mode in demo!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
            event.preventDefault();
        });


        $(document).on('click', '.maintenance-mode', function (event) {
            event.preventDefault();
            $('#maintenance-mode-modal').modal('show');

        });
        $(document).on('click', '.turn_off_maintenance_mode', function (event) {
            event.preventDefault();
            $('#maintenance-off-mode-modal').modal('show');
        });


        $('#advanceFeatureToggle').click(function (event) {
                event.preventDefault();
                $('#advanceFeatureSection').show();
                $('#advanceFeatureButtonDiv').hide();
            });

            $('#seeLessToggle').click(function (event) {
                event.preventDefault();
                $('#advanceFeatureSection').hide();
                $('#advanceFeatureButtonDiv').show();
            });

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#favIconUpload").change(function() {
            readURL(this, 'iconViewer');
        });

        function initAutocomplete() {
            var myLatLng = {
                lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                    lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                        }
                    }
                });
            });
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length === 0) {
                    return;
                }
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        $(document).on('ready', function() {
            initAutocomplete();
            @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())

            @if ($country)
                $("#country option[value='{{ $country->value }}']").attr('selected', 'selected').change();
            @endif

        });

        $(document).on("keydown", "input", function(e) {
            if (e.which === 13) e.preventDefault();
        });
        $(document).ready(function() {
            let selectedCurrency = "{{ $currency_code ? $currency_code->value : 'USD' }}";
            let currencyConfirmed = false;
            let updatingCurrency = false;

            $("#change_currency").change(function() {
                if (!updatingCurrency) check_currency($(this).val());
            });

            $("#confirm-currency-change").click(function() {
                currencyConfirmed = true;
                update_currency(selectedCurrency);
                $('#currency-warning-modal').modal('hide');
            });

            function check_currency(currency) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: "{{route('admin.system_currency')}}",
                    method: 'GET',
                    data: { currency: currency },
                    success: function(response) {
                        if (response.data) {
                            $('#currency-warning-modal').modal('show');
                        } else {
                            update_currency(currency);
                        }
                    }
                });
            }

            function update_currency(currency) {
                if (currencyConfirmed) {
                    updatingCurrency = true;
                    $("#change_currency").val(currency).trigger('change');
                    updatingCurrency = false;
                    currencyConfirmed = false;
                }
            }
        });



        $(document).ready(function() {

            $('#advanceFeatureToggle').click(function (event) {
                event.preventDefault();
                $('#advanceFeatureSection').show();
                $('#advanceFeatureButtonDiv').hide();
            });

            $('#seeLessToggle').click(function (event) {
                event.preventDefault();
                $('#advanceFeatureSection').hide();
                $('#advanceFeatureButtonDiv').show();
            });

            $('#allSystem').change(function () {
                var isChecked = $(this).is(':checked');
                $('.system-checkbox').prop('checked', isChecked);
            });

            $('.system-checkbox').not('#allSystem').change(function () {
                if (!$(this).is(':checked')) {
                    $('#allSystem').prop('checked', false);
                } else {
                    if ($('.system-checkbox').not('#allSystem').length === $('.system-checkbox:checked').not('#allSystem').length) {
                        $('#allSystem').prop('checked', true);
                    }
                }
            }).trigger('change');;

            $(document).ready(function () {
                var startDate = $('#startDate');
                var endDate = $('#endDate');
                var dateError = $('#dateError');

                function updateDatesBasedOnDuration(selectedOption) {
                    if (selectedOption === 'one_day' || selectedOption === 'one_week') {
                        var now = new Date();
                        var timezoneOffset = now.getTimezoneOffset() * 60000;
                        var formattedNow = new Date(now.getTime() - timezoneOffset).toISOString().slice(0, 16);

                        if (selectedOption === 'one_day') {
                            var end = new Date(now);
                            end.setDate(end.getDate() + 1);
                        } else if (selectedOption === 'one_week') {
                            var end = new Date(now);
                            end.setDate(end.getDate() + 7);
                        }

                        var formattedEnd = new Date(end.getTime() - timezoneOffset).toISOString().slice(0, 16);

                        startDate.val(formattedNow).prop('readonly', false).prop('required', true);
                        endDate.val(formattedEnd).prop('readonly', false).prop('required', true);
                        startDate.closest('div').css('display', 'block');
                        endDate.closest('div').css('display', 'block');
                        dateError.hide();
                    } else if (selectedOption === 'until_change') {
                        startDate.val('').prop('readonly', true).prop('required', false);
                        endDate.val('').prop('readonly', true).prop('required', false);
                        startDate.closest('div').css('display', 'none');
                        endDate.closest('div').css('display', 'none');
                        dateError.hide();
                    } else if (selectedOption === 'customize') {
                        startDate.prop('readonly', false).prop('required', true);
                        endDate.prop('readonly', false).prop('required', true);
                        startDate.closest('div').css('display', 'block');
                        endDate.closest('div').css('display', 'block');
                        dateError.hide();
                    }
                }

                function validateDates() {
                    var start = new Date(startDate.val());
                    var end = new Date(endDate.val());
                    if (start >= end) {
                        dateError.show();
                        startDate.val('');
                        endDate.val('');
                    } else {
                        dateError.hide();
                    }
                }

                var selectedOption = $('input[name="maintenance_duration"]:checked').val();
                updateDatesBasedOnDuration(selectedOption);

                $('input[name="maintenance_duration"]').change(function () {
                    var selectedOption = $(this).val();
                    updateDatesBasedOnDuration(selectedOption);
                });

                $('#startDate, #endDate').change(function () {
                    $('input[name="maintenance_duration"][value="customize"]').prop('checked', true);
                    startDate.prop('readonly', false).prop('required', true);
                    endDate.prop('readonly', false).prop('required', true);
                    validateDates();
                });
            });

        });




    </script>
@endpush
