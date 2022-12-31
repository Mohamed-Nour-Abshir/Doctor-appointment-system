<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Models\DoctorStatus;
use Illuminate\Support\Facades\Auth;

class PatientListController extends Controller
{
    public function index(Request $request)
    { // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        if ($request->date) {
            $date = $request->date;
            if (Auth()->user()->role_id === 2) {
                $bookings = Booking::latest()->where('date', $date)->get();
            } else
                $bookings = Booking::latest()->where('date', $date)->where('doctor_id', auth()->user()->id)->get();
            return view('admin.patientlist.index', compact('bookings', 'date'));
        };
        if (Auth()->user()->role_id === 2) {
            $bookings = Booking::latest()->where('date', date('m-d-Y'))->get();
        } else {
            $bookings = Booking::latest()->where('date', date('m-d-Y'))->where('doctor_id', auth()->user()->id)->get();
        }
        return view('admin.patientlist.index', compact('bookings'));
    }
    public function toggleStatus($id)
    {
        $booking = Booking::find($id);
        $booking->status = !$booking->status;
        $booking->save();
        return redirect()->back();
    }
    public function toggleDoctorStatusStarted($id)
    {
        $booking = DoctorStatus::find($id);
        $booking->doctor_status = 'started';
        $booking->save();
        return redirect()->back();
    }

    public function toggleDoctorStatusPused($id)
    {
        $booking = DoctorStatus::find($id);
        $booking->doctor_status = 'paused';
        $booking->save();
        return redirect()->back();
    }

    public function toggleDoctorStatusEnded($id)
    {
        $booking = DoctorStatus::find($id);
        $booking->doctor_status = 'closed';
        $booking->save();
        return redirect()->back();
    }

    public function allTimeAppointment()
    {
        if (Auth()->user()->role_id === 2) {
            $bookings = Booking::all()->paginate(20);
            $doctor_status = DoctorStatus::all();
        } else {
            $bookings = Booking::where('doctor_id', auth()->user()->id)->paginate(20);
            $doctor_status = DoctorStatus::all();
        }

        return view('admin.patientlist.all', compact('bookings', 'doctor_status'));
    }
}
