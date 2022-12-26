<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
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

    public function allTimeAppointment()
    {
        if (Auth()->user()->role_id === 2) {
            $bookings = Booking::latest()->paginate(20);
        } else {
            $bookings = Booking::latest()->where('doctor_id', auth()->user()->id)->paginate(20);
        }

        return view('admin.patientlist.all', compact('bookings'));
    }
}
