// Initialize all dashboard components
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard initialization started');

    // Check if chart objects are available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    // Check if the activity chart container exists
    const activityChartEl = document.getElementById('activityDistributionChart');
    const isoChartEl = document.getElementById('isoChart');
    const regionChartEl = document.getElementById('regionChart');

    if (!activityChartEl) {
        console.error('Activity chart container not found');
    }

    if (!isoChartEl) {
        console.error('ISO chart container not found');
    }

    if (!regionChartEl) {
        console.error('Region chart container not found');
    }

    // Initialize the recent activities data table
    if (window.populateRecentActivitiesTable) {
        console.log('Initializing recent activities table');
        window.populateRecentActivitiesTable();
    } else {
        console.error('Recent activities table function not found');
    }

    // Initialize the activity distribution chart
    if (window.createActivityDistributionChart) {
        console.log('Initializing activity distribution chart');
        window.createActivityDistributionChart();
    } else {
        console.error('Activity distribution chart function not found');
    }

    // Check if recent activities data is available
    if (!window.recentActivitiesData) {
        console.error('Recent activities data not found');
    } else {
        console.log('Recent activities data loaded with ' + window.recentActivitiesData.length + ' items');
    }

    console.log('Dashboard initialization completed');
});
