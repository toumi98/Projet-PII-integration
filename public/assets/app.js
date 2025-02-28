import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('statsChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril'],
                datasets: [{
                    label: 'Ventes',
                    data: [10, 20, 15, 30], 
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            }
        });
    } else {
        console.error("Canvas 'statsChart' non trouvé !");
    }
});
