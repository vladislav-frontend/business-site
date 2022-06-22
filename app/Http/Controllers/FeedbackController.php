<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

class FeedbackController extends Controller
{
    public function send(FeedbackRequest $request)
    {
        $mail_data = [
            'feedback_theme'    => $request->input('feedback_theme'),
            'feedback_name'     => $request->input('feedback_name'),
            'feedback_email'    => $request->input('feedback_email'),
            'feedback_phone'    => $request->input('feedback_phone'),
            'feedback_message'  => $request->input('feedback_message')
        ];
        $to_email = env('MAIL_FROM_ADDRESS');

        Mail::to($to_email)->send(new FeedbackMail($mail_data));

        return redirect()->route('index');
    }
}
