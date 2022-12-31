@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Appointments: {{ $appointments->count() }}</div>
                        @foreach ($doctor_status as $item)
                            @if ($item->doctor_status == 'started')
                            <p class="bg-success text-center text-light p-3">Doctor has now {{$item->doctor_status}} watching the patients</p>
                            @elseif ($item->doctor_status == 'paused')
                            <p class="bg-primary text-center text-light">Doctor is taking some break please wait for a while</p>
                            @elseif ($item->doctor_status == 'closed')
                            <p class="bg-danger text-center text-light">Doctor has {{$item->doctor_status}} watching the patients</p>
                            @else
                            <p class="bg-warning text-center">waiting</p>
                            @endif
                        @endforeach
                    <div class="card-body table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Date for</th>
                                    <th scope="col">Created date</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $key=>$appointment)
                                @if ($appointment->status == 0)
                                <tr>
                                    <td>{{ $appointment->id }}</td>
                                    <td>{{ $appointment->doctor->name }}</td>
                                    <td>{{ $appointment->time }}</td>
                                    <td>{{ $appointment->date }}</td>
                                    <td>{{ $appointment->created_at->format('m-d-Y') }}</td>
                                    <td>
                                        @if ($appointment->status == 0)
                                            <p class="btn btn-warning btn-sm">pending</p>
                                        @else
                                            <p>Checked-In</p>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @empty
                                    <td>You have no any appointments</td>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
