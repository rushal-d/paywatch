@extends('layouts.default', ['crumbroute' => 'publicholiday'])
@section('title', 'Public Holiday')

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .button {
            border: none;
            color: white;
            padding: 5px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 2px 1px;
            cursor: pointer;
        }

        .adjust-width {
            max-width: 170px;
            width: 100%;
            margin-left: 10px;
            display: block;
        }
    </style>
@endsection
@section('content')

    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('public-holiday-create') }}"><i class="fa fa-plus"></i>
                        Add
                        New</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="search-box">
            {{ Form::open(array('route' => 'public-holiday','method' => 'get'))  }}
            <div class="row">
                {!! Form::select('branch_id', $branches , request('branch_id'),array('id'=>'branch','class'=> 'adjust-width','data-validation' => 'required',
                         ) ) !!}

                {!! Form::select('religion_id', $religions , request('religion_id'), array('id'=>'religion', 'class'=> 'adjust-width', 'placeholder'=>'Select a religion') ) !!}

                {!! Form::select('caste_id', $castes , request('caste_id'), array( 'id'=>'caste', 'class'=> 'adjust-width', 'placeholder'=>'Select a caste') ) !!}

                {!! Form::select('gender', $genders , request('gender'), array( 'id'=>'gender', 'class'=> 'adjust-width') ) !!}

                {{ Form::select('rpp', $records_per_page_options, $records_per_page , array('id' => 'records_per_page', 'class'=> 'adjust-width', 'placeholder' => 'Select a Record Per Page')) }}

                <button class="button btn-success adjust-width" type="submit">Filter</button>
                {{ Form::close()  }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Public Holidays
            <span class="tag tag-pill tag-success pull-right">{{ $publicHolidays->total() }}</span>
        </div>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th width="2%"><input type="checkbox" class="check-all"></th>
                    <th width="15%">Public Holidays</th>
                    <th width="15%">Religions</th>
                    <th width="15%">Castes</th>
                    <th width="15%">Gender</th>
                    <th width="8%">Action</th>
                </tr>

                @forelse($publicHolidays as $publicHoliday)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $publicHoliday->id }}"></td>
                        <td>
                            <a href="{{ route('public-holiday-edit',['id' => $publicHoliday->id]) }}">{{ $publicHoliday->name  }}</a>
                        </td>
                        <td>{{implode(' ,',$publicHoliday->getRelatedReligionsName()->toArray())}}</td>
                        <td>{{implode(' ,',$publicHoliday->getRelatedCastesName()->toArray())}}</td>
                        <td>{{$genders[$publicHoliday->gender]}}</td>
                        <td class="actions-col">
                            <div class="actions">
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $publicHoliday->id }}"
                                   href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="5">There are no public holidays
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>
            <div class="pagination-links">{{ $publicHolidays->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
    </script>

    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>
        //check uncheck all
        $('.check-all').change(function () {
            console.log($(this).prop('checked'))
            var checked = $(this).prop('checked')
            if (checked) {
                $('.check-id').prop('checked', true).trigger('change');
            } else {
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        //individual checkbox change -- on change push to ids array for selected checkbox
        $(document).on('change', '.check-id', function () {
            console.log('checked');
            var checked = $(this).prop('checked');
            if (checked) {
                //check if already in array
                if ((ids.indexOf($(this).data('id')) > -1)) {
                } else {
                    ids.push($(this).data('id'));
                }
            } else {
                ids.splice($.inArray($(this).data('id'), ids), 1);
            }
        });
    </script>

    <script>
        //deleted bulk selected
        $('#delete-selected').click(function (e) {
            e.preventDefault();
            //Check if checkbox is unchecked
            if (ids != '') {
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete?',
                    callback: function (value) {
                        if (value) { //true if clicked on ok
                            $.ajax({
                                type: "POST",
                                url: '{{ route('public-holiday-destroy-selected') }}',
                                data: {_token: '{{ csrf_token() }}', ids: ids},
                                // send Blob objects via XHR requests:
                                success: function (response) {
                                    if (response == 'Successfully Deleted') {
                                        toastr.success('Successfully Deleted')
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 600);
                                    } else {
                                        vex.dialog.alert(response)
                                    }
                                },
                                error: function (response) {
                                    vex.dialog.alert(response)
                                }
                            });
                        }
                    }
                });
            } else {
                vex.dialog.alert('Please first make selection form the list')
            }
        });
    </script>

    <script>
        //delete
        $('body').on('click', '.delete-btn', function () {
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function (value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "POST",
                            url: '{{ route('public-holiday-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted');
                                    $this.parent().parent().parent().remove();
                                } else {
                                    vex.dialog.alert(response)
                                }
                            },
                            error: function (response) {
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>
@endsection
