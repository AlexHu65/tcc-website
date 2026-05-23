<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CpSensitiveSubmissionController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (! $this->authorized()) {
            abort(403);
        }

        $submissions = DB::table('sensitive_form_submissions')
            ->orderByDesc('submitted_at')
            ->paginate(25);

        $submissions->getCollection()->transform(function ($row) {
            $payload = $this->decryptPayload($row->encrypted_payload);

            $row->nombre = $payload['nombre'] ?? null;
            $row->email = $payload['email'] ?? null;
            $row->telefono = $payload['telefono'] ?? null;
            $row->mensaje = $payload['mensaje'] ?? null;

            return $row;
        });

        return view('cp.sensitive-submissions.index', [
            'submissions' => $submissions,
        ]);
    }

    public function show(int $id): View|RedirectResponse
    {
        if (! $this->authorized()) {
            abort(403);
        }

        $submission = DB::table('sensitive_form_submissions')->where('id', $id)->firstOrFail();
        $payload = $this->decryptPayload($submission->encrypted_payload);

        return view('cp.sensitive-submissions.show', [
            'submission' => $submission,
            'payload' => $payload,
        ]);
    }

    private function decryptPayload(string $encrypted): array
    {
        try {
            $json = Crypt::decryptString($encrypted);
            $decoded = json_decode($json, true);

            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable) {
            return [];
        }
    }

    private function authorized(): bool
    {
        $user = auth()->user();

        return (bool) $user && (bool) ($user->super ?? false);
    }
}
