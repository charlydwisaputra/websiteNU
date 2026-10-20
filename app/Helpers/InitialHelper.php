<?php

namespace App\Helpers;

class InitialHelper
{
    /**
     * Get 2-letter initials from name, ignoring common titles/honorifics.
     */
    public static function getInitials(?string $name): string
    {
        if (! $name || trim($name) === '') {
            return 'NU';
        }

        $honorifics = [
            'h', 'hj', 'kh', 'k.h', 'k.h.', 'k', 'kyai', 'kiai',
            'ustadz', 'ust', 'ustd', 'ustazah', 'ustadzah',
            'dr', 'dra', 'drs', 'prof', 'ir', 'gus', 'lora', 'ning', 'nyai',
            'habib', 'sayyid', 'raden', 'teuku', 'sutan', 'lr', 'tb', 'ct',
        ];

        $words = preg_split('/\s+/', trim($name));
        $filtered = [];

        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word, ".,:;-()[]/'\""));
            if (! in_array($cleanWord, $honorifics, true)) {
                $filtered[] = $word;
            }
        }

        if (empty($filtered)) {
            $filtered = $words;
        }

        if (count($filtered) === 1) {
            $clean = preg_replace('/[^\p{L}\p{N}]/u', '', $filtered[0]);
            $initials = mb_substr($clean, 0, 2);
        } else {
            $initials = '';
            foreach (array_slice($filtered, 0, 2) as $w) {
                $char = mb_substr(preg_replace('/[^\p{L}\p{N}]/u', '', $w), 0, 1);
                if ($char !== '') {
                    $initials .= $char;
                }
            }
        }

        return mb_strtoupper($initials) ?: mb_strtoupper(mb_substr($name, 0, 2));
    }
}
