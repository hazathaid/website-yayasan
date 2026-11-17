<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmitted;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('success', __('site.contact.success'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $contact = Contact::create($data + ['is_read' => false]);

        try {
            if ($email = setting('email')) {
                Mail::to($email)->send(new ContactSubmitted($contact));
            }
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('success', __('site.contact.success'));
    }
}
