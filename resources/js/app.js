import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();

// ---- Shared palette -------------------------------------------------
const emerald = '#059669';
const emeraldDark = '#047857';
const emeraldLight = '#34d399';
const emeraldPale = '#a7f3d0';
const emeraldGlow = 'rgba(16, 185, 129, 0.55)'; // used for shadow/glow blur
const donutPalette = ['#047857', '#34d399', '#a7f3d0', '#fbbf24', '#f87171', '#818cf8'];

Chart.defaults.font.family = "'Inter', 'ui-sans-serif', 'system-ui', sans-serif";
Chart.defaults.color = '#9ca3af';

function parseData(canvas) {
    return {
        labels: JSON.parse(canvas.dataset.labels || '[]'),
        values: JSON.parse(canvas.dataset.values || '[]'),
    };
}

// ---- Reusable glow plugin --------------------------------------------
// Draws a soft blurred halo behind a chosen dataset before Chart.js paints
// its normal (crisp) version on top. This is what gives the line its
// "glass / neon" glow instead of a flat stroke.
function makeGlowPlugin({ datasetIndex = 0, color = emeraldGlow, blur = 14 } = {}) {
    return {
        id: `glow-${datasetIndex}-${blur}`,
        beforeDatasetDraw(chart, args) {
            if (args.index !== datasetIndex) return;
            const { ctx } = chart;
            ctx.save();
            ctx.shadowColor = color;
            ctx.shadowBlur = blur;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 4;
        },
        afterDatasetDraw(chart, args) {
            if (args.index !== datasetIndex) return;
            chart.ctx.restore();
        },
    };
}

// ---- Employees Per Branch: 3D glass bars ------------------------------
const branchChart = document.getElementById('branchChart');

if (branchChart) {
    const { labels, values } = parseData(branchChart);
    const ctx = branchChart.getContext('2d');
    const maxVal = Math.max(1, ...values);
    const h = branchChart.height || 220;

    // Main body gradient: light glass top -> saturated emerald base.
    const barGradient = ctx.createLinearGradient(0, 0, 0, h);
    barGradient.addColorStop(0, emeraldLight);
    barGradient.addColorStop(0.55, emerald);
    barGradient.addColorStop(1, emeraldDark);

    // Thin bright strip along one edge to fake a glass highlight / bevel.
    const highlightGradient = ctx.createLinearGradient(0, 0, 0, h);
    highlightGradient.addColorStop(0, 'rgba(255, 255, 255, 0.85)');
    highlightGradient.addColorStop(1, 'rgba(255, 255, 255, 0.05)');

    new Chart(branchChart, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                // Pale background track, full height, drawn behind the value bar.
                {
                    label: 'Track',
                    data: labels.map(() => maxVal),
                    backgroundColor: '#f0fdf4',
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 34,
                    categoryPercentage: 0.5,
                    barPercentage: 1,
                    order: 3,
                },
                // Foreground gradient value bar (the 3D "glass pillar").
                {
                    label: 'Employees',
                    data: values,
                    backgroundColor: barGradient,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 34,
                    categoryPercentage: 0.5,
                    barPercentage: 1,
                    order: 2,
                },
                // Narrow highlight sliver on top of the bar for the glass-edge look.
                {
                    label: 'Highlight',
                    data: values,
                    backgroundColor: highlightGradient,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 6,
                    categoryPercentage: 0.5,
                    barPercentage: 0.16,
                    order: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    filter: (item) => item.datasetIndex === 1,
                },
            },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    border: { display: false },
                    ticks: { display: false },
                },
            },
            interaction: { intersect: false, mode: 'index' },
        },
        plugins: [makeGlowPlugin({ datasetIndex: 1, color: 'rgba(5, 150, 105, 0.45)', blur: 12 })],
    });
}

// ---- Attendance Status: segmented donut with soft glow ----------------
const attendanceStatusChart = document.getElementById('attendanceStatusChart');

if (attendanceStatusChart) {
    const { labels, values } = parseData(attendanceStatusChart);

    new Chart(attendanceStatusChart, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: donutPalette,
                borderWidth: 0,
                borderRadius: 8,
                spacing: 4,
                hoverOffset: 8,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '74%',
            rotation: -90,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 10,
                    cornerRadius: 8,
                },
            },
        },
        plugins: [makeGlowPlugin({ datasetIndex: 0, color: 'rgba(16, 185, 129, 0.35)', blur: 18 })],
    });
}

// ---- Attendance Trend: glowing glass line chart ------------------------
const attendanceTrendChart = document.getElementById('attendanceTrendChart');

if (attendanceTrendChart) {
    const { labels, values } = parseData(attendanceTrendChart);
    const ctx = attendanceTrendChart.getContext('2d');
    const h = attendanceTrendChart.height || 150;

    // Frosted / glass fill: brighter glassy band near the line, fading fast.
    const fill = ctx.createLinearGradient(0, 0, 0, h);
    fill.addColorStop(0, 'rgba(52, 211, 153, 0.35)');
    fill.addColorStop(0.25, 'rgba(5, 150, 105, 0.18)');
    fill.addColorStop(1, 'rgba(5, 150, 105, 0)');

    const echoFill = ctx.createLinearGradient(0, 0, 0, h);
    echoFill.addColorStop(0, 'rgba(167, 243, 208, 0.18)');
    echoFill.addColorStop(1, 'rgba(167, 243, 208, 0)');

    // Softened/offset copy of the same series — a cosmetic echo layer only,
    // not a second real metric — to recreate the dual-wave reference look.
    const echoValues = values.map((v, i) => {
        const prev = values[i - 1] ?? v;
        const next = values[i + 1] ?? v;
        return Math.round(((prev + v + next) / 3) * 0.85);
    });

    new Chart(attendanceTrendChart, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Trend',
                    data: echoValues,
                    borderColor: 'rgba(167, 243, 208, 0.55)',
                    backgroundColor: echoFill,
                    fill: true,
                    tension: 0.5,
                    borderWidth: 1.5,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    order: 3,
                },
                {
                    // Wide soft-glow duplicate of the main line, drawn underneath it.
                    label: 'Glow',
                    data: values,
                    borderColor: 'rgba(5, 150, 105, 0.35)',
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.45,
                    borderWidth: 8,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    order: 2,
                },
                {
                    // Crisp glass line on top: bright emerald core with a thin
                    // near-white highlight edge to sell the "glass tube" look.
                    label: 'Attendance',
                    data: values,
                    borderColor: emerald,
                    backgroundColor: fill,
                    fill: true,
                    tension: 0.45,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: emeraldDark,
                    pointHoverBorderWidth: 2,
                    order: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 0,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    filter: (item) => item.datasetIndex === 2,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    offset: false,
                },
                y: { display: false, grid: { display: false } },
            },
            interaction: { intersect: false, mode: 'index' },
        },
        // Extra soft halo behind the crisp top line (dataset index 2) so the
        // glow reads even where the "Glow" dataset stroke doesn't fully cover it.
        plugins: [makeGlowPlugin({ datasetIndex: 2, color: emeraldGlow, blur: 14 })],
    });
}