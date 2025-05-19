@extends('layouts.admin.app')

@section('title', translate('priority_setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
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
                                {{translate('Don’t_forget_to_click_the_respective_‘Save_Information’_and_‘Submit’_buttons_below_to_save_changes')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>

    <!-- Main Content -->
    <div class="card">
        <form method="post" action="{{ route('admin.business-settings.update-priority') }}">
            @csrf
            <div class="card-body">

                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="max-w-353px">
                            <h4 class="mb-2 mt-4">{{translate('Category_List')}}</h4>
                            <p class="m-0 fs-12">
                                {{ translate('The_Food_Category_list_groups_similar_items_together_arranged_with_the_latest_category_first_and_in_alphabetical_order.') }}
                            </p>
                        </div>
                    </div>
                    @php($category_list_default_status = \App\Models\BusinessSetting::where('key', 'category_list_default_status')->first()?->value ?? 1  )
                    <div class="col-lg-6">
                        <div class="__bg-FAFAFA rounded">
                            <div class="sorting-card p-20px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-0 flex-grow">
                                        <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                        <label class="form-label m-0">
                                            <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                            <div class="fs-12">{{ translate('Currently sorting this section by priority') }}</div>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                            <input type="radio" name="category_list_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $category_list_default_status == '1'? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="sorting-card p-20px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-0 flex-grow">
                                        <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                        <label class="form-label m-0">
                                            <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                            <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                            <input type="radio" name="category_list_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $category_list_default_status == '0'? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="inner-collapse-div">
                                    <div class="pt-4">
                                        @php($category_list_sort_by_general = \App\Models\PriorityList::where('name', 'category_list_sort_by_general')->where('type','general')->first()?->value ?? '' )
                                        <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                            <label class="form-check form--check">
                                                <input class="form-check-input" type="radio" name="category_list_sort_by_general" value="latest" {{ $category_list_sort_by_general == 'latest' ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('Sort by latest created')}}
                                                </span>
                                            </label>
                                            <label class="form-check form--check">
                                                <input class="form-check-input" type="radio" name="category_list_sort_by_general" value="oldest" {{ $category_list_sort_by_general == 'oldest' ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('Sort by first created')}}
                                                </span>
                                            </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="category_list_sort_by_general" value="order_count" {{ $category_list_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by orders')}}
                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="category_list_sort_by_general" value="a_to_z" {{ $category_list_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by Alphabetical (A to Z)')}}
                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="category_list_sort_by_general" value="z_to_a" {{ $category_list_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by Alphabetical (Z to A)')}}
                                                    </span>
                                                </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                {{-- Cuisine List --}}

                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="max-w-353px">
                            <h4 class="mb-2 mt-4">{{translate('Cuisine_List')}}</h4>
                            <p class="m-0 fs-12">
                                {{ translate('Cuisines_are_lists_of_the_foods_people_like,_organize__by_putting_the_newest_ones_at_the_top_and_arranging_everything_alphabetically') }}
                            </p>
                        </div>
                    </div>
                    @php($cuisine_list_default_status = \App\Models\BusinessSetting::where('key', 'cuisine_list_default_status')->first()?->value ?? 1  )
                    <div class="col-lg-6">
                        <div class="__bg-FAFAFA rounded">
                            <div class="sorting-card p-20px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-0 flex-grow">
                                        <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                        <label class="form-label m-0">
                                            <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                            <div class="fs-12">{{ translate('Currently sorting this section by latest') }}</div>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                            <input type="radio" name="cuisine_list_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $cuisine_list_default_status == '1'? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="sorting-card p-20px">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-0 flex-grow">
                                        <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                        <label class="form-label m-0">
                                            <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                            <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                            <input type="radio" name="cuisine_list_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $cuisine_list_default_status == '0'? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="inner-collapse-div">
                                    <div class="pt-4">
                                        @php($cuisine_list_sort_by_general = \App\Models\PriorityList::where('name', 'cuisine_list_sort_by_general')->where('type','general')->first()?->value ?? '' )
                                        <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                            <label class="form-check form--check">
                                                <input class="form-check-input" type="radio" name="cuisine_list_sort_by_general" value="latest" {{ $cuisine_list_sort_by_general == 'latest' ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('Sort by latest created')}}
                                                </span>
                                            </label>
                                            <label class="form-check form--check">
                                                <input class="form-check-input" type="radio" name="cuisine_list_sort_by_general" value="oldest" {{ $cuisine_list_sort_by_general == 'oldest' ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{translate('Sort by first created')}}
                                                </span>
                                            </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="cuisine_list_sort_by_general" value="restaurant_count" {{ $cuisine_list_sort_by_general == 'restaurant_count' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by Total Restaurants')}}
                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="cuisine_list_sort_by_general" value="a_to_z" {{ $cuisine_list_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by Alphabetical (A to Z)')}}
                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio" name="cuisine_list_sort_by_general" value="z_to_a" {{ $cuisine_list_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                                    <span class="form-check-label">
                                                        {{translate('Sort by Alphabetical (Z to A)')}}
                                                    </span>
                                                </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>



            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Popular Foods Nearby')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('Popular food Nearby means the food items list  which are mostly ordered by the customers and have good reviews & ratings') }}
                        </p>
                    </div>
                </div>
                @php($popular_food_default_status = \App\Models\BusinessSetting::where('key', 'popular_food_default_status')->first())
                @php($popular_food_default_status = $popular_food_default_status ? $popular_food_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('This_section_is_currently_sorted_by_higher_ratings,_reviews,_and_total_orders.') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="popular_food_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $popular_food_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="popular_food_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $popular_food_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($popular_food_sort_by_general = \App\Models\PriorityList::where('name', 'popular_food_sort_by_general')->where('type','general')->first())
                                    @php($popular_food_sort_by_general = $popular_food_sort_by_general ? $popular_food_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="order_count" {{ $popular_food_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by orders')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="review_count" {{ $popular_food_sort_by_general == 'review_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews count')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="rating" {{ $popular_food_sort_by_general == 'rating' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="nearest_first" {{ $popular_food_sort_by_general == 'nearest_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show nearest food first')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="a_to_z" {{ $popular_food_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (A to Z)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_general" value="z_to_a" {{ $popular_food_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (Z to A)')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($popular_food_sort_by_unavailable = \App\Models\PriorityList::where('name', 'popular_food_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($popular_food_sort_by_unavailable = $popular_food_sort_by_unavailable ? $popular_food_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_unavailable" value="last" {{ $popular_food_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show unavailable foods in the last (both food & restaurant are unavailable)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_unavailable" value="remove" {{ $popular_food_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove unavailable foods from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_unavailable" value="none" {{ $popular_food_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($popular_food_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'popular_food_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($popular_food_sort_by_temp_closed = $popular_food_sort_by_temp_closed ? $popular_food_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_temp_closed" value="last" {{ $popular_food_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show food in the last if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_temp_closed" value="remove" {{ $popular_food_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove food from the list if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_food_sort_by_temp_closed" value="none" {{ $popular_food_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Popular Restaurant')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('Popular Restaurants is the list of customer choices in which customer ordered items most and also highly rated with good reviews') }}
                        </p>
                    </div>
                </div>
                @php($popular_restaurant_default_status = \App\Models\BusinessSetting::where('key', 'popular_restaurant_default_status')->first())
                @php($popular_restaurant_default_status = $popular_restaurant_default_status ? $popular_restaurant_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('This_section_is_currently_sorted_by_total_orders.') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="popular_restaurant_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $popular_restaurant_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="popular_restaurant_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $popular_restaurant_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($popular_restaurant_sort_by_general = \App\Models\PriorityList::where('name', 'popular_restaurant_sort_by_general')->where('type','general')->first())
                                    @php($popular_restaurant_sort_by_general = $popular_restaurant_sort_by_general ? $popular_restaurant_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_general" value="order_count" {{ $popular_restaurant_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by orders')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_general" value="review_count" {{ $popular_restaurant_sort_by_general == 'review_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews count')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_general" value="rating" {{ $popular_restaurant_sort_by_general == 'rating' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($popular_restaurant_sort_by_unavailable = \App\Models\PriorityList::where('name', 'popular_restaurant_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($popular_restaurant_sort_by_unavailable = $popular_restaurant_sort_by_unavailable ? $popular_restaurant_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_unavailable" value="last" {{ $popular_restaurant_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show currently closed restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_unavailable" value="remove" {{ $popular_restaurant_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove currently closed restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_unavailable" value="none" {{ $popular_restaurant_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($popular_restaurant_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'popular_restaurant_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($popular_restaurant_sort_by_temp_closed = $popular_restaurant_sort_by_temp_closed ? $popular_restaurant_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_temp_closed" value="last" {{ $popular_restaurant_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show temporarily off restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_temp_closed" value="remove" {{ $popular_restaurant_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove temporarily off restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="popular_restaurant_sort_by_temp_closed" value="none" {{ $popular_restaurant_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('New Restaurant')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('The_New_restaurant_list_arranges_all_restaurants_based_on_the_latest_join_that_are_closest_to_the_customers_location.') }}
                        </p>
                    </div>
                </div>
                @php($new_restaurant_default_status = \App\Models\BusinessSetting::where('key', 'new_restaurant_default_status')->first())
                @php($new_restaurant_default_status = $new_restaurant_default_status ? $new_restaurant_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('Currently sorting this section by latest restaurants') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="new_restaurant_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $new_restaurant_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                        <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="new_restaurant_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $new_restaurant_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($new_restaurant_sort_by_general = \App\Models\PriorityList::where('name', 'new_restaurant_sort_by_general')->where('type','general')->first())
                                    @php($new_restaurant_sort_by_general = $new_restaurant_sort_by_general ? $new_restaurant_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_general" value="latest_created" {{ $new_restaurant_sort_by_general == 'latest_created' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by latest created')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_general" value="nearby_first" {{ $new_restaurant_sort_by_general == 'nearby_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort new restaurants by distance')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_general" value="delivery_time" {{ $new_restaurant_sort_by_general == 'delivery_time' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort new restaurants by delivery time')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($new_restaurant_sort_by_unavailable = \App\Models\PriorityList::where('name', 'new_restaurant_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($new_restaurant_sort_by_unavailable = $new_restaurant_sort_by_unavailable ? $new_restaurant_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_unavailable" value="last" {{ $new_restaurant_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show currently closed restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_unavailable" value="remove" {{ $new_restaurant_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove currently closed restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_unavailable" value="none" {{ $new_restaurant_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($new_restaurant_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'new_restaurant_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($new_restaurant_sort_by_temp_closed = $new_restaurant_sort_by_temp_closed ? $new_restaurant_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_temp_closed" value="last" {{ $new_restaurant_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show temporarily off restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_temp_closed" value="remove" {{ $new_restaurant_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove temporarily off restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="new_restaurant_sort_by_temp_closed" value="none" {{ $new_restaurant_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Restaurant List, Category wise restaurant list, Cuisine wise restaurant list')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('A list of all the restaurants which are sorted based on  latest joined, mostly ordered, customer choice and good review & ratings') }}
                        </p>
                    </div>
                </div>
                @php($all_restaurant_default_status = \App\Models\BusinessSetting::where('key', 'all_restaurant_default_status')->first())
                @php($all_restaurant_default_status = $all_restaurant_default_status ? $all_restaurant_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Currently showing this section by all active restaurants') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="all_restaurant_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $all_restaurant_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="all_restaurant_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $all_restaurant_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($all_restaurant_sort_by_general = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_general')->where('type','general')->first())
                                    @php($all_restaurant_sort_by_general = $all_restaurant_sort_by_general ? $all_restaurant_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="latest_created" {{ $all_restaurant_sort_by_general == 'latest_created' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by latest created')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="first_created" {{ $all_restaurant_sort_by_general == 'first_created' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by first created')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="order_count" {{ $all_restaurant_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by orders')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="nearest_first" {{ $all_restaurant_sort_by_general == 'nearest_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show nearest restaurant first')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="review_count" {{ $all_restaurant_sort_by_general == 'review_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews count')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="rating" {{ $all_restaurant_sort_by_general == 'rating' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="a_to_z" {{ $all_restaurant_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (A to Z)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_general" value="z_to_a" {{ $all_restaurant_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (Z to A)')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($all_restaurant_sort_by_unavailable = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($all_restaurant_sort_by_unavailable = $all_restaurant_sort_by_unavailable ? $all_restaurant_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_unavailable" value="last" {{ $all_restaurant_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show currently closed restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_unavailable" value="remove" {{ $all_restaurant_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove currently closed restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_unavailable" value="none" {{ $all_restaurant_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($all_restaurant_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'all_restaurant_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($all_restaurant_sort_by_temp_closed = $all_restaurant_sort_by_temp_closed ? $all_restaurant_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_temp_closed" value="last" {{ $all_restaurant_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show temporarily off restaurants in the last')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_temp_closed" value="remove" {{ $all_restaurant_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove temporarily off restaurants from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="all_restaurant_sort_by_temp_closed" value="none" {{ $all_restaurant_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Food Campaign')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('The food campaign includes the list of discounted food items offered for the customers') }}
                        </p>
                    </div>
                </div>
                @php($campaign_food_default_status = \App\Models\BusinessSetting::where('key', 'campaign_food_default_status')->first())
                @php($campaign_food_default_status = $campaign_food_default_status ? $campaign_food_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Currently showing this section by active food campaigns') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="campaign_food_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $campaign_food_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="campaign_food_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $campaign_food_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($campaign_food_sort_by_general = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_general')->where('type','general')->first())
                                    @php($campaign_food_sort_by_general = $campaign_food_sort_by_general ? $campaign_food_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="latest_created" {{ $campaign_food_sort_by_general == 'latest_created' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by latest created')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="first_created" {{ $campaign_food_sort_by_general == 'first_created' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by first created')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="order_count" {{ $campaign_food_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by orders')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="nearest_first" {{ $campaign_food_sort_by_general == 'nearest_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show nearest food first')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="nearest_end_first" {{ $campaign_food_sort_by_general == 'nearest_end_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show end date near foods first')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="a_to_z" {{ $campaign_food_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (A to Z)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_general" value="z_to_a" {{ $campaign_food_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by Alphabetical (Z to A)')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($campaign_food_sort_by_unavailable = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($campaign_food_sort_by_unavailable = $campaign_food_sort_by_unavailable ? $campaign_food_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_unavailable" value="last" {{ $campaign_food_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show unavailable foods in the last (both food & restaurant are unavailable)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_unavailable" value="remove" {{ $campaign_food_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove unavailable foods from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_unavailable" value="none" {{ $campaign_food_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($campaign_food_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'campaign_food_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($campaign_food_sort_by_temp_closed = $campaign_food_sort_by_temp_closed ? $campaign_food_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_temp_closed" value="last" {{ $campaign_food_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show food in the last if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_temp_closed" value="remove" {{ $campaign_food_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove food from the list if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="campaign_food_sort_by_temp_closed" value="none" {{ $campaign_food_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Best Reviewed Food')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('Best Reviewed items are the top most ordered item list of customer choice which are highly rated & reviewed ') }}
                        </p>
                    </div>
                </div>
                @php($best_reviewed_food_default_status = \App\Models\BusinessSetting::where('key', 'best_reviewed_food_default_status')->first())
                @php($best_reviewed_food_default_status = $best_reviewed_food_default_status ? $best_reviewed_food_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Currently sorting this section by top ratings') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="best_reviewed_food_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $best_reviewed_food_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="best_reviewed_food_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $best_reviewed_food_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($best_reviewed_food_sort_by_general = \App\Models\PriorityList::where('name', 'best_reviewed_food_sort_by_general')->where('type','general')->first())
                                    @php($best_reviewed_food_sort_by_general = $best_reviewed_food_sort_by_general ? $best_reviewed_food_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_general" value="review_count" {{ $best_reviewed_food_sort_by_general == 'review_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by reviews count')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_general" value="rating" {{ $best_reviewed_food_sort_by_general == 'rating' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Sort by ratings')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_general" value="nearest_first" {{ $best_reviewed_food_sort_by_general == 'nearest_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show nearest food first')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($best_reviewed_food_sort_by_rating = \App\Models\PriorityList::where('name', 'best_reviewed_food_sort_by_rating')->where('type','rating')->first())
                                    @php($best_reviewed_food_sort_by_rating = $best_reviewed_food_sort_by_rating ? $best_reviewed_food_sort_by_rating->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_rating" value="four_plus" {{ $best_reviewed_food_sort_by_rating == 'four_plus' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show 4+ rated foods')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_rating" value="three_half_plus" {{ $best_reviewed_food_sort_by_rating == 'three_half_plus' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show 3.5+ rated foods')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_rating" value="three_plus" {{ $best_reviewed_food_sort_by_rating == 'three_plus' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show 3+ rated foods')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_rating" value="none" {{ $best_reviewed_food_sort_by_rating == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($best_reviewed_food_sort_by_unavailable = \App\Models\PriorityList::where('name', 'best_reviewed_food_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($best_reviewed_food_sort_by_unavailable = $best_reviewed_food_sort_by_unavailable ? $best_reviewed_food_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_unavailable" value="last" {{ $best_reviewed_food_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show unavailable foods in the last (both food & restaurant are unavailable)')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_unavailable" value="remove" {{ $best_reviewed_food_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove unavailable foods from the list')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_unavailable" value="none" {{ $best_reviewed_food_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                    @php($best_reviewed_food_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'best_reviewed_food_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($best_reviewed_food_sort_by_temp_closed = $best_reviewed_food_sort_by_temp_closed ? $best_reviewed_food_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_temp_closed" value="last" {{ $best_reviewed_food_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Show food in the last if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_temp_closed" value="remove" {{ $best_reviewed_food_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('Remove food from the list if restaurant is temporarily off')}}
                                            </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="best_reviewed_food_sort_by_temp_closed" value="none" {{ $best_reviewed_food_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                {{translate('None')}}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Category Wise Foods')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('Category Wise Foods means the latest food items list under a specific category') }}
                        </p>
                    </div>
                </div>
                @php($category_food_default_status = \App\Models\BusinessSetting::where('key', 'category_food_default_status')->first())
                @php($category_food_default_status = $category_food_default_status ? $category_food_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Currently sorting this section by latest items.') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="category_food_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $category_food_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="category_food_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $category_food_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($category_food_sort_by_general = \App\Models\PriorityList::where('name', 'category_food_sort_by_general')->where('type','general')->first())
                                    @php($category_food_sort_by_general = $category_food_sort_by_general ? $category_food_sort_by_general->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="order_count" {{ $category_food_sort_by_general == 'order_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Sort by orders')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="review_count" {{ $category_food_sort_by_general == 'review_count' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Sort by reviews count')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="rating" {{ $category_food_sort_by_general == 'rating' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Sort by ratings')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="nearest_first" {{ $category_food_sort_by_general == 'nearest_first' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Show nearest food first')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="a_to_z" {{ $category_food_sort_by_general == 'a_to_z' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Sort by Alphabetical (A to Z)')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_general" value="z_to_a" {{ $category_food_sort_by_general == 'z_to_a' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Sort by Alphabetical (Z to A)')}}
                                        </span>
                                        </label>
                                    </div>
                                    @php($category_food_sort_by_unavailable = \App\Models\PriorityList::where('name', 'category_food_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($category_food_sort_by_unavailable = $category_food_sort_by_unavailable ? $category_food_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_unavailable" value="last" {{ $category_food_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Show unavailable foods in the last (both food & restaurant are unavailable)')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_unavailable" value="remove" {{ $category_food_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Remove unavailable foods from the list')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_unavailable" value="none" {{ $category_food_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('None')}}
                                        </span>
                                        </label>
                                    </div>
                                    @php($category_food_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'category_food_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($category_food_sort_by_temp_closed = $category_food_sort_by_temp_closed ? $category_food_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_temp_closed" value="last" {{ $category_food_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Show food in the last if restaurant is temporarily off')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_temp_closed" value="remove" {{ $category_food_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Remove food from the list if restaurant is temporarily off')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="category_food_sort_by_temp_closed" value="none" {{ $category_food_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('None')}}
                                        </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="max-w-353px">
                        <h4 class="mb-2 mt-4">{{translate('Food and Restaurant list (Search Bar)')}}</h4>
                        <p class="m-0 fs-12">
                            {{ translate('Food and Restaurant list (Search Bar) means the food and restaurant list from top search bar.') }}
                        </p>
                    </div>
                </div>
                @php($search_bar_default_status = \App\Models\BusinessSetting::where('key', 'search_bar_default_status')->first())
                @php($search_bar_default_status = $search_bar_default_status ? $search_bar_default_status->value : 1)
                <div class="col-lg-6">
                    <div class="__bg-FAFAFA rounded">
                        <!-- Default Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use default sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Currently sorting this section by latest items.') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="search_bar_default_status" value="1" class="toggle-switch-input collapse-div-toggler" {{ $search_bar_default_status == '1'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Custom Collapsible Card -->
                        <div class="sorting-card p-20px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="w-0 flex-grow">
                                    <h5 class="fs-14 font-semibold">{{ translate('Use custom sorting list') }}</h5>
                                    <label class="form-label m-0">
                                    <span class="input-label-secondary text--title ml-0 mr-1" data-toggle="tooltip" data-placement="top" data-original-title="">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                        <div class="fs-12">{{ translate('Set customized condition to show this list') }}</div>
                                    </label>
                                </div>
                                <div>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                        <input type="radio" name="search_bar_default_status" value="0" class="toggle-switch-input collapse-div-toggler" {{ $search_bar_default_status == '0'? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                    </label>
                                </div>
                            </div>
                            <div class="inner-collapse-div">
                                <div class="pt-4">
                                    @php($search_bar_sort_by_unavailable = \App\Models\PriorityList::where('name', 'search_bar_sort_by_unavailable')->where('type','unavailable')->first())
                                    @php($search_bar_sort_by_unavailable = $search_bar_sort_by_unavailable ? $search_bar_sort_by_unavailable->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_unavailable" value="last" {{ $search_bar_sort_by_unavailable == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Show unavailable foods & restaurant in the last (both food & restaurant are unavailable)')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_unavailable" value="remove" {{ $search_bar_sort_by_unavailable == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Remove unavailable foods & restaurant from the list')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_unavailable" value="none" {{ $search_bar_sort_by_unavailable == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('None')}}
                                        </span>
                                        </label>
                                    </div>
                                    @php($search_bar_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'search_bar_sort_by_temp_closed')->where('type','temp_closed')->first())
                                    @php($search_bar_sort_by_temp_closed = $search_bar_sort_by_temp_closed ? $search_bar_sort_by_temp_closed->value : '')
                                    <div class="border rounded p-3 d-flex flex-column gap-2 fs-14 mb-10px">
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_temp_closed" value="last" {{ $search_bar_sort_by_temp_closed == 'last' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Show food & restaurant in the last if restaurant is temporarily off')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_temp_closed" value="remove" {{ $search_bar_sort_by_temp_closed == 'remove' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('Remove food & restaurant from the list if restaurant is temporarily off')}}
                                        </span>
                                        </label>
                                        <label class="form-check form--check">
                                            <input class="form-check-input" type="radio" name="search_bar_sort_by_temp_closed" value="none" {{ $search_bar_sort_by_temp_closed == 'none' ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                            {{translate('None')}}
                                        </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="btn--container justify-content-end position-sticky bottom-0 p-3 bg-white">
                <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" class="btn btn--primary">{{translate('Save Information')}}</button>
            </div>
        </div>
        </form>
    </div>

</div>
@endsection

@push('script_2')
<script>
    $(".collapse-div-toggler").on('change', function() {
        $(this).closest('.sorting-card').find('.inner-collapse-div').slideToggle();
        $(this).closest('.sorting-card').siblings().find('.inner-collapse-div').slideUp();
        $(this).closest('.sorting-card').siblings().find('.toggle-switch-input').prop('checked', false);
    });

    $(window).on('load', function(){
        $('.collapse-div-toggler').each(function(){
            if($(this).prop('checked') == true){
                $(this).closest('.sorting-card').find('.inner-collapse-div').show();
            }
        });
    })

    $('#reset_btn').click(function(){
        $('.collapse-div-toggler').each(function(){
            if($(this).prop('checked') == true){
                $(this).closest('.sorting-card').find('.inner-collapse-div').show();
            }else{
                $(this).closest('.sorting-card').siblings().find('.inner-collapse-div').slideUp();
                $(this).closest('.sorting-card').siblings().find('.toggle-switch-input').prop('checked', false);
            }
        });
    })

</script>
@endpush
