<?php
include("db.php");

// Average rating by decade
$sql_decade = "SELECT FLOOR(year/10)*10 AS decade, AVG(rating) AS avg_rating
               FROM movies GROUP BY decade ORDER BY decade";
$result_decade = $conn->query($sql_decade);
$decades = []; $avgRatingsDecade = [];
while($row = $result_decade->fetch_assoc()) {
    $decades[] = $row['decade'];
    $avgRatingsDecade[] = round($row['avg_rating'],2);
}

// Genre popularity
$sql_genre = "SELECT genre FROM movies";
$result_genre = $conn->query($sql_genre);
$genreCounts = [];
while($row = $result_genre->fetch_assoc()) {
    $parts = explode(',', $row['genre']);
    foreach ($parts as $g) {
        $g = trim($g);
        if (!isset($genreCounts[$g])) $genreCounts[$g] = 0;
        $genreCounts[$g]++;
    }
}
$genreLabels = array_keys($genreCounts);
$genreData   = array_values($genreCounts);

// Runtime trends
$sql_runtime = "SELECT FLOOR(year/10)*10 AS decade, AVG(runtime_minutes) AS avg_runtime
                FROM movies GROUP BY decade ORDER BY decade";
$result_runtime = $conn->query($sql_runtime);
$runtimeDecades = []; $avgRuntimes = [];
while($row = $result_runtime->fetch_assoc()) {
    $runtimeDecades[] = $row['decade'];
    $avgRuntimes[] = round($row['avg_runtime'],1);
}

// Rating distribution
$sql_ratings = "SELECT ROUND(rating) AS rating_bin, COUNT(*) AS count 
                FROM movies GROUP BY rating_bin ORDER BY rating_bin";
$result_ratings = $conn->query($sql_ratings);
$ratingBins = []; $ratingCounts = [];
while($row = $result_ratings->fetch_assoc()) {
    $ratingBins[] = $row['rating_bin'];
    $ratingCounts[] = $row['count'];
}

// Decade genre mix
$sql_decade_genre = "SELECT FLOOR(year/10)*10 AS decade, genre FROM movies";
$result_decade_genre = $conn->query($sql_decade_genre);
$decadeGenres = [];
while($row = $result_decade_genre->fetch_assoc()) {
    $decade = $row['decade'];
    $parts = explode(',', $row['genre']);
    foreach($parts as $g) {
        $g = trim($g);
        if (!isset($decadeGenres[$decade][$g])) $decadeGenres[$decade][$g] = 0;
        $decadeGenres[$decade][$g]++;
    }
}
$decadeLabelsMix = array_keys($decadeGenres);
$genreLabelsMix = array_unique(array_merge(...array_map('array_keys',$decadeGenres)));

// Average rating by genre
$sql_genres_ratings = "SELECT genre, rating FROM movies";
$result_genres_ratings = $conn->query($sql_genres_ratings);
$genreRatings = [];
foreach($result_genres_ratings as $row) {
    $parts = explode(',', $row['genre']);
    foreach($parts as $g) {
        $g = trim($g);
        if (!isset($genreRatings[$g])) $genreRatings[$g] = [];
        $genreRatings[$g][] = $row['rating'];
    }
}
$avgRatingsByGenre = [];
foreach($genreRatings as $g => $arr) {
    $avgRatingsByGenre[$g] = round(array_sum($arr)/count($arr),2);
}

// Runtime distribution
$sql_runtime_dist = "SELECT FLOOR(runtime_minutes/30)*30 AS runtime_bin, COUNT(*) AS count 
                     FROM movies GROUP BY runtime_bin ORDER BY runtime_bin";
$result_runtime_dist = $conn->query($sql_runtime_dist);
$runtimeBins = []; $runtimeCounts = [];
while($row = $result_runtime_dist->fetch_assoc()) {
    $runtimeBins[] = $row['runtime_bin']."-".($row['runtime_bin']+29)." mins";
    $runtimeCounts[] = $row['count'];
}

