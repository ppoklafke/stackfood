<?php

namespace App\Mail;

use App\Models\DataSetting;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewOrShift extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $status;
    protected $name;

    public function __construct($status, $name)
    {
        $this->status = $status;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $company_name = BusinessSetting::where('key', 'business_name')->first()->value;
        if($this->status == 'renew'){

            $data=EmailTemplate::where('type','restaurant')->where('email_type', 'subscription-renew')->first();
        } else{
            $data=EmailTemplate::where('type','restaurant')->where('email_type', 'subscription-shift')->first();
        }
        $user_link=DataSetting::where('key','restaurant_login_url')->first()?->value ?? 'restaurant';

        $template=$data?$data->email_template:5;
        $url = route('login',[$user_link]);
        $restaurant_name = $this->name;
        $title = Helpers::text_variable_data_format( value:$data['title']??'',restaurant_name:$restaurant_name??'');
        $body = Helpers::text_variable_data_format( value:$data['body']??'',restaurant_name:$restaurant_name??'');
        $footer_text = Helpers::text_variable_data_format( value:$data['footer_text']??'',restaurant_name:$restaurant_name??'');
        $copyright_text = Helpers::text_variable_data_format( value:$data['copyright_text']??'',restaurant_name:$restaurant_name??'');
        return $this->subject( $this->status == 'renew'? translate('Subscription_renew_successful') : translate('Subscription_Shift_successful'))->view('email-templates.new-email-format-'.$template, ['company_name'=>$company_name,'data'=>$data,'title'=>$title,'body'=>$body,'footer_text'=>$footer_text,'copyright_text'=>$copyright_text,'url'=>$url]);
    }
}
