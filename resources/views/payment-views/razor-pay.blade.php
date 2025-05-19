@extends('payment-views.layouts.master')


@section('content')
    <div class="razorpay-container">
        <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>

        <div class="razorpay-button-container">
            <button type="button" id="rzp-button1">Pay</button>
            <button type="button" class="razorpay-cancel-button" id="razorpay-cancel-button">Cancel</button>
        </div>
    </div>

    <script type="text/javascript">
        "use strict";
        document.getElementById('razorpay-cancel-button').onclick = function () {
            window.location.href = '{{ route('razor-pay.cancel', ['payment_id' => $data->id]) }}';
        };
        setTimeout(function () {
            let payButton = document.getElementById("rzp-button1");
            if (payButton) {
                payButton.click();
            }
        }, 500);
    </script>
@endsection

@push('script')
    <style>
        .razorpay-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            gap: 1rem;
        }

        .razorpay-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .razorpay-button-container button {
            --background-color: 69, 160, 73;
            --background-opacity: .8;
            background-color: rgba(var(--background-color), var(--background-opacity));
            color: white;
            border: none;
            padding: .5rem 2.5rem;
            font-size: .85rem;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .razorpay-button-container button:last-child {
            --background-color: 235, 20, 20;
        }

        .razorpay-button-container button:hover,
        .razorpay-button-container button:focus {
            outline: none;
            --background-opacity: 1;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
    </style>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let rzpButton = document.getElementById('rzp-button1');
            if (rzpButton) {
                fetch("{{ route('razor-pay.create-order') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        payment_request_id: "{{ $data->id }}",
                        payment_amount: "{{ $data->payment_amount }}",
                        currency_code: "{{ $data->currency_code }}"
                    })
                })
                .then(response => response.json())
                .then(orderData => {
                    var rzp1 = new Razorpay({
                        "key": "{{ config()->get('razor_config.api_key') }}",
                        "amount": orderData.amount,
                        "currency": orderData.currency,
                        "name": "{{ $business_name }}",
                        "description": "{{ $data->payment_amount }}",
                        "image": "{{ $business_logo }}",
                        "order_id": orderData.order_id,
                        "handler": function (response) {
                            console.log("Payment successful!", response);
                            window.location.href = "{{ route('razor-pay.verify-payment') }}?" + new URLSearchParams({
                                payment_request_id: "{{ $data->id }}",
                                payment_id: response.razorpay_payment_id,
                                order_id: response.razorpay_order_id,
                                signature: response.razorpay_signature
                            }).toString();
                        },
                        "prefill": {
                            "name": "{{ $payer?->name ?? '' }}",
                            "email": "{{ $payer?->email ?? '' }}",
                            "contact": "{{ $payer?->phone ?? '' }}"
                        },
                        "theme": {
                            "color": "#ff7529"
                        }
                    });

                    rzpButton.onclick = function (e) {
                        rzp1.open();
                        e.preventDefault();
                    };
                })
                .catch(error => {
                    console.error("Error creating order:", error);
                });
            } else {
                console.error("Button with ID 'rzp-button1' not found!");
            }
        });
    </script>
@endpush