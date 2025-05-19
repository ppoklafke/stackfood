<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{translate('Subscription_Invoice')}}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .trx-invoice table {
            width: 100%;
            border-spacing: 0;
        }
        .trx-invoice {
            font-size: 0.75rem;
            font-family: "Inter", sans-serif;
            font-weight: 400
        }
        .trx-invoice * {
            margin: 0;
            padding: 0;
            line-height: 1.6;
            font-family: "Inter", sans-serif;
            color: #6a707c;
        }
        .trx-invoice .ltr {
            direction: ltr;
        }
        .trx-invoice .rtl {
            direction: rtl;
        }
        .trx-invoice .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
        }
        .trx-invoice img {
            /* max-width: 100%; */
        }
        .trx-invoice .customers {
            border-collapse: collapse;
            width: 100%;
        }
        .trx-invoice table {
            width: 100%;
        }
        .trx-invoice table thead th {
            padding: 8px;
            font-size: 9px;
        }
        .trx-invoice table tbody th, .trx-invoice table tbody td {
            padding: 8px;
            color: #6a707c;
        }
        .trx-invoice table.fz-12 thead th {
            font-size: 12px;
        }
        .trx-invoice table.fz-12 tbody th, .trx-invoice table.fz-12 tbody td {
            font-size: 12px;
        }
        .trx-invoice table.fz-10 thead th {
            font-size: 10px;
        }
        .trx-invoice table.fz-10 tbody th, .trx-invoice table.fz-10 tbody td {
            font-size: 10px;
        }
        .trx-invoice table.customers thead th {
            background-color: #f5fbff;
            color: #222;
            border-top: 1px solid #d6ebff;
            border-bottom: 1px solid #d6ebff;
            padding-top: 10px;
        }
        .trx-invoice table.customers tbody th {
            background-color: #fafcff;
        }
        .trx-invoice table.customers tbody td {
            padding-block: 10px;
            border-bottom: 1px solid #d7dae0;
        }
        .trx-invoice .calc-table * {
            color: #222;
        }
        .trx-invoice .calc-table td {
            padding-inline: 0 !important;
        }
        .trx-invoice .calc-table {
            padding: 0 !important;
        }
        .trx-invoice .text-left {
            text-align: left !important;
        }
        .trx-invoice .pb-2 {
            padding-bottom: 8px !important;
        }
        .trx-invoice .pb-3 {
            padding-bottom: 16px !important;
        }
        .trx-invoice .text-right {
            text-align: right !important;
        }
        .trx-invoice table th.text-right {
            text-align: right !important;
        }
        .fz-10 {
            font-size: 13px;
        }
        .fz-11 {
            font-size: 14px;
            color:rgb(105, 101, 101)
        }
        .fz-17 {
            font-size: 19px;
            font-weight: 700
        }
        .fz-12 {
            font-size: 15px;
        }
        .border {
            border: 1px solid #e5e5e5
        }
        .border-bottom {
            border-bottom: 1px solid #e5e5e5
        }
        .border-left {
            border-left: 1px solid #e5e5e5
        }
        .__subscribe-table thead tr th {
            font-size: 13px;
            font-weight: 500;
            color: rgba(51, 66, 87, 1);
            background: rgba(16, 121, 128, 0.08);
        }

        .__subscribe-table thead tr th:first-child {
            border-top-left-radius: 5px
        }
        .__subscribe-table thead tr th:last-child {
            border-top-right-radius: 5px
        }
        .__subscribe-table tbody tr td {
            background: rgba(249, 251, 251, 1);
            text-align: center;
            font-size: 12px;
        }
        .__subscribe-table tbody tr td:first-child {
            border-bottom-left-radius: 5px;
        }
        .__subscribe-table tbody tr td:last-child {
            border-bottom-right-radius: 5px;
        }
        .__subscribe-table thead tr th span,
        .__subscribe-table tbody tr td span {
            display: block;
            border-right: 1px solid #e5e5e5;
        }
        .__subscribe-table thead tr th:last-child span,
        .__subscribe-table tbody tr td:last-child span {
            border: none
        }
        .footer-table {
            border-radius: 6px;
            font-size: 12px
        }
        img {
            max-width: 40%;
            height: auto;
            }
    </style>
</head>
<body>


