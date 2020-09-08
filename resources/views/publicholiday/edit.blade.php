@extends('layouts.default', ['crumbroute' => 'publicholidayedit'])
@section('title', 'Edit Public Holiday')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .selectize-control{
            width: 72%;
        }
    </style>
@endsection

@section('content')
    {{ Form::model($publicHoliday, ['route' => ['public-holiday-update', $publicHoliday->id]])  }}
    @include('partials.form.publicholiday.edit')
    {{ Form::close()  }}
@endsection

@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{asset('js/main/publicholiday.js')}}"></script>
@endsection
