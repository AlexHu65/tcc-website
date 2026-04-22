<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeGalleryExampleSeeder extends Seeder
{
    private const SEED_KEY = 'example_gallery_svg_v1';

    public function run(): void
    {
        $homeEntry = DB::table('entries')
            ->where('collection', 'pages')
            ->where('site', 'default')
            ->where(function ($query): void {
                $query->where('uri', '/')
                    ->orWhere('slug', '/')
                    ->orWhere('slug', 'home');
            })
            ->orderByDesc('updated_at')
            ->first();

        if (! $homeEntry) {
            return;
        }

        $data = json_decode((string) $homeEntry->data, true);
        if (! is_array($data)) {
            $data = [];
        }

        $sections = $data['sections'] ?? [];
        if (! is_array($sections)) {
            $sections = [];
        }

        $foundSeededBlock = false;

        foreach ($sections as $index => $section) {
            $seedKey = $section['seed_key'] ?? ($section['_seed_key'] ?? null);

            if ($seedKey === self::SEED_KEY) {
                $sections[$index] = $this->normalizeSeededSection($section);
                $foundSeededBlock = true;
            }
        }

        if (! $foundSeededBlock) {
            $sections[] = $this->newSeededSection();
        }

        $data['sections'] = $sections;

        DB::table('entries')
            ->where('id', $homeEntry->id)
            ->update([
                'data' => json_encode($data, JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    public function rollback(): void
    {
        $homeEntry = DB::table('entries')
            ->where('collection', 'pages')
            ->where('site', 'default')
            ->where(function ($query): void {
                $query->where('uri', '/')
                    ->orWhere('slug', '/')
                    ->orWhere('slug', 'home');
            })
            ->orderByDesc('updated_at')
            ->first();

        if (! $homeEntry) {
            return;
        }

        $data = json_decode((string) $homeEntry->data, true);
        if (! is_array($data)) {
            return;
        }

        $sections = $data['sections'] ?? [];
        if (! is_array($sections)) {
            return;
        }

        $filteredSections = array_values(array_filter($sections, function ($section): bool {
            $seedKey = $section['seed_key'] ?? ($section['_seed_key'] ?? null);

            return $seedKey !== self::SEED_KEY;
        }));

        if ($filteredSections === $sections) {
            return;
        }

        $data['sections'] = $filteredSections;

        DB::table('entries')
            ->where('id', $homeEntry->id)
            ->update([
                'data' => json_encode($data, JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    private function buildSvgDataUri(string $background, string $accent, string $label): string
    {
        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');

        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='1200' height='900' viewBox='0 0 1200 900'>"
            ."<rect width='1200' height='900' fill='{$background}'/>"
            ."<circle cx='220' cy='220' r='140' fill='{$accent}' opacity='0.22'/>"
            ."<circle cx='980' cy='670' r='200' fill='{$accent}' opacity='0.18'/>"
            ."<path d='M0 650 C260 520 420 760 700 620 C900 520 1010 560 1200 500 L1200 900 L0 900 Z' fill='{$accent}' opacity='0.26'/>"
            ."<text x='80' y='820' fill='{$accent}' font-size='72' font-family='Arial, Helvetica, sans-serif' font-weight='700'>{$safeLabel}</text>"
            .'</svg>';

        return 'data:image/svg+xml;charset=UTF-8,'.rawurlencode($svg);
    }

    private function newSeededSection(): array
    {
        return [
            'id' => Str::random(8),
            'type' => 'gallery',
            'enabled' => true,
            'eyebrow' => 'Demo visual',
            'heading' => 'Galeria de ejemplo',
            'description' => 'Bloque inicial generado automaticamente para mostrar layouts con SVG.',
            'layout' => 'carousel',
            'columns_desktop' => 3,
            'seed_key' => self::SEED_KEY,
            'items' => [
                [
                    'id' => Str::random(8),
                    'title' => 'Respira profundo',
                    'alt_override' => 'Ilustracion abstracta azul con ondas',
                    'image' => ['assets::seed-gallery-1.svg'],
                ],
                [
                    'id' => Str::random(8),
                    'title' => 'Balance emocional',
                    'alt_override' => 'Ilustracion abstracta verde con circulos',
                    'image' => ['assets::seed-gallery-2.svg'],
                ],
                [
                    'id' => Str::random(8),
                    'title' => 'Confianza diaria',
                    'alt_override' => 'Ilustracion abstracta arena con arco',
                    'image' => ['assets::seed-gallery-3.svg'],
                ],
            ],
        ];
    }

    private function normalizeSeededSection(array $section): array
    {
        $template = $this->newSeededSection();

        // Keep editorial fields but enforce structural ones
        $normalized = $section;
        $normalized['enabled'] = true;
        $normalized['seed_key'] = self::SEED_KEY;
        unset($normalized['_seed_key']);

        if (empty($normalized['id'])) {
            $normalized['id'] = Str::random(8);
        }

        // Only replace items if they are clearly corrupted (wrong format or empty)
        $items = $normalized['items'] ?? [];
        if (! is_array($items) || empty($items)) {
            $normalized['items'] = $template['items'];

            return $normalized;
        }

        $normalized['items'] = array_map(function ($item, $index) use ($template): array {
            if (! is_array($item)) {
                return $template['items'][$index] ?? $template['items'][0];
            }

            if (empty($item['id'])) {
                $item['id'] = Str::random(8);
            }

            // Migrate image_url to native assets field
            if (isset($item['image_url']) && ! isset($item['image'])) {
                $item['image'] = $template['items'][$index]['image'] ?? $template['items'][0]['image'];
            }
            unset($item['image_url']);

            // Ensure image is array with canonical asset IDs
            if (isset($item['image'])) {
                if (is_string($item['image'])) {
                    $item['image'] = [$item['image']];
                }

                if (is_array($item['image'])) {
                    $item['image'] = array_values(array_map(function ($assetRef) {
                        if (! is_string($assetRef) || $assetRef === '') {
                            return $assetRef;
                        }

                        return str_contains($assetRef, '::') ? $assetRef : 'assets::'.$assetRef;
                    }, $item['image']));
                }
            }

            return $item;
        }, array_values($items), array_keys($items));

        return $normalized;
    }
}
