{{-- Homepage portfolio: reuses published Project records from the existing Projects manager. --}}
@if(isset($homepageProjects) && $homepageProjects->isNotEmpty())
    <section class="sda-home-projects" aria-labelledby="sda-home-projects-title">
        <div class="container sda-home-projects__container">
            <div class="sda-home-projects__header">
                <div class="sda-home-projects__heading">
                    <p class="sda-home-projects__eyebrow">SELECTED WORK</p>
                    <h2 id="sda-home-projects-title">Our Recent Projects</h2>
                    <p>Explore a few of the digital experiences we've helped bring to life. Every project starts with listening and grows through thoughtful collaboration.</p>
                </div>
                <a class="sda-home-projects__all" href="{{ route('frontend_Project_Index') }}">
                    View All Projects <span aria-hidden="true">↗</span>
                </a>
            </div>

            <div class="sda-home-projects__grid">
                @foreach($homepageProjects as $project)
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
                        <a class="sda-home-projects__image-link" href="{{ route('frontend_Project_Single', $project->slug) }}" aria-label="View {{ $project->title }} project details">
                            <img src="{{ $projectImageUrl }}"
                                 alt="{{ $project->title }} project preview"
                                 loading="lazy" decoding="async" width="720" height="405">
                            <span class="sda-home-projects__image-action" aria-hidden="true">Explore project ↗</span>
                        </a>

                        <div class="sda-home-projects__content">
                            @if(trim((string) $project->categories) !== '')
                                <span class="sda-home-projects__category">{{ $project->categories }}</span>
                            @endif
                            <h3><a href="{{ route('frontend_Project_Single', $project->slug) }}">{{ $project->title }}</a></h3>
                            @if(trim(strip_tags((string) $project->sort_description)) !== '')
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $project->sort_description), 155) }}</p>
                            @else
                                <p>Discover the goals, thinking, and digital solution behind this project.</p>
                            @endif

                            <div class="sda-home-projects__actions">
                                <a class="sda-home-projects__details" href="{{ route('frontend_Project_Single', $project->slug) }}">
                                    Project Details <span aria-hidden="true">→</span>
                                </a>
                                @if($hasLiveProjectUrl)
                                    <a class="sda-home-projects__live" href="{{ $liveProjectUrl }}" target="_blank" rel="noopener noreferrer">
                                        Live Website <span aria-hidden="true">↗</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
