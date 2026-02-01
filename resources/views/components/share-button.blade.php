@php
    // Default values
    $title = $title ?? '';
    $url = $url ?? '';
    $contentType = $contentType ?? 'default';
    $size = $size ?? 'sm';
    $variant = $variant ?? 'outline-secondary';
    $showText = $showText ?? true;
    $customClass = $class ?? '';
    $icon = $iconClass ?? $icon ?? 'bi-share';
    $text = $text ?? 'Bagikan';
    $textClass = $textClass ?? '';
    
    // Determine data attributes based on content type
    $dataTitleAttr = '';
    $dataUrlAttr = '';
    
    switch($contentType) {
        case 'infographic':
            $dataTitleAttr = 'data-infographic-title';
            $dataUrlAttr = 'data-infographic-url';
            break;
        case 'publication':
            $dataTitleAttr = 'data-pub-title';
            $dataUrlAttr = 'data-pub-url';
            break;
        case 'news':
            $dataTitleAttr = 'data-news-title';
            $dataUrlAttr = 'data-news-url';
            break;
        default:
            $dataTitleAttr = 'data-share-title';
            $dataUrlAttr = 'data-share-url';
            break;
    }
    
    // Build class string
    $buttonClasses = "btn btn-{$size} btn-{$variant} share-btn";
    if ($customClass) {
        $buttonClasses .= " {$customClass}";
    }
    
    // Ensure URL is complete
    if ($url && strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
        $url = url($url);
    }
@endphp

<button 
    type="button" 
    class="{{ $buttonClasses }}"
    {{ $dataTitleAttr }}="{{ $title }}"
    {{ $dataUrlAttr }}="{{ $url }}"
    @if(isset($onclick)) onclick="{{ $onclick }}" @endif
    @if(isset($id)) id="{{ $id }}" @endif
    @if(isset($dataAttributes))
        @foreach($dataAttributes as $key => $value)
            data-{{ $key }}="{{ $value }}"
        @endforeach
    @endif
    title="Bagikan {{ $title ?: 'konten' }}"
    aria-label="Bagikan {{ $title ?: 'konten' }}"
>
    <i class="{{ $icon }}"></i>
    @if($showText)
        <span class="share-btn-text {{ $textClass }}">{{ $text }}</span>
    @endif
</button>

