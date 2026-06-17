<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBuddy | The Ultimate Cinema Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* The Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Neon Glow Text */
        .glow-text {
            text-shadow: 0 0 30px rgba(99, 102, 241, 0.6);
        }

        /* Floating Posters CSS (Recycled & Tweaked for Dark Mode!) */
        #poster-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.25; 
        }
        .floating-poster {
            position: absolute;
            width: 180px; 
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.9);
            animation: floatUp linear infinite;
            bottom: -300px;
        }
        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.8) rotate(-5deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-120vh) scale(1.2) rotate(5deg); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <div id="poster-bg"></div>

    <div class="relative z-10 flex flex-col min-h-screen">
        
        <?php include("nav.php"); ?>
        
        <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 mt-12 mb-20">
            <div class="max-w-6xl mx-auto text-center space-y-12">
                
                <h1 class="text-6xl md:text-8xl font-black tracking-tighter">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-green-400 to-blue-400">
                        CineBuddy
                    </span>
                </h1>
                <div class="animate-bounce inline-flex items-center rounded-full px-5 py-2 text-sm font-semibold text-indigo-300 ring-1 ring-inset ring-indigo-500/30 glass">
                    ✨ Your personal AI film curator
                </div>

                <p class="mx-auto max-w-2xl text-xl text-gray-400 sm:text-2xl font-bold">
                    Dive into decades of cinematic history. Analyze the trends, explore the data, and let our AI find your next favorite masterpiece.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mt-10">
                    
                    <a href="dashboard.php" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-blue transition-all duration-300 bg-green-600 rounded-xl focus:outline-none hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_40px_rgba(79,70,229,0.6)]">
                        <span class="mr-3 text-xl"></span>Enter the Dashboard
                    </a>
                    
                    <a href="recommend.php" class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-orange transition-all duration-300 bg-orange-600 rounded-xl focus:outline-none hover:bg-blue-500 hover:scale-105 hover:shadow-[0_0_40px_rgba(236,72,153,0.4)]">
                        <span class="mr-3 text-xl"></span>Ask the AI
                    </a>
                    
                </div>
        </main>
        
<footer class="mt-20 py-6 border-t border-white/5 bg-black/40 backdrop-blur-lg relative z-10">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
        
<p class="text-sm text-gray-400 font-light text-center md:text-left">
            Built by <span class="font-bold text-green-400 glow-text">Ashanti</span> 
            <span class="hidden md:inline mx-2 opacity-30">|</span> 
            <br class="md:hidden">
            <span class="opacity-75">The person behind your screen watching u 👀</span>
            <span class="block md:inline mt-1 md:mt-0 md:ml-2 text-xs font-mono text-orange-400/70 tracking-tight">
                [PHP, Tailwind CSS, JS, MySQL]
            </span>
        </p>

        <div class="flex items-center justify-center gap-3">

        <a href="mailto:aaashantilouise@email.com" target="_blank" title="Email" 
   class="p-2.5 rounded-lg bg-orange-400 text-black hover:bg-white/5 hover:border hover:border-white/10 hover:text-orange-400 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(251,146,60,0.4)]">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
</a>
            
<a href="https://www.linkedin.com/in/ashanti-louise-villadiego/" target="_blank" title="LinkedIn" 
   class="p-2.5 rounded-lg bg-green-500 text-white hover:bg-white/5 hover:border hover:border-white/10 hover:text-green-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(34,197,94,0.4)]">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
</a>

<a href="https://github.com/AshantiVilladiego" target="_blank" title="GitHub" 
   class="p-2.5 rounded-lg bg-blue-600 text-black hover:bg-white/5 hover:border hover:border-white/10 hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(37,99,235,0.4)]">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
</a>



        </div>
    </div>
</footer>

    <script src="assets/posters.js"></script>

</body>
</html>