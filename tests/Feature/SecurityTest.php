<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityTest extends TestCase
{
    /**
     * Test se a aplicação injeta os headers de segurança (nosniff, frame-options, etc).
     */
    public function test_it_has_basic_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
    }

    /**
     * Test se Strict-Transport-Security não é enviado em requisições HTTP normais.
     */
    public function test_it_does_not_send_hsts_on_http_requests(): void
    {
        $response = $this->get('http://localhost/');
        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    /**
     * Test se Strict-Transport-Security é enviado em requisições HTTPS.
     */
    public function test_it_sends_hsts_on_https_requests(): void
    {
        $response = $this->get('https://localhost/');
        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
}
