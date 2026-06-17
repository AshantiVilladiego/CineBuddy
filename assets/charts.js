const chartOptions = {
    responsive: true,
    plugins: { legend: { position: 'bottom' }, tooltip: { enabled: true } }
};

// 1. Average Rating by Decade
try {
    new Chart(document.getElementById('decadeChart'), {
        type: 'bar',
        data: { labels: decades,
                datasets: [{ label: 'Avg Rating', backgroundColor:'#4F46E5', data: avgRatingsDec }] },
        options: chartOptions
    });
} catch(e) { console.error("Crash in decadeChart", e); }

// 2. Genre Popularity
try {
    new Chart(document.getElementById('genreChart'), {
        type: 'bar',
        data: { labels: genreLabels,
                datasets: [{ label: 'Movies', backgroundColor:'#10B981', data: genreData }] },
        options: chartOptions
    });
} catch(e) { console.error("Crash in genreChart", e); }

// 3. Average Runtime by Decade
try {
    new Chart(document.getElementById('runtimeChart'), {
        type: 'line',
        data: { labels: runtimeDecades,
                datasets: [{ label: 'Avg Runtime', borderColor:'#F59E0B', data: avgRuntimes }] },
        options: chartOptions
    });
} catch(e) { console.error("Crash in runtimeChart", e); }

// 4. Rating Distribution (Doughnut)
try {
    new Chart(document.getElementById('ratingChart'), {
        type: 'doughnut',
        data: {
            labels: ratingBins,
            datasets: [{
                label: 'Movie Count',
                data: ratingCounts,
                backgroundColor: [
                    '#6366F1', '#10B981', '#F59E0B', '#EF4444',
                    '#3B82F6', '#22C55E', '#8B5CF6', '#EC4899',
                    '#14B8A6', '#F97316'
                ]
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' }, tooltip: { enabled: true } } }
    });
} catch(e) { console.error("Crash in ratingChart", e); }


// 5. Genre Co-occurrence Heatmap (Matrix)
// 5. Top 10 Genre Mashups (Horizontal Bar)
try {
    new Chart(document.getElementById('genreCoChart'), {
        type: 'bar',
        data: {
            labels: mashupLabels,
            datasets: [{
                label: 'Movies with this Combo',
                data: mashupCounts,
                backgroundColor: '#8B5CF6', // A nice vibrant violet
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // Flips it sideways
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    title: { display: true, text: 'Number of Movies' },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });
} catch (e) { 
    console.error("🚨 CRASH IN MASHUP CHART:", e); 
}
// --- NEW VISUALIZATIONS (BULLETPROOF VERSION) ---

// 6. Scatter Chart
try {
    new Chart(document.getElementById('scatterChart'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Movies',
                data: scatterData,
                backgroundColor: '#EC4899', 
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => `${ctx.raw.title}: ${ctx.raw.x} mins, ${ctx.raw.y} ⭐` } }
            },
            scales: {
                x: { title: { display: true, text: 'Runtime (Minutes)' } },
                y: { title: { display: true, text: 'Rating (0-10)' } }
            }
        }
    });
} catch (error) { console.error("🚨 CRASH IN SCATTER CHART:", error); }

// 7. Horizontal Bar Chart
try {
    new Chart(document.getElementById('top10Chart'), {
        type: 'bar',
        data: {
            labels: top10Titles,
            datasets: [{
                label: 'Rating',
                data: top10Ratings,
                backgroundColor: '#F59E0B',
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', 
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { min: 6, max: 10 } }
        }
    });
} catch (error) { console.error("🚨 CRASH IN TOP 10 CHART:", error); }

// 8. Polar Area Chart
try {
    new Chart(document.getElementById('polarRuntimeChart'), {
        type: 'polarArea',
        data: {
            labels: polarLabels,
            datasets: [{
                label: 'Avg Runtime',
                data: polarData,
                backgroundColor: [
                    'rgba(99, 102, 241, 0.6)', 'rgba(16, 185, 129, 0.6)',
                    'rgba(245, 158, 11, 0.6)', 'rgba(239, 68, 68, 0.6)',
                    'rgba(59, 130, 246, 0.6)', 'rgba(34, 197, 94, 0.6)',
                    'rgba(139, 92, 246, 0.6)', 'rgba(236, 72, 153, 0.6)'
                ]
            }]
        },
        options: chartOptions
    });
} catch (error) { console.error("🚨 CRASH IN POLAR CHART:", error); }