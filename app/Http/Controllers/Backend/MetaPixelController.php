<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MetaPixelIntegration;
use App\Services\MetaConversionsService;
use App\Support\MetaPixelScriptParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MetaPixelController extends Controller
{
    public function index()
    {
        return view('backend.marketing.meta-pixel.index', [
            'integrations' => MetaPixelIntegration::query()->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request, MetaPixelScriptParser $parser): RedirectResponse
    {
        $data = $request->validate([
            'integrations' => ['required', 'array', 'min:1', 'max:10'],
            'integrations.*.name' => ['required', 'string', 'max:120'],
            'integrations.*.pixel_script' => ['required', 'string', 'max:20000'],
            'integrations.*.access_token' => ['required', 'string', 'min:20', 'max:5000'],
            'integrations.*.test_event_code' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/D'],
            'integrations.*.is_active' => ['required', 'boolean'],
        ]);

        $prepared = [];
        $errors = [];
        $submittedIds = [];

        foreach ($data['integrations'] as $index => $row) {
            try {
                $pixelId = $parser->extractPixelId($row['pixel_script']);
            } catch (ValidationException $exception) {
                $errors["integrations.{$index}.pixel_script"] = $exception->errors()['pixel_script'][0] ?? 'Invalid Meta Pixel script.';
                continue;
            }

            if (in_array($pixelId, $submittedIds, true)) {
                $errors["integrations.{$index}.pixel_script"] = 'The same Pixel ID appears more than once in this form.';
                continue;
            }
            if (MetaPixelIntegration::query()->where('pixel_id', $pixelId)->exists()) {
                $errors["integrations.{$index}.pixel_script"] = 'This Pixel ID is already configured. Edit its existing integration instead.';
                continue;
            }

            $submittedIds[] = $pixelId;
            $prepared[] = [
                'name' => trim($row['name']),
                'pixel_id' => $pixelId,
                'pixel_script' => $row['pixel_script'],
                'access_token' => $row['access_token'],
                'test_event_code' => $row['test_event_code'] ?? null,
                'is_active' => (bool) $row['is_active'],
            ];
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        DB::transaction(function () use ($prepared): void {
            foreach ($prepared as $integration) {
                MetaPixelIntegration::query()->create($integration);
            }
        });

        return redirect()->route('admin.meta-pixel.index')
            ->with('meta_pixel_success', count($prepared) . ' Meta Pixel integration(s) saved securely.');
    }

    public function update(Request $request, MetaPixelIntegration $integration, MetaPixelScriptParser $parser): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'pixel_script' => ['required', 'string', 'max:20000'],
            'access_token' => ['nullable', 'string', 'min:20', 'max:5000'],
            'test_event_code' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_-]+$/D'],
            'is_active' => ['required', 'boolean'],
        ]);

        $pixelId = $parser->extractPixelId($data['pixel_script']);
        $duplicate = MetaPixelIntegration::query()
            ->where('pixel_id', $pixelId)
            ->where('id', '<>', $integration->getKey())
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'pixel_script' => 'Another integration already uses this Pixel ID.',
            ]);
        }

        $integration->name = trim($data['name']);
        $integration->pixel_id = $pixelId;
        $integration->pixel_script = $data['pixel_script'];
        $integration->test_event_code = $data['test_event_code'] ?? null;
        $integration->is_active = (bool) $data['is_active'];
        if (filled($data['access_token'] ?? null)) {
            $integration->access_token = $data['access_token'];
        }
        $integration->save();

        return redirect()->route('admin.meta-pixel.index')->with('meta_pixel_success', 'Meta Pixel integration updated.');
    }

    public function test(Request $request, MetaPixelIntegration $integration, MetaConversionsService $service): RedirectResponse
    {
        $result = $service->sendTestEvent($integration, $request);

        return redirect()->route('admin.meta-pixel.index')
            ->with($result['success'] ? 'meta_pixel_success' : 'meta_pixel_error', $result['message']);
    }

    public function destroy(MetaPixelIntegration $integration): RedirectResponse
    {
        $integration->delete();

        return redirect()->route('admin.meta-pixel.index')->with('meta_pixel_success', 'Meta Pixel integration deleted.');
    }
}
