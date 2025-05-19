<!-- Header -->
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
    <div class="dropdown">
        <button class="btn shadow-none" data-toggle="dropdown">
            <img src="{{dynamicAsset('/public/assets/admin/img/ellipsis.png')}}" alt="">
        </button>
        @if ($user?->user)

            <ul class="dropdown-menu conv-dropdown-menu">
                <li>
                    <a class="text-primary text-center" href="{{ route('admin.customer.view', [$user->user->id]) }}"
                    >{{ translate('view_order_list') }}</a>
                </li>
            </ul>
        @else
            <ul class="dropdown-menu conv-dropdown-menu">
                <li>
                    <a class="text-primary text-center" href="{{ route('admin.restaurant.view', [$user->vendor->restaurants[0]?->id]) }}"
                    >{{ translate('view_restaurant') }}</a>
                </li>
            </ul>
        @endif
    </div>
</div>
@php
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $videoExtensions = ['mp4', 'mov', 'avi', 'wmv', 'flv', 'mkv'];
    $pdfExtension = ['pdf'];
    $user_id = $user?->user ? $user?->user->id: $user?->vendor->id;
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

<div class="card-footer border-0 conv-reply-form">
    <form action="javascript:" method="post" id="reply-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="receiver_type" value="{{ $user?->user ? 'customer': 'vendor' }}">
        <input type="hidden" name="receiver_id" value="{{ $user_id }}">
        <div class="input_msg_write">
            <div class="position-relative d-flex">
                <div class="d-flex align-items-center m-0 position-absolute top-3 px-3 gap-2">
                    <label class="py-0 d-inline-flex cursor-pointer text-primary fs-18 mb-1">
                        <svg width="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.14992 15.8414L2.13325 15.8581C1.90825 15.3664 1.76659 14.8081 1.70825 14.1914C1.76659 14.7997 1.92492 15.3497 2.14992 15.8414Z" fill="#1455AC"/>
                            <path d="M7.50018 8.65026C8.59558 8.65026 9.4835 7.76229 9.4835 6.66693C9.4835 5.57156 8.59558 4.68359 7.50018 4.68359C6.40481 4.68359 5.51685 5.57156 5.51685 6.66693C5.51685 7.76229 6.40481 8.65026 7.50018 8.65026Z" fill="#1455AC"/>
                            <path d="M13.4917 1.66797H6.50841C3.47508 1.66797 1.66675 3.4763 1.66675 6.50964V13.493C1.66675 14.4013 1.82508 15.193 2.13341 15.8596C2.85008 17.443 4.38341 18.3346 6.50841 18.3346H13.4917C16.5251 18.3346 18.3334 16.5263 18.3334 13.493V11.5846V6.50964C18.3334 3.4763 16.5251 1.66797 13.4917 1.66797ZM16.9751 10.418C16.3251 9.85964 15.2751 9.85964 14.6251 10.418L11.1584 13.393C10.5084 13.9513 9.45842 13.9513 8.80841 13.393L8.52508 13.1596C7.93341 12.643 6.99175 12.593 6.32508 13.043L3.20841 15.1346C3.02508 14.668 2.91675 14.1263 2.91675 13.493V6.50964C2.91675 4.15964 4.15841 2.91797 6.50841 2.91797H13.4917C15.8417 2.91797 17.0834 4.15964 17.0834 6.50964V10.5096L16.9751 10.418Z" fill="#1455AC"/>
                        </svg>
                        <input type="file"  id="select-image" class="h-100 position-absolute w-100 " hidden multiple
                            accept="image/*">
                    </label>
                    <label class="py-0 d-inline-flex cursor-pointer text-primary fs-18 mb-1">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.61597 18.2902C4.66813 18.2904 3.7415 18.0096 2.95335 17.483C2.16519 16.9565 1.55092 16.2081 1.18827 15.3323C0.825613 14.4566 0.730874 13.493 0.916037 12.5634C1.1012 11.6338 1.55794 10.7801 2.22847 10.1102L9.2993 3.03849C9.41655 2.92124 9.57557 2.85537 9.74139 2.85537C9.9072 2.85537 10.0662 2.92124 10.1835 3.03849C10.3007 3.15574 10.3666 3.31476 10.3666 3.48058C10.3666 3.64639 10.3007 3.80541 10.1835 3.92266L3.11181 10.9935C2.76945 11.3193 2.49576 11.7103 2.30686 12.1435C2.11796 12.5768 2.01768 13.0434 2.01193 13.516C2.00617 13.9885 2.09506 14.4575 2.27334 14.8952C2.45163 15.3329 2.71572 15.7304 3.05004 16.0645C3.38436 16.3985 3.78216 16.6623 4.21999 16.8402C4.65783 17.0181 5.12685 17.1066 5.59941 17.1005C6.07198 17.0943 6.53854 16.9936 6.9716 16.8044C7.40465 16.6151 7.79545 16.3411 8.12097 15.9985L17.2543 6.86516C17.6728 6.43296 17.9047 5.85356 17.8999 5.25195C17.895 4.65033 17.6539 4.07473 17.2285 3.64931C16.8031 3.22389 16.2275 2.98276 15.6258 2.97793C15.0242 2.9731 14.4448 3.20496 14.0126 3.62349L6.64764 10.9935C6.45226 11.1889 6.3425 11.4539 6.3425 11.7302C6.3425 12.0065 6.45226 12.2715 6.64764 12.4668C6.84301 12.6622 7.108 12.772 7.3843 12.772C7.66061 12.772 7.9256 12.6622 8.12097 12.4668L12.8335 7.75349C12.8911 7.69377 12.96 7.64613 13.0363 7.61334C13.1125 7.58054 13.1945 7.56326 13.2775 7.5625C13.3605 7.56174 13.4428 7.57752 13.5196 7.60891C13.5964 7.6403 13.6663 7.68667 13.725 7.74533C13.7837 7.80398 13.8301 7.87374 13.8616 7.95054C13.893 8.02733 13.9089 8.10963 13.9082 8.19261C13.9075 8.2756 13.8903 8.35762 13.8576 8.43389C13.8249 8.51015 13.7773 8.57914 13.7176 8.63683L9.0043 13.351C8.57454 13.7809 7.99162 14.0224 7.38377 14.0225C6.77591 14.0226 6.19293 13.7812 5.76305 13.3514C5.33318 12.9216 5.09164 12.3387 5.09156 11.7309C5.09148 11.123 5.33288 10.54 5.76264 10.1102L13.1293 2.74849C13.7935 2.08424 14.6943 1.71102 15.6336 1.71094C16.5729 1.71086 17.4738 2.08393 18.1381 2.74808C18.8023 3.41222 19.1755 4.31304 19.1756 5.25236C19.1757 6.19169 18.8026 7.09257 18.1385 7.75683L9.00514 16.8868C8.56103 17.3332 8.03283 17.687 7.45109 17.9279C6.86934 18.1688 6.24561 18.2919 5.61597 18.2902Z" fill="#46A046"/>
                        </svg>
                        <input type="file"  id="select-file" class="h-100 position-absolute w-100 " hidden multiple
                            accept=".pdf,video/*">
                    </label>
                    <label class="py-0 d-inline-flex cursor-pointer text-primary fs-18 mb-1" id="trigger">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_161_7809)">
                            <path d="M10 20C8.02219 20 6.08879 19.4135 4.4443 18.3147C2.79981 17.2159 1.51809 15.6541 0.761209 13.8268C0.00433286 11.9996 -0.193701 9.98891 0.192152 8.0491C0.578004 6.10929 1.53041 4.32746 2.92894 2.92894C4.32746 1.53041 6.10929 0.578004 8.0491 0.192152C9.98891 -0.193701 11.9996 0.00433286 13.8268 0.761209C15.6541 1.51809 17.2159 2.79981 18.3147 4.4443C19.4135 6.08879 20 8.02219 20 10C19.9971 12.6513 18.9426 15.1932 17.0679 17.0679C15.1932 18.9426 12.6513 19.9971 10 20ZM10 1.66667C8.35183 1.66667 6.74066 2.15541 5.37025 3.07109C3.99984 3.98677 2.93174 5.28826 2.30101 6.81098C1.67028 8.33369 1.50525 10.0092 1.82679 11.6258C2.14834 13.2423 2.94201 14.7271 4.10745 15.8926C5.27289 17.058 6.75774 17.8517 8.37425 18.1732C9.99076 18.4948 11.6663 18.3297 13.189 17.699C14.7118 17.0683 16.0132 16.0002 16.9289 14.6298C17.8446 13.2593 18.3333 11.6482 18.3333 10C18.3309 7.79061 17.4522 5.67241 15.8899 4.11013C14.3276 2.54785 12.2094 1.6691 10 1.66667ZM14.7217 13.1217C14.8868 12.9747 14.9867 12.7682 14.9995 12.5475C15.0123 12.3268 14.937 12.1101 14.79 11.945C14.643 11.7799 14.4365 11.68 14.2158 11.6671C13.9952 11.6543 13.7784 11.7297 13.6133 11.8767C12.5946 12.7344 11.3288 13.2447 10 13.3333C8.67202 13.2448 7.40686 12.7351 6.38834 11.8783C6.22346 11.7311 6.00687 11.6555 5.7862 11.668C5.56553 11.6805 5.35887 11.7801 5.21167 11.945C5.06448 12.1099 4.98881 12.3265 5.00131 12.5471C5.01381 12.7678 5.11346 12.9745 5.27834 13.1217C6.60156 14.2521 8.26185 14.9126 10 15C11.7382 14.9126 13.3984 14.2521 14.7217 13.1217ZM5 8.33334C5 9.16667 5.74584 9.16667 6.66667 9.16667C7.5875 9.16667 8.33334 9.16667 8.33334 8.33334C8.33334 7.89131 8.15774 7.46739 7.84518 7.15483C7.53262 6.84227 7.1087 6.66667 6.66667 6.66667C6.22464 6.66667 5.80072 6.84227 5.48816 7.15483C5.1756 7.46739 5 7.89131 5 8.33334ZM11.6667 8.33334C11.6667 9.16667 12.4125 9.16667 13.3333 9.16667C14.2542 9.16667 15 9.16667 15 8.33334C15 7.89131 14.8244 7.46739 14.5118 7.15483C14.1993 6.84227 13.7754 6.66667 13.3333 6.66667C12.8913 6.66667 12.4674 6.84227 12.1548 7.15483C11.8423 7.46739 11.6667 7.89131 11.6667 8.33334Z" fill="#F9BD23"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_161_7809">
                            <rect width="20" height="20" fill="white"/>
                            </clipPath>
                            </defs>
                        </svg>
                    </label>
                </div>
                <label class="w-0 flex-grow-1 uploaded-file-container">
                    <textarea class="form-control shadow-none rounded-end-0 resize-none h-auto" id="msgInputValue" name="reply" type="text" placeholder="{{translate('send_a_message')}}" aria-label="Search"></textarea>
                    <div class="d-flex justify-content-between items-container">
                        <div class="overflow-x-auto scrollbar-thin pb-2 pt-3 w-100">
                            <div class="d-flex gap-3">
                                <div class="d-flex gap-3 image-array"></div>
                                <div class="d-flex gap-3 file-array"></div>
                                <div id="selected-files-container"></div>
                                <div id="selected-image-container"></div>
                            </div>
                        </div>
                    </div>
                </label>
                <button class="btn btn-soft-submit d-flex align-items-center justify-content-center" type="submit" id="msgSendBtn">
                    <i id="hide_icon" class="tio-send-outlined rotate-45deg d-inline-block"></i>
                    <img class="d-none" id="loader_icon" width="20px" src="{{dynamicAsset('/public/assets/admin/img/loader-icon.gif')}}" alt="">
                </button>
            </div>
        </div>
    </form>
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


    $('#reply-form').on('submit', function() {
        $('button[type=submit]').prop('disabled',true);
        $('#hide_icon').addClass('d-none').removeClass('d-inline-block');
        $('#loader_icon').removeClass('d-none');

            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.message.store', [$user_id]) }}',
                data: $('reply-form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.errors && data.errors.length > 0) {

                        if (data.errors[1] && data.errors[1].code == 'images') {
                            toastr.error(data.errors[1].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        } else {
                            toastr.error('{{ translate('Write_something_to_send_massage!') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }

                    }else{
                        if(data.response_message){
                                toastr.success(data.response_message, {
                                CloseButton: true,
                                ProgressBar: true
                                });
                            }
                        if (data.error_message !== null) {
                            toastr.error(data.error_message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }

                        $('#admin-view-conversation').html(data.view);

                        $('#msgInputValue').val('');
                        $('#select-file').val('');
                        $('#select-image').val('');
                        selectedFiles = [];
                        selectedImages = [];
                        $('.image-array, .file-array, #selected-files-container, #selected-image-container').html('');

                    }
                    $('button[type=submit]').prop('disabled',false);
                    $('#hide_icon').removeClass('d-none').addClass('d-inline-block');
                    $('#loader_icon').addClass('d-none');
                },
                error() {
                    toastr.error('{{ translate('Write_something_to_send_massage!') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('button[type=submit]').prop('disabled',false);
                    $('#hide_icon').removeClass('d-none').addClass('d-inline-block');
                    $('#loader_icon').addClass('d-none');
                }
            });
        });
</script>
