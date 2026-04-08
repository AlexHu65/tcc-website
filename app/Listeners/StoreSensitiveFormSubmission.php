<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Statamic\Events\FormSubmitted;

class StoreSensitiveFormSubmission
{
    public function handle(FormSubmitted $event): void
    {
        $submission = $event->submission;
        $form = $submission->form();

        if (! $form || $form->handle() !== 'contacto') {
            return;
        }

        $data = $submission->data()->except(['website'])->all();
        $fingerprint = hash('sha256', mb_strtolower((string) ($data['email'] ?? '')).'||'.trim((string) ($data['mensaje'] ?? '')));

        // Prevent accidental duplicate inserts from rapid double submits.
        $acquired = Cache::add('contacto:dedupe:'.$fingerprint, true, now()->addSeconds(15));
        if (! $acquired) {
            return;
        }

        DB::table('sensitive_form_submissions')->insert([
            'form_handle' => $form->handle(),
            'submission_id' => (string) $submission->id(),
            'email_hash' => isset($data['email']) ? hash('sha256', mb_strtolower((string) $data['email'])) : null,
            'encrypted_payload' => Crypt::encryptString(json_encode($data, JSON_UNESCAPED_UNICODE)),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
