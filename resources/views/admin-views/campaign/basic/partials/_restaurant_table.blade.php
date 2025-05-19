@foreach ($restaurants as $key => $restaurant)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            <a href="{{ route('admin.restaurant.view', $restaurant->id) }}" alt="view restaurant" class="table-rest-info">
                <img class="onerror-image" data-onerror-image="{{dynamicAsset('public/assets/admin/img/100x100/food-default-image.png')}}" src="{{$restaurant['logo_full_url'] }}"
                >
                <div class="info">
                    <span class="d-block text-body">
                        {{ Str::limit($restaurant->name, 20, '...') }}<br>




                        @php($reviews = $restaurant->reviews()->with('food',function($query){
                            $query->withoutGlobalScope(\App\Scopes\RestaurantScope::class);
                        })->get())
                        @php($user_rating = null)
                        @php($total_rating = 0)
                        @php($total_reviews = 0)
                        @foreach ($reviews as $key=>$value)
                            @php($user_rating += $value->rating)
                            @php($total_rating +=1)
                            @php($total_reviews +=1)
                        @endforeach
                        @php($user_rating = isset($user_rating) ? ($user_rating)/count($reviews) : 0)


                        <!-- Rating -->
                        <span class="rating">
                            @php($restaurant_rating = $restaurant['rating'] == null ? 0 : array_sum($restaurant['rating']) / 5)
                            <i class="tio-star"></i> {{ number_format($user_rating, 1) }}
                        </span>
                        <!-- Rating -->
                    </span>
                </div>
            </a>
        </td>
        <td>
            <span class="d-block owner--name">
                {{ $restaurant->vendor->f_name . ' ' . $restaurant->vendor->l_name }}
            </span>
            <span class="d-block font-size-sm ">
                {{ $restaurant['phone'] }}
            </span>
        </td>
        <td>
            {{ $restaurant->email }}
        </td>
        <td>
            {{ $restaurant->zone ? $restaurant->zone->name : translate('messages.zone_deleted') }}
        </td>
        @php($status = $restaurant->pivot ? $restaurant->pivot->campaign_status : translate('messages.not_found'))
        <td class="text-capitalize">
            @if ($status == 'pending')
                <span class="badge badge-soft-info">
                    {{ translate('messages.not_approved') }}
                </span>
            @elseif($status == 'confirmed')
                <span class="badge badge-soft-success">
                    {{ translate('messages.confirmed') }}
                </span>
            @elseif($status == 'rejected')
                <span class="badge badge-soft-danger">
                    {{ translate('messages.rejected') }}
                </span>
            @else
                <span class="badge badge-soft-info">
                    {{ translate(str_replace('_', ' ', $status)) }}
                </span>
            @endif

        </td>
        <td>
            @if ($restaurant->pivot && $restaurant->pivot->campaign_status == 'pending')
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn status-change-alert"
                       data-url="{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'confirmed']) }}" data-message="{{ translate('messages.you_want_to_confirm_this_restaurant') }}"
                        data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}">
                        <i class="tio-done font-weight-bold"></i>
                    </a>
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn status-change-alert" href="javascript:"
                       data-url="{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'rejected']) }}" data-message="{{ translate('messages.you_want_to_reject_this_restaurant') }}" data-toggle="tooltip" data-placement="top" title="{{translate('Deny')}}">
                        <i class="tio-clear font-weight-bold"></i>
                    </a>
                    <div></div>
                </div>
            @elseif ($restaurant->pivot && $restaurant->pivot->campaign_status == 'rejected')
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn status-change-alert"
                    data-url="{{ route('admin.campaign.restaurant_confirmation', [$campaign->id, $restaurant->id, 'confirmed']) }}" data-message="{{ translate('messages.you_want_to_confirm_this_restaurant') }}"
                        data-toggle="tooltip" data-placement="top" title="{{translate('Approve')}}">
                        <i class="tio-done font-weight-bold"></i>
                    </a>
                </div>
            @else
                <div class="btn--container justify-content-center">
                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:"
                        data-id="restaurant-{{ $restaurant['id'] }}" data-message="{{ translate('Want_to_remove_this_restaurant') }}"
                        title="{{ translate('messages.delete_restaurant') }}"><i
                            class="tio-delete-outlined"></i>
                    </a>
                    <form action="{{ route('admin.campaign.remove-restaurant', [$campaign->id, $restaurant['id']]) }}"
                        method="get" id="restaurant-{{ $restaurant['id'] }}">
                        @csrf @method('get')
                    </form>
                </div>
            @endif

        </td>

    </tr>
@endforeach
