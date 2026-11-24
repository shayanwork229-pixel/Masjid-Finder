<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_public_routes_load(): void
    {
        $this->get('/')->assertOk();
        $this->get('/masjids')->assertOk();
        $this->get('/map')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/timings')->assertOk();
    }

    public function test_timetable_pdf_export_returns_pdf_response(): void
    {
        $this->get('/pdf-timetable')->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
