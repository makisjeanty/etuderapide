<?php

namespace Tests\Feature\Public;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_public_home_returns_ok(): void
    {
        config()->set('services.whatsapp.admin_phone', '5511999999999');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('https://wa.me/5511999999999', false);

        $this->assertSame(
            1,
            substr_count($response->getContent(), 'O que dizem nossos clientes')
        );
    }
}
