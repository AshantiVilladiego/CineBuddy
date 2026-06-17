<?php
include("db.php");

// Step 1: Get all distinct genres
$sql_genres = "SELECT DISTINCT genre FROM movies ORDER BY genre";
$result_genres = $conn->query($sql_genres);

$allGenres = [];

// Step 2: Split combined genres into individual ones
while($row = $result_genres->fetch_assoc()) {
    $parts = explode(',', $row['genre']); 
    foreach ($parts as $g) {
        $g = trim($g); 
        if (!in_array($g, $allGenres)) {
            $allGenres[] = $g;
        }
    }
}
sort($allGenres); 

// Step 3: Handle genre selection
$genre = isset($_GET['genre']) ? $_GET['genre'] : null;

if ($genre) {
    // OR gate logic: match any movie where the genre string contains the selected genre
    $sql_random = "SELECT * FROM movies WHERE genre LIKE '%$genre%' ORDER BY RAND() LIMIT 4";
} else {
    $sql_random = "SELECT * FROM movies ORDER BY RAND() LIMIT 4";
}
$result_random = $conn->query($sql_random);

// Collect the random movies into an array to pass securely to JavaScript for poster fetching
$randomMoviesList = [];
while($row = $result_random->fetch_assoc()) {
    $randomMoviesList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Randomizer | Feeling La-La-Lucky? 🎲</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glow-text {
            text-shadow: 0 0 30px rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans antialiased min-h-screen pb-12">

    <?php include("nav.php"); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 space-y-12">
        
        <div class="text-center md:text-left space-y-2">
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-white-400 glow-text">
                Leave it to fate.
            </h1>
            <p class="text-white-400 max-w-xl">
                Filter by a specific genre below and four fantastic movies from the IMDB Top Movies from 1980 - 2026
            </p>
        </div>

<div class="space-y-3 max-w-sm">
            <div class="flex items-center justify-between">
                <label for="genre-select" class="text-sm font-semibold uppercase tracking-wider text-gray-500">
                    Filter By Genre
                </label>
                <?php if ($genre) { ?>
                    <a href="random.php" class="text-xs font-bold text-orange-400 hover:text-green-300 transition-colors">
                        ✕ Clear Filter
                    </a>
                <?php } ?>
            </div>
            
            <form action="random.php" method="GET" class="relative group">
                <select name="genre" id="genre-select" onchange="this.form.submit()" 
                        class="w-full appearance-none bg-blue/40 border border-white/10 text-white px-5 py-4 rounded-xl glass focus:outline-none focus:ring-2 focus:ring-green-500 transition-all cursor-pointer font-medium shadow-lg hover:bg-black/60">
                    
                    <option value="" class="bg-gray-900 text-gray-300">
                        🎬 All Genres (Completely Random)
                    </option>
                    
                    <?php foreach($allGenres as $g) { ?>
                        <option value="<?php echo htmlspecialchars($g); ?>" class="bg-gray-900 text-white" <?php if($genre === $g) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($g); ?>
                        </option>
                    <?php } ?>
                    
                </select>
                
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400 group-hover:text-indigo-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </form>
        </div>

        <hr class="border-white/5 my-8">

        <div class="space-y-6">
            <h2 class="text-2xl font-bold tracking-tight">
                <?php echo $genre ? "Random <span class='text-indigo-400'>$genre</span> Picks:" : "Random Picks For You:"; ?>
            </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach($randomMoviesList as $index => $movie) { ?>
                        <div class="glass rounded-2xl overflow-hidden group hover:-translate-y-2 hover:border-indigo-500/30 transition-all duration-300 flex flex-col justify-between">
                            
                            <div class="relative aspect-[2/3] w-full bg-gray-900/50 overflow-hidden flex items-center justify-center">
                                <div id="loader-<?php echo $index; ?>" class="absolute inset-0 bg-gray-900 animate-pulse flex items-center justify-center text-xs text-gray-600">
                                    Fetching poster...
                                </div>
                                <img id="poster-<?php echo $index; ?>" src="" alt="<?php echo htmlspecialchars($movie['title']); ?>" class="w-full h-full object-cover opacity-0 transition-opacity duration-500">
                            </div>

                            <div class="p-5 space-y-3 flex-grow flex flex-col justify-between">
                                <div class="space-y-1">
                                    <h3 class="font-bold text-lg leading-snug group-hover:text-indigo-300 transition-colors line-clamp-1">
                                        <?php echo htmlspecialchars($movie['title']); ?>
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                                        <span class="px-2 py-0.5 bg-white/5 border border-white/5 rounded-md text-gray-300">
                                            <?php echo $movie['year']; ?>
                                        </span>
                                        <?php if(isset($movie['rating'])) { ?>
                                            <span class="text-amber-400">★ <?php echo number_format($movie['rating'], 1); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <p class="text-xs text-gray-400 bg-black/20 p-2 rounded-lg border border-white/5 line-clamp-1">
                                    🎬 <?php echo htmlspecialchars($movie['genre']); ?>
                                </p>
                            </div>

                        </div>
                    <?php } ?>
                </div>
        </div>

    </div>

    <script>
        const TMDB_API_KEY = '81fd073be0edefb6975b1bc6a02dbfef';
        const IMG_BASE_URL = 'https://image.tmdb.org/t/p/w400'; // w400 for highly crisp card display
        
        // Bring our current random dataset array directly into JavaScript
        const moviesToFetch = <?php echo json_encode($randomMoviesList); ?>;

        async function fetchMoviePosters() {
            for (let i = 0; i < moviesToFetch.length; i++) {
                const title = moviesToFetch[i].title;
                const year = moviesToFetch[i].year;
                
                const loaderEl = document.getElementById(`loader-${i}`);
                const imgEl = document.getElementById(`poster-${i}`);

                try {
                    // Use TMDB Search query matched with the production year for precise matching
                    const searchUrl = `https://api.themoviedb.org/3/search/movie?api_key=${TMDB_API_KEY}&query=${encodeURIComponent(title)}&primary_release_year=${year}`;
                    
                    const response = await fetch(searchUrl);
                    if (!response.ok) throw new Error("API Network issue");
                    
                    const data = await response.json();
                    
                    if (data.results && data.results.length > 0 && data.results[0].poster_path) {
                        imgEl.src = IMG_BASE_URL + data.results[0].poster_path;
                        imgEl.onload = () => {
                            imgEl.classList.remove('opacity-0'); // Transition CSS fades poster in smoothly
                            loaderEl.style.display = 'none';
                        };
                    } else {
                        // Fallback message if no official path found
                        loaderEl.textContent = "No Poster Found";
                        loaderEl.classList.remove('animate-pulse');
                    }
                } catch (error) {
                    console.error(`🚨 Error fetching poster for ${title}:`, error);
                    loaderEl.textContent = "Error Loading";
                    loaderEl.classList.remove('animate-pulse');
                }
            }
        }

        // Run as soon as page data models initialize
        if(moviesToFetch.length > 0) {
            fetchMoviePosters();
        }
    </script>
</body>
</html>