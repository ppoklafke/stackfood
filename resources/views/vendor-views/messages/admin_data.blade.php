@php($user= $admin)
@php($user->id = 0)
@if($user)
    @php($unchecked=($admin_conversation?->last_message?->sender_id == $user->id) ? $admin_conversation?->unread_message_count : 0)
    <div
        class="chat-user-info d-flex  p-3 align-items-center customer-list mb-2 {{$unchecked ? 'conv-active' : ''}}"
        onclick="viewConvs('{{route('vendor.message.view',['conversation_id'=>$admin_conversation?->id,'user_id'=>$user?->id])}}','customer-{{$user?->id}}','{{ $admin_conversation?->id }}','{{ $user?->id }}')"
        id="customer-{{$user?->id}}">
        @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first())
        <div class="chat-user-info-img d-none d-md-block">
            <img class="avatar-img onerror-image"
                 data-onerror-image="{{dynamicAsset('public/assets/admin/img/160x160/img1.jpg')}}"
                 src="{{ App\CentralLogics\Helpers::get_full_url('business', $restaurant_logo?->value, $restaurant_logo?->storage[0]?->value ?? 'public', 'favicon') }}"
                 alt="Image Description">
        </div>
        @php($admin_name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
        <div class="chat-user-info-content">
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3">{{ $admin_name->value ?? translate('admin') }}</span> <span
                    class="{{$unchecked ? 'badge badge-info' : ''}}">{{$unchecked ? $unchecked : ''}}</span>
                @if ($admin_conversation?->last_message?->created_at)
                    <small>
                        {{ Carbon\Carbon::parse($admin_conversation?->last_message?->created_at)->diffForHumans() }}
                    </small>
                @endif
            </h5>
            <span>{{ $user['phone'] }} </span>
            <div class="text-title">{{ $admin_conversation->last_message?->message ? Str::limit($admin_conversation->last_message?->message ??'', 35, '...') : (count($admin_conversation?->last_message?->file_full_url ?? []) > 0 ?  count($admin_conversation?->last_message?->file_full_url) .' '. translate('messages.Attachments') :'' )}}</div>
        </div>
    </div>
@endif
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
