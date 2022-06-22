<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderServiceRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderServiceMail;

class OrderServiceController extends Controller
{
    public function send(OrderServiceRequest $request)
    {
        $mail_data = [
            'feedback_service'  => $request->input('feedback_service'),
            'feedback_email'    => $request->input('feedback_email'),
            'feedback_message'  => $request->input('feedback_message')
        ];
        $to_email = env('MAIL_FROM_ADDRESS');

        Mail::to($to_email)->send(new OrderServiceMail($mail_data));

        return redirect()->route('index');
    }
}
