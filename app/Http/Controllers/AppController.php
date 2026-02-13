<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
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
            
            // Get admin email from config or use default
            $adminEmail = env('MAIL_ADMIN_EMAIL', config('mail.from.address', 'bps3578@bps.go.id'));
            
            // Send email to admin
            Mail::to($adminEmail)->send(new ContactMail(
                name: $validated['name'],
                surname: $validated['surname'] ?? null,
                email: $validated['email'],
                message: $validated['message']
            ));
            
            // Log the contact form submission
            Log::info('Contact form submitted and email sent', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'admin_email' => $adminEmail,
            ]);
            
            return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
            
        } catch (\Exception $e) {
            Log::error('Error processing contact form: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return redirect()->back()
                ->with('error', 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi.')
                ->withInput();
        }
    }
}
