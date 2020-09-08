@extends('layouts.default', ['crumbroute' => 'permission-create'])

@section('content')
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" data-background-color="custom-color">
                        <i class="fa fa-align-justify"></i>
                        MENU
                        <div class="card-header-actions">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Add Permission/Menu
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-offset-2">
                            <!-- The Modal -->
                            <div class="modal" id="myModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Create a Sidebar Head Menu</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form action="{{route('permission-add')}}" method="post">
                                                {{csrf_field()}}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="Name" class="col-md-2">Name</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="submitForm" action="{{route('permission-addmenu')}}" method="post">
                                {{csrf_field()}}
                                <section id="demo">
                                    <ol class="sortable">
                                        @each('entrust.permission.permission-recursive', $permissions, 'permission', 'entrust.permission.permission-recursive-none')
                                    </ol>
                                    <div class="text-center">
                                        <input id="toArray" name="toArray" type="button" value=
                                        "Change Order" class="btn btn-warning">
                                        <pre id="toArrayOutput"></pre>
                                    </div>
                                </section>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{--for hiearchy menu--}}
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}"/>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/nestedSortable.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#select-gear').selectize({
                sortField: 'text'
            });
            $('#select-beast').selectize({
                create: true,
                sortField: 'text'
            });
        });
    </script>
    <script>
       /* $(document).ready(function(){
            var ns = $('ol.sortable').nestedSortable({
                forcePlaceholderSize: true,
                handle: 'div',
                helper: 'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                // revert: 250,
                tabSize: 999999999999,
                tolerance: 'pointer',
                toleranceElement: '> div',
                maxLevels: 5,
                isTree: true,
                // expandOnHover: 700,
                startCollapsed: false,
                doNotClear: true
            });
        });*/
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
           maxLevels: 4,
           isTree: true,
           expandOnHover: 700,
           startCollapsed: false
       });

        $('.expandEditor').attr('title', 'Click to show/hide item editor');
        $('.disclose').attr('title', 'Click to show/hide children');
        $('.deleteMenu').attr('title', 'Click to delete item.');

        $('.disclose').on('click', function () {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');
        });

        $('.expandEditor, .itemTitle').click(function () {
            var id = $(this).attr('data-id');
            $('#menuEdit' + id).toggle();
            $(this).toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');
        });

        $('.deleteMenu').click(function () {
            var id = $(this).attr('data-id');
            $('#menuItem_' + id).remove();
        });

        $('#serialize').click(function () {
            serialized = $('ol.sortable').nestedSortable('serialize');
            $('#serializeOutput').text(serialized + '\n\n');
        })

        $('#toHierarchy').click(function (e) {
            hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
            hiered = dump(hiered);

            (typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
                $('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
        })

        $('#toArray').click(function (e) {

            arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
            // var json = JSON.stringify(arraied);
            var token = $('input[name=_token]').val();
            $.ajax({
                url: '{{route('permission-store')}}',
                type: "POST",
                dataType: "json",
                data: {
                    '_token': token,
                    "menu": arraied,
                },
                success: function (data) {
                     if(data.success == 1){
                        $('#submitForm').submit();
                     }
                     else{
                         alert('Error Occured! Please try again later!');
                     }
                }
            });
            /*arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
            arraied = dump(arraied);

            (typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
                $('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;*/
        });


        function dump(arr, level) {
            var dumped_text = "";
            if (!level) level = 0;

            //The padding given at the beginning of the line.
            var level_padding = "";
            for (var j = 0; j < level + 1; j++) level_padding += "    ";

            if (typeof(arr) == 'object') { //Array/Hashes/Objects
                for (var item in arr) {
                    var value = arr[item];

                    if (typeof(value) == 'object') { //If it is an array,
                        dumped_text += level_padding + "'" + item + "' ...\n";
                        dumped_text += dump(value, level + 1);
                    } else {
                        dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                    }
                }
            } else { //Strings/Chars/Numbers etc.
                dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
            }
            return dumped_text;
        }
    </script>
    <script>
        $(document).ready(function () {
            $.get('{{asset('https://raw.githubusercontent.com/FortAwesome/Font-Awesome/master/advanced-options/metadata/icons.json')}}',
                function (data) {
                    var icons = [];
                    $.each(JSON.parse(data), function (index, icon) {
                        //get type of icon
                        var style = icon.styles[0];
                        var prefix = 'fa' + style.substring(0, 1);
                        var iconName = 'fa-' + index;
                        var label = icon.label;
                        var iconFullName = prefix + ' ' + iconName;
                        icons.push({id: iconFullName, title: label});
                    });

                    //show items in selectize
                    $('.select').selectize({
                        maxItems: 1,
                        valueField: 'id',
                        searchField: 'title',
                        options: icons,
                        render: {
                            option: function (data, escape) {  // options to show
                                return '<div class="option">' +
                                    '<i class="' + data.id + '"></i> ' + data.title +
                                    '</div>';
                            },
                            item: function (data, escape) { //selected option
                                return '<div class="option">' +
                                    '<i class="' + data.id + '"></i> ' + data.title +
                                    '</div>';
                            }
                        },
                        create: function (input) { //create item
                            return {
                                id: input,
                                title: input,
                            };
                        }
                    });

                });

        });
    </script>
@endsection
