const TMDB_API_KEY = '81fd073be0edefb6975b1bc6a02dbfef';
const IMG_BASE_URL = 'https://image.tmdb.org/t/p/w200'; 

async function spawnFloatingPosters() {
    try {
        // Limit to the top 20 pages (the absolute best "Letterboxd-style" cinema)
        const randomPage = Math.floor(Math.random() * 20) + 1;
        
        // CHANGED: swapped 'popular' for 'top_rated'
        const TMDB_URL = `https://api.themoviedb.org/3/movie/top_rated?api_key=${TMDB_API_KEY}&language=en-US&page=${randomPage}`;
        
        const response = await fetch(TMDB_URL);
        if (!response.ok) throw new Error("Network response was not ok");
        
        const data = await response.json();
        const container = document.getElementById('poster-bg');

        if (!container) return;

        // Loop through the 20 masterpiece movies returned by the API
        data.results.forEach((movie) => {
            if (!movie.poster_path) return; 

            const img = document.createElement('img');
            img.src = IMG_BASE_URL + movie.poster_path;
            img.className = 'floating-poster';

            const leftPos = Math.random() * 95; 
            const animDuration = 25 + Math.random() * 30; 
            const animDelay = Math.random() * -40; 

            img.style.left = `${leftPos}vw`;
            img.style.animationDuration = `${animDuration}s`;
            img.style.animationDelay = `${animDelay}s`;

            container.appendChild(img);
        });

    } catch (error) {
        console.error("🚨 Failed to fetch TMDb posters:", error);
    }
}

spawnFloatingPosters();