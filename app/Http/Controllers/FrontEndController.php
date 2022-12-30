<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\Time;
use App\User;
use App\Booking;
use App\Prescription;
use App\Mail\AppointmentMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class FrontEndController extends Controller
{
    public function index(Request $request)
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // If there is set date, find the doctors
        if (request('search')) {
            $searchDocotor = request('search');
            $doctors = User::where('name', $searchDocotor)->orwhere('department', $searchDocotor)->get();
            $users  = User::where('role_id', 1)->get();
            return view('welcome', compact('doctors', 'searchDocotor', 'users'));
        }
        // Get the doctor info
        // Return all doctors avalable for today to the welcome page
        $doctors = User::where('name')->get();
        $users  = User::where('role_id', 1)->get();
        return view('welcome', compact('doctors', 'users'));
    }

    public function show($doctorId, $date)
    {
        $appointment = Appointment::where('user_id', $doctorId)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $user = User::where('id', $doctorId)->first();
        $doctor_id = $doctorId;
        return view('appointment', compact('times', 'date', 'user', 'doctor_id'));
    }

    public function store(Request $request)
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');

        $request->validate(['time' => 'required']);
        $check = $this->checkBookingTimeInterval();
        if ($check) {
            return redirect()->back()->with('errMessage', 'You already made an appointment. Please check your email for the appointment!');
        }

        $doctorId = $request->doctorId;
        $time = $request->time;
        $appointmentId = $request->appointmentId;
        $date = Carbon::today()->toDateString();
        Booking::create([
            'user_id' => auth()->user()->id,
            'doctor_id' => $doctorId,
            'time' => $time,
            'date' => $date,
            'status' => 0
        ]);
        $doctor = User::where('id', $doctorId)->first();
        Time::where('appointment_id', $appointmentId)->where('time', $time)->update(['status' => 1]);

        // Send email notification
        $mailData = [
            'name' => auth()->user()->name,
            'time' => $time,
            'date' => $date,
            'doctorName' => $doctor->name
        ];
        try {
            Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));
        } catch (\Exception $e) {
        }

        return redirect()->back()->with('message', 'Your appointment was booked for ' . $date . ' at ' . $time . ' with ' . $doctor->name . '.');
    }

    // check if user already make a booking.
    public function checkBookingTimeInterval()
    {
        return Booking::orderby('id', 'desc')
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', date('y-m-d'))
            ->exists();
    }

    public function myBookings()
    {
        $appointments = Booking::all();
        return view('booking.index', compact('appointments'));
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id', auth()->user()->id)->get();
        return view('my-prescription', compact('prescriptions'));
    }
}
