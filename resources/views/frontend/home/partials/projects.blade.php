{{-- Homepage portfolio: all published Project records from the existing Projects manager. --}}
@if(isset($homepageProjects) && $homepageProjects->isNotEmpty())
    @php
        $projectCount = $homepageProjects->count();
        // Approx. 8 seconds per card keeps a steady, readable speed on desktop and mobile.
        $marqueeDuration = max(18, $projectCount * 8);
        // More visual copies are needed when the source list is short, so the viewport never runs empty.
        $duplicateCopies = $projectCount === 1 ? 5 : ($projectCount === 2 ? 2 : 1);
        $groupCount = $duplicateCopies + 1;
        $loopShift = number_format(100 / $groupCount, 4, '.', '');
    @endphp

    <section class="sda-home-projects" aria-labelledby="sda-home-projects-title">
        <div class="container sda-home-projects__container">
            <div class="sda-home-projects__header">
                <div class="sda-home-projects__heading">
                    <p class="sda-home-projects__eyebrow">SELECTED WORK</p>
                    <h2 id="sda-home-projects-title">Our Recent Projects</h2>
                    <p>Explore the digital experiences we've helped bring to life. Every project starts with listening and grows through thoughtful collaboration.</p>
                </div>
                <a class="sda-home-projects__all" href="{{ route('frontend_Project_Index') }}">
                    View All Projects <span aria-hidden="true">&nearr;</span>
                </a>
            </div>

            <div class="sda-home-projects__viewport" role="region" aria-label="All recent projects">
                <div class="sda-home-projects__track sda-home-projects__track--marquee"
                     style="--sda-projects-marquee-duration: {{ $marqueeDuration }}s; --sda-projects-loop-shift: -{{ $loopShift }}%;">
                    <div class="sda-home-projects__group sda-home-projects__group--primary">
                        @foreach($homepageProjects as $project)
                            @include('frontend.home.partials.project-card', ['project' => $project, 'isDuplicate' => false])
                        @endforeach
                    </div>

                    @for($copy = 0; $copy < $duplicateCopies; $copy++)
                        <div class="sda-home-projects__group sda-home-projects__group--duplicate"
                             aria-hidden="true" inert>
                            @foreach($homepageProjects as $project)
                                @include('frontend.home.partials.project-card', ['project' => $project, 'isDuplicate' => true])
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
@endif
