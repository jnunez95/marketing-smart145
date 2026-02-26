<?php

namespace App\Http\Controllers;

use App\Models\CampaignLog;
use App\Models\EmailEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PostmarkWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->all();
        $recordType = $payload['RecordType'] ?? null;

        if (!$recordType) {
            return response()->json(['message' => 'Missing RecordType'], 422);
        }

        $supported = ['Delivery', 'Bounce', 'Open', 'Click', 'SpamComplaint'];
        if (!in_array($recordType, $supported, true)) {
            return response()->json(['message' => 'Unsupported RecordType'], 422);
        }

        $data = $this->extractEventData($recordType, $payload);
        $data['raw_payload'] = $payload;

        $event = EmailEvent::create($data);

        $this->updateCampaignLogAndCampaign($recordType, $payload, $event);

        return response()->json(['message' => 'OK'], 200);
    }

    private function extractEventData(string $recordType, array $payload): array
    {
        $recipient = $payload['Recipient'] ?? $payload['Email'] ?? '';
        $messageId = $payload['MessageID'] ?? '';
        $tag = $payload['Tag'] ?? null;
        $metadata = $payload['Metadata'] ?? null;

        $eventAt = match ($recordType) {
            'Delivery' => isset($payload['DeliveredAt']) ? Carbon::parse($payload['DeliveredAt']) : now(),
            'Bounce', 'SpamComplaint' => isset($payload['BouncedAt']) ? Carbon::parse($payload['BouncedAt']) : now(),
            'Open', 'Click' => isset($payload['ReceivedAt']) ? Carbon::parse($payload['ReceivedAt']) : now(),
            default => now(),
        };

        $data = [
            'message_id'   => $messageId,
            'record_type'  => $recordType,
            'recipient'    => $recipient,
            'tag'          => $tag,
            'event_at'     => $eventAt,
            'metadata'     => $metadata,
        ];

        if (in_array($recordType, ['Bounce', 'SpamComplaint'], true)) {
            $data['bounce_type'] = $payload['Type'] ?? null;
            $data['bounce_type_code'] = $payload['TypeCode'] ?? null;
        }

        if (in_array($recordType, ['Open', 'Click'], true)) {
            $data['platform'] = $payload['Platform'] ?? null;
            $data['user_agent'] = $payload['UserAgent'] ?? null;
            $data['os_name'] = $payload['OS']['Name'] ?? null;
            $data['client_name'] = $payload['Client']['Name'] ?? null;

            $geo = $payload['Geo'] ?? null;
            if ($geo) {
                $data['geo_ip'] = $geo['IP'] ?? null;
                $data['geo_city'] = $geo['City'] ?? null;
                $data['geo_country'] = $geo['Country'] ?? null;
                $data['geo_country_code'] = $geo['CountryISOCode'] ?? null;
                $data['geo_region'] = $geo['Region'] ?? null;
            }
        }

        if ($recordType === 'Click') {
            $data['original_link'] = $payload['OriginalLink'] ?? null;
        }

        return $data;
    }

    private function updateCampaignLogAndCampaign(string $recordType, array $payload, EmailEvent $event): void
    {
        $tag = $payload['Tag'] ?? null;
        $recipient = $payload['Recipient'] ?? $payload['Email'] ?? null;

        if (!$tag || !$recipient) {
            return;
        }

        $campaignLog = CampaignLog::where('tracking_token', $tag)
            ->where('email', $recipient)
            ->first();

        if (!$campaignLog) {
            return;
        }

        $event->update(['campaign_log_id' => $campaignLog->id]);

        $campaign = $campaignLog->campaign;
        if (!$campaign) {
            return;
        }

        $newStatus = null;
        $incrementColumn = null;

        switch ($recordType) {
            case 'Delivery':
                $newStatus = CampaignLog::STATUS_SENT;
                break;
            case 'Bounce':
                $newStatus = CampaignLog::STATUS_BOUNCED;
                $incrementColumn = 'total_bounced';
                break;
            case 'SpamComplaint':
                $newStatus = CampaignLog::STATUS_SPAM_COMPLAINT;
                $incrementColumn = 'total_spam_complaints';
                break;
            case 'Open':
                $newStatus = CampaignLog::STATUS_OPENED;
                $openedAt = isset($payload['ReceivedAt']) ? Carbon::parse($payload['ReceivedAt']) : now();
                if (!$campaignLog->opened_at) {
                    $campaignLog->update(['opened_at' => $openedAt, 'status' => CampaignLog::STATUS_OPENED]);
                    $campaign->increment('total_opened');
                }

                return;
            case 'Click':
                $newStatus = CampaignLog::STATUS_CLICKED;
                $clickedAt = isset($payload['ReceivedAt']) ? Carbon::parse($payload['ReceivedAt']) : now();
                if (!$campaignLog->clicked_at) {
                    $campaignLog->update(['clicked_at' => $clickedAt, 'status' => CampaignLog::STATUS_CLICKED]);
                    $campaign->increment('total_clicked');
                }

                return;
        }

        if ($newStatus) {
            $campaignLog->update(['status' => $newStatus]);
        }
        if ($incrementColumn) {
            $campaign->increment($incrementColumn);
        }
    }
}
