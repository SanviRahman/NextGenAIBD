<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\HomepageHeroSetting;
use App\Models\HomepageSpotlightSetting;
use App\Models\HomepageSpotlightVideo;
use App\Models\HomepagePressMention;
use App\Models\HomepageHeroSlide;
use App\Models\Course;
use App\Models\CourseBundle;
use App\Models\Service;
use App\Models\HomepageServiceSetting;
use App\Models\HomepageFeaturedItem;
use App\Models\HomepageConsultationSetting;
use App\Models\HomepageRatingSetting;
use App\Models\HomepageRatingPlatform;
use App\Models\Project;
use App\Models\Team;
use App\Models\ClientReview;
use App\Support\TestimonialVideoUrl;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home_Index()
    {
        // $courses = Course::where('status', 1)->get();
        // $courses = $courses->shuffle();
        // $courses = $courses->take(4);

        // $courses_Latest = Course::where('status', 1)->orWhere('status', 2)->inRandomOrder()->latest('id')->limit(8)->get();
        // $courses_Beginner = Course::where('status', 1)->orWhere('status', 2)->where('skill_requirements', 'Beginner')->inRandomOrder()->latest('id')->limit(8)->get();
        // $courses_Intermediate = Course::where('status', 1)->orWhere('status', 2)->where('skill_requirements', 'Intermediate')->inRandomOrder()->latest('id')->limit(8)->get();
        // $courses_Advanced = Course::where('status', 1)->orWhere('status', 2)->where('skill_requirements', 'Advanced')->inRandomOrder()->latest('id')->limit(8)->get();
        // $courses_Expert = Course::where('status', 1)->orWhere('status', 2)->where('skill_requirements', 'Expert')->inRandomOrder()->latest('id')->limit(8)->get();

        // Keep the legacy About Us row distinct from homepage-only Benefits records.
        $about = About::query()->whereNull('homepage_role')->orderBy('id')->first();

        // Step-06: Reuse abouts table for dynamic benefits. No separate benefits table.
        $benefitsSettings = About::query()->where('homepage_role', 'benefits_settings')->first() ?? new About();
        $homepageBenefits = About::query()->where('homepage_role', 'benefit')
            ->where('status', '1')->orderBy('homepage_sort_order')->orderBy('id')->get();
        $banner = Banner::where('status', '1')->orderBy('id')->first() ?? Banner::first();
        $heroSettings = HomepageHeroSetting::query()->first() ?? new HomepageHeroSetting();
        $heroSlides = HomepageHeroSlide::query()
            ->where('is_active', true)
            ->with(['highlights' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
            }])
            ->orderBy('sort_order')->orderBy('id')->get();

        // Spotlight (independent of Hero): deterministic admin sort order.
        $spotlightSettings = HomepageSpotlightSetting::query()->first() ?? new HomepageSpotlightSetting();
        $spotlightVideo = HomepageSpotlightVideo::query()->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')->first();
        $spotlightMentions = HomepagePressMention::query()->where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')->get();

        // Step-05: only genuine verified public review profiles, ordered by admin.
        $ratingSettings = HomepageRatingSetting::query()->first() ?? new HomepageRatingSetting();
        $ratingPlatforms = HomepageRatingPlatform::query()
            ->where('is_active', true)->whereNotNull('verified_at')
            ->orderBy('sort_order')->orderBy('id')->get();

        // Step-07: client showcase rows reuse abouts; only authorized, active logos go public.
        $clientsSettings = About::query()->where('homepage_role', 'clients_settings')->first() ?? new About();
        $homepageClients = About::query()->where('homepage_role', 'client_logo')
            ->where('status', '1')->where('homepage_client_permission_confirmed', true)
            ->whereNotNull('image')->where('image', '!=', '')
            ->orderBy('homepage_sort_order')->orderBy('id')->get();
        $clientProjectLinks = Project::query()->where('status', '1')
            ->whereIn('id', $homepageClients->pluck('homepage_client_project_id')->filter())
            ->get(['id', 'slug'])->keyBy('id');

        // Step-08: reuse existing client_reviews for real, consent-confirmed video testimonials.
        $testimonialsSettings = About::query()->where('homepage_role', 'testimonials_settings')->first() ?? new About();
        $homepageTestimonials = ClientReview::query()->where('status', '1')
            ->where('homepage_permission_confirmed', true)
            ->whereNotNull('image')->where('image', '!=', '')
            ->whereNotNull('video_url')->where('video_url', '!=', '')
            ->orderBy('homepage_sort_order')->orderBy('id')->get()
            ->filter(fn ($review) => TestimonialVideoUrl::embed($review->video_url) !== null)
            ->values();

        // Step-09: existing abouts rows marked as gallery; never expose unapproved photos.
        $gallerySettings = About::query()->where('homepage_role', 'gallery_settings')->first() ?? new About();
        $homepageGallery = About::query()->where('homepage_role', 'gallery_item')
            ->where('status', '1')->where('homepage_gallery_permission_confirmed', true)
            ->whereNotNull('image')->where('image', '!=', '')
            ->orderBy('homepage_sort_order')->orderBy('id')->get()
            ->filter(fn ($photo) => (bool)preg_match('/^sda-gallery-[a-f0-9-]+\.(png|jpe?g|webp)$/iD', (string)$photo->image))
            ->values();

        // Step-04: public consultation section presentation settings.
        $consultationSettings = HomepageConsultationSetting::query()->first() ?? new HomepageConsultationSetting();

        // Step-03: Homepage Services are sorted intentionally; no random shuffle.
        $homepageServicesSettings = HomepageServiceSetting::query()->first() ?? new HomepageServiceSetting();
        $featuredIds = HomepageFeaturedItem::query()
            ->where('section_key', 'services')->where('item_type', 'service')
            ->where('is_active', true)->orderBy('sort_order')->orderBy('id')
            ->pluck('item_id')->map(fn ($id) => (int)$id);

        if ($featuredIds->isNotEmpty()) {
            $byId = Service::query()->where('status', '1')->whereIn('id', $featuredIds)->get()->keyBy('id');
            $homepageServices = $featuredIds->map(fn ($id) => $byId->get($id))->filter()->values();
        } else {
            $homepageServices = Service::query()->where('status', '1')->orderByDesc('id')->get();
        }
        $homepageServices = $homepageServices->take((int)($homepageServicesSettings->cards_limit ?: 9));

        // Step-10: Reuse the existing Blog model, status and backend CRUD.
        // Latest 3 published entries, deterministic order. Never show draft/pending posts.
        $latestBlogs = Blog::query()
            ->where('status', '1')
            ->whereNotNull('slug')->where('slug', '!=', '')
            ->orderByDesc('created_at')->orderByDesc('id')
            ->limit(3)->get();

        return view('frontend.home.index', compact(
            'about',
            'benefitsSettings',
            'homepageBenefits',
            'clientsSettings',
            'homepageClients',
            'clientProjectLinks',
            'testimonialsSettings',
            'homepageTestimonials',
            'gallerySettings',
            'homepageGallery',
            'banner',
            'heroSettings',
            'heroSlides',
            'spotlightSettings',
            'spotlightVideo',
            'spotlightMentions',
            'homepageServicesSettings',
            'homepageServices',
            'consultationSettings',
            'ratingSettings',
            'ratingPlatforms',
            'latestBlogs',
        ));
    }
}
