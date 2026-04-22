<?php

use Illuminate\Database\Migrations\Migration;
use Statamic\Facades\Entry;
use Symfony\Component\Yaml\Yaml;

return new class extends Migration
{
    /**
     * El entry `home` en BD puede seguir apuntando a `pages/ilse` y sin `sections`
     * si el contenido solo se actualizo en home.md (driver Eloquent).
     */
    public function up(): void
    {
        $path = base_path('content/collections/pages/home.md');
        if (! is_readable($path)) {
            return;
        }

        $entry = Entry::find('home');
        if (! $entry) {
            return;
        }

        $raw = file_get_contents($path);
        if ($raw === false || ! preg_match('/^---\s*\r?\n(.*?)\r?\n---\s*/s', $raw, $m)) {
            return;
        }

        $parsed = Yaml::parse($m[1]);
        if (! is_array($parsed)) {
            return;
        }

        unset($parsed['id']);

        foreach ($parsed as $key => $value) {
            $entry->set($key, $value);
        }

        $entry->save();
    }

    public function down(): void
    {
        //
    }
};