// Movie count by year
$sql_year_count = "SELECT year, COUNT(*) AS count FROM movies GROUP BY year ORDER BY year";
$result_year_count = $conn->query($sql_year_count);
$years = []; $yearCounts = [];
while($row = $result_year_count->fetch_assoc()) {
    $years[] = $row['year'];
    $yearCounts[] = $row['count'];
}

// Genre Co-Occurrence Matrix Logic
// Genre Co-Occurrence Logic (Upgraded to Top Mashups)
$sql_genres = "SELECT genre FROM movies";
$result_genres = $conn->query($sql_genres);

$coOccurrences = [];
while($row = $result_genres->fetch_assoc()) {
    $parts = array_map('trim', explode(',', $row['genre']));
    for ($i = 0; $i < count($parts); $i++) {
        for ($j = $i+1; $j < count($parts); $j++) {
            $pair = [$parts[$i], $parts[$j]];
            sort($pair);
            $key = implode('-', $pair);
            if (!isset($coOccurrences[$key])) $coOccurrences[$key] = 0;
            $coOccurrences[$key]++;
        }
    }
}

// Sort from highest to lowest and grab the Top 10 Mashups
arsort($coOccurrences);
$topMashups = array_slice($coOccurrences, 0, 10, true);

$mashupLabels = [];
$mashupCounts = [];
foreach($topMashups as $pair => $count) {
    // Replace the dash with a nice plus sign for the chart (e.g., "Action + Sci-Fi")
    $mashupLabels[] = str_replace('-', ' + ', $pair); 
    $mashupCounts[] = $count;

}

// --- NEW VISUALIZATIONS ---

// 1. Scatter Plot: Runtime vs Rating
$sql_scatter = "SELECT title, runtime_minutes, rating FROM movies WHERE runtime_minutes > 0 AND rating > 0";
$result_scatter = $conn->query($sql_scatter);
$scatterData = [];
while($row = $result_scatter->fetch_assoc()) {
    $scatterData[] = [
        'x' => (int)$row['runtime_minutes'],
        'y' => (float)$row['rating'],
        'title' => $row['title'] 
    ];
}

// 2. Top 10 Rated Movies
$sql_top10 = "SELECT title, rating FROM movies ORDER BY rating DESC LIMIT 10";
$result_top10 = $conn->query($sql_top10);
$top10Titles = []; $top10Ratings = [];
while($row = $result_top10->fetch_assoc()) {
    $top10Titles[] = $row['title'];
    $top10Ratings[] = (float)$row['rating'];
}

// 3. Average Runtime by Genre (Polar Area)
$sql_genres_runtime = "SELECT genre, runtime_minutes FROM movies";
$result_genres_runtime = $conn->query($sql_genres_runtime);
$genreRuntimes = [];
foreach($result_genres_runtime as $row) {
    $parts = explode(',', $row['genre']);
    foreach($parts as $g) {
        $g = trim($g);
        if (!isset($genreRuntimes[$g])) $genreRuntimes[$g] = [];
        $genreRuntimes[$g][] = $row['runtime_minutes'];
    }
}
$avgRuntimeByGenre = [];
foreach($genreRuntimes as $g => $arr) {
    if(count($arr) >= 2) { 
        $avgRuntimeByGenre[$g] = round(array_sum($arr)/count($arr), 1);
    }
}
arsort($avgRuntimeByGenre); 
$polarLabels = array_keys(array_slice($avgRuntimeByGenre, 0, 8));
$polarData = array_values(array_slice($avgRuntimeByGenre, 0, 8));

?>
<!DOCTYPE html>
<html>
<head>
    
    <title>Movie Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.1.0/dist/chartjs-chart-matrix.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        #poster-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1; overflow: hidden; pointer-events: none; opacity: 0.15; 
        }
        .floating-poster {
            position: absolute; width: 150px; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            animation: floatUp linear infinite; bottom: -250px; 
        }
        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.8) rotate(-5deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-120vh) scale(1.3) rotate(5deg); opacity: 0; }
        }
    </style>
