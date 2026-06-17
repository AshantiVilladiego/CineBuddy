<?php
// Get the current file name to determine which tab is active
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* 2. Apply Inter Bold as the universal font across your pages */
    html, body {
        font-family: 'Inter', sans-serif !important;
        font-weight: 700 !important;
    }

    /* Universal Glassmorphism Classes for the Nav */
    .nav-glass {
        background: rgba(17, 24, 39, 0.7); /* Dark gray tint */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .glow-text-nav {
        text-shadow: 0 0 20px rgba(99, 102, 241, 0.8);
    }
</style>

<nav class="sticky top-0 z-50 w-full nav-glass shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <div class="flex-shrink-0">
                <a href="index.php" class="text-2xl font-black tracking-tighter">
                    Your buddy that will stay until the credits with you.
                </a>
            </div>
            
            <div class="flex items-center space-x-1 sm:space-x-4">
                
                <a href="index.php" 
                   class="px-3 py-2 rounded-lg text-sm transition-all duration-200 
                   <?php echo ($currentPage === 'index.php') 
                       ? 'bg-orange-400 text-white' 
                       : 'text-gray-300 hover:text-white hover:bg-white/10'; ?>">
                    HOME
                </a>
                
                <a href="dashboard.php" 
                   class="px-3 py-2 rounded-lg text-sm transition-all duration-200 
                   <?php echo ($currentPage === 'dashboard.php') 
                       ? 'bg-blue-400 text-white' 
                       : 'text-gray-300 hover:text-white hover:bg-white/10'; ?>">
                    DASHBOARD
                </a>
                
                <a href="random.php" 
                   class="px-3 py-2 rounded-lg text-sm transition-all duration-200 
                   <?php echo ($currentPage === 'random.php') 
                       ? 'bg-green-400 text-white' 
                       : 'text-gray-300 hover:text-white hover:bg-white/10'; ?>">
                    RANDOMIZER
                </a>

                <a href="recommend.php" 
                   class="px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-all duration-200 
                   <?php echo ($currentPage === 'recommend.php') 
                       ? 'text-white-400 bg-gray-400' 
                       : 'text-white hover:bg-gray-500  hover:scale-105'; ?>">
                    AI Chat
                </a>
            </div>

        </div>
    </div>
</nav>