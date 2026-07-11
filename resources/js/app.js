import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();

const branchChart = document.getElementById('branchChart');

if (branchChart) {

    const labels = JSON.parse(branchChart.dataset.labels);
    const values = JSON.parse(branchChart.dataset.values);

    new Chart(branchChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Employees',
                data: values,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

}

const attendanceStatusChart =
    document.getElementById('attendanceStatusChart');

if (attendanceStatusChart) {

    new Chart(attendanceStatusChart, {

        type: 'pie',

        data: {

            labels: JSON.parse(
                attendanceStatusChart.dataset.labels
            ),

            datasets: [{
                data: JSON.parse(
                    attendanceStatusChart.dataset.values
                )
            }]
        }
    });
}


const attendanceTrendChart =
    document.getElementById('attendanceTrendChart');

if (attendanceTrendChart) {

    new Chart(attendanceTrendChart, {

        type: 'line',

        data: {

            labels: JSON.parse(
                attendanceTrendChart.dataset.labels
            ),

            datasets: [{
                label: 'Attendance',
                data: JSON.parse(
                    attendanceTrendChart.dataset.values
                )
            }]
        }
    });
}