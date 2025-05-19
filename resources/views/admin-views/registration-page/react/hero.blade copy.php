@php use App\CentralLogics\Helpers; @endphp
@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))
@section('content')

    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title text-capitalize">
                    <!-- <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ dynamicAsset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                    </div> -->
                    <span>
                        {{ translate('React_Registration_Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.registration-page.top_menu.react_registration_menu')
            </div>
        </div>

         <!-- StackFood 8.2 Version Structure -->
        <!-- Registration From -->
        <div class="">  
           <h1 class="page-header-title mb-5">Registration from</h1>
           <!-- Hero -->
           <div class="js-nav-scroller hs-nav-scroller-horizontal mb-4">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">FAQ</a>
                    </li>
                </ul>
            </div>
            <div class="page--header-title mb-3">
                <h1 class="page-header-title">Hero Section Image</h1>
            </div>
            <div class="card p-sm-4 p-3">
                <h4 class="text-center mb-5 mt-md-4 mt-2">Upload Image</h4>
                <!-- Drag & Drop Upload -->
                <div class="registration-upload mb-5">
                    <div class="image-box">
                        <label for="image-input" class="d-flex flex-column align-items-center justify-content-center h-100 cursor-pointer gap-1">
                        <img width="30" class="upload-icon mb-2" src="http://localhost/teststacfood/public/assets/admin/img/image-upload.png" alt="Upload Icon">                        
                        <span class="drag-text upload-icon fs-12">
                            <span class="clickupload-text mb-0 upload-icon ">Click to upload</span> <br>
                            or drag and drop
                        </span>                        
                        <img src="#" alt="Preview Image" class="preview-image">
                        </label>
                        <button type="button" class="delete_image">
                            <i class="tio-delete"></i>
                        </button>
                        <input type="file" id="image-input" name="image" accept="image/*" hidden="">
                    </div>
                </div>
                <p class="text-center image-formate fs-12">JPG, JPEG, PNG Less Than 5MB <span class="">(1200 x 750 px)</span></p>
                <div class="btn--container justify-content-sm-end mt-lg-8 mt-4">
                    <button type="reset" class="btn btn--reset">Reset</button>
                    <button type="submit" class="btn btn--primary">Add</button>
                </div>
            </div>
            <!-- Hero End -->

            <!-- Steeper -->
           <div class="pt-8 pb-8 registration-form-wrapper">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
                    <li class="nav-item">
                        <a class="nav-link " href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">FAQ</a>
                    </li>
                </ul>
                <div class="card">
                    <div class=" steeper-header-title border-bottom my-4 pb-4 px-4">
                        <div class="page--header-title mb-2">
                            <h1 class="page-header-title">Add Steeper</h1>
                        </div>
                        <p class="mb-0">Setup the information that you want to highlight for the restaurants</p>
                    </div>
                    <ul class="nav nav-tabs mx-4">
                        <li class="nav-item">
                            <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                        </li>
                    </ul>
                    <div class="row g-3 m-2">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 1</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>                                        
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn delete-custom" style="opacity: 0;">
                                                <i class="tio-delete"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                        <g id="fi_8191581">
                                                        <g id="image-upload">
                                                        <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                        <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                        </g>
                                                        </g>
                                                    </svg>
                                                    <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                        <span class="text-info">Click to upload</span>
                                                        <br>
                                                        Or drag and drop
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 2</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn delete-custom" style="opacity: 0;">
                                                <i class="tio-delete"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                        <g id="fi_8191581">
                                                        <g id="image-upload">
                                                        <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                        <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                        </g>
                                                        </g>
                                                    </svg>
                                                    <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                        <span class="text-info">Click to upload</span>
                                                        <br>
                                                        Or drag and drop
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 3</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn delete-custom" style="opacity: 0;">
                                                <i class="tio-delete"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                        <g id="fi_8191581">
                                                        <g id="image-upload">
                                                        <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                        <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                        </g>
                                                        </g>
                                                    </svg>
                                                    <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                        <span class="text-info">Click to upload</span>
                                                        <br>
                                                        Or drag and drop
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- Steeper End -->

            <!-- Steeper Cross Remove Version02 -->
            <div class="pt-8 pb-8 registration-form-wrapper">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
                    <li class="nav-item">
                        <a class="nav-link " href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">FAQ</a>
                    </li>
                </ul>
                <div class="card">
                    <div class=" steeper-header-title border-bottom my-4 pb-4 px-4">
                        <div class="page--header-title mb-2">
                            <h1 class="page-header-title">Add Steeper</h1>
                        </div>
                        <p class="mb-0">Setup the information that you want to highlight for the restaurants</p>
                    </div>
                    <ul class="nav nav-tabs mx-4">
                        <li class="nav-item">
                            <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                        </li>
                    </ul>
                    <div class="row g-3 m-2">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 1</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                                <i class="tio-clear"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <img src="http://localhost/teststacfood/public/assets/admin/img/showing-profile2.png" alt="replace">
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 2</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                                <i class="tio-clear"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                        <g id="fi_8191581">
                                                        <g id="image-upload">
                                                        <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                        <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                        </g>
                                                        </g>
                                                    </svg>
                                                    <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                        <span class="text-info">Click to upload</span>
                                                        <br>
                                                        Or drag and drop
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="border-bottom py-3 px-4">
                                    <h4 class="mb-0">Step 3</h4>
                                </div>
                                <div class="p-4"> 
                                    <form action="#" class="get-checked-required-field" novalidate>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 50</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Get Order</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/50</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                                <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                    <div class='text-start'>Your english text limit right now 100</div>
                                                    ">
                                                    <i class="tio-info"></i>
                                                </span>
                                            </label>
                                            <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="100" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                            <div class="d-flex justify-content-end">
                                                <span class="text-body-light">0/100</span>
                                            </div>
                                        </div>
                                    </form>                               
                                    <div class="registration-upload registration-upload-cmn p-sm-4 p-3 mb-5 mt-sm-4 mt-3">
                                        <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                        <div class="upload-file mb-4">
                                            <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                            <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                                <i class="tio-clear"></i>
                                            </button>
                                            <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                                <div class="upload-file-textbox text-center" style="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                        <g id="fi_8191581">
                                                        <g id="image-upload">
                                                        <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                        <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                        <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                        </g>
                                                        </g>
                                                    </svg>
                                                    <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                        <span class="text-info">Click to upload</span>
                                                        <br>
                                                        Or drag and drop
                                                    </h6>
                                                </div>
                                                <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                            </label>
                                        </div>
                                        <p class="text-center image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                    </div>
                                    <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                        <button type="reset" class="btn btn--reset">Reset</button>
                                        <button type="submit" class="btn btn--primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- Steeper Version02 End -->

           <!-- Steeper opportunities -->
           <div class="pt-8 pb-8 registration-form-wrapper">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
                    <li class="nav-item">
                        <a class="nav-link " href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">FAQ</a>
                    </li>
                </ul>
                <div class="steeper-header-title mb-3">
                    <div class="page--header-title">
                        <h1 class="page-header-title">Add Opportunities</h1>
                    </div>
                </div>
                <div class="card p-sm-4 p-3">
                    <div class="row g-4 justify-content-between"> 
                        <div class="col-xxl-9 col-lg-8 col-md-7">
                            <div class="px-xl-4 pt-xl-3">
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                                    </li>
                                </ul>
                                <form action="#" class="get-checked-required-field" novalidate>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 20</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="20">Restaurant Panel</textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/20</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 100</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="step_2_text" rows="2" data-maxlength="60" placeholder="Step text...">Consectetur...</textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/60</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>                               
                        <div class="col-xxl-3 col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-center">
                            <div>
                                <div class="registration-upload opportunities-upload p-md-4 mb-lg-5">
                                    <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                    <div class="upload-file mb-4">
                                        <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                        <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                            <i class="tio-clear"></i>
                                        </button>
                                        <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                            <div class="upload-file-textbox text-center" style="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="35" viewBox="0 0 34 35" fill="none" class="svg replaced-svg">
                                                    <g id="fi_8191581">
                                                    <g id="image-upload">
                                                    <path id="Vector" d="M30.8125 13.2922V24.7927C30.8963 25.5106 30.8165 26.2381 30.5791 26.9207C30.3418 27.6034 29.953 28.2235 29.4419 28.7346C28.9309 29.2456 28.3108 29.6344 27.6281 29.8718C26.9454 30.1091 26.2179 30.1889 25.5 30.1052H8.50004C7.78216 30.1889 7.05463 30.1091 6.37197 29.8718C5.68931 29.6344 5.06922 29.2456 4.55816 28.7346C4.0471 28.2235 3.65832 27.6034 3.42095 26.9207C3.18359 26.2381 3.10379 25.5106 3.18754 24.7927V10.626C3.10379 9.90813 3.18359 9.18061 3.42095 8.49795C3.65832 7.81529 4.0471 7.1952 4.55816 6.68414C5.06922 6.17308 5.68931 5.7843 6.37197 5.54693C7.05463 5.30956 7.78216 5.22977 8.50004 5.31352H20.9667C21.0294 5.31516 21.0909 5.33065 21.1469 5.35888C21.2029 5.38711 21.252 5.42739 21.2906 5.47681C21.3292 5.52624 21.3563 5.58359 21.3701 5.64475C21.384 5.70592 21.3841 5.76939 21.3705 5.8306C21.0362 7.48588 21.3724 9.20615 22.3052 10.6138C23.2379 12.0215 24.6911 13.0016 26.3458 13.3389C27.6554 13.6007 29.0142 13.4447 30.2303 12.8927C30.2951 12.8669 30.3653 12.8574 30.4347 12.8651C30.5041 12.8729 30.5705 12.8976 30.628 12.9371C30.6856 12.9766 30.7325 13.0296 30.7647 13.0915C30.7969 13.1535 30.8133 13.2224 30.8125 13.2922Z" fill="#A6A6A6" fill-opacity="0.35"></path>
                                                    <path id="Vector_2" d="M28.5628 20.7694L22.2558 14.4624C21.9887 14.1963 21.627 14.0469 21.25 14.0469C20.873 14.0469 20.5113 14.1963 20.2442 14.4624L13.2458 21.4607C13.1134 21.5905 12.9354 21.6632 12.75 21.6632C12.5646 21.6632 12.3866 21.5905 12.2542 21.4607L10.9225 20.1291C10.6554 19.863 10.2937 19.7135 9.91667 19.7135C9.53962 19.7135 9.17795 19.863 8.91083 20.1291L5.43717 23.6027C5.35744 23.6823 5.3126 23.7904 5.3125 23.9031V24.7899C5.3125 27.0282 6.26167 27.9774 8.5 27.9774H25.5C27.7383 27.9774 28.6875 27.0282 28.6875 24.7899V21.0697C28.6874 20.957 28.6426 20.849 28.5628 20.7694Z" fill="#999999" fill-opacity="0.7"></path>
                                                    <path id="Vector_3" d="M11.3339 15.2292C11.1013 15.23 10.8709 15.185 10.6557 15.0968C10.4405 15.0086 10.2449 14.8789 10.0798 14.715C9.74653 14.3841 9.55834 13.9344 9.55665 13.4647C9.55496 12.9951 9.73991 12.544 10.0708 12.2107C10.4017 11.8774 10.8515 11.6892 11.3211 11.6875H11.3339C11.8035 11.6875 12.2539 11.8741 12.586 12.2062C12.9181 12.5383 13.1047 12.9887 13.1047 13.4583C13.1047 13.928 12.9181 14.3784 12.586 14.7105C12.2539 15.0426 11.8035 15.2292 11.3339 15.2292Z" fill="#999999" fill-opacity="0.7"></path>
                                                    <path id="Vector_4" d="M30.5009 6.33358L28.3759 4.20858C28.1765 4.00996 27.9066 3.89844 27.6251 3.89844C27.3437 3.89844 27.0737 4.00996 26.8743 4.20858L24.7493 6.33358C24.5616 6.535 24.4594 6.8014 24.4643 7.07666C24.4691 7.35192 24.5806 7.61455 24.7753 7.80922C24.97 8.00388 25.2326 8.11539 25.5079 8.12025C25.7831 8.12511 26.0495 8.02293 26.2509 7.83525L26.5626 7.525V10.6261C26.5626 10.9079 26.6745 11.1781 26.8738 11.3774C27.0731 11.5766 27.3433 11.6886 27.6251 11.6886C27.9069 11.6886 28.1772 11.5766 28.3764 11.3774C28.5757 11.1781 28.6876 10.9079 28.6876 10.6261V7.525L28.9993 7.83525C29.2007 8.02293 29.4671 8.12511 29.7423 8.12025C30.0176 8.11539 30.2802 8.00388 30.4749 7.80922C30.6696 7.61455 30.7811 7.35192 30.7859 7.07666C30.7908 6.8014 30.6886 6.535 30.5009 6.33358Z" fill="#999999" fill-opacity="0.7"></path>
                                                    </g>
                                                    </g>
                                                </svg>
                                                <h6 class="m-0 fs-10 fw-medium lh-base text-center">
                                                    <span class="text-info">Click to upload</span>
                                                    <br>
                                                    Or drag and drop
                                                </h6>
                                            </div>
                                            <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                        </label>
                                    </div>
                                    <p class="text-center fs-10 image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                </div>
                                <div class="btn--container justify-content-sm-end mt-lg-5 mt-4">
                                    <button type="reset" class="btn btn--reset">Reset</button>
                                    <button type="submit" class="btn btn--primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-1 pt-3 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">Opportunities list</h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="Search here" aria-label="Search here">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable" class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options="{
                                        &quot;order&quot;: [],
                                        &quot;orderCellsTop&quot;: true,
                                        &quot;paging&quot;:false
                                    }">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Image</th>
                                    <th>Title </th>
                                    <th>Subtitle</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <span class="opportunities-list-img">
                                            <img src="http://localhost/teststacfood/public/assets/admin/img/restaurant-panel.png" alt="panel">
                                        </span>
                                    </td>
                                    <td>
                                        Restaurant Panel
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox3">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/3/0" data-message="Want to change status for this shift " id="stocksCheckbox3" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/3/0" method="get" id="stocksCheckbox-3">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <button data-toggle="modal" data-target="#add_update_shift_3" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-3" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/3" method="post" id="shift-3">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <span class="opportunities-list-img">
                                            <img src="http://localhost/teststacfood/public/assets/admin/img/restaurant-app.png" alt="panel">
                                        </span>
                                    </td>
                                    <td>
                                        Restaurant App
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox2">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/2/0" data-message="Want to change status for this shift " id="stocksCheckbox2" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="http://localhost/teststacfood/admin/shift/status/2/0" method="get" id="stocksCheckbox-2">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <button data-toggle="modal" data-target="#add_update_shift_2" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-2" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/2" method="post" id="shift-2">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <span class="opportunities-list-img">
                                            <img src="http://localhost/teststacfood/public/assets/admin/img/deliveryman-app.png" alt="panel">
                                        </span>
                                    </td>
                                    <td>
                                        Deliveryman App
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox1">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/1/0" data-message="Want to change status for this shift " id="stocksCheckbox1" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/1/0" method="get" id="stocksCheckbox-1">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <button data-toggle="modal" data-target="#add_update_shift_1" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-1" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/1" method="post" id="shift-1">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <span class="opportunities-list-img">
                                            <img src="http://localhost/teststacfood/public/assets/admin/img/business-website.png" alt="panel">
                                        </span>
                                    </td>
                                    <td>
                                        Business Website
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox1">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/1/0" data-message="Want to change status for this shift " id="stocksCheckbox1" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/1/0" method="get" id="stocksCheckbox-1">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <button data-toggle="modal" data-target="#add_update_shift_1" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-1" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/1" method="post" id="shift-1">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           <!-- FAQ Table List -->
           <div class="pt-8 pb-8 registration-form-wrapper">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link " href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">FAQ</a>
                    </li>
                </ul>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                    </li>
                </ul>
                <div class="steeper-header-title mb-3">
                    <div class="page--header-title">
                        <h1 class="page-header-title">Add FAQ</h1>
                    </div>
                </div>
                <div class="card p-sm-4 p-3">
                    <div class="px-xl-4 pt-xl-3">
                        <form action="#" class="row g-3 get-checked-required-field faq-from-list" novalidate>                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Question
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Your english text limit right now 50</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Enter Title</textarea>
                                    <div class="d-flex justify-content-end">
                                        <span class="text-body-light">0/50</span>
                                    </div>
                                </div>
                                <div class="">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Priority
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Priority...</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <label for="zone_id"></label>
                                    <select name="zone_id" class="form-control js-select2-custom h--45x set-filter select2-hidden-accessible" data-url="http://localhost/teststacfood/admin/pos" data-filter="zone_id" id="zone_id" tabindex="-1" aria-hidden="true" data-select2-id="zone_id">
                                        <option value="" selected="" disabled="" data-select2-id="48">1 <span>*</span>
                                        </option>
                                        <option value="1" data-select2-id="49">
                                            2
                                        </option>
                                        <option value="8" data-select2-id="50">
                                            3
                                        </option>
                                        <option value="10" data-select2-id="51">
                                            4
                                        </option>
                                        <option value="7" data-select2-id="52">
                                            5
                                        </option>
                                        <option value="9" data-select2-id="53">
                                            6
                                        </option>
                                        <option value="11" data-select2-id="54">
                                            n7
                                        </option>                                    
                                    </select>                                
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Answer
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Write Description.</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <textarea class="form-control" name="step_2_text" rows="6" data-maxlength="150" placeholder="Step text...">Write Description...</textarea>
                                    <div class="d-flex justify-content-end">
                                        <span class="text-body-light">0/150</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                            <button type="reset" class="btn btn--reset">Reset</button>
                            <button type="submit" class="btn btn--primary">Add</button>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-1 pt-3 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">FAQ list</h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="Search here" aria-label="Search here">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">                        
                        <table id="columnSearchDatatable" class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options="{
                                        &quot;order&quot;: [],
                                        &quot;orderCellsTop&quot;: true,
                                        &quot;paging&quot;:false
                                    }">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Question</th>
                                    <th>Answer </th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <p class="question-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur 
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>1</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox3">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/3/0" data-message="Want to change status for this shift " id="stocksCheckbox3" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/3/0" method="get" id="stocksCheckbox-3">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="modal" id="edit_faq-modal" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details"> <i class="tio-invisible font-weight-bold"></i> </a>
                                        <!-- <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="tooltip" data-placement="top" title="" href="#" data-original-title="Details"> <i class="tio-invisible font-weight-bold"></i> </a> -->
                                            <button data-toggle="modal" data-target="#add_update_shift_3" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-3" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                                <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/3" method="post" id="shift-3">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <p class="question-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur 
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>2</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox2">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/2/0" data-message="Want to change status for this shift " id="stocksCheckbox2" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="http://localhost/teststacfood/admin/shift/status/2/0" method="get" id="stocksCheckbox-2">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="tooltip" data-placement="top" title="" href="#" data-original-title="Details"> <i class="tio-invisible font-weight-bold"></i> </a>
                                            <button data-toggle="modal" data-target="#add_update_shift_2" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-2" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/2" method="post" id="shift-2">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <p class="question-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur 
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>3</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox1">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/1/0" data-message="Want to change status for this shift " id="stocksCheckbox1" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/1/0" method="get" id="stocksCheckbox-1">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="tooltip" data-placement="top" title="" href="#" data-original-title="Details"> <i class="tio-invisible font-weight-bold"></i> </a>
                                            <button data-toggle="modal" data-target="#add_update_shift_1" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-1" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/1" method="post" id="shift-1">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <p class="question-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur 
                                        </p>
                                    </td>
                                    <td>
                                        <p class="opportunities-pragraph">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                        </p>
                                    </td>
                                    <td>4</td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox1">
                                            <input class="toggle-switch-input status_change_alert" type="checkbox" data-url="http://localhost/teststacfood/admin/shift/status/1/0" data-message="Want to change status for this shift " id="stocksCheckbox1" checked="">
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                        <form action="http://localhost/teststacfood/admin/shift/status/1/0" method="get" id="stocksCheckbox-1">
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="tooltip" data-placement="top" title="" href="#" data-original-title="Details"> <i class="tio-invisible font-weight-bold"></i> </a>
                                            <button data-toggle="modal" data-target="#add_update_shift_1" class="btn btn-sm btn--primary btn-outline-primary action-btn edit-shift">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:" data-id="shift-1" data-message="Want to delete this shift data. All of data related to this shift will be gone !!!" title="Delete shift">
                                            <i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="http://localhost/teststacfood/admin/shift/delete/1" method="post" id="shift-1">
                                                <input type="hidden" name="_token" value="56RXEWeVmEfuhomAgxGYQz9j79nkIGl32qLMA5kG" autocomplete="off"> <input type="hidden" name="_method" value="delete">            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FAQ Table Emty -->
           <div class="pt-8 pb-8 registration-form-wrapper">
                <!-- Nav -->
                <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link " href="#">Hero Section</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Steeper</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Opportunities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">FAQ</a>
                    </li>
                </ul>
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                    </li>
                </ul>
                <div class="steeper-header-title mb-3">
                    <div class="page--header-title">
                        <h1 class="page-header-title">Add FAQ</h1>
                    </div>
                </div>
                <div class="card p-sm-4 p-3">
                    <div class="px-xl-4 pt-xl-3">
                        <form action="#" class="row g-3 get-checked-required-field faq-from-list" novalidate>                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Question
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Your english text limit right now 50</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Enter Title</textarea>
                                    <div class="d-flex justify-content-end">
                                        <span class="text-body-light">0/50</span>
                                    </div>
                                </div>
                                <div class="">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Priority
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Priority...</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <label for="zone_id"></label>
                                    <select name="zone_id" class="form-control js-select2-custom h--45x set-filter select2-hidden-accessible" data-url="http://localhost/teststacfood/admin/pos" data-filter="zone_id" id="zone_id" tabindex="-1" aria-hidden="true" data-select2-id="zone_id">
                                        <option value="" selected="" disabled="" data-select2-id="48">1 <span>*</span>
                                        </option>
                                        <option value="1" data-select2-id="49">
                                            2
                                        </option>
                                        <option value="8" data-select2-id="50">
                                            3
                                        </option>
                                        <option value="10" data-select2-id="51">
                                            4
                                        </option>
                                        <option value="7" data-select2-id="52">
                                            5
                                        </option>
                                        <option value="9" data-select2-id="53">
                                            6
                                        </option>
                                        <option value="11" data-select2-id="54">
                                            n7
                                        </option>                                    
                                    </select>                                
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label tooltip-label" for="company_copyright_text">Answer
                                        <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                            <div class='text-start'>Write Description.</div>
                                            ">
                                            <i class="tio-info"></i>
                                        </span>
                                    </label>
                                    <textarea class="form-control" name="step_2_text" rows="6" data-maxlength="150" placeholder="Step text...">Write Description...</textarea>
                                    <div class="d-flex justify-content-end">
                                        <span class="text-body-light">0/150</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                            <button type="reset" class="btn btn--reset">Reset</button>
                            <button type="submit" class="btn btn--primary">Add</button>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header pb-1 pt-3 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title">FAQ list</h5>
                            <form>
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="Search here" aria-label="Search here">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable" class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options="{
                                        &quot;order&quot;: [],
                                        &quot;orderCellsTop&quot;: true,
                                        &quot;paging&quot;:false
                                    }">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl</th>
                                    <th>Question</th>
                                    <th>Answer </th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">
                                        <div class="no-faq-list d-flex flex-column text-center gap-1 py-md-8 py-7 my-md-4">
                                            <div class="">
                                                <svg width="74" height="57" viewBox="0 0 74 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M40.4365 53C45.261 53 49.1865 49.0745 49.1865 44.25V12.75C49.1865 7.92554 45.261 4 40.4365 4H15.9365C11.1121 4 7.18652 7.92554 7.18652 12.75V44.25C7.18652 49.0745 11.1121 53 15.9365 53H40.4365ZM10.6865 12.75C10.6865 9.85498 13.0415 7.5 15.9365 7.5H40.4365C43.3315 7.5 45.6865 9.85498 45.6865 12.75V44.25C45.6865 47.145 43.3315 49.5 40.4365 49.5H15.9365C13.0415 49.5 10.6865 47.145 10.6865 44.25V12.75Z" fill="#A7A7A7"/>
                                                    <path d="M24.6865 30.25H38.6865C39.6538 30.25 40.4365 29.4664 40.4365 28.5C40.4365 27.5336 39.6538 26.75 38.6865 26.75H24.6865C23.7192 26.75 22.9365 27.5336 22.9365 28.5C22.9365 29.4664 23.7192 30.25 24.6865 30.25Z" fill="#A7A7A7"/>
                                                    <path d="M24.6865 21.5H38.6865C39.6538 21.5 40.4365 20.7164 40.4365 19.75C40.4365 18.7836 39.6538 18 38.6865 18H24.6865C23.7192 18 22.9365 18.7836 22.9365 19.75C22.9365 20.7164 23.7192 21.5 24.6865 21.5Z" fill="#A7A7A7"/>
                                                    <path d="M24.6865 39H38.6865C39.6538 39 40.4365 38.2164 40.4365 37.25C40.4365 36.2836 39.6538 35.5 38.6865 35.5H24.6865C23.7192 35.5 22.9365 36.2836 22.9365 37.25C22.9365 38.2164 23.7192 39 24.6865 39Z" fill="#A7A7A7"/>
                                                    <path d="M17.7205 21.5C18.6877 21.5 19.4705 20.7164 19.4705 19.75C19.4705 18.7836 18.6877 18 17.7205 18H17.7034C16.7378 18 15.9619 18.7836 15.9619 19.75C15.9619 20.7164 16.7549 21.5 17.7205 21.5Z" fill="#A7A7A7"/>
                                                    <path d="M17.7205 30.25C18.6877 30.25 19.4705 29.4664 19.4705 28.5C19.4705 27.5336 18.6877 26.75 17.7205 26.75H17.7034C16.7378 26.75 15.9619 27.5336 15.9619 28.5C15.9619 29.4664 16.7549 30.25 17.7205 30.25Z" fill="#A7A7A7"/>
                                                    <path d="M17.7205 39C18.6877 39 19.4705 38.2164 19.4705 37.25C19.4705 36.2836 18.6877 35.5 17.7205 35.5H17.7034C16.7378 35.5 15.9619 36.2836 15.9619 37.25C15.9619 38.2164 16.7549 39 17.7205 39Z" fill="#A7A7A7"/>
                                                    <path d="M65.8834 49.0514C66.2576 48.6772 66.2576 48.0703 65.8834 47.6961L64.1893 46.002L65.8834 44.3078C66.2576 43.9336 66.2576 43.3268 65.8834 42.9526C65.5092 42.5783 64.9024 42.5783 64.5281 42.9526L62.834 44.6467L61.1399 42.9526C60.7656 42.5783 60.1588 42.5783 59.7846 42.9526C59.4103 43.3268 59.4103 43.9336 59.7846 44.3078L61.4787 46.002L59.7846 47.6961C59.4103 48.0703 59.4103 48.6772 59.7846 49.0514C60.1588 49.4256 60.7656 49.4256 61.1399 49.0514L62.834 47.3572L64.5281 49.0514C64.9024 49.4256 65.5092 49.4256 65.8834 49.0514Z" fill="#A7A7A7"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M56.463 39.1505C60.1064 35.5071 66.0134 35.5071 69.6568 39.1505C73.0714 42.5651 73.2858 47.968 70.3001 51.6323C66.6413 56.1225 59.8776 55.7589 56.463 52.3443C52.8196 48.7009 52.8196 42.7939 56.463 39.1505ZM68.3015 40.5058C65.4066 37.6109 60.7132 37.6109 57.8183 40.5058C54.9234 43.4007 54.9234 48.0941 57.8183 50.989C60.7132 53.8839 65.4066 53.8839 68.3015 50.989C71.1964 48.0941 71.1964 43.4007 68.3015 40.5058Z" fill="#A7A7A7"/>
                                                </svg>
                                            </div>
                                            <span>No FAQ List</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Oportunities Modal -->
            <div class="card p-sm-4 p-3 m-4 registration-form-wrapper">
                <!-- oportunities -->
                <button data-toggle="modal" data-target="#edit_oportunities-modal" class=" edit-shift">
                    orportunities modal check
                </button>
                <div class="modal fade" id="edit_oportunities-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content p-4">
                            <div class="modal-header p-0">
                                <h3 class="modal-title" id="exampleModalLabel">Edit Opportunities  </h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class=""> 
                                <div class="registration-upload upload-showing-file p-lg-4 p-md-3 mb-md-5 mb-4">
                                    <h5 class="text-center mb-4 mt-2">Image/Icon</h5>
                                    <div class="upload-file border-0 mb-sm-4 mb-3">
                                        <input type="file" name="thumbnail" class="upload-file__input single_file_input" accept=".webp, .jpg, .jpeg, .png, .gif" value="" required="">
                                        <button type="button" class="remove_btn bg-transparent border-0 outline-0" style="opacity: 0;">
                                            <i class="tio-clear"></i>
                                        </button>
                                        <label class="upload-file__wrapper d-flex align-items-center justify-content-center text-center ratio-7-1">
                                            <div class="upload-file-textbox text-center" style="">
                                                <img src="http://localhost/teststacfood/public/assets/admin/img/oportunities-profile.png" alt="replace">
                                            </div>
                                            <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                        </label>
                                    </div>
                                    <p class="text-center fs-12 image-formate mb-0">JPG, JPEG, PNG Less Than 5MB <span class="">(1:1)</span></p>
                                </div>
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active" href="#" id="default-link">Default(EN)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="en-link">English(EN)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="ar-link">Arabic-(AR)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#" id="es-link">Spanish(ES)</a>
                                    </li>
                                </ul>
                                <form action="#" class="get-checked-required-field" novalidate>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Tittle (EN)
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 20</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="20">Restaurant Panel</textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/20</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Subtitle (EN)
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 60</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="step_2_text" rows="3" data-maxlength="60" placeholder="Step text...">Consectetur adipiscing elit.</textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/60</span>
                                        </div>
                                    </div>
                                </form>      
                                <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                                    <button type="reset" class="btn btn--reset">Reset</button>
                                    <button type="submit" class="btn btn--primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edit Faqs -->
                <button data-toggle="modal" data-target="#edit_faq-modal" class=" edit-shift">
                    Faqs modal check
                </button>
                <div class="modal fade" id="edit_faq-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content p-4">
                            <div class="modal-header p-0">
                                <h3 class="modal-title mb-3" id="exampleModalLabel">Edit FAQ  </h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class=""> 
                                <form action="#" class="get-checked-required-field" novalidate>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Question
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 50</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="company_copyright_text" rows="1" placeholder="Type about the description" data-maxlength="50">Lorem ipsum dolor sit amet, consectetur </textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/50</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Priority
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Priority...</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <label for="zone_id"></label>
                                        <select name="zone_id" class="form-control js-select2-custom h--45x set-filter select2-hidden-accessible" data-url="http://localhost/teststacfood/admin/pos" data-filter="zone_id" id="zone_id" tabindex="-1" aria-hidden="true" data-select2-id="zone_id">
                                            <option value="" selected="" disabled="" data-select2-id="48">1 <span>*</span>
                                            </option>
                                            <option value="1" data-select2-id="49">
                                                2
                                            </option>
                                            <option value="8" data-select2-id="50">
                                                3
                                            </option>
                                            <option value="10" data-select2-id="51">
                                                4
                                            </option>
                                            <option value="7" data-select2-id="52">
                                                5
                                            </option>
                                            <option value="9" data-select2-id="53">
                                                6
                                            </option>
                                            <option value="11" data-select2-id="54">
                                                n7
                                            </option>                                    
                                        </select>                                
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label tooltip-label" for="company_copyright_text">Answer
                                            <span class="tooltip-icon" data-toggle="tooltip" data-placement="right" data-html="true" aria-label="Enter copyright text" data-title="
                                                <div class='text-start'>Your english text limit right now 150</div>
                                                ">
                                                <i class="tio-info"></i>
                                            </span>
                                        </label>
                                        <textarea class="form-control" name="step_2_text" rows="6" data-maxlength="150" placeholder="Step text...">Lorem ipsum dolor sit amet, consectetur a...</textarea>
                                        <div class="d-flex justify-content-end">
                                            <span class="text-body-light">0/150</span>
                                        </div>
                                    </div>
                                </form>      
                                <div class="btn--container justify-content-sm-end mt-lg-4 mt-3">
                                    <button type="reset" class="btn btn--reset">Reset</button>
                                    <button type="submit" class="btn btn--primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Quick View Faqs -->
                <button data-toggle="modal" data-target="#quick_faq-modal" class=" edit-shift">
                    Faqs Quick View modal check
                </button>
                <div class="modal fade" id="quick_faq-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content p-sm-4 p-3">
                            <div class="modal-header p-0 pb-4">
                                <h3 class="modal-title" id="exampleModalLabel">Quick View  </h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="quick-view-box p-sm-4 p-2"> 
                                <div class="mb-lg-4 mb-3">
                                    <h5>Question</h5>
                                    <p class="mb-0">
                                        Lorem ipsum dolor sit amet, consectetur?
                                    </p>
                                </div>
                                <div>
                                    <h5>Answer</h5>
                                    <p class="mb-0">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam odio tellus, laoreet.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                                            

        </div>


@endsection

