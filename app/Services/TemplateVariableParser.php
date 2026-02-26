<?php

namespace App\Services;

use App\Models\Station;

class TemplateVariableParser
{
    public static function replace(string $content, Station $station): string
    {
        $replacements = [
            '{agency_name}' => $station->agency_name ?? '',
            '{email}' => $station->email ?? '',
            '{accountable_manager}' => $station->accountable_manager ?? '',
            '{accountable_manager_email}' => $station->accountable_manager_email ?? '',
            '{accountable_manager_phone}' => $station->accountable_manager_phone ?? '',
            '{liaison}' => $station->liaison ?? '',
            '{liaison_email}' => $station->liaison_email ?? '',
            '{liaison_phone}' => $station->liaison_phone ?? '',
            '{cert_no}' => $station->cert_no ?? '',
            '{city}' => $station->city ?? '',
            '{country}' => $station->country ?? '',
            '{phone}' => $station->phone ?? '',
            '{slug}' => $station->slug ?? '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
