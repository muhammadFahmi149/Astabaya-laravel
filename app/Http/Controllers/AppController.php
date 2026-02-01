<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Handle contact form submission.
     */
    public function contactUs(ContactRequest $request)
    {
        // Validation is automatically handled by ContactRequest
        
        try {
            // Get validated data
            $validated = $request->validated();
            
            // TODO: Implement email sending or save to database
            // For now, just log the contact form submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => substr($validated['message'], 0, 100) . '...',
            ]);
            
            // You can send email here using Laravel Mail
            // Mail::to(config('mail.admin_email'))->send(new ContactMail($validated));
            
            return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
            
        } catch (\Exception $e) {
            Log::error('Error processing contact form: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi.')
                ->withInput();
        }
    }
}
