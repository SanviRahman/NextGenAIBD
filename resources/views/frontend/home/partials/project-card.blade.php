{{-- Reusable card for the homepage project marquee. --}}
@php
    $projectImageName = basename(str_replace('\\', '/', (string) $project->image));
    $projectImagePath = $projectImageName !== '' ? 'media/project/' . $projectImageName : null;
    $projectImageUrl = $projectImagePath
        ? \App\Support\WebsiteMedia::url($projectImagePath)
        : asset(\App\Support\WebsiteMedia::PLACEHOLDER);
    $liveProjectUrl = trim((string) $project->video_url);
    $liveProjectParts = $liveProjectUrl !== '' ? parse_url($liveProjectUrl) : false;
    $hasLiveProjectUrl = filter_var($liveProjectUrl, FILTER_VALIDATE_URL) !== false
        && is_array($liveProjectParts)
        && in_array(strtolower((string) ($liveProjectParts['scheme'] ?? '')), ['http', 'https'], true)
        && !empty($liveProjectParts['host']);
@endphp

<article class="sda-home-projects__card">
    <a class="sda-home-projects__image-link" href="{{ route('frontend_Project_Single', $project->slug) }}" aria-label="View {{ $project->title }} project details" tabindex="{{ ($isDuplicate ?? false) ? '-1' : '0' }}">
        <img src="{{ $projectImageUrl }}"
             alt="{{ $project->title }} project preview"
             loading="lazy" decoding="async" width="720" height="405">
        <span class="sda-home-projects__image-action" aria-hidden="true">Explore project &nearr;</span>
    </a>

    <div class="sda-home-projects__content">
        @if(trim((string) $project->categories) !== '')
            <span class="sda-home-projects__category">{{ $project->categories }}</span>
        @endif
        <h3><a href="{{ route('frontend_Project_Single', $project->slug) }}" tabindex="{{ ($isDuplicate ?? false) ? '-1' : '0' }}">{{ $project->title }}</a></h3>
        @if(trim(strip_tags((string) $project->sort_description)) !== '')
            <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $project->sort_description), 155) }}</p>
        @else
            <p>Discover the goals, thinking, and digital solution behind this project.</p>
        @endif

        <div class="sda-home-projects__actions">
            <a class="sda-home-projects__details" href="{{ route('frontend_Project_Single', $project->slug) }}" tabindex="{{ ($isDuplicate ?? false) ? '-1' : '0' }}">
                Project Details <span aria-hidden="true">&rarr;</span>
            </a>
            @if($hasLiveProjectUrl)
                <a class="sda-home-projects__live" href="{{ $liveProjectUrl }}" target="_blank" rel="noopener noreferrer" tabindex="{{ ($isDuplicate ?? false) ? '-1' : '0' }}">
                    Live Website <span aria-hidden="true">&nearr;</span>
                </a>
            @endif
        </div>
    </div>
</article>
