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
  let str = Math.abs(num).toString();
  // If it's a float, it will have a dot. We should also check if the caller passed a string with .toFixed(2)
  if (typeof number === 'string' && number.includes('.')) {
      str = number.replace('-', '');
  }
  let parts = str.split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  let formatted = parts.join(',');
  return num < 0 ? '-' + formatted : formatted;
};

// Helper to check authentication before downloading files
window.checkAuthBeforeDownload = function(callback, itemName = 'data') {
  const isAuth = (window.ASTABAYA && window.ASTABAYA.isAuthenticated) || (window.APP_CONFIG && window.APP_CONFIG.isAuthenticated);
  if (isAuth) {
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

// Global fix for ARIA-hidden focus warning on all modals
document.addEventListener('hide.bs.modal', function(event) {
  // When any modal starts to hide, remove focus from whatever is focused inside it (like the close button)
  // This prevents browsers from throwing "Blocked aria-hidden on an element because its descendant retained focus"
  if (document.activeElement && document.activeElement !== document.body) {
    document.activeElement.blur();
  }
});
document.addEventListener('click', function(e) {
  const dismissBtn = e.target.closest('[data-bs-dismiss="modal"]');
  if (dismissBtn) {
    dismissBtn.blur();
    
    // Also try to move focus to a safe element like document.body
    if (document.activeElement) {
        document.activeElement.blur();
    }
  }
}, true); // Use capture phase to blur before Bootstrap sets aria-hidden
// Clean up old session storage cache versions (v1, v2)
try {
  Object.keys(sessionStorage).forEach(key => {
    if (key.startsWith('astabaya_v1_') || key.startsWith('astabaya_v2_')) {
      sessionStorage.removeItem(key);
    }
  });
} catch (e) {}

// Helper to fetch API with SessionStorage Caching (v3 prevents empty data caching and forces fresh data after schema/sync update)
window.fetchAPIWithCache = async function(url, options = {}) {
  const cacheKey = 'astabaya_v3_cache_' + url.replace(/[^a-zA-Z0-9]/g, '_');
  const cachedData = sessionStorage.getItem(cacheKey);
  
  const isCacheValid = (item) => {
    if (!item) return false;
    if (item.success === false || item.status === 'error') return false;
    if (item.data !== undefined && item.data !== null) {
      if (Array.isArray(item.data) && item.data.length === 0) return false;
      if (typeof item.data === 'object' && !Array.isArray(item.data) && Object.keys(item.data).length === 0) return false;
    }
    return true;
  };
  
  if (cachedData) {
    try {
      const parsed = JSON.parse(cachedData);
      if (isCacheValid(parsed)) {
        return parsed;
      }
    } catch (e) {
      sessionStorage.removeItem(cacheKey);
    }
  }
  
  const response = await fetch(url, options);
  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }
  
  const data = await response.json();
  if (isCacheValid(data)) {
    sessionStorage.setItem(cacheKey, JSON.stringify(data));
  }
  return data;
};

// Globally accessible bookmark toggle function
  window.toggleBookmark = async function(button) {
    // Prevent multiple clicks
    if (button.disabled) return;
    button.disabled = true;

    const contentType = button.dataset.contentType;
    // Ensure objectId is a string, handle array case
    let objectId = button.dataset.objectId;
    if (Array.isArray(objectId)) {
      objectId = String(objectId[0]);
    } else if (objectId && typeof objectId === 'object') {
      objectId = String(objectId);
    } else {
      objectId = String(objectId || '');
    }
    let bookmarkId = button.dataset.bookmarkId;
    const isBookmarked = button.classList.contains("bookmarked");

    const icon = button.querySelector("i");
    const text = button.querySelector("span");

    function getCookie(name) {
      let cookieValue = null;
      if (document.cookie && document.cookie !== "") {
        const cookies = document.cookie.split(";");
        for (let i = 0; i < cookies.length; i++) {
          const cookie = cookies[i].trim();
          if (cookie.substring(0, name.length + 1) === name + "=") {
            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
            break;
          }
        }
      }
      return cookieValue;
    }
    // Validate required data
    if (!contentType || !objectId) {
      console.error("Missing required data:", { contentType, objectId });
      alert("Data tidak lengkap. Silakan refresh halaman.");
      button.disabled = false;
      return;
    }

    // Get CSRF token from meta tag (Laravel standard)
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrftoken = metaTag ? metaTag.getAttribute('content') : null;
    
    console.log("[Publications Bookmark] CSRF Token:", csrftoken ? "Found" : "NOT FOUND");

    if (!csrftoken) {
      console.error("CSRF token not found! Silakan refresh halaman (Ctrl+F5).");
      alert("Token CSRF tidak ditemukan. Silakan refresh halaman (Ctrl+F5).");
      button.disabled = false;
      return;
    }

    try {
      if (isBookmarked) {
        // --- Hapus Bookmark ---
        if (!bookmarkId) {
          console.error("Bookmark ID tidak ditemukan untuk penghapusan");
          button.disabled = false;
          return;
        }

        console.log("Deleting bookmark:", { bookmarkId, contentType, objectId });
        const response = await fetch(window.ASTABAYA.baseUrl + `/bookmarks/${bookmarkId}`, {
          method: "DELETE",
          headers: { 
            "X-CSRF-TOKEN": csrftoken,
            "X-Requested-With": "XMLHttpRequest"
          },
          credentials: "include",
        });

        console.log("Delete response status:", response.status);
        
        if (response.ok || response.status === 204) {
          button.classList.remove("bookmarked");
          icon.classList.remove("bi-bookmark-fill");
          icon.classList.add("bi-bookmark");
          if (text) text.textContent = "Bookmark";
          button.dataset.bookmarkId = "";
          
          // Sync with other bookmark buttons for the same item
          if (typeof window.syncBookmarkButtons === 'function') {
            window.syncBookmarkButtons(contentType, objectId, false, "");
          }
          
          // Broadcast change to other tabs
          if (typeof broadcastBookmarkChange === 'function') {
            broadcastBookmarkChange(contentType, objectId, false, "");
          }
          
          // Update bookmark list in header
          if (typeof updateBookmarkList === 'function') {
            updateBookmarkList();
          }
        } else {
          const errorData = await response.json().catch(() => ({}));
          console.error("Delete bookmark error:", errorData);
          alert("Gagal menghapus bookmark: " + (errorData.error || errorData.detail || "Terjadi kesalahan"));
        }
      } else {
        // --- Tambah Bookmark ---
        const requestBody = { 
          content_type_name: contentType, 
          object_id: objectId 
        };
        
        console.log("Adding bookmark:", requestBody);
        
        const response = await fetch(window.ASTABAYA.baseUrl + `/bookmarks/add`, {
          method: "POST",
          headers: { 
            "Content-Type": "application/json", 
            "X-CSRF-TOKEN": csrftoken,
            "X-Requested-With": "XMLHttpRequest"
          },
          credentials: "include",
          body: JSON.stringify(requestBody),
        });

        console.log("Add response status:", response.status);
        const responseData = await response.json().catch(() => ({}));
        console.log("Add response data:", responseData);

        if (response.ok) {
          button.classList.add("bookmarked");
          icon.classList.remove("bi-bookmark");
          icon.classList.add("bi-bookmark-fill");
          if (text) text.textContent = "Tersimpan";
          button.dataset.bookmarkId = String(responseData.id);
          
          if (typeof window.syncBookmarkButtons === 'function') {
            window.syncBookmarkButtons(contentType, objectId, true, String(responseData.id));
          }
          
          // Broadcast change to other tabs
          if (typeof broadcastBookmarkChange === 'function') {
            broadcastBookmarkChange(contentType, objectId, true, String(responseData.id));
          }
          
          // Update bookmark list in header
          if (typeof updateBookmarkList === 'function') {
            updateBookmarkList();
          }
        } else {
          if (response.status === 409) {
            // Bookmark already exists, fetch and update UI
            try {
              const existingBookmarks = await fetch(window.ASTABAYA.baseUrl + `/bookmarks`, {
                headers: { 
                  "X-CSRF-TOKEN": csrftoken,
                  "X-Requested-With": "XMLHttpRequest"
                },
                credentials: "include",
              }).then(r => r.json()).catch(() => []);
              
              const bookmark = existingBookmarks.find(b => 
                b.content_type_model === contentType && 
                String(b.object_id) === String(objectId)
              );
              
              if (bookmark) {
                button.classList.add("bookmarked");
                icon.classList.remove("bi-bookmark");
                icon.classList.add("bi-bookmark-fill");
                if (text) text.textContent = "Tersimpan";
                if (typeof window.syncBookmarkButtons === 'function') {
                  window.syncBookmarkButtons(contentType, objectId, true, String(bookmark.id));
                }
                
                // Broadcast change to other tabs
                if (typeof broadcastBookmarkChange === 'function') {
                  broadcastBookmarkChange(contentType, objectId, true, String(bookmark.id));
                }
                
                // Update bookmark list in header
                if (typeof updateBookmarkList === 'function') {
                  updateBookmarkList();
                }
              } else {
                alert("Bookmark sudah ada tetapi tidak dapat ditemukan.");
              }
            } catch (fetchError) {
              console.error("Error fetching existing bookmarks:", fetchError);
              alert("Bookmark sudah ada di daftar Anda.");
            }
          } else {
            const errorMsg = responseData.error || responseData.detail || responseData.non_field_errors || "Terjadi kesalahan";
            console.error("Add bookmark error:", responseData);
            alert("Gagal menambahkan bookmark: " + (Array.isArray(errorMsg) ? errorMsg.join(", ") : errorMsg));
          }
        }
      }
    } catch (error) {
      console.error("Error toggling bookmark:", error);
      alert("Terjadi kesalahan: " + error.message);
    } finally {
      button.disabled = false;
    }
  }
