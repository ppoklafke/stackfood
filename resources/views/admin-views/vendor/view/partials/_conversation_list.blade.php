@foreach($conversations as $conv)


    @php($user= $conv->sender_type == 'vendor' ? $conv->receiver :  $conv->sender)
    @if (isset($user))
        @php($unchecked=($conv->last_message?->sender_id == $user->id) ? $conv->unread_message_count : 0)
        <div
            class="chat-user-info d-flex  p-3 align-items-center customer-list view-dm-conv {{$unchecked!=0?'conv-active':''}}"
            data-url="{{route('admin.delivery-man.message-view',['conversation_id'=>$conv->id,'user_id'=>$user->id])}}" data-active-id="customer-{{$user->id}}" data-conv-id="{{ $conv->id }}" data-sender-id="{{ $user->id }}"
            id="customer-{{$user->id}}">
        <div class="chat-user-info-img d-none d-md-block">
            <img class="avatar-img onerror-image"
                 src="{{ $user['image_full_url'] ?? dynamicAsset('public/assets/admin/img/160x160/img1.jpg') }}"
                 data-onerror-image="{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg"
                 alt="Image Description">
        </div>
        <div class="chat-user-info-content">
            <h5 class="mb-0 d-flex justify-content-between">
                <span class=" mr-3">{{$user['f_name'].' '.$user['l_name']}}</span> <span
                    class="{{$unchecked ? 'badge badge-info' : ''}}">{{$unchecked ? $unchecked : ''}}</span>
            </h5>
            <span>{{ $user['phone'] }}</span>
            <div class="text-title">{{ $conv->last_message?->message ? Str::limit($conv->last_message?->message ??'', 35, '...') : (count($conv->last_message->file_full_url) > 0 ?  count($conv->last_message->file_full_url) .' '. translate('messages.Attachments') :'' )}}</div>

        </div>
        </div>
    @else
        <div
            class="chat-user-info d-flex  p-3 align-items-center customer-list">
            <div class="chat-user-info-img d-none d-md-block">
                <img class="avatar-img"
                        src='{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg'
                        alt="Image Description">
            </div>
            <div class="chat-user-info-content">
                <h5 class="mb-0 d-flex justify-content-between">
                    <span class=" mr-3">{{translate('Account_not_found')}}</span>
                </h5>
            </div>
        </div>
    @endif
@endforeach

<script>
        $('.view-dm-conv').on('click', function (){
        let url = $(this).data('url');
        let id_to_active = $(this).data('active-id');
        let conv_id = $(this).data('conv-id');
        let sender_id = $(this).data('sender-id');
        viewConvs(url, id_to_active, conv_id, sender_id);
    })

    function viewConvs(url, id_to_active, conv_id, sender_id) {
        $('.customer-list').removeClass('conv-active');
        $('#' + id_to_active).addClass('conv-active');
        let new_url= "{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}" + '?conversation=' + conv_id+ '&user=' + sender_id;
            $.get({
                url: url,
                success: function(data) {
                    window.history.pushState('', 'New Page Title', new_url);
                    $('#vendor-view-conversation').html(data.view);
                }
            });
    }
</script>
