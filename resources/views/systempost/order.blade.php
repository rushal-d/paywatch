@extends('layouts.default', ['crumbroute' => 'post'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .selectize-input {
            min-height: 25px !important;
        }

        .selectize-control.select.single {
            margin: -5px 0px -7px 0px;
        }

        .custom-row .col-md-4 {
            padding: 0px 6px !important;
            height: 25px;
        }

        a, a:visited {

            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        pre, code {
            font-size: 12px;
        }

        pre {
            width: 100%;
            overflow: auto;
        }

        small {
            font-size: 90%;
        }

        small code {
            font-size: 11px;
        }

        .placeholder {
            outline: 1px dashed #4183C4;
        }

        .mjs-nestedSortable-error {
            background: #fbe3e4;
            border-color: transparent;
        }

        #tree {
            width: 550px;
            margin: 0;
        }

        ol {
            padding-left: 25px;
        }

        ol.sortable, ol.sortable ol {
            list-style-type: none;
        }

        input {
            transform: translate3d(0, -0.3rem, 0);
        }

        .sortable li div {

            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: move;
            border-color: #D4D4D4 #D4D4D4 #BCBCBC;
            margin: 0;
            padding: 3px;
        }

        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
        }

        .disclose, .expandEditor {
            cursor: pointer;
            width: 20px;
            display: none;
        }

        .sortable li.mjs-nestedSortable-collapsed > ol {
            display: none;
        }

        .sortable li.mjs-nestedSortable-branch > div > .disclose {
            display: inline-block;
        }

        .sortable span.ui-icon {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        .menuDiv {
            border: 1px solid #d4d4d4;
            background: #EBEBEB;
        }

        .menuEdit {
            background: #FFF;
        }

        .itemTitle {
            vertical-align: middle;
            cursor: pointer;
        }

        .deleteMenu {
            float: right;
            cursor: pointer;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 0;
        }

        h2 {
            font-size: 1.2em;
            font-weight: 400;
            font-style: italic;
            margin-top: .2em;
            margin-bottom: 1.5em;
        }

        h3 {
            font-size: 1em;
            margin: 1em 0 .3em;
        }

        p, ol, ul, pre, form {
            margin-top: 0;
            margin-bottom: 1em;
        }

        dl {
            margin: 0;
        }

        dd {
            margin: 0;
            padding: 0 0 0 1.5em;
        }

        code {
            background: #e5e5e5;
        }

        input {
            vertical-align: text-bottom;
        }

        .notice {
            color: #c33;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-lg-8 col-md-offset-2">

                            <section id="demo" class="mt-3">
                                <ol class="sortable">
                                    @foreach($systemposts as $post)
                                        <li id="menuItem_{{$post->post_id}}">
                                            <div class="row custom-row menuDiv">
                                                <div data-id="{{$post->post_id}}" class="itemTitle col-md-12">
                                                    <span>{{$post->post_title}}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                                <div class="text-center">
                                    <input id="toArray" name="toArray" type="button" value=
                                    "Change Order" class="btn btn-warning">
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}"/>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/nestedSortable.js')}}"></script>

    <script>
        var ns = $('ol.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 1,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false
        });

        $('#toArray').click(function (e) {

            arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
            var token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('system-post-order-save')}}',
                type: "POST",
                dataType: "json",
                data: {
                    '_token': token,
                    "posts": arraied,
                },
                success: function (data) {
                    toastr.success('Ordered Changed!')
                }
            });

        });
    </script>
@endsection
