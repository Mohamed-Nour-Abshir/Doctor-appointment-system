@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Total Appointments: {{ $bookings->count() }}
                    </div>
                    <form action="{{ route('patients') }}" method="GET">

                        <div class="card-header">
                            Filter by Date: &nbsp;
                            <div class="row">
                                <div class="col-md-10 col-sm-6">
                                    <input type="text" class="form-control datetimepicker-input" id="datepicker"
                                        data-toggle="datetimepicker" data-target="#datepicker" name="date"
                                        placeholder=@isset($date) {{ $date }} @endisset>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                        <div class="btn-group d-flex justify-content-center mt-3" role="group" aria-label="Basic mixed styles example">
                            @foreach($doctor_status as $item)
                            @if ($item->doctor_status === 'waiting')
                                <a href="{{route('update.doctorstatusStarted', [$item->id])}}" class="btn btn-success" name="start">Start</a>
                            @elseif ($item->doctor_status === 'started')
                                 <a href="{{route('update.doctorstatusPaused', [$item->id])}}" class="btn btn-warning" name="pause">Pause</a>
                                 <a href="{{route('update.doctorstatusEnded', [$item->id])}}" class="btn btn-danger" name="end">End</a>
                            @elseif ($item->doctor_status === 'paused')
                                <a href="{{route('update.doctorstatusStarted', [$item->id])}}" class="btn btn-success" name="start">Start</a>
                                <a href="{{route('update.doctorstatusEnded', [$item->id])}}" class="btn btn-danger" name="end">End</a>
                            @else
                                <a href="{{route('update.doctorstatusStarted', [$item->id])}}" class="btn btn-success" name="start">Start</a>
                            @endif
                            @endforeach
                          </div>

                    <div class="card-body table-responsive-lg">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $key=>$booking)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><img src="profile/{{ $booking->user->image }}" width="80">
                                        </td>
                                        <td>{{ $booking->date }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->user->email }}</td>
                                        <td>{{ $booking->user->phone_number }}</td>
                                        <td>{{ $booking->user->gender }}</td>
                                        <td>{{ $booking->time }}</td>
                                        <td>{{ $booking->doctor->name }}</td>
                                        <td>
                                            @if ($booking->status == 0)
                                                <a href="{{ route('update.status', [$booking->id]) }}"><button
                                                        class="btn btn-warning">Pending</button></a>
                                            @else
                                                <a href="{{ route('update.status', [$booking->id]) }}"><button
                                                        class="btn btn-success">Checked-In</button></a>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <td>There is no appointment!</td>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
