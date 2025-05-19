<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\Validation\Validator;
class AdvertisementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'title.*' => 'max:255',
            'title.0' => 'required',
            'description.*' => 'nullable|max:65000',
            'restaurant_id' => 'required',
            'dates' => 'required',
            'advertisement_type' => 'required|in:video_promotion,restaurant_promotion',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'video_attachment' => 'nullable|file|mimes:mp4,mkv,webm|max:5120',


        ];
    }

    public function messages()
    {
        return [
            'restaurant_id.required' => translate('messages.Please_select_a_restaurant'),
            'title.0.required'=>translate('default_title_is_required'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dateRange = $this->dates;
            list($startDate, $endDate) = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay();

            if ($startDate < Carbon::today()) {
                $validator->errors()->add('date', translate('messages.Start date must be greater than or equal to today'));
            }

            if ($endDate < $startDate) {
                $validator->errors()->add('date', translate('messages.End date must be greater than start date'));
            }
        });
    }
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json(['errors' => Helpers::error_processor($validator)]);
        throw new ValidationException($validator, $response);
    }

}
