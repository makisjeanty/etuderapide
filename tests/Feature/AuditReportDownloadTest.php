<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuditReportDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_unsigned_audit_report_url_is_rejected(): void
    {
        $lead = Lead::create([
            'name' => 'Lead Teste',
            'email' => 'lead@example.com',
            'message' => 'Diagnostico confidencial',
            'status' => 'new',
        ]);

        $this->get(route('audit.report.download', $lead))
            ->assertForbidden();
    }

    public function test_expired_signed_audit_report_url_is_rejected(): void
    {
        $lead = Lead::create([
            'name' => 'Lead Teste',
            'email' => 'lead@example.com',
            'message' => 'Diagnostico confidencial',
            'status' => 'new',
        ]);

        $url = URL::temporarySignedRoute(
            'audit.report.download',
            now()->subMinute(),
            ['lead' => $lead->id]
        );

        $this->get($url)->assertForbidden();
    }

    public function test_valid_signed_audit_report_url_downloads_the_pdf(): void
    {
        $lead = Lead::create([
            'name' => 'Lead Teste',
            'email' => 'lead@example.com',
            'message' => 'Diagnostico confidencial',
            'status' => 'new',
        ]);

        $url = URL::temporarySignedRoute(
            'audit.report.download',
            now()->addMinutes(5),
            ['lead' => $lead->id]
        );

        $this->get($url)
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
