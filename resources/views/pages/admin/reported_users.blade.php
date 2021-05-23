@extends('layouts.admin_dashboard', ['sub' => 'reported_users'])

@section('page_head')
    <div class="my-4">
        <h2>Reported Users</h2>

        @include("partials.breadcrumbs", [ "pages" => [
            ["title" => "Home", "href" => "/"],
            ["title" => "Dashboard", "href" => "/dashboard"]
        ]])
    </div>
@endsection

@section('columns')
    <tr>
        <th scope="col">Username</th>
        <th scope="col">Reported for</th>
        <th scope="col">Details</th>
        <th scope="col">Date</th>
        {{-- <th scope="col">Action</th> --}}
    </tr>
@endsection
@section('table_body')
    {{-- Reported user entries --}}
    @foreach ($reports as $report)
        <tr class="user-entry align-middle" user_id="{{$report->member_id}}">
            <th scope="row">{{$report->username}}</th>
            <td>
                {{$report->reason}}
            </td>
            <td>
                {{ $report->description}}
            </td>
            <td>{{\Carbon\Carbon::parse($report->timestamp)->format('d M Y')}}</td>
        </tr>
    @endforeach
@endsection