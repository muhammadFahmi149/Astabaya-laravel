// resources/js/utilities.js

// Function to escape HTML to prevent XSS attacks
window.escapeHtml = function(text) {
  if (text === null || text === undefined) return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return String(text).replace(/[&<>"']/g, m => map[m]);
};

// Function to validate and sanitize numeric value
window.sanitizeNumber = function(value) {
  if (value === null || value === undefined) return null;
  const num = Number(value);
  if (isNaN(num) || !isFinite(num)) return null;
  return num;
};

// Function to validate year value
window.sanitizeYear = function(value) {
  if (value === null || value === undefined) return null;
  const year = parseInt(value, 10);
  if (isNaN(year) || year < 1900 || year > 2100) return null;
  return year;
};

// Function to format number with thousand separators (Indonesian format using dot)
window.formatRupiah = function(number) {
  const num = window.sanitizeNumber(number);
  if (num === null) return '';
  const numStr = Math.abs(num).toString().split('.')[0];
  const formatted = numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  return num < 0 ? '-' + formatted : formatted;
};

// Helper to check authentication before downloading files
window.checkAuthBeforeDownload = function(callback, itemName = 'data') {
  if (window.APP_CONFIG && window.APP_CONFIG.isAuthenticated) {
    // User authenticated, proceed with download
    callback();
    return true;
  } else {
    // User not authenticated, show login modal
    if (typeof showLoginRequiredModal === 'function') {
      showLoginRequiredModal(itemName);
    } else {
      alert('Ingin mengunduh ' + itemName + ' ini? Silakan login terlebih dahulu.');
      const loginModal = document.getElementById('loginModal');
      if (loginModal) {
        const modal = new bootstrap.Modal(loginModal);
        modal.show();
      } else {
        if (window.APP_CONFIG && window.APP_CONFIG.loginRoute) {
          window.location.href = window.APP_CONFIG.loginRoute;
        } else {
          window.location.href = '/login';
        }
      }
    }
    return false;
  }
};

// Format number to thousands/millions
window.formatPopulation = function(num) {
  if (num === null || num === undefined || isNaN(num)) return '0';
  const numValue = parseFloat(num);
  if (numValue >= 1000000) {
    return (numValue / 1000000).toFixed(2).replace(/\.?0+$/, '') + ' juta';
  } else if (numValue >= 1000) {
    return (numValue / 1000).toFixed(2).replace(/\.?0+$/, '') + ' ribu';
  }
  return numValue.toString();
};

// Format change values
window.formatChangeValue = function(value) {
  if (value === null || value === undefined || isNaN(value)) return '';
  const absValue = Math.abs(value);
  if (absValue >= 1000000) {
    return (absValue / 1000000).toFixed(2).replace(/\.?0+$/, '') + ' juta';
  } else if (absValue >= 1000) {
    return (absValue / 1000).toFixed(2).replace(/\.?0+$/, '') + ' ribu';
  }
  return absValue.toString();
};





// Globally patch ECharts to show loading on init and hide on setOption
document.addEventListener('DOMContentLoaded', () => {
  if (window.echarts) {
    const originalInit = window.echarts.init;
    window.echarts.init = function() {
      const chart = originalInit.apply(this, arguments);
      
      // Show loading automatically upon initialization
      chart.showLoading({ text: 'Memuat data...', color: '#3b82f6' });

      const originalSetOption = chart.setOption;
      chart.setOption = function() {
        chart.hideLoading();
        return originalSetOption.apply(this, arguments);
      };
      
      return chart;
    };
  }
});
