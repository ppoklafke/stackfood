<!-- Header -->
<div class="card h-100">
<div class="card-header">
    <div class="chat-user-info w-100 d-flex align-items-center">
        <div class="chat-user-info-img">
            <img class="avatar-img onerror-image"
                    src="{{$user['image_full_url']}}"
                    data-onerror-image="{{dynamicAsset('public/assets/admin')}}/img/160x160/img1.jpg"
                    alt="Image Description">
        </div>
        <div class="chat-user-info-content">
            <h5 class="mb-0 text-capitalize">
                {{$user['f_name'].' '.$user['l_name']}}</h5>
            <span dir="ltr"><a href="tel:{{ $user['phone'] }}"> {{ $user['phone'] }}</a></span>
        </div>
    </div>

</div>
@php
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv'];
    $pdfExtension = ['pdf'];
    $user_id = $user?->user ? $user?->user?->id: $user?->vendor->id ??  $user?->delivery_man?->id ;
    $date=null;
    @endphp
<div class="scroll-down card-body">
    @foreach($convs as $con)
        @php
            $isSender = $con->sender_id == $user->id;
            $convClass = $isSender ? 'conv-reply-1' : 'conv-reply-2';
            $timeAlignment = $isSender ? 'pl-1' : 'text-right pr-1';
        @endphp
        <div class="pt-1 pb-1">
            @if (!$date?->isSameDay($con->created_at) )
            <div class="text-center">
                <small  >{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}</small>
            </div>

        @endif
        @php
        $date=$con->created_at;
        @endphp
            @if($con?->message)
                <div class="{{ $convClass }}">
                    <div class="message" data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">{{ $con->message }}</div>
                </div>
            @endif
            @php
                $count=0;
            @endphp
            @if($con->file != null)
            <div class="{{ $convClass }} bg-transparent py-1">
                <div class="w-100">
                    <div class="row w-200px g-1 flex-wrap pt-1 {{ $isSender ? "justify-content-start" : "ms-auto justify-content-end"}}">
                        @foreach ($con->file_full_url as $index => $file)
                            @php
                                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            @endphp
                            @if (in_array($extension, $imageExtensions))
                                @if($index < 3)
                                    <div class="col-6">
                                        <div class="position-relative h-100 img_row{{$index}}">
                                            <a data-lightbox="user-conv-images-{{ $con->id }}"
                                                href="{{ $file }}"
                                                class="conversation-img"  data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">
                                                <img class="img-fit" alt="" src="{{ $file }}">
                                            </a>
                                        </div>
                                    </div>
                                @elseif($index == 3)
                                    <div class="col-6">
                                        <div class="position-relative h-100 img_row{{$index}}">
                                            <a data-lightbox="user-conv-images-{{ $con->id }}"
                                                href="{{ $file }}"
                                                class="conversation-img"  data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">
                                                <img class="img-fit" alt=""
                                                        src="{{ $file }}">
                                                        @if ( count(json_decode($con->file)) > 4)
                                                        <div class="extra-images">
                                                            <span class="extra-image-count">
                                                                +{{ count(json_decode($con->file)) - $index }}
                                                            </span>
                                                        </div>
                                                        @endif
                                            </a>
                                        </div>
                                    </div>
                                @else

                                <div class="col-6 d-none">
                                    <div class="position-relative h-100 img_row{{$index}}">
                                        <a data-lightbox="user-conv-images-{{ $con->id }}"
                                            href="{{ $file }}"
                                            class="conversation-img"  data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">
                                            <img class="img-fit" alt=""
                                                    src="{{ $file }}">
                                            <div class="extra-images">
                                                <span class="extra-image-count">
                                                    +{{ count(json_decode($con->file)) - $index }}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                @endif
                            @endif

                            @if (in_array($extension, $videoExtensions))
                                <div class="col-12">
                                    <a href="{{$file}}" target="_blank" class="uploaded-file-item"  data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">
                                        <img src="undefined" class="file-icon" alt="">
                                        <div class="upload-file-item-content">
                                            @php
                                            $file_size = get_headers($file, true);
                                            $size = isset($file_size['Content-Length'])?(int) $file_size['Content-Length']:0;
                                            $size=  $size > 0 ?  $size : 1;
                                            $size= $size/1024  > 1024 ?  round($size/(1024*1024),2 ) .'Mb' :  round($size/1024,2) .'Kb' ;
                                        @endphp

                                            <div class="text-dark">{{translate('messages.Video_Attachment')}}-{{ ++$count }} .{{ $extension }}</div>
                                            <small>{{ $size}}</small>
                                        </div>
                                    </a>
                                </div>
                            @elseif (in_array($extension, $pdfExtension))
                                <div class="col-12">
                                    <a href="{{$file}}" class="uploaded-file-item" target="_blank"  data-toggle="tooltip" data-placement="top" title="{{ \App\CentralLogics\Helpers::time_date_format($con->created_at) }}">
                                        <img src="undefined" class="file-icon" alt="">
                                        <div class="upload-file-item-content">

                                            @php
                                                $file_size = get_headers($file, true);
                                                $size = isset($file_size['Content-Length'])?(int) $file_size['Content-Length']:0;
                                                $size=  $size > 0 ?  $size : 1;
                                                $size= $size/1024  > 1024 ?  round($size/(1024*1024),2 ) .'Mb' :  round($size/1024,2) .'Kb' ;
                                            @endphp

                                            <div>{{translate('messages.Attachment')}}-{{ ++$count }}.{{ $extension }}</div>
                                            <small>{{ $size}}</small>
                                        </div>
                                    </a>
                                </div>
                            @endif

                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <div class="{{ $timeAlignment }}">
                @if (!$isSender)
                    @if ($con->is_seen == 1)
                    <span class="text-primary"><i class="tio-all-done"></i></span>
                    @else
                        <span><i class="tio-done"></i></span>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
    <div id="scroll-here"></div>
</div>


</div>




<script src="{{ dynamicAsset('/public/assets/admin/plugins/lightbox/js/lightbox.min.js')}}"></script>

<script src="{{ dynamicAsset('public/assets/admin/js/chatting/select-multiple-file.js')}}"></script>
<script src="{{ dynamicAsset('public/assets/admin/js/chatting/select-multiple-image-for-message.js')}}"></script>

<script src="{{ dynamicAsset('public/assets/admin/js/chatting/picmo-emoji.js')}}"></script>
<script src="{{dynamicAsset('public/assets/admin')}}/js/view-pages/common.js"></script>
<script>

    $(document).ready(function () {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        },0);

        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tooltip"]').on('show.bs.tooltip', function() {
            let $tooltip = $($(this).data('bs.tooltip').tip);
            $tooltip.addClass("conversation-tooltip");
        });

    });


    $(document).ready(function () {
        const { createPopup } = window.picmoPopup;
        const trigger = document.getElementById("trigger");
        const inputField = document.getElementById("msgInputValue");

        const picker = createPopup(
            {},
            {
                referenceElement: trigger,
                triggerElement: trigger,
                position: "right-end",
            }
        );

        $("#trigger").on("click", function () {
            picker.toggle();
        });

        picker.addEventListener("emoji:select", (selection) => {
            const { emoji } = selection;

            const startPos = inputField.selectionStart;
            const endPos = inputField.selectionEnd;
            const value = inputField.value;

            inputField.value =
                value.substring(0, startPos) + emoji + value.substring(endPos);

            inputField.selectionStart = inputField.selectionEnd =
                startPos + emoji.length;

            inputField.focus();
        });
    });

</script>
