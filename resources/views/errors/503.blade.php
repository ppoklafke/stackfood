@extends('errors::minimal')

@section('title', translate('Service_Unavailable'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Service_Unavailable'))
