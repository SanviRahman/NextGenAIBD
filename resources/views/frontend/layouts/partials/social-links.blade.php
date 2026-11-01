@if (!empty($socialLinks))
    <ul class="{{ $listClass ?? 'footer-social' }}">
        @foreach ($socialLinks as $socialLink)
            <li>
                <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer"
                    aria-label="{{ $socialLink['label'] }}" title="{{ $socialLink['label'] }}">
                    <i class="{{ $socialLink['icon'] }}" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ $socialLink['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
@endif
