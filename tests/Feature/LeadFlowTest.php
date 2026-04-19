<?php

namespace Tests\Feature;

use App\Mail\AdminNotificationMail;
use App\Mail\LeadConfirmationCustomer;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a lead can be submitted successfully.
     */
    public function test_lead_can_be_submitted_and_notifies_admin(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.submit'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'This is a test message from automated test.',
        ]);

        $response->assertRedirect(route('contact.success'));

        $this->assertDatabaseHas('leads', [
            'email' => 'test@example.com',
        ]);

        // Verifica se o e-mail de confirmação foi enviado ao cliente
        Mail::assertSent(LeadConfirmationCustomer::class);

        // Verifica se o admin foi notificado
        Mail::assertSent(AdminNotificationMail::class);
    }

    /**
     * Test if bot protection (honeypot) works.
     */
    public function test_bot_is_blocked_by_honeypot(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'Spam',
            'website_url' => 'http://spam.com', // Honeypot field
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('leads', ['email' => 'bot@example.com']);
    }
}
