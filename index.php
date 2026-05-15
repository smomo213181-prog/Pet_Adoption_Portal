<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=index.php');
    exit;
}

require_once 'db.php';

// Fetch pets
$stmt = $pdo->query('SELECT * FROM pets ORDER BY created_at DESC');
$pets = $stmt->fetchAll();

$user_logged_in = true;
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>The Editorial Sanctuary | Pet Adoption Portal</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,300;1,400;1,500;1,600;1,700;1,800&amp;family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container": "#f0f2f2",
                    "primary-fixed": "#c0eef3",
                    "error": "#ba1a1a",
                    "on-tertiary-container": "#fffbff",
                    "primary-container": "#3ba9b1",
                    "surface-variant": "#dfe4e4",
                    "surface-bright": "#f8fafa",
                    "on-secondary": "#ffffff",
                    "on-primary-container": "#f5feff",
                    "on-tertiary-fixed-variant": "#643f00",
                    "on-surface": "#191c1d",
                    "on-tertiary": "#ffffff",
                    "error-container": "#ffdad6",
                    "secondary": "#a66900",
                    "tertiary-fixed": "#ffddb6",
                    "inverse-primary": "#70d6df",
                    "primary-fixed-dim": "#70d6df",
                    "on-background": "#191c1d",
                    "surface-dim": "#d8dada",
                    "secondary-fixed-dim": "#ffb783",
                    "outline-variant": "#bdc9ca",
                    "on-surface-variant": "#3d494a",
                    "surface-container-lowest": "#ffffff",
                    "surface": "#f8fafa",
                    "tertiary": "#815200",
                    "on-error": "#ffffff",
                    "on-primary": "#ffffff",
                    "surface-container-high": "#e6e8e9",
                    "surface-tint": "#006970",
                    "primary": "#00676e",
                    "on-primary-fixed-variant": "#004f54",
                    "inverse-on-surface": "#eff1f1",
                    "secondary-container": "#ffb87a",
                    "background": "#fcfdfd",
                    "outline": "#6d797a",
                    "on-tertiary-fixed": "#2a1800",
                    "on-error-container": "#93000a",
                    "on-primary-fixed": "#002022",
                    "tertiary-fixed-dim": "#ffb95a",
                    "surface-container-low": "#f2f4f4",
                    "surface-container-highest": "#e1e3e3",
                    "inverse-surface": "#2e3131",
                    "on-secondary-fixed": "#301400"
            },
            "borderRadius": {
                    "DEFAULT": "1rem",
                    "lg": "2rem",
                    "xl": "3rem",
                    "full": "9999px"
            },
            "fontFamily": {
                    "headline": ["Plus Jakarta Sans"],
                    "body": ["Be Vietnam Pro"],
                    "label": ["Plus Jakarta Sans"]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #fcfdfd;
            color: #191c1d;
        }
        h1, h2, h3, .font-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-background text-on-surface">
<!-- TopNavBar Shell -->
<nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl flex justify-between items-center px-8 py-4 max-w-full mx-auto" style="box-shadow: 0px 12px 32px rgba(25, 28, 29, 0.04);">
<div class="flex items-center gap-12">
<span class="text-2xl font-bold tracking-tight text-primary dark:text-primary-fixed">The Editorial Sanctuary</span>
<div class="hidden md:flex items-center gap-8 font-label text-sm tracking-tight">
<a class="text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold transition-colors duration-300" href="index.php">Home</a>
<a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300" href="favorites.php">Favorites</a>
<a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300" href="#">My Pets</a>
<a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300" href="add_pet.php">Add Pet</a>
<a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300" href="#">Requests</a>
<a class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300" href="#">Profile</a>
</div>
</div>
<div class="flex items-center gap-6">
<!-- Mode Toggle -->
<div class="flex items-center bg-surface-container rounded-full p-1 shadow-sm">
<button class="px-4 py-1.5 rounded-full text-xs font-bold bg-primary text-on-primary transition-transform scale-95 active:scale-90">Adopt Mode</button>
<button class="px-4 py-1.5 rounded-full text-xs font-bold text-on-surface-variant hover:text-primary transition-colors">Rehome Mode</button>
</div>
<div class="flex items-center gap-4 text-primary dark:text-primary-fixed"><?php if ($user_logged_in): ?>
<span class="text-sm font-medium">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
<button class="hover:text-primary-container transition-colors duration-300" onclick="window.location.href='logout.php'">
<span class="material-symbols-outlined" data-icon="logout">logout</span>
</button>
<?php else: ?>
<button class="hover:text-primary-container transition-colors duration-300" onclick="window.location.href='login.php'">
<span class="material-symbols-outlined" data-icon="login">login</span>
</button>
<?php endif; ?><button class="hover:text-primary-container transition-colors duration-300">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
</button>
<button class="hover:text-primary-container transition-colors duration-300">
<span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
</button>
</div>
</div>
</nav>
<main class="pt-24">
<!-- Hero Section -->
<section class="relative px-8 py-20 overflow-hidden">
<div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-12">
<div class="flex-1 space-y-6 z-10">
<h1 class="text-6xl md:text-7xl font-bold tracking-tighter text-on-surface leading-none">
                    Find Your Perfect <br/><span class="text-primary-container">Companion 🐾</span>
