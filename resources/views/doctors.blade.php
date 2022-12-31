@extends('layouts.base')
@section('content')

<div class="page-banner overlay-dark bg-image" style="background-image: url(../assets/img/bg_image_1.jpg);">
    <div class="banner-section">
      <div class="container text-center wow fadeInUp">
        <nav aria-label="Breadcrumb">
          <ol class="breadcrumb breadcrumb-dark bg-transparent justify-content-center py-0 mb-2">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Doctors</li>
          </ol>
        </nav>
        <h1 class="font-weight-normal">Our Doctors</h1>
      </div> <!-- .container -->
    </div> <!-- .banner-section -->
  </div> <!-- .page-banner -->

  <div class="page-section bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">

          <div class="row">

            @foreach ($users as $doctor)
            <div class="col-md-3 col-lg-3 wow zoomIn">
                <div class="card-doctor">
                  <div class="header">
                    <img src="{{ asset('images') }}/{{ $doctor->image }}" alt="">
                    <div class="meta">
                      <a href="#"><span class="mai-call"></span></a>
                      <a href="#"><span class="mai-logo-whatsapp"></span></a>
                    </div>
                  </div>
                  <div class="body">
                    <p class="text-xl mb-0">{{$doctor->name}}</p>
                    <span class="text-sm text-grey">{{$doctor->department}}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

        </div>
      </div>
    </div> <!-- .container -->
  </div> <!-- .page-section -->

  <div class="page-section">
    <div class="container">
      <h1 class="text-center wow fadeInUp">Make an Appointment</h1>

         {{-- Input --}}
    <form action="{{ url('/doctors') }}" method="GET">
        <div class="card">
            <div class="card-body">
                <div class="card-header">Find Doctors</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-8">
                            <input type="date" name='date' id="datepicker" class='form-control'>
                        </div>
                        <div class="col-md-6 col-sm-4">
                            <button class="btn btn-primary" type="submit">Go</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Display doctors --}}
    <div class="card">
        <div class="card-body">
            <div class="card-header">List of Doctors Available: @isset($formatDate) {{ $formatDate }}
                @endisset
            </div>
            <div class="card-body table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Expertise</th>
                            <th>Book</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doctors as $key=>$doctor)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><img src="{{ asset('images') }}/{{ $doctor->doctor->image }}" alt="doctor photo"
                                        width="100px"></td>
                                <td>{{ $doctor->doctor->name }}</td>
                                <td>{{ $doctor->doctor->department }}</td>
                                @if (Auth::check() && auth()->user()->role->name == 'patient')
                                    <td>
                                        <a href="{{ route('create.appointment', [$doctor->user_id, $doctor->date]) }}"><button
                                                class="btn btn-primary">Appointment</button></a>
                                    </td>
                                @else
                                    <td>For patients ONLY</td>
                                @endif
                            </tr>
                        @empty
                            <td>No doctors available</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    </div> <!-- .container -->
  </div> <!-- .page-section -->
@endsection
