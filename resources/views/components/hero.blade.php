{{-- resources/views/components/hero.blade.php --}}

@php
    use Illuminate\Support\Facades\Storage;

    // Try to load uploaded hero
    $files = Storage::disk('public')->files('hero');
    $uploaded = $files[0] ?? null;
@endphp

@if($uploaded && Storage::disk('public')->exists($uploaded))
    @php
        $url = Storage::url($uploaded);
        $ext = strtolower(pathinfo($uploaded, PATHINFO_EXTENSION));
        $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
    @endphp

    {{-- Uploaded video --}}
    @if($isVideo)
        <video autoplay muted loop playsinline class="w-full h-auto">
            <source src="{{ $url }}" type="video/{{ $ext }}">
        </video>

    {{-- Uploaded image --}}
    @else
        <img src="{{ $url }}" class="w-full h-auto" alt="Hero">
    @endif

@else
    {{-- FALLBACK DEFAULT VIDEO --}}
    <video autoplay muted loop playsinline class="w-full h-auto">
        <source src="{{ asset('videos/hero_vid.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
@endif
