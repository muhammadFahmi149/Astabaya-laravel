/**
 * Astabaya Share Utility
 * Utility untuk share visualisasi data
 * Mendukung berbagai platform dan responsif untuk semua device
 */

(function() {
    'use strict';

    // Share utility object
    window.AstabayaShare = {
        /**
         * Share chart atau visualisasi
         * @param {Object} options - Configuration options
         * @param {string} options.title - Title untuk share
         * @param {string} options.url - URL untuk share (optional, default: current URL)
         * @param {string} options.text - Deskripsi untuk share
         * @param {string} options.chartId - ID dari chart element (untuk screenshot)
         * @param {string} options.imageUrl - Direct image URL (optional)
         */
        share: function(options) {
            const defaults = {
                title: document.title,
                url: window.location.href,
                text: 'Lihat visualisasi data dari Aastabaya',
                chartId: null,
                imageUrl: null
            };

            const config = Object.assign({}, defaults, options);

            // Check if Web Share API is available (untuk mobile)
            if (navigator.share) {
                this.webShare(config);
            } else {
                // Fallback: show modal dengan berbagai opsi share
                this.showShareModal(config);
            }
        },

        /**
         * Web Share API (native share untuk mobile)
         */
        webShare: function(config) {
            const shareData = {
                title: config.title,
                text: config.text,
                url: config.url
            };

            navigator.share(shareData)
                .then(() => console.log('Share berhasil'))
                .catch((error) => {
                    console.log('Share dibatalkan atau error:', error);
                    // Fallback ke modal jika native share gagal
                    this.showShareModal(config);
                });
        },

        /**
         * Show share modal (fallback untuk desktop)
         */
        showShareModal: function(config) {
            const modal = this.createShareModal(config);
            document.body.appendChild(modal);
            
            // Show modal dengan animasi
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Close modal ketika klik backdrop
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeShareModal(modal);
                }
            });
        },

        /**
         * Create share modal element
         */
        createShareModal: function(config) {
            const modal = document.createElement('div');
            modal.className = 'astabaya-share-modal';
            modal.innerHTML = `
                <div class="astabaya-share-content">
                    <div class="astabaya-share-header">
                        <h3>Bagikan Visualisasi</h3>
                        <button class="astabaya-share-close" aria-label="Tutup">&times;</button>
                    </div>
                    <div class="astabaya-share-body">
                        <p class="astabaya-share-title">${config.title}</p>
                        <div class="astabaya-share-buttons">
                            ${this.generateShareButtons(config)}
                        </div>
                        <div class="astabaya-share-link">
                            <input type="text" readonly value="${config.url}" id="astabaya-share-url" class="astabaya-share-input">
                            <button class="astabaya-share-copy-btn" data-url="${config.url}">
                                <i class="bi bi-clipboard"></i> Salin Link
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Event listeners
            const closeBtn = modal.querySelector('.astabaya-share-close');
            closeBtn.addEventListener('click', () => this.closeShareModal(modal));

            const copyBtn = modal.querySelector('.astabaya-share-copy-btn');
            copyBtn.addEventListener('click', () => this.copyToClipboard(config.url, copyBtn));

            // Share button listeners
            const shareButtons = modal.querySelectorAll('[data-share-platform]');
            shareButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const platform = e.currentTarget.dataset.sharePlatform;
                    this.shareToPlat form(platform, config);
                });
            });

            return modal;
        },

        /**
         * Generate share buttons untuk berbagai platform
         */
        generateShareButtons: function(config) {
            const platforms = [
                {
                    name: 'whatsapp',
                    label: 'WhatsApp',
                    icon: 'bi-whatsapp',
                    color: '#25D366'
                },
                {
                    name: 'facebook',
                    label: 'Facebook',
                    icon: 'bi-facebook',
                    color: '#1877F2'
                },
                {
                    name: 'twitter',
                    label: 'Twitter',
                    icon: 'bi-twitter',
                    color: '#1DA1F2'
                },
                {
                    name: 'telegram',
                    label: 'Telegram',
                    icon: 'bi-telegram',
                    color: '#0088cc'
                },
                {
                    name: 'email',
                    label: 'Email',
                    icon: 'bi-envelope',
                    color: '#EA4335'
                }
            ];

            return platforms.map(platform => `
                <button class="astabaya-share-platform-btn" 
                        data-share-platform="${platform.name}"
                        style="background-color: ${platform.color};">
                    <i class="bi ${platform.icon}"></i>
                    <span>${platform.label}</span>
                </button>
            `).join('');
        },

        /**
         * Share ke platform tertentu
         */
        shareToPlatform: function(platform, config) {
            const encodedUrl = encodeURIComponent(config.url);
            const encodedTitle = encodeURIComponent(config.title);
            const encodedText = encodeURIComponent(config.text);
            
            const urls = {
                whatsapp: `https://wa.me/?text=${encodedTitle}%20-%20${encodedUrl}`,
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
                twitter: `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`,
                telegram: `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`,
                email: `mailto:?subject=${encodedTitle}&body=${encodedText}%20${encodedUrl}`
            };

            if (urls[platform]) {
                window.open(urls[platform], '_blank', 'width=600,height=400');
            }
        },

        /**
         * Copy URL to clipboard
         */
        copyToClipboard: function(url, button) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url)
                    .then(() => {
                        this.showCopySuccess(button);
                    })
                    .catch(err => {
                        console.error('Gagal menyalin:', err);
                        this.fallbackCopyToClipboard(url, button);
                    });
            } else {
                this.fallbackCopyToClipboard(url, button);
            }
        },

        /**
         * Fallback copy to clipboard untuk browser lama
         */
        fallbackCopyToClipboard: function(url, button) {
            const input = document.getElementById('astabaya-share-url');
            input.select();
            input.setSelectionRange(0, 99999); // Untuk mobile

            try {
                document.execCommand('copy');
                this.showCopySuccess(button);
            } catch (err) {
                console.error('Gagal menyalin:', err);
                alert('Gagal menyalin link. Silakan salin manual.');
            }
        },

        /**
         * Show copy success feedback
         */
        showCopySuccess: function(button) {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
            button.classList.add('success');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('success');
            }, 2000);
        },

        /**
         * Close share modal
         */
        closeShareModal: function(modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.remove();
            }, 300);
        },

        /**
         * Generate shareable link untuk chart
         */
        generateChartShareLink: function(chartId, chartTitle) {
            // Generate URL dengan parameter
            const url = new URL(window.location.href);
            url.searchParams.set('chart', chartId);
            return url.toString();
        },

        /**
         * Download chart sebagai image dan share
         * Menggunakan ECharts getDataURL jika available
         */
        shareChartAsImage: async function(chartId, title) {
            try {
                // Cari instance ECharts
                const chartElement = document.getElementById(chartId);
                if (!chartElement) {
                    console.error('Chart element tidak ditemukan:', chartId);
                    return;
                }

                // Coba ambil instance ECharts
                const chartInstance = echarts.getInstanceByDom(chartElement);
                if (chartInstance) {
                    // Generate image dari chart
                    const imageUrl = chartInstance.getDataURL({
                        type: 'png',
                        pixelRatio: 2,
                        backgroundColor: '#fff'
                    });

                    // Untuk sementara, share link saja
                    // Di masa depan bisa implement share image langsung
                    this.share({
                        title: title,
                        text: 'Lihat visualisasi data dari Aastabaya',
                        chartId: chartId
                    });
                } else {
                    // Fallback: share URL saja
                    this.share({
                        title: title,
                        text: 'Lihat visualisasi data dari Aastabaya'
                    });
                }
            } catch (error) {
                console.error('Error sharing chart:', error);
                // Fallback
                this.share({
                    title: title,
                    text: 'Lihat visualisasi data dari Aastabaya'
                });
            }
        }
    };

    // Fungsi untuk copy URL chart langsung ke clipboard
    window.copyChartURL = function(button) {
        const url = window.location.href;
        
        // Copy to clipboard
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url)
                .then(() => {
                    showCopySuccessChart(button);
                })
                .catch(err => {
                    console.error('Gagal menyalin:', err);
                    fallbackCopyChart(url, button);
                });
        } else {
            fallbackCopyChart(url, button);
        }
    };

    function fallbackCopyChart(url, button) {
        // Create temporary input
        const input = document.createElement('input');
        input.value = url;
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();
        input.setSelectionRange(0, 99999);

        try {
            document.execCommand('copy');
            showCopySuccessChart(button);
        } catch (err) {
            console.error('Gagal menyalin:', err);
            alert('Gagal menyalin link. Silakan salin manual.');
        }
        
        document.body.removeChild(input);
    }

    function showCopySuccessChart(button) {
        const originalHTML = button.innerHTML;
        const originalTitle = button.title;
        
        button.innerHTML = '<i class="fas fa-check"></i> <span>Tersalin!</span>';
        button.title = 'Link berhasil disalin';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-primary');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.title = originalTitle;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
    }

    // Initialize share buttons yang sudah ada di halaman
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation untuk tombol share
        document.addEventListener('click', function(e) {
            const shareBtn = e.target.closest('[data-share]');
            if (shareBtn) {
                e.preventDefault();
                const title = shareBtn.dataset.shareTitle || document.title;
                const chartId = shareBtn.dataset.shareChart;
                const url = shareBtn.dataset.shareUrl || window.location.href;

                if (chartId) {
                    window.AstabayaShare.shareChartAsImage(chartId, title);
                } else {
                    window.AstabayaShare.share({
                        title: title,
                        url: url
                    });
                }
            }
        });
    });
})();


