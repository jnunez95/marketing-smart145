<?php

namespace Tests\Feature;

use App\Mail\CampaignEmail;
use App\Models\EmailTemplate;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Visualbuilder\EmailTemplates\Models\EmailTemplateTheme;

class EmailTemplateIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        EmailTemplateTheme::create([
            'name' => 'Test Theme',
            'colours' => [
                'header_bg_color' => '#1E88E5',
                'content_bg_color' => '#FFFFFB',
                'body_bg_color' => '#f4f4f4',
                'body_color' => '#333333',
                'footer_bg_color' => '#34495E',
                'footer_color' => '#FFFFFB',
                'callout_bg_color' => '#FFC107',
                'callout_color' => '#212121',
                'button_bg_color' => '#FFC107',
                'button_color' => '#2A2A11',
                'anchor_color' => '#1E88E5',
            ],
            'is_default' => true,
        ]);
    }

    public function test_campaign_email_replaces_station_tokens(): void
    {
        $template = EmailTemplate::factory()->campaign()->create([
            'key' => 'test-campaign-template',
            'language' => 'es',
            'content' => '<p>Hola ##station.agency_name##, tu certificado es ##station.cert_no##.</p>',
            'subject' => 'Asunto para ##station.agency_name##',
        ]);

        $station = Station::factory()->create([
            'agency_name' => 'Test Station',
            'cert_no' => 'CERT-123',
            'email' => 'station@test.com',
        ]);

        App::setLocale('es');

        $mailable = new CampaignEmail($template->key, $station->email, $station);
        $mailable->build();
        $html = $mailable->render();

        $this->assertStringContainsString('Test Station', $html);
        $this->assertStringContainsString('CERT-123', $html);
        $this->assertStringNotContainsString('##station.', $html);
    }

    public function test_campaign_email_uses_template_subject_with_tokens(): void
    {
        $template = EmailTemplate::factory()->campaign()->create([
            'key' => 'test-subject-template',
            'language' => 'es',
            'subject' => 'Hola ##station.agency_name##',
            'content' => '<p>Contenido</p>',
        ]);

        $station = Station::factory()->create([
            'agency_name' => 'Mi Station',
            'email' => 'test@example.com',
        ]);

        App::setLocale('es');

        $mailable = new CampaignEmail($template->key, $station->email, $station);
        $mailable->build();

        $this->assertEquals('Hola Mi Station', $mailable->subject);
    }
}
