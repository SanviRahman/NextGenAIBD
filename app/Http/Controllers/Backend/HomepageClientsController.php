<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomepageClientsController extends Controller
{
    private const SETTINGS_ROLE = 'clients_settings';
    private const LOGO_ROLE = 'client_logo';

    public function index()
    {
        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $clients = About::query()->where('homepage_role', self::LOGO_ROLE)
            ->orderBy('homepage_sort_order')->orderBy('id')->get();
        $projects = Project::query()->where('status', '1')->orderBy('title')->get(['id', 'title', 'slug']);

        return view('backend.homepage.clients', compact('settings', 'clients', 'projects'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:800'],
            'homepage_bg_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'homepage_accent_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/D'],
            'status' => ['required', Rule::in(['0', '1'])],
        ]);

        $settings = About::query()->where('homepage_role', self::SETTINGS_ROLE)->first() ?? new About();
        $settings->homepage_role = self::SETTINGS_ROLE;
        $settings->title = $data['title'];
        $settings->description = $data['description'] ?? null;
        $settings->homepage_bg_color = $data['homepage_bg_color'];
        $settings->homepage_accent_color = $data['homepage_accent_color'];
        $settings->status = $data['status'];
        $settings->save();

        return back()->with('clients_success', 'Client Showcase settings saved.');
    }

    public function store(Request $request)
    {
        $data = $this->validatedLogoData($request, true);
        $logo = $this->uploadLogo($request->file('image'));

        try {
            About::query()->create([
                'homepage_role' => self::LOGO_ROLE,
                'title' => $data['title'],
                'image' => $logo,
                'homepage_image_alt' => $data['homepage_image_alt'] ?? null,
                'homepage_client_url' => $data['homepage_client_url'] ?? null,
                'homepage_client_project_id' => $data['homepage_client_project_id'] ?? null,
                'homepage_client_permission_confirmed' => true,
                'homepage_sort_order' => (int)$data['homepage_sort_order'],
                'status' => $data['status'],
            ]);
        } catch (\Throwable $exception) {
            $this->deleteOwnedLogo($logo);
            throw $exception;
        }

        return back()->with('clients_success', 'Approved client logo added.');
    }

    public function update(Request $request, About $client)
    {
        $this->ensureClient($client);
        $data = $this->validatedLogoData($request, false);
        $oldLogo = $client->image;
        $newLogo = $request->hasFile('image') ? $this->uploadLogo($request->file('image')) : null;

        try {
            $client->fill([
                'title' => $data['title'],
                'homepage_image_alt' => $data['homepage_image_alt'] ?? null,
                'homepage_client_url' => $data['homepage_client_url'] ?? null,
                'homepage_client_project_id' => $data['homepage_client_project_id'] ?? null,
                'homepage_client_permission_confirmed' => $request->boolean('homepage_client_permission_confirmed'),
                'homepage_sort_order' => (int)$data['homepage_sort_order'],
                'status' => $data['status'],
            ]);
            if ($newLogo !== null) {
                $client->image = $newLogo;
            }
            $client->save();
        } catch (\Throwable $exception) {
            $this->deleteOwnedLogo($newLogo);
            throw $exception;
        }
        if ($newLogo !== null) {
            $this->deleteOwnedLogo($oldLogo);
        }

        return back()->with('clients_success', 'Client logo updated.');
    }

    public function destroy(About $client)
    {
        $this->ensureClient($client);
        $oldLogo = $client->image;
        $client->delete();
        $this->deleteOwnedLogo($oldLogo);

        return back()->with('clients_success', 'Client logo deleted.');
    }

    private function validatedLogoData(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'image' => [$creating ? 'required' : 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'homepage_image_alt' => ['nullable', 'string', 'max:200'],
            'homepage_client_url' => ['nullable', 'url', 'max:2048', 'regex:/^https:\/\//i'],
            'homepage_client_project_id' => ['nullable', 'integer', Rule::exists('projects', 'id')->where('status', '1')],
            'homepage_client_permission_confirmed' => [$creating ? 'accepted' : 'sometimes', 'boolean'],
            'homepage_sort_order' => ['required', 'integer', 'between:0,100000'],
            'status' => ['required', Rule::in(['0', '1'])],
        ]);
        return $data;
    }

    private function ensureClient(About $client): void
    {
        abort_unless($client->homepage_role === self::LOGO_ROLE, 404);
    }

    private function uploadLogo(UploadedFile $upload): string
    {
        // Root media directory matches the live Hostinger public_html asset layout.
        $directory = base_path('media/homepage/clients');
        File::ensureDirectoryExists($directory);
        $filename = 'sda-client-' . Str::uuid() . '.' . $upload->extension();
        $upload->move($directory, $filename);
        return $filename;
    }

    private function deleteOwnedLogo(?string $filename): void
    {
        // Never delete the original About Us image or legacy project assets.
        if ($filename && preg_match('/^sda-client-[0-9a-f-]+\.(?:jpg|jpeg|png|webp)$/iD', $filename)) {
            File::delete(base_path('media/homepage/clients/' . $filename));
        }
    }
}
