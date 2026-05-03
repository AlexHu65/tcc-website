<?php

namespace App\Http\Controllers;

use App\Support\CuestionarioAboutBody;
use App\Support\CuestionarioHeroImage;
use Statamic\Facades\Entry;

class BienestarController extends Controller
{
    public function show()
    {
        $entry = Entry::find('bienestar');

        if (! $entry) {
            $entry = Entry::query()
                ->where('collection', 'pages')
                ->where('slug', 'bienestar')
                ->first();
        }

        if (! $entry || ! $entry->published()) {
            abort(404);
        }

        // Solo datos crudos: no pasar el Entry a la vista (evita blueprint() al inspeccionar variables).
        $page = $entry->data()->all();

        $finalUrl = $page['final_cta_url'] ?? null;
        $finalCtaUrl = is_string($finalUrl) && trim($finalUrl) !== ''
            ? trim($finalUrl)
            : (string) (($page['hero_primary_cta_url'] ?? null) ?: '#');

        return view('pages.cuestionario', [
            'page' => $page,
            'title' => $page['title'] ?? '',
            'hero_image_url' => CuestionarioHeroImage::heroUrl($entry),
            'about_body_html' => CuestionarioAboutBody::toHtml($entry->get('about_body')),
            'meta_title' => $page['meta_title'] ?? null,
            'meta_description' => $page['meta_description'] ?? null,
            'final_cta_url' => $finalCtaUrl,
        ]);
    }
}
