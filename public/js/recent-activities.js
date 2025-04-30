// Recent Activities Data
const recentActivitiesData = [
    {
        date: "2023-06-30",
        region: "Region 5 (Bicol)",
        category: "Surrendered",
        details: "Roberto Albay",
        location: "Legazpi Police Station"
    },
    {
        date: "2023-06-29",
        region: "Region 5 (Bicol)",
        category: "PAG Member",
        details: "Domingo Bicolano",
        location: "Daraga, Albay"
    },
    {
        date: "2023-06-28",
        region: "Region 5 (Bicol)",
        category: "Firearm",
        details: "Shotgun (12 gauge)",
        location: "Camalig, Albay"
    },
    {
        date: "2023-06-27",
        region: "Region 5 (Bicol)",
        category: "Sighting",
        details: "Vehicle matching description of known PAG member....",
        location: "Sorsogon City"
    },
    {
        date: "2023-06-27",
        region: "Region 5 (Bicol)",
        category: "CTG Member",
        details: "Pedro Bicol",
        location: "Rural Sorsogon"
    },
    {
        date: "2023-06-25",
        region: "Region 5 (Bicol)",
        category: "ISO Operation",
        details: "Operation Bicol Safe Streets",
        location: "Naga City"
    },
    {
        date: "2023-06-24",
        region: "Region 4B (MIMAROPA)",
        category: "PAG Member",
        details: "Luisa Fernandez",
        location: "Roxas, Oriental Mindoro"
    },
    {
        date: "2023-06-23",
        region: "Region 4B (MIMAROPA)",
        category: "Firearm",
        details: "9mm Pistol (9mm)",
        location: "Romblon"
    },
    {
        date: "2023-06-22",
        region: "Region 4B (MIMAROPA)",
        category: "Sighting",
        details: "Report of armed men near coastal area....",
        location: "San Jose, Occidental Mindoro"
    },
    {
        date: "2023-06-22",
        region: "Region 4B (MIMAROPA)",
        category: "CTG Member",
        details: "Maria Reyes",
        location: "Rural Occidental Mindoro"
    }
];

// Make data available globally
window.recentActivitiesData = recentActivitiesData;

// Function to populate the recent activities table
function populateRecentActivitiesTable() {
    const tableBody = document.getElementById('recentActivityTable');

    if (tableBody) {
        tableBody.innerHTML = '';
        recentActivitiesData.forEach(activity => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.region}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.category}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${activity.details}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.location}</td>
            `;
            tableBody.appendChild(row);
        });
    }
}

// Function to initialize the activity distribution chart
function createActivityDistributionChart() {
    const ctx = document.getElementById('activityDistributionChart');
    if (!ctx) return;

    // Count categories from recent activities data
    const categories = {};
    recentActivitiesData.forEach(activity => {
        if (!categories[activity.category]) {
            categories[activity.category] = 0;
        }
        categories[activity.category]++;
    });

    // Prepare chart data
    const labels = Object.keys(categories);
    const data = Object.values(categories);
    const backgroundColors = [
        'rgba(59, 130, 246, 0.7)',   // Blue
        'rgba(16, 185, 129, 0.7)',   // Green
        'rgba(245, 158, 11, 0.7)',   // Yellow
        'rgba(239, 68, 68, 0.7)',    // Red
        'rgba(139, 92, 246, 0.7)',   // Purple
        'rgba(236, 72, 153, 0.7)'    // Pink
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Activity Count',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Count'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Category'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Export functions for use in dashboard.blade.php
window.populateRecentActivitiesTable = populateRecentActivitiesTable;
window.createActivityDistributionChart = createActivityDistributionChart;
