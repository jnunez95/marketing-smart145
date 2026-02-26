<?php

namespace App\Mail;

use App\Models\Station;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Visualbuilder\EmailTemplates\Facades\TokenHelper;
use Visualbuilder\EmailTemplates\Models\EmailTemplate;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class CampaignEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    use BuildGenericEmail;

    public string $template;

    public string $sendTo;

    public ?Station $station = null;

    public ?string $trackingPixelHtml = null;

    public function __construct(string $templateKey, string $recipientEmail, ?Station $station = null, ?string $trackingPixelHtml = null)
    {
        $this->template = $templateKey;
        $this->sendTo = $recipientEmail;
        $this->station = $station;
        $this->trackingPixelHtml = $trackingPixelHtml;
    }

    public function build()
    {
        $this->emailTemplate = EmailTemplate::findEmailByKey($this->template, App::currentLocale());

        if (! $this->emailTemplate) {
            Log::warning("Email template {$this->template} was not found.");

            return $this;
        }

        $data = [
            'content' => TokenHelper::replace($this->emailTemplate->content ?? '', $this),
            'preHeaderText' => TokenHelper::replace($this->emailTemplate->preheader ?? '', $this),
            'title' => TokenHelper::replace($this->emailTemplate->title ?? '', $this),
            'theme' => $this->emailTemplate->theme->colours,
            'logo' => $this->emailTemplate->logo,
            'tracking_pixel' => $this->trackingPixelHtml ?? '',
        ];

        if (is_array($this->emailTemplate->cc) && count($this->emailTemplate->cc)) {
            $this->cc($this->emailTemplate->cc);
        }
        if (is_array($this->emailTemplate->bcc) && count($this->emailTemplate->bcc)) {
            $this->bcc($this->emailTemplate->bcc);
        }

        return $this->from($this->emailTemplate->from['email'], $this->emailTemplate->from['name'])
            ->view($this->emailTemplate->view_path)
            ->subject(TokenHelper::replace($this->emailTemplate->subject, $this))
            ->to($this->sendTo)
            ->with(['data' => $data]);
    }
}
