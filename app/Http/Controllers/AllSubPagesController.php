<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\User;
use Illuminate\Http\Request;

class AllSubPagesController extends Controller
{
    //about page
    public function about()
    {
        $doctors  = User::where('role_id', 1)->get();
        return view('about', compact('doctors'));
    }


    //Doctors page
    public function doctor()
    {
        // Set timezone
        date_default_timezone_set('Asia/Dhaka');
        // If there is set date, find the doctors
        if (request('date')) {
            $formatDate = date('m-d-Y', strtotime(request('date')));
            $doctors = Appointment::where('date', $formatDate)->get();
            $users  = User::where('role_id', 1)->get();
            return view('doctors', compact('doctors', 'formatDate', 'users'));
        }
        // Get the doctor info
        // Return all doctors avalable for today to the welcome page
        $doctors = Appointment::where('date', date('m-d-Y'))->get();
        $users  = User::where('role_id', 1)->get();
        return view('doctors', compact('doctors', 'users'));
    }

    // news page
    public function news()
    {
        return view('news');
    }

    // news details page
    public function newsDetails()
    {
        return view('news-details');
    }

    // Contact page
    public function contact()
    {
        return view('contact');
    }
}
