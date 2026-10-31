@php
    // Only records explicitly approved by the agency reach this partial.
    $project = $clientProjectLinks->get($client->homepage_client_project_id);
    $projectUrl = ($project && $project->slug) ? route('frontend_Project_Single', $project->slug) : null;
    $clientUrl = $projectUrl ?: $client->homepage_client_url;
    $alt = $client->homepage_image_alt ?: ($client->title . ' logo');
@endphp
<div class="sda-clients__tile" data-sda-clients-reveal style="--sda-client-index:{{ min((int)$logoIndex, 8) }}">
    @if($clientUrl)
        <a href="{{ $clientUrl }}" class="sda-clients__tile-link" aria-label="View {{ $client->title }}" @if(!$projectUrl) target="_blank" rel="noopener noreferrer" @endif>
    @else
        <div class="sda-clients__tile-link">
    @endif
            <img src="{{ \App\Support\WebsiteMedia::url('media/homepage/clients/' . basename((string) $client->image)) }}"
                 alt="{{ $alt }}" loading="lazy" decoding="async" width="250" height="120">
            <span class="sda-clients__tile-name">{{ $client->title }}</span>
    @if($clientUrl)
        </a>
    @else
        </div>
    @endif
</div>
