@extends('layouts.app')

@section('title', 'Kasir Dashboard')

@push('style')
@endpush

@section('main')
    <div class="main-content">
        @if (auth()->user()->roles == 'admin' || auth()->user()->roles == 'reseller')
            <section class="section">
                <div class="section-header">
                    <h1>Users</h1>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            @include('layouts.alert')
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Trial User</h4>
                                </div>
                                <div class="card-body">
                                    <div class="float-right">
                                        <form method="GET" action="{{ route('home.post') }}">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Search"
                                                    name="name">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="clearfix mb-3"></div>

                                    <div class="table-responsive">
                                        <table class="table-striped table">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Roles</th>
                                                <th>Reseller</th>
                                                <th>Created At</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}{{ $user->roles == 'reseller' ? ' (' . $user->reseller_id . ')' : '' }}
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ ucwords($user->roles) }}</td>
                                                    <td>{{ $user->marketing }}</td>
                                                    <td>{{ $user->created_at }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href='/konfirmasi/{{ $user->phone }}'
                                                                class="btn btn-sm btn-warning btn-icon" target="_blank">
                                                                <i class="fas fa-edit"></i>
                                                                Approval
                                                            </a>
                                                            {{-- <a href='{{ route('user.edit', $user->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <a href='#' id="delete-data" data-id="{{ $user->id }}"
                                                            class="ml-2 btn btn-sm btn-danger btn-icon">
                                                            <i class="fas fa-times"></i>
                                                            Delete
                                                        </a> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </table>
                                    </div>
                                    <div class="float-right">
                                        {{ $users->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
@endpush
