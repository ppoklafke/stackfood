<div class="col-6 pr-0">
    <input type="text" class="form-control form-control-lg form-recapcha" name="custome_recaptcha"
            id="custome_recaptcha" required placeholder="{{\translate('Enter_recaptcha_value')}}" autocomplete="off" value="{{env('APP_MODE')=='dev'? session('six_captcha'):''}}">
</div>
<div class="col-6 bg-white rounded d-flex">
    <img src="<?php echo $custome_recaptcha->inline(); ?>" class="rounded w-100" />
    <div class="p-3 pr-0"  id="reloadCaptcha">
        <i class="tio-cached"></i>
    </div>
</div>
<script>
    $("#reloadCaptcha").click(function() {
        $.ajax({
            url: "{{ route('reload-captcha') }}",
            type: "GET",
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show()
            },
            success: function(data) {
                $('#reload-captcha').html(data.view);
            },
            complete: function () {
                $('#loading').hide()
            }
        });
    })
</script>
