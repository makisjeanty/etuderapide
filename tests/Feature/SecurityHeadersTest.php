<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /**
     * Test if security headers are present in the response.
     */
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Verifica se a CSP está presente
        $this->assertTrue($response->headers->has('Content-Security-Policy'));

        // Em ambiente de teste/local o HSTS pode não estar ativo se não for HTTPS,
        // mas o middleware deve injetar se as condições forem atendidas.
    }
}
