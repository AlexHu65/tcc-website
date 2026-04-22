<?php

namespace App\Support;

use Statamic\Contracts\Assets\Asset as AssetContract;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Asset;

final class BlogImage
{
    public static function featuredAsset(?EntryContract $entry): ?AssetContract
    {
        if (! $entry) {
            return null;
        }

        $raw = $entry->get('featured_image');
        if (empty($raw)) {
            return null;
        }

        $ids = collect(is_array($raw) ? $raw : [$raw])
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->values();

        foreach ($ids as $id) {
            $asset = self::findAssetByLegacyOrCurrentId($id);

            if ($asset && $asset->isImage()) {
                return $asset;
            }
        }

        return null;
    }

    private static function findAssetByLegacyOrCurrentId(string $id): ?AssetContract
    {
        $id = trim($id);
        if ($id === '') {
            return null;
        }

        $candidates = str_contains($id, '::')
            ? [$id]
            : [$id, 'assets::'.$id];

        foreach ($candidates as $candidate) {
            $asset = Asset::find($candidate);

            if ($asset) {
                return $asset;
            }
        }

        return null;
    }

    public static function cardUrl(?EntryContract $entry): ?string
    {
        $asset = self::featuredAsset($entry);

        return $asset
            ? $asset->manipulate(['w' => 720, 'h' => 450, 'fit' => 'crop_focal', 'q' => 82])
            : null;
    }

    public static function heroUrl(?EntryContract $entry): ?string
    {
        $asset = self::featuredAsset($entry);

        return $asset
            ? $asset->manipulate(['w' => 1400, 'h' => 560, 'fit' => 'crop_focal', 'q' => 85])
            : null;
    }
}