<div class="content container-fluid">
    <div id="printableArea2">
        <div class="first content-position trx-invoice" style="width:732px;margin: 0 auto;">
            <div class="bg-white p-3 rounded-md">
                <table class="fz-10">
                    <tr>
                        <td style="padding:0;text-align:left">
                            <div class="text-dark" style="text-transform:uppercase; font-size:22px;margin-bottom:5px">
                                {{ translate('Invoice')}}
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('Transaction ID')}}</span> : #{{ $transaction->id }}
                            </div>
                            <div class="font-normal">
                                <span class="text-dark">{{ translate('invoice_Date')}}</span> : {{ App\CentralLogics\Helpers::date_format($transaction->created_at) }}
                            </div>
                        </td>
                        <td style="padding:0;text-align:right">
                            <img  alt="6amMart"
                            src="{{ \App\CentralLogics\Helpers::get_full_url('business',$logo?->value,$logo?->storage[0]?->value ?? 'public' ) }}"
                            style="margin-bottom:5px">
                            <div class="font-normal">
                                {{ $BusinessData['address'] }}
                            </div>
                            {{-- <div  class="font-normal">
                                {{ translate('TNX ID') }} {{ $transaction->id}}
                            </div> --}}
                        </td>
                    </tr>
                </table>
                <br>
                <table class="border" style="border-radius:12px;">
                    <tr>
                        <td class="text-left" style="padding:21px 8px;">
                            <div class="fz-11">{{ translate('Restaurant Owner')}}</div>
                            <span class="text-dark fz-10">{{ $transaction?->restaurant?->vendor?->f_name. ' '.$transaction?->restaurant?->vendor?->l_name }}</span>
                        </td>
                        <td class="text-left" style="padding:21px 8px;">
                            <div class="fz-11">{{ translate('Phone')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">{{ $transaction?->restaurant?->vendor?->phone }}</span></div>
                        </td>
                        <td class="text-left" style="padding:21px 8px;">
                            <div class="fz-11">{{ translate('Email')}}</div>
                            <div class="font-medium fz-10 mb-2 text-capitalize">
                            <span class="text-dark">{{ $transaction?->restaurant?->vendor?->email }}</span></div>
                        </td>
                        <td class="text-right" style="padding:21px 8px;">
                            <div class="mb-1 fz-10" style="white-space: nowrap">
                                <span class="text-dark">{{translate('invoice_of')}}</span> <span class="font-normal">({{  App\CentralLogics\Helpers::currency_code() }})</span>
                            </div>
                            <div class="text-right" style="font-size: 24px;font-weight:800;color:#ff8a00;white-space:nowrap;">{{  App\CentralLogics\Helpers::format_currency($transaction->paid_amount)  }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="border-bottom" style="padding: 0"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: 10px !important;"></td>
                    </tr>
                    <tr>
                        <td style="padding:0" colspan="4">
                            <table>
                                <tr>
                                    <td class="vertical-align-top" style="padding:8px 24px; width:30%">
                                        <div class="fz-11">{{ translate('payment')}}</div>
                                        <div class="font-medium fz-10 mb-2 text-capitalize">
                                        <span class="text-dark">{{ translate($transaction->payment_method) }}</span></div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 24px; width:34%">
                                        <div>{{ translate('Purchased') }}</div>
                                        <div class="font-bold fz-11">{{ $transaction->package->package_name}} {{ translate('messages.Package') }}</div>
                                    </td>
                                    <td class="fz-10 border-left vertical-align-top" style="padding:8px 24px; width:34%">
                                        <div>{{translate('Duration')}}</div>
                                        <div class="font-bold fz-11"> {{ $transaction->validity }} {{translate('Days')}} </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="height: 10px;padding: 0 !important;line-height:10px"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="height: 20px;padding: 0 !important;line-height:20px">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: 10px">

                            <table class="table __subscribe-table table-borderless mt-3" style="color: rgb(105, 101, 101)">
                                <thead>
                                    <tr>
                                        <th>
                                            <span>{{ translate('Transaction ID') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Package Name') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Transaction Time') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Validity Time') }}</span>
                                        </th>
                                        <th>
                                            <span>{{ translate('Amount') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 18px 10px;">
                                            <span>{{ $transaction->id}}</span>
                                        </td>
                                        <td style="padding: 18px 10px;">
                                            <span>{{ $transaction->package->package_name}}</span>
                                        </td>
                                        <td style="padding: 18px 10px;">
                                            <span>{{ App\CentralLogics\Helpers::date_format($transaction->created_at) }}</span>
                                        </td>
                                        <td style="padding: 18px 10px;">
                                            <span>{{ $transaction->validity }} {{translate('Days')}}</span>
                                        </td>
                                        <td style="padding: 18px 10px;">
                                            <span class="__txt-nowrap">
                                                {{  App\CentralLogics\Helpers::format_currency($transaction->paid_amount) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 180px" colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="font-semibold fz-12 pt-0" style="text-align: center;padding-bottom: 14px">
                            {{translate('Thanks for the Subscription')}}
                        </td>
                    </tr>
                </table>
            </div>
            <table><tr><td style="padding: 10px"></td></tr></table>
            <table class="border-0 footer-table" style="text-align:center; background-color: rgba(16, 121, 128, 0.08)">
                <tr>
                    <td style="padding: 10px;">
                        {{url('/') }}
                    </td>
                    <td style="padding: 10px">
                        {{ $BusinessData['phone'] }}
                    </td>
                    <td style="padding: 10px">
                        {{ $BusinessData['email_address'] }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>



</body>
</html>