</h1>
<p class="text-lg text-on-surface-variant max-w-lg font-body leading-relaxed">
                    Your journey to a lifelong connection starts here. Browse through our curated collection of sanctuary residents.
                </p>
<div class="flex items-center gap-4 pt-4">
<button class="bg-gradient-to-tr from-primary to-primary-container text-on-primary px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-primary/20 transition-all scale-95 active:scale-90">
                        Browse Pets
                    </button>
<button class="border border-outline-variant/30 text-primary px-8 py-4 rounded-full font-bold hover:bg-surface-container transition-colors">
                        Our Process
                    </button>
</div>
</div>
<div class="flex-1 relative">
<div class="w-full aspect-square rounded-xl overflow-hidden bg-surface-container-high relative">
<img alt="Fluffy puppy" class="w-full h-full object-cover" src="images/home-hero.jpg"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
</div>
<!-- Signature Component Overlay -->
<div class="absolute -bottom-8 -left-8 bg-secondary-container p-6 rounded-lg max-w-xs shadow-xl hidden md:block">
<div class="flex items-center gap-3 mb-2">
<span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label text-sm font-bold text-on-secondary-container">Pet of the Day</span>
</div>
<p class="text-on-secondary-container font-headline font-semibold text-lg">Snowball is waiting for a forever home.</p>
</div>
</div>
</div>
</section>
<!-- Quick Action Cards (Bento style) -->
<section class="px-8 py-16 bg-surface-container-low">
<div class="max-w-7xl mx-auto">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Browse Pets -->
<div class="bg-surface-container-lowest p-8 rounded-lg flex flex-col justify-between group cursor-pointer transition-transform hover:-translate-y-1">
<div class="mb-8">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-6">
<span class="material-symbols-outlined" data-icon="search">search</span>
</div>
<h3 class="text-2xl font-bold mb-2">Browse Pets</h3>
<p class="text-on-surface-variant font-body">Explore hundreds of animals looking for a second chance.</p>
</div>
<span class="text-primary font-bold flex items-center gap-2 group-hover:gap-4 transition-all">Start exploring <span class="material-symbols-outlined">arrow_forward</span></span>
</div>
<!-- View Favorites -->
<div class="bg-surface-container-lowest p-8 rounded-lg flex flex-col justify-between group cursor-pointer transition-transform hover:-translate-y-1">
<div class="mb-8">
<div class="w-12 h-12 rounded-full bg-secondary-container/10 flex items-center justify-center text-secondary mb-6">
<span class="material-symbols-outlined" data-icon="favorite" style="font-variation-settings: 'FILL' 1;">favorite</span>
</div>
<h3 class="text-2xl font-bold mb-2">View Favorites</h3>
<p class="text-on-surface-variant font-body">Access your shortlisted companions and compare profiles.</p>
</div>
<span class="text-secondary font-bold flex items-center gap-2 group-hover:gap-4 transition-all">My wishlist <span class="material-symbols-outlined">arrow_forward</span></span>
</div>
<!-- My Requests -->
<div class="bg-surface-container-lowest p-8 rounded-lg flex flex-col justify-between group cursor-pointer transition-transform hover:-translate-y-1">
<div class="mb-8">
<div class="w-12 h-12 rounded-full bg-tertiary-container/10 flex items-center justify-center text-tertiary mb-6">
<span class="material-symbols-outlined" data-icon="description">description</span>
</div>
<h3 class="text-2xl font-bold mb-2">My Requests</h3>
<p class="text-on-surface-variant font-body">Track the status of your adoption applications in real-time.</p>
</div>
<span class="text-tertiary font-bold flex items-center gap-2 group-hover:gap-4 transition-all">Check status <span class="material-symbols-outlined">arrow_forward</span></span>
</div>
</div>
</div>
</section>
<!-- Main Content: Filters & Pet Grid -->
<section class="px-8 py-24 bg-background">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
<div>
<h2 class="text-4xl font-bold tracking-tight mb-2">Available Residents</h2>
<p class="text-on-surface-variant">Find a matching personality for your lifestyle</p>
</div>
<!-- Filter Bar -->
<div class="w-full md:w-auto flex flex-wrap gap-3 bg-surface-container p-2 rounded-lg">
<select class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 cursor-pointer">
<option>Pet Type</option>
<option>Dog</option>
<option>Cat</option>
</select>
<select class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 cursor-pointer">
<option>Breed</option>
</select>
<select class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 cursor-pointer">
<option>Age</option>
<option>Puppy</option>
<option>Adult</option>
</select>
<button class="bg-primary text-on-primary px-4 py-2 rounded-full text-sm font-bold ml-2">Apply Filters</button>
</div>
</div>
<!-- Pet Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
<?php if (empty($pets)): ?>
    <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-12 text-center text-slate-600 shadow-sm">
        <p class="text-xl font-semibold mb-2">No pets are available yet.</p>
        <p class="text-sm">Please add a pet or check back later.</p>
    </div>
