import './bootstrap';
import "@hotwired/turbo";

// Garbage Collector for ECharts & Chart.js to prevent memory leaks on SPA navigation
document.addEventListener("turbo:before-cache", function() {
    // 1. Cleanup ECharts instances
    if (typeof echarts !== 'undefined') {
        const echartsDoms = document.querySelectorAll('[_echarts_instance_]');
        echartsDoms.forEach(dom => {
            try { echarts.dispose(dom); } catch (e) {}
        });
    }

    // 2. Cleanup Chart.js instances (if any)
    if (typeof Chart !== 'undefined') {
        Object.keys(Chart.instances).forEach(function(key) {
            try { Chart.instances[key].destroy(); } catch (e) {}
        });
    }
    
    // Clear global references manually set in indicator scripts
    window.chartInstances = {};
    window.comparisonLineChartInstance = null;
    window.comparisonBarChartInstance = null;
    window.trendChart = null;
    window.compositionChart = null;
});
