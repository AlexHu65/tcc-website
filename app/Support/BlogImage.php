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

        $ids = is_array($raw) ? $raw : [$raw];
        $id = collect($ids)->filter()->first();
        if (! $id) {
            return null;
        }

        $asset = Asset::find($id);

        return $asset && $asset->isImage() ? $asset : null;
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
