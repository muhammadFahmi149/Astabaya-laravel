document.addEventListener("turbo:load", () => {
    if (!window.IPM_CONFIG) return;

    const {
        apiEndpoint,
        chartTitle,
        yAxisName,
        valuePrefix = '',
        valueSuffix = '',
        exportPrefix,
        isCurrency = false
    } = window.IPM_CONFIG;

    const API_BASE = window.APP_CONFIG ? window.APP_CONFIG.apiUrl : '/api';
    let surabayaData = [];
    let jatimData = [];
    let comparisonChart = null;

    // Improved formatNumber: doesn't round decimals, uses Indonesian locale formatting
    // For 14.50 -> 14,50
    // For 21 -> 21
    // For 1000.5 -> 1.000,5
    function formatNumber(num) {
        if (num === null || num === undefined) return '-';
        let str = num.toString();
        let parts = str.split(".");
        parts[0] = parts[0].replace(/B(?=(d{3})+(?!d))/g, ".");
        return parts.length > 1 ? parts.join(",") : parts[0];
    }

    async function loadSummaryData() {
        // Cache Key
        const CACHE_KEY = 'astabaya_ipm_' + apiEndpoint.replace(/[^a-zA-Z0-9]/g, '_');
        let result = null;

        try {
            const cachedData = sessionStorage.getItem(CACHE_KEY);
            if (cachedData) {
                result = JSON.parse(cachedData);
                console.log('Loaded IPM data from sessionStorage cache');
            } else {
                const response = await fetch(`${API_BASE}${apiEndpoint}`);
                result = await response.json();
                
                if (result && result.success) {
                    sessionStorage.setItem(CACHE_KEY, JSON.stringify(result));
                }
            }
            
            if (result.success && result.data) {
                const data = result.data;
                
                // Update Surabaya card
                if (data.surabaya_latest) {
                    const valEl = document.getElementById('surabaya-value');
                    if (valEl) {
                        if (data.surabaya_latest.value !== null) {
                            if (isCurrency) {
                                valEl.textContent = valuePrefix + formatNumber(data.surabaya_latest.value) + valueSuffix;
                            } else {
                                valEl.textContent = formatNumber(data.surabaya_latest.value);
                            }
                        } else {
                            valEl.textContent = '-';
                        }
                    }
                    
                    const yearEl = document.getElementById('surabaya-year');
                    if (yearEl) yearEl.textContent = `Tahun ${data.surabaya_latest.year}`;
                    
                    const changeEl = document.getElementById('surabaya-change');
                    if (changeEl) {
                        if (data.surabaya_change !== null) {
                            const changeValue = Math.abs(data.surabaya_change);
                            let changeText = '';
                            if (isCurrency) {
                                changeText = data.surabaya_change > 0 
                                    ? `+${valuePrefix}${formatNumber(changeValue)}${valueSuffix}` 
                                    : `-${valuePrefix}${formatNumber(changeValue)}${valueSuffix}`;
                            } else {
                                changeText = data.surabaya_change > 0 
                                    ? `+${formatNumber(changeValue)}${valueSuffix}` 
                                    : `-${formatNumber(changeValue)}${valueSuffix}`;
                            }
                            
                            const arrow = data.surabaya_change > 0 ? '▲' : '▼';
                            const previousYear = data.surabaya_previous ? data.surabaya_previous.year : '';
                            
                            changeEl.innerHTML = `
                                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                                ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
                            `;
                        } else {
                            changeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
                        }
                    }
                } else {
                    const valEl = document.getElementById('surabaya-value');
                    if (valEl) valEl.textContent = '-';
                    const yearEl = document.getElementById('surabaya-year');
                    if (yearEl) yearEl.textContent = 'Data tidak tersedia';
                    const changeEl = document.getElementById('surabaya-change');
                    if (changeEl) changeEl.innerHTML = '';
                }
                
                // Update Jatim card
                if (data.jatim_latest) {
                    const valEl = document.getElementById('jatim-value');
                    if (valEl) {
                        if (data.jatim_latest.value !== null) {
                            if (isCurrency) {
                                valEl.textContent = valuePrefix + formatNumber(data.jatim_latest.value) + valueSuffix;
                            } else {
                                valEl.textContent = formatNumber(data.jatim_latest.value);
                            }
                        } else {
                            valEl.textContent = '-';
                        }
                    }
                    
                    const yearEl = document.getElementById('jatim-year');
                    if (yearEl) yearEl.textContent = `Tahun ${data.jatim_latest.year}`;
                    
                    const changeEl = document.getElementById('jatim-change');
                    if (changeEl) {
                        if (data.jatim_change !== null) {
                            const changeValue = Math.abs(data.jatim_change);
                            let changeText = '';
                            if (isCurrency) {
                                changeText = data.jatim_change > 0 
                                    ? `+${valuePrefix}${formatNumber(changeValue)}${valueSuffix}` 
                                    : `-${valuePrefix}${formatNumber(changeValue)}${valueSuffix}`;
                            } else {
                                changeText = data.jatim_change > 0 
                                    ? `+${formatNumber(changeValue)}${valueSuffix}` 
                                    : `-${formatNumber(changeValue)}${valueSuffix}`;
                            }
                            
                            const arrow = data.jatim_change > 0 ? '▲' : '▼';
                            const previousYear = data.jatim_previous ? data.jatim_previous.year : '';
                            
                            changeEl.innerHTML = `
                                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                                ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
                            `;
                        } else {
                            changeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
                        }
                    }
                } else {
                    const valEl = document.getElementById('jatim-value');
                    if (valEl) valEl.textContent = '-';
                    const yearEl = document.getElementById('jatim-year');
                    if (yearEl) yearEl.textContent = 'Data tidak tersedia';
                    const changeEl = document.getElementById('jatim-change');
                    if (changeEl) changeEl.innerHTML = '';
                }
                
                // Store data for chart
                surabayaData = data.surabaya_data || [];
                jatimData = data.jatim_data || [];
                
                // Render chart
                renderChart();
            }
        } catch (error) {
            console.error(`Error loading ${chartTitle} summary data:`, error);
        }
    }

    function renderChart() {
        // Process data
        const surabayaProcessed = surabayaData.map(d => ({
            year: d.year,
            value: d.value !== null ? parseFloat(d.value) : null
        }));
        
        const jatimProcessed = jatimData.map(d => ({
            year: d.year,
            value: d.value !== null ? parseFloat(d.value) : null
        }));

        surabayaProcessed.sort((a, b) => a.year - b.year);
        jatimProcessed.sort((a, b) => a.year - b.year);

        const allYearsSet = new Set([
            ...surabayaProcessed.map(d => d.year),
            ...jatimProcessed.map(d => d.year)
        ]);
        const allYears = Array.from(allYearsSet).sort((a, b) => a - b);
        
        // Only show last 10 years if more than 10 years
        const displayYears = allYears.length > 10 ? allYears.slice(-10) : allYears;
        const years = displayYears.map(y => y.toString());
        
        // Store displayYears in global scope for export function
        window.displayYearsData = displayYears;

        const surabayaValues = displayYears.map(year => {
            const data = surabayaProcessed.find(d => d.year === year);
            return data && data.value !== null ? data.value : null;
        });

        const jatimValues = displayYears.map(year => {
            const data = jatimProcessed.find(d => d.year === year);
            return data && data.value !== null ? data.value : null;
        });
        
        // Store values in global scope for export function
        window.surabayaValuesData = surabayaValues;
        window.jatimValuesData = jatimValues;

        const comparisonChartDom = document.getElementById('comparisonChart');
        if (!comparisonChartDom) return;
        
        comparisonChart = echarts.init(comparisonChartDom);
        
        // Default yAxis configuration
        const yAxisConfig = {
            type: 'value',
            name: yAxisName,
            position: 'left',
            nameLocation: 'end',
            nameGap: 10,
            axisLabel: { formatter: '{value}' }
        };
        
        comparisonChart.setOption({
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'line', snap: true, lineStyle: { type: 'dashed' } },
                formatter: function(params) {
                    let result = 'Tahun: ' + params[0].axisValue + '<br/>';
                    params.forEach(function(item) {
                        result += item.marker + item.seriesName + ': ' + 
                            (item.value !== null ? valuePrefix + formatNumber(item.value) + valueSuffix : 'Data tidak tersedia') + '<br/>';
                    });
                    return result;
                }
            },
            legend: { data: ['Kota Surabaya', 'Jawa Timur'], top: 10 },
            grid: { left: '12%', right: '4%', bottom: '10%', top: '20%', containLabel: false },
            xAxis: { type: 'category', data: years, boundaryGap: false },
            yAxis: yAxisConfig,
            series: [
                {
                    name: 'Kota Surabaya',
                    type: 'line',
                    data: surabayaValues,
                    itemStyle: { color: 'rgb(59, 130, 246)' },
                    lineStyle: { width: 3 },
                    symbol: 'circle',
                    symbolSize: 8,
                    smooth: true,
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0, y: 0, x2: 0, y2: 1,
                            colorStops: [
                                { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                                { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
                            ]
                        }
                    }
                },
                {
                    name: 'Jawa Timur',
                    type: 'line',
                    data: jatimValues,
                    itemStyle: { color: 'rgb(16, 185, 129)' },
                    lineStyle: { width: 3 },
                    symbol: 'circle',
                    symbolSize: 8,
                    smooth: true,
                    areaStyle: {
                        color: {
                            type: 'linear',
                            x: 0, y: 0, x2: 0, y2: 1,
                            colorStops: [
                                { offset: 0, color: 'rgba(16, 185, 129, 0.3)' },
                                { offset: 1, color: 'rgba(16, 185, 129, 0.05)' }
                            ]
                        }
                    }
                }
            ]
        });

        window.addEventListener('resize', function() {
            if (comparisonChart) comparisonChart.resize();
        });
    }

    // Export to Excel function
    function exportToExcel() {
        const exportData = [];
        exportData.push(['Tahun', `Kota Surabaya (${exportPrefix})`, `Jawa Timur (${exportPrefix})`]);
        
        if (window.displayYearsData && window.surabayaValuesData && window.jatimValuesData) {
            window.displayYearsData.forEach((year, index) => {
                const surabayaVal = window.surabayaValuesData[index] !== null 
                    ? formatNumber(window.surabayaValuesData[index])
                    : 'Data tidak tersedia';
                const jatimVal = window.jatimValuesData[index] !== null 
                    ? formatNumber(window.jatimValuesData[index])
                    : 'Data tidak tersedia';
                exportData.push([year.toString(), surabayaVal, jatimVal]);
            });
        }
        
        if (typeof XLSX === 'undefined') {
            alert('Library XLSX tidak dimuat');
            return;
        }
        
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(exportData);
        
        ws['!cols'] = [
            { wch: 10 },
            { wch: 25 },
            { wch: 25 }
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, `Data ${exportPrefix}`);
        
        const today = new Date();
        const dateStr = today.toISOString().split('T')[0];
        const filename = `${exportPrefix}_Surabaya_vs_JawaTimur_${dateStr}.xlsx`;
        
        XLSX.writeFile(wb, filename);
    }
    
    // Helper function to check authentication before download


    const downloadChartBtn = document.getElementById('downloadChartData');
    if (downloadChartBtn) {
        downloadChartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.checkAuthBeforeDownload(exportToExcel, `data ${exportPrefix}`);
        });
    }
    
    // Export chart as PNG
    function exportToPNG() {
        if (!comparisonChart) {
            alert('Grafik belum dimuat. Silakan tunggu sebentar.');
            return;
        }
        
        const url = comparisonChart.getDataURL({
            type: 'png',
            pixelRatio: 2,
            backgroundColor: '#fff'
        });
        const link = document.createElement('a');
        link.download = `${exportPrefix}_Chart_Surabaya_vs_JawaTimur_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
    }
    
    const downloadImageBtn = document.getElementById('downloadImageData');
    if (downloadImageBtn) {
        downloadImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.checkAuthBeforeDownload(exportToPNG, `grafik ${exportPrefix}`);
        });
    }

    // Initialize
    loadSummaryData();
});
