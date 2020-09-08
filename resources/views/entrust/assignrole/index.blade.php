@extends('layouts.default', ['crumbroute' => 'assignrole-index'])
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>
                        Role with Permissions
                    </div>
                    <div class="card-content table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Role Name</th>
                                <th id="action-th">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($roles->count() > 0)
                                @foreach($roles as $role )
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $role->display_name ?? 'no data present' }}</td>
                                        <td class="td-actions">
                                            <a type="button" rel="tooltip" title="View"
                                               href="{{ route('assignrole-edit',$role->id) }}"
                                               class="btn btn-success btn-simple btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No Data Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
