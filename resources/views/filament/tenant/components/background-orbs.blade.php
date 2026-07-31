{{-- Google Fonts: Inter --}}
@once
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endonce

<!-- Dynamic Animated Background Orbs for Frost UI (Light + Dark adaptive) -->
<div class="fixed inset-0 pointer-events-none overflow-hidden z-[0]" aria-hidden="true">
    <!-- Top-Left Intense Orange Glow Orb -->
    <div class="absolute -top-[10%] -left-[10%] w-[45vw] h-[45vw] sm:w-[400px] sm:h-[400px] md:w-[600px] md:h-[600px] bg-[#FF6600] rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[70px] sm:blur-[100px] md:blur-[140px] opacity-[0.12] dark:opacity-[0.25] animate-orb-1"></div>
    
    <!-- Bottom-Right Red-Orange Glow Orb -->
    <div class="absolute -bottom-[10%] -right-[10%] w-[50vw] h-[50vw] sm:w-[450px] sm:h-[450px] md:w-[650px] md:h-[650px] bg-[#FF4500] rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[80px] sm:blur-[110px] md:blur-[160px] opacity-[0.09] dark:opacity-[0.2] animate-orb-2"></div>
    
    <!-- Center-Left Soft Warm Glow Orb -->
    <div class="absolute top-[30%] -left-[5%] w-[35vw] h-[35vw] sm:w-[350px] sm:h-[350px] md:w-[500px] md:h-[500px] bg-[#FF8C00] rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[60px] sm:blur-[90px] md:blur-[130px] opacity-[0.08] dark:opacity-[0.15] animate-orb-3"></div>
    
    <!-- Top-Right Subtle Highlight Orb -->
    <div class="absolute top-[10%] right-[10%] w-[30vw] h-[30vw] sm:w-[300px] sm:h-[300px] md:w-[400px] md:h-[400px] bg-[#FFA07A] rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[50px] sm:blur-[70px] md:blur-[100px] opacity-[0.07] dark:opacity-[0.12] animate-orb-4"></div>
</div>
