@props([
    'chartId' => '',
    'title' => '',
    'class' => ''
])

<button 
    type="button" 
    class="btn btn-sm btn-outline-primary share-chart-button copy-url-btn {{ $class }}" 
    data-share-chart="{{ $chartId }}" 
    data-share-title="{{ $title }}"
    title="Salin link {{ $title }}"
    aria-label="Salin link {{ $title }}"
    onclick="(function(btn) {
        const url = window.location.href;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class=\'fas fa-check\'></i> <span>Tersalin!</span>';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin:', err);
                alert('Gagal menyalin link');
            });
        } else {
            const input = document.createElement('input');
            input.value = url;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class=\'fas fa-check\'></i> <span>Tersalin!</span>';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 2000);
            } catch (err) {
                alert('Gagal menyalin link');
            }
            document.body.removeChild(input);
        }
    })(this)"
>
    <i class="fas fa-share-alt"></i>
    <span>Bagikan</span>
</button>