<?php else: ?>
    <?php foreach ($pets as $pet): ?>
<div class="group">
<div class="relative aspect-[4/5] rounded-lg overflow-hidden mb-4 bg-surface-container-high">
<img alt="<?php echo htmlspecialchars($pet['name']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="<?php echo htmlspecialchars($pet['image']); ?>"/>
<button class="favorite-btn absolute top-4 right-4 w-10 h-10 rounded-full bg-white/80 backdrop-blur shadow-sm flex items-center justify-center <?php echo $user_logged_in ? 'text-outline hover:text-error' : 'text-outline/50 cursor-not-allowed'; ?>" data-pet-id="<?php echo $pet['id']; ?>" <?php echo !$user_logged_in ? 'disabled' : ''; ?>>
<span class="material-symbols-outlined" data-icon="favorite">favorite</span>
</button>
</div>
<div class="space-y-1">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold"><?php echo htmlspecialchars($pet['name']); ?></h3>
<span class="text-xs font-bold uppercase tracking-wider text-secondary px-2 py-1 bg-secondary-fixed rounded-full"><?php echo htmlspecialchars(ucfirst($pet['age_category'])); ?></span>
</div>
<p class="text-on-surface-variant text-sm font-medium"><?php echo htmlspecialchars($pet['breed'] ? $pet['breed'] : 'Mixed Breed'); ?> • <?php echo htmlspecialchars(ucfirst($pet['gender'])); ?></p>
<div class="flex items-center gap-1 text-outline text-xs mt-2">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span><?php echo htmlspecialchars($pet['location']); ?></span>
</div>
<button class="w-full mt-4 py-3 rounded-full border border-outline-variant/20 font-bold text-primary hover:bg-primary hover:text-on-primary transition-all">View Details</button>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</section>
<!-- Rehome Mode Stats (Visual Indicator) -->
<section class="px-8 py-24 bg-surface-container-low hidden">
<div class="max-w-7xl mx-auto">
<div class="flex items-center gap-4 mb-12">
<h2 class="text-4xl font-bold tracking-tight">Rehoming Dashboard</h2>
<span class="px-3 py-1 bg-primary text-on-primary text-xs font-bold rounded-full">Active Mode</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-surface-container-lowest p-10 rounded-lg">
<p class="font-label text-sm uppercase tracking-widest text-on-surface-variant mb-2">Total Pets Posted</p>
<p class="text-5xl font-bold text-primary">12</p>
</div>
<div class="bg-surface-container-lowest p-10 rounded-lg">
<p class="font-label text-sm uppercase tracking-widest text-on-surface-variant mb-2">Pending Requests</p>
<p class="text-5xl font-bold text-secondary">08</p>
</div>
<div class="bg-surface-container-lowest p-10 rounded-lg">
<p class="font-label text-sm uppercase tracking-widest text-on-surface-variant mb-2">Approved Adoptions</p>
<p class="text-5xl font-bold text-tertiary">142</p>
</div>
</div>
</div>
</section>
</main>
<!-- Footer Shell -->
<footer class="w-full py-12 px-8 bg-slate-50 dark:bg-slate-950">
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-center border-t border-slate-100 dark:border-slate-800 pt-12">
<div>
<span class="text-xl font-bold text-primary dark:text-primary-fixed block mb-2">The Editorial Sanctuary</span>
<p class="text-slate-500 dark:text-slate-500 text-sm font-body max-w-xs">Connecting loving homes with pets who have a story to tell.</p>
</div>
<div class="flex flex-wrap gap-8 justify-center font-label text-sm">
<a class="text-slate-500 dark:text-slate-500 hover:text-primary underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">About</a>
<a class="text-slate-500 dark:text-slate-500 hover:text-primary underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Contact</a>
<a class="text-slate-500 dark:text-slate-500 hover:text-primary underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
<a class="text-slate-500 dark:text-slate-500 hover:text-primary underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Terms of Service</a>
</div>
<div class="text-right md:text-right">
<p class="text-slate-500 dark:text-slate-500 text-xs font-body">© 2024 The Editorial Sanctuary. Every pet deserves a story.</p>
</div>
</div>
</footer>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const petId = this.getAttribute('data-pet-id');
            const isFavorited = this.classList.contains('text-error');

            try {
                const response = await fetch('backend/favorites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        pet_id: petId,
                        action: isFavorited ? 'remove' : 'add'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    if (result.action === 'added') {
                        this.classList.remove('text-outline');
                        this.classList.add('text-error');
                    } else {
                        this.classList.remove('text-error');
                        this.classList.add('text-outline');
                    }
                } else {
                    alert('Error: ' + result.error);
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        });
    });
});
</script>
</body></html>
