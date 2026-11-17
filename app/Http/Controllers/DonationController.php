<?php

namespace App\Http\Controllers;

use App\Mail\DonationSubmitted;
use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::verified()->with('program')->latest('verified_at')->paginate(10);
        $total = Donation::verified()->sum('amount');
        $count = Donation::verified()->count();
        $programs = Program::published()->latest()->get();

        return view('donations.index', compact('donations', 'total', 'count', 'programs'));
    }

    public function store(Request $request)
    {
        if ($request->filled('website')) {
            return back()->with('success', __('site.donations.success'));
        }

        $data = $request->validate([
            'program_id' => ['nullable', 'integer', Rule::exists('programs', 'id')->where('is_published', true)],
            'donor_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'note' => ['nullable', 'string', 'max:1000'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $data['proof_path'] = $request->file('proof')->store('donations', 'local');
        $data['method'] = 'transfer';
        $data['status'] = 'pending';

        unset($data['proof']);

        $donation = Donation::create($data);

        try {
            if ($email = setting('email')) {
                Mail::to($email)->send(new DonationSubmitted($donation));
            }
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('success', __('site.donations.success'));
    }
}
