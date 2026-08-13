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
const donutPalette = ['#047857', '#34d399', '#a7f3d0', '#fbbf24', '#f87171', '#818cf8'];

Chart.defaults.font.family = "'Inter', 'ui-sans-serif', 'system-ui', sans-serif";
Chart.defaults.color = '#9ca3af';

function parseData(canvas) {
    return {
        labels: JSON.parse(canvas.dataset.labels || '[]'),
        values: JSON.parse(canvas.dataset.values || '[]'),
    };
}

// ---- Employees Per Branch: gradient bar sitting on a pale "track" ---
const branchChart = document.getElementById('branchChart');

if (branchChart) {
    const { labels, values } = parseData(branchChart);
    const ctx = branchChart.getContext('2d');
    const maxVal = Math.max(1, ...values);

    const gradient = ctx.createLinearGradient(0, 0, 0, branchChart.height || 220);
    gradient.addColorStop(0, emeraldLight);
    gradient.addColorStop(1, emeraldDark);

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
                    order: 2,
                },
                // Foreground gradient value bar.
                {
                    label: 'Employees',
                    data: values,
                    backgroundColor: gradient,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 34,
                    categoryPercentage: 0.5,
                    barPercentage: 1,
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
    });
}

// ---- Attendance Status: segmented donut with gaps + rounded caps ----
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
    });
}

// ---- Attendance Trend: smooth gradient area line + soft echo wave ---
const attendanceTrendChart = document.getElementById('attendanceTrendChart');

if (attendanceTrendChart) {
    const { labels, values } = parseData(attendanceTrendChart);
    const ctx = attendanceTrendChart.getContext('2d');

    const fill = ctx.createLinearGradient(0, 0, 0, attendanceTrendChart.height || 150);
    fill.addColorStop(0, 'rgba(5, 150, 105, 0.25)');
    fill.addColorStop(1, 'rgba(5, 150, 105, 0)');

    const echoFill = ctx.createLinearGradient(0, 0, 0, attendanceTrendChart.height || 150);
    echoFill.addColorStop(0, 'rgba(52, 211, 153, 0.15)');
    echoFill.addColorStop(1, 'rgba(52, 211, 153, 0)');

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
                    borderColor: emeraldPale,
                    backgroundColor: echoFill,
                    fill: true,
                    tension: 0.5,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    order: 2,
                },
                {
                    label: 'Attendance',
                    data: values,
                    borderColor: emerald,
                    backgroundColor: fill,
                    fill: true,
                    tension: 0.45,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: emerald,
                    pointHoverBorderColor: '#ffffff',
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
                    filter: (item) => item.datasetIndex === 1,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    // offset:false removes the default half-category inset,
                    // so the line starts/ends flush with the plot edges
                    // instead of leaving empty space on both sides.
                    offset: false,
                },
                y: { display: false, grid: { display: false } },
            },
            interaction: { intersect: false, mode: 'index' },
        },
    });
}

m6fjkyKxFqITh9P0 - cheryl

