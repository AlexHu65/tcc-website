<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Statamic\Fields\Field;
use Statamic\Fieldtypes\Bard\Augmentor;

/**
 * Convierte bard about_body a HTML sin usar augmentedValue() sobre la entrada Statamic
 * (evita resolver blueprint cuando falta en Eloquent).
 */
final class CuestionarioAboutBody
{
    public static function toHtml(mixed $aboutBody): string
    {
        if ($aboutBody === null || $aboutBody === '') {
            return '';
        }

        if (is_string($aboutBody)) {
            return $aboutBody;
        }

        if (! is_array($aboutBody)) {
            return '';
        }

        try {
            $field = new Field('about_body', [
                'type' => 'bard',
                'buttons' => ['bold', 'italic', 'unorderedlist', 'orderedlist', 'anchor'],
                'save_html' => false,
            ]);

            $bard = $field->fieldtype();

            $html = (new Augmentor($bard))->augment($aboutBody, false);

            if ($html === null) {
                return '';
            }

            return is_string($html) ? $html : (string) $html;
        } catch (\Throwable) {
            $blocks = self::normalizeBardBlocksForFallback($aboutBody);

            return self::fallbackHtmlFromBlocks($blocks);
        }
    }

    /**
     * @param  array<string, mixed>  $aboutBody
     * @return array<int, mixed>
     */
    private static function normalizeBardBlocksForFallback(array $aboutBody): array
    {
        if (($aboutBody['type'] ?? '') === 'doc') {
            return array_values(Arr::wrap($aboutBody['content'] ?? []));
        }

        if (array_is_list($aboutBody)) {
            return $aboutBody;
        }

        return [$aboutBody];
    }

    /**
     * @param  array<int, mixed>  $blocks
     */
    private static function fallbackHtmlFromBlocks(array $blocks): string
    {
        $out = '';

        foreach ($blocks as $node) {
            if (! is_array($node)) {
                continue;
            }

            $type = $node['type'] ?? '';

            if ($type === 'paragraph') {
                $out .= '<p>'.self::inlineHtml($node['content'] ?? []).'</p>';

                continue;
            }

            if ($type === 'bullet_list' || $type === 'ordered_list') {
                $tag = $type === 'ordered_list' ? 'ol' : 'ul';
                $out .= '<'.$tag.'>';
                foreach ($node['content'] ?? [] as $item) {
                    if (! is_array($item) || ($item['type'] ?? '') !== 'list_item') {
                        continue;
                    }
                    $out .= '<li>'.self::listItemInnerHtml($item).'</li>';
                }
                $out .= '</'.$tag.'>';
            }
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $listItem
     */
    private static function listItemInnerHtml(array $listItem): string
    {
        $parts = '';

        foreach ($listItem['content'] ?? [] as $block) {
            if (! is_array($block)) {
                continue;
            }

            if (($block['type'] ?? '') === 'paragraph') {
                $parts .= self::inlineHtml($block['content'] ?? []);
            }
        }

        return $parts !== '' ? $parts : self::inlineHtml($listItem['content'] ?? []);
    }

    /**
     * @param  array<int, mixed>  $inlines
     */
    private static function inlineHtml(array $inlines): string
    {
        $html = '';

        foreach ($inlines as $inline) {
            if (! is_array($inline)) {
                continue;
            }

            if (($inline['type'] ?? '') === 'text') {
                $html .= e($inline['text'] ?? '');

                continue;
            }

            if (($inline['type'] ?? '') === 'hard_break') {
                $html .= '<br>';

                continue;
            }

            $html .= self::inlineHtml($inline['content'] ?? []);
        }

        return $html;
    }
}
