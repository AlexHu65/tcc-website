<?php

namespace App\Support;

use Statamic\Facades\Asset;

final class IlseHeroBlock
{
    /**
     * Resolve hero image URL from a Statamic assets field, legacy filename in public/images/ilse,
     * or asset ID without container prefix.
     *
     * @param  mixed  $raw  Value of hero_image (array of IDs, string ID, or legacy filename)
     */
    public static function imageUrl(mixed $raw, callable $legacyFallback): ?string
    {
        $ref = null;
        if (is_array($raw)) {
            $ref = $raw[0] ?? null;
        } elseif (is_string($raw)) {
            $ref = trim($raw);
        }

        if (! is_string($ref) || $ref === '') {
            return null;
        }

        $candidates = str_contains($ref, '::')
            ? [$ref]
            : [$ref, 'assets::'.$ref];

        foreach ($candidates as $candidate) {
            $asset = Asset::find($candidate);
            if ($asset && $asset->isImage()) {
                return $asset->url();
            }
        }

        if (preg_match('/\.[a-z0-9]{2,8}$/i', $ref)) {
            return $legacyFallback($ref);
        }

        return null;
    }
}
