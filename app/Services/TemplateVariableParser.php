<?php

namespace App\Services;

use App\Models\Agency;

class TemplateVariableParser
{
    public static function replace(string $content, Agency $agency): string
    {
        $replacements = [
            '{agency_name}' => $agency->agency_name ?? '',
            '{email}' => $agency->email ?? '',
            '{accountable_manager}' => $agency->accountable_manager ?? '',
            '{accountable_manager_email}' => $agency->accountable_manager_email ?? '',
            '{accountable_manager_phone}' => $agency->accountable_manager_phone ?? '',
            '{liaison}' => $agency->liaison ?? '',
            '{liaison_email}' => $agency->liaison_email ?? '',
            '{liaison_phone}' => $agency->liaison_phone ?? '',
            '{cert_no}' => $agency->cert_no ?? '',
            '{city}' => $agency->city ?? '',
            '{country}' => $agency->country ?? '',
            '{phone}' => $agency->phone ?? '',
            '{slug}' => $agency->slug ?? '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
