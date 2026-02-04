@props([
    'chartId' => '',
    'title' => '',
    'class' => ''
])

<button 
    type="button" 
    class="btn btn-sm btn-outline-primary share-chart-button {{ $class }}" 
    data-share
    data-share-chart="{{ $chartId }}" 
    data-share-title="{{ $title }}"
    title="Bagikan {{ $title }}"
    aria-label="Bagikan {{ $title }}"
>
    <i class="fas fa-share-alt"></i>
    <span>Bagikan</span>
</button>
