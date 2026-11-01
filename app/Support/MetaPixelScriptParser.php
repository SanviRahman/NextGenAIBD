<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

class MetaPixelScriptParser
{
    /**
     * Accept only a pasted official Meta Pixel base-code snippet and extract
     * its single Pixel ID. The submitted script is never executed or echoed
     * into the public website; the frontend renders a canonical safe snippet.
     */
    public function extractPixelId(string $script): string
    {
        $script = trim($script);
        $lower = strtolower($script);

        if ($script === '' || strlen($script) > 20000) {
            throw ValidationException::withMessages([
                'pixel_script' => 'Paste the complete official Meta Pixel base code (maximum 20 KB).',
            ]);
        }

        if (!str_contains($lower, 'connect.facebook.net') || !str_contains($lower, 'fbevents.js') || !str_contains($lower, 'fbq')) {
            throw ValidationException::withMessages([
                'pixel_script' => 'This does not look like the official Meta Pixel base code. Include fbevents.js and the fbq init line.',
            ]);
        }

        preg_match_all(
            '/fbq\s*\(\s*[\'\"]init[\'\"]\s*,\s*[\'\"]([0-9]{5,25})[\'\"]\s*\)/i',
            $script,
            $matches
        );

        $pixelIds = array_values($matches[1] ?? []);

        if (count($pixelIds) !== 1) {
            throw ValidationException::withMessages([
                'pixel_script' => 'Each script field must contain exactly one fbq init Pixel ID. Use Add More for additional pixels.',
            ]);
        }

        return $pixelIds[0];
    }
}
