@extends('layouts.default', ['crumbroute' => 'staff'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('staffmain.staff-edit-nav')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <a class="btn-success btn" href="{{route('training-detail-create',$staffmain->id)}}">
                Add New Training</a>
            Staff Training - {{$staffmain->name_eng}} -
            [CID: {{$staffmain->staff_central_id}}] - [Branch
            ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]
            <span class="tag tag-pill tag-success pull-right">{{ $trainings->count() }}</span>
        </div>
        <div class="card-block">

            <div class="table-responsive">
                <table class="table  table-striped table-hover table-all">
                    <tbody>
                    <tr>
                        <th>SN</th>
                        <th>Organization Name</th>
                        <th>Training Title</th>
                        <th>Category</th>
                        <th>Result</th>
                        <th>Major Subject</th>
                        <th>Action</th>
                    </tr>
                    @foreach($trainings as $training)
                        <tr>
                            <td>
                                <a href="{{route('training-detail-edit',$training->id)}}">
                                    {{$loop->iteration}}
                                </a>
                            </td>
                            <td>
                                <a href="{{route('training-detail-edit',$training->id)}}">
                                    {{$training->training_organization_name}}
                                </a>
                            </td>
                            <td>{{$training->training_title}}</td>
                            <td>{{$training->training_category}}</td>
                            <td>{{$training->result}}</td>
                            <td>{{$training->training_main_subject}}</td>
                            <td>
                                <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $training->id }}"
                                   href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                            url: '{{ route('training-detail-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted');
                                    $this.parent().parent().remove();
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
@endsection