</head>
<body style="font-family:sans-serif; margin:20px; position:relative;">
    
    <div id="poster-bg"></div>

    <?php include("nav.php"); ?>

    <div class="dashboard">
        <h1> IMDB TOP MOVIES FROM 1980-2026 ANALYTICS </h1>

        <div class="grid">
            <div class="card">
                <h2>Average Rating by Decade</h2>
                <canvas id="decadeChart"></canvas>
            </div>

            <div class="card">
                <h2>Genre Popularity</h2>
                <canvas id="genreChart"></canvas>
            </div>

            <div class="card md:col-span-1">
                <h2>Longest Genres (Avg Runtime)</h2>
                <canvas id="polarRuntimeChart"></canvas>
            </div>


            <div class="card">
                <h2>Rating Distribution</h2>
                <canvas id="ratingChart"></canvas>
            </div>

            <div class="card md:col-span-2">
                <h2>Top 10 Genre Mashups</h2>
                <canvas id="genreCoChart"></canvas>
            </div>
            
            <div class="card md:col-span-2"> 
                <h2>Time vs. Quality (Runtime / Rating)</h2>
                <canvas id="scatterChart"></canvas>
            </div>

            <div class="card md:col-span-2">
                <h2>Hall of Fame: Top 10 Highest Rated</h2>
                <canvas id="top10Chart"></canvas>
            </div>
                        
            <div class="card md:col-span-2">
                <h2>Average Runtime by Decade</h2>
                <canvas id="runtimeChart"></canvas>
            </div>

        </div>
    </div> <script>
        const decades        = <?php echo json_encode($decades); ?>;
        const avgRatingsDec  = <?php echo json_encode($avgRatingsDecade); ?>;
        const genreLabels    = <?php echo json_encode($genreLabels); ?>;
        const genreData      = <?php echo json_encode($genreData); ?>;
        const runtimeDecades = <?php echo json_encode($runtimeDecades); ?>;
        const avgRuntimes    = <?php echo json_encode($avgRuntimes); ?>;
        const ratingBins     = <?php echo json_encode($ratingBins); ?>;
        const ratingCounts   = <?php echo json_encode($ratingCounts); ?>;
        const decadeLabelsMix= <?php echo json_encode($decadeLabelsMix); ?>;
        const genreLabelsMix = <?php echo json_encode(array_values($genreLabelsMix)); ?>;
        const decadeGenres   = <?php echo json_encode($decadeGenres); ?>;
        const avgGenreLabels = <?php echo json_encode(array_keys($avgRatingsByGenre)); ?>;
        const avgGenreData   = <?php echo json_encode(array_values($avgRatingsByGenre)); ?>;
        const runtimeBins    = <?php echo json_encode($runtimeBins); ?>;
        const runtimeCounts  = <?php echo json_encode($runtimeCounts); ?>;
        const years          = <?php echo json_encode($years); ?>;
        const yearCounts     = <?php echo json_encode($yearCounts); ?>;
        
// Top Genre Mashup Data
        const mashupLabels = <?php echo json_encode($mashupLabels); ?>;
        const mashupCounts = <?php echo json_encode($mashupCounts); ?>;
        
        // NEW CHART DATA
        const scatterData = <?php echo json_encode($scatterData); ?>;
        const top10Titles = <?php echo json_encode($top10Titles); ?>;
        const top10Ratings = <?php echo json_encode($top10Ratings); ?>;
        const polarLabels = <?php echo json_encode($polarLabels); ?>;
        const polarData = <?php echo json_encode($polarData); ?>;
    </script>

    <script src="assets/charts.js?v=4.0"></script>
    <script src="assets/posters.js"></script> 
</body>
</html>