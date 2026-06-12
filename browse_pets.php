<?php
session_start();

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=browse_pets.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
// Logged in flag for header
$user_logged_in = isset($_SESSION['user_id']);

// Get filter and search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$pet_type = isset($_GET['type']) ? $_GET['type'] : '';
$age_category = isset($_GET['age']) ? $_GET['age'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$query = 'SELECT p.*, 
          CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited 
          FROM pets p 
          LEFT JOIN favorites f ON p.id = f.pet_id AND f.user_id = ?';
$params = [$user_id];

$conditions = [
    'p.user_id != ?'
];
$params[] = $user_id;

if ($search) {
    $conditions[] = "(p.name LIKE ? OR p.breed LIKE ? OR p.location LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($pet_type && $pet_type !== 'all') {
    $conditions[] = "p.type = ?";
    $params[] = $pet_type;
}

if ($age_category && $age_category !== 'all') {
    $conditions[] = "p.age_category = ?";
    $params[] = $age_category;
}

if ($conditions) {
    $query .= ' WHERE ' . implode(' AND ', $conditions);
}

// Add sorting
switch($sort) {
    case 'distance':
        $query .= ' ORDER BY p.location ASC';
        break;
    case 'age_young':
        $query .= ' ORDER BY FIELD(p.age_category, "puppy", "adult", "senior") ASC';
        break;
    case 'newest':
    default:
        $query .= ' ORDER BY p.created_at DESC';
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$pets = $stmt->fetchAll();

// Check if user is logged in
$user_logged_in = true;
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Browse Pets | The Editorial Sanctuary</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Be+Vietnam+Pro:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#ffb95a",
                        "secondary-fixed": "#ffdcc5",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f2f4f4",
                        "primary-fixed": "#8df2fc",
                        "inverse-primary": "#70d6df",
                        "surface-container-lowest": "#ffffff",
                        "on-primary": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-surface": "#191c1d",
                        "primary-container": "#00818a",
                        "outline-variant": "#bdc9ca",
                        "surface-container-high": "#e6e8e9",
                        "tertiary-container": "#a26800",
                        "primary-fixed-dim": "#70d6df",
                        "outline": "#6d797a",
                        "tertiary-fixed": "#ffddb6",
                        "on-secondary-fixed": "#301400",
                        "background": "#f8fafa",
                        "inverse-on-surface": "#eff1f1",
                        "secondary-container": "#ff9742",
                        "surface-tint": "#006970",
                        "on-secondary-container": "#6c3400",
                        "tertiary": "#815200",
                        "on-tertiary-fixed-variant": "#643f00",
                        "on-tertiary-fixed": "#2a1800",
                        "inverse-surface": "#2e3131",
                        "on-surface-variant": "#3d494a",
                        "surface-dim": "#d8dada",
                        "surface-container-highest": "#e1e3e3",
                        "on-tertiary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "surface": "#f8fafa",
                        "on-tertiary-container": "#fffbff",
                        "on-primary-fixed-variant": "#004f54",
                        "error-container": "#ffdad6",
                        "secondary": "#944a00",
                        "on-background": "#191c1d",
                        "surface-container": "#eceeee",
                        "on-primary-container": "#f5feff",
                        "surface-bright": "#f8fafa",
                        "on-secondary-fixed-variant": "#713700",
                        "on-error": "#ffffff",
                        "surface-variant": "#e1e3e3",
                        "secondary-fixed-dim": "#ffb783",
                        "primary": "#00666d",
                        "on-primary-fixed": "#002022"
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
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            font-family: 'Be Vietnam Pro', sans-serif;
            border-left: 4px solid #00676e;
        }
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        .toast.removing {
            animation: slideOut 0.3s ease-in;
        }
        .toast-icon {
            font-size: 20px;
            color: #00676e;
        }
        .toast-message {
            color: #191c1d;
            font-weight: 500;
        }
        .pet-img {
            object-position: center center;
            image-rendering: auto;
            -webkit-filter: contrast(1.03) saturate(1.02);
            filter: contrast(1.03) saturate(1.02);
            transition: transform 0.5s ease, filter 0.3s ease;
        }
        .pet-img.focus-top {
            object-position: top center !important;
        }
    </style>
</head>
<body class="bg-background text-on-surface">
<?php require_once 'header.php'; ?>

<main class="pt-24 pb-20">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 mb-12 mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-5xl font-bold text-gray-900 tracking-tight mb-3 leading-tight font-['Plus_Jakarta_Sans']">
                    Find Your Perfect <br/><span class="text-teal-700">Companion</span>
                </h1>
                <p class="text-gray-600 text-base mb-8 leading-relaxed">
                    Every pet has a story waiting to be written. Search and filter through our curated selection of pets looking for their forever sanctuary.
                </p>
                <!-- Search Bar -->
                <form method="GET" class="relative max-w-md group mb-6">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-teal-700 text-xl">search</span>
                    <input 
                        name="search"
                        class="w-full h-14 pl-12 pr-4 bg-white rounded-full text-base border border-gray-200 focus:ring-2 focus:ring-teal-700 focus:border-transparent shadow-sm transition-all placeholder:text-gray-400" 
                        placeholder="Search by breed, name, or traits..." 
                        type="text"
                        value="<?php echo htmlspecialchars($search); ?>"/>
                    <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 bg-teal-700 text-white px-6 h-12 rounded-full font-bold hover:bg-teal-800 transition-colors text-sm">Search</button>
                </form>
            </div>
            <div class="hidden lg:block">
                <img alt="Pet Gallery Header" class="w-full h-auto rounded-2xl shadow-lg object-cover" src="images/home-hero.jpg"/>
            </div>
        </div>
    </section>

    <!-- Filters & Sorting Row -->
    <section class="bg-gray-50 py-6 mb-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" id="filterForm" class="flex flex-wrap items-center gap-3">
                    <!-- Keep search param -->
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"/>
                    
                    <button class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                        <span class="material-symbols-outlined text-base">pets</span>
                        Pet Type
                    </button>

                    <button class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                        <span class="material-symbols-outlined text-base">fingerprint</span>
                        Breed
                    </button>

                    <select name="age" class="bg-white px-4 py-2 rounded-full border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                        <option value="">Age</option>
                        <option value="puppy" <?php echo $age_category === 'puppy' ? 'selected' : ''; ?>>Puppy/Kitten</option>
                        <option value="adult" <?php echo $age_category === 'adult' ? 'selected' : ''; ?>>Adult</option>
                        <option value="senior" <?php echo $age_category === 'senior' ? 'selected' : ''; ?>>Senior</option>
                    </select>

                    <button class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                        <span class="material-symbols-outlined text-base">location_on</span>
                        Location
                    </button>

                    <button class="flex items-center gap-2 bg-teal-100 text-teal-700 px-4 py-2 rounded-full font-bold text-sm hover:bg-teal-200 transition-all">
                        <span class="material-symbols-outlined text-base">tune</span>
                        All Filters
                    </button>
                </form>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-sm font-medium">Sort by:</span>
                <select name="sort" class="bg-transparent border-none font-bold text-gray-900 focus:ring-0 cursor-pointer text-sm">
                    <option value="newest">Newest First</option>
                    <option value="distance">Distance</option>
                    <option value="age_young">Age (Youngest)</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Results Grid -->
    <section class="max-w-7xl mx-auto px-6">
        <?php if (count($pets) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($pets as $pet): ?>
            <!-- Pet Card -->
            <?php
                $focus = ($pet['name'] === 'Luna') ? '50% 12%' : '';
                $imgSrc = (!empty($pet['image']) && is_file(__DIR__ . '/' . $pet['image'])) ? $pet['image'] : 'images/home-hero.jpg';
            ?>
            <div class="flex flex-col h-full bg-white rounded-3xl overflow-hidden hover:shadow-lg transition-all duration-300" <?php if ($focus) echo 'data-focus="'.htmlspecialchars($focus).'"'; ?>>
                <div class="relative w-full aspect-[5/4] overflow-hidden bg-gray-200">
                    <img alt="<?php echo htmlspecialchars($pet['name']); ?>" class="pet-img w-full h-full object-cover object-center hover:scale-110 transition-transform duration-500" src="<?php echo htmlspecialchars($imgSrc); ?>"/>

                    <button type="button" class="favorite-btn absolute top-4 right-4 z-10 h-12 w-12 bg-white rounded-full flex items-center justify-center text-gray-400 hover:bg-white hover:text-red-500 transition-all shadow-lg hover:shadow-xl" data-pet-id="<?php echo $pet['id']; ?>">
                        <span class="material-symbols-outlined text-2xl <?php echo $pet['is_favorited'] ? 'fill-icon text-red-500' : ''; ?>">favorite</span>
                    </button>

                    <?php if ($pet['type'] === 'cat' && stripos($pet['breed'], 'Long Hair') !== false): ?>
                    <div class="absolute bottom-4 left-4 flex gap-2">
                        <span class="bg-teal-700 text-white px-3 py-1 rounded-full text-xs font-bold">Vaccinated</span>
                    </div>
                    <?php elseif ($pet['type'] === 'dog' && $pet['age_category'] === 'puppy'): ?>
                    <div class="absolute bottom-4 left-4 flex gap-2">
                        <span class="bg-amber-600 text-white px-3 py-1 rounded-full text-xs font-bold">New Listing</span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-gray-900 font-['Plus_Jakarta_Sans']"><?php echo htmlspecialchars($pet['name']); ?></h3>
                        <span class="text-xs font-bold text-gray-600 capitalize"><?php echo ucfirst($pet['type']); ?></span>
                    </div>
                    <p class="text-gray-600 mb-4 text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">location_on</span> <?php echo htmlspecialchars($pet['location']); ?> • <?php echo ucfirst(str_replace('_', ' ', $pet['age_category'])); ?> Old
                    </p>
                    <div class="flex items-center justify-between gap-3 mt-auto">
                        <span class="text-xs text-gray-600"><?php echo htmlspecialchars($pet['breed']); ?></span>
                        <a href="pet_detail.php?id=<?php echo $pet['id']; ?>" class="bg-teal-700 text-white px-4 py-2 rounded-full font-bold hover:bg-teal-800 transition-all text-xs whitespace-nowrap">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <span class="material-symbols-outlined text-6xl text-gray-400 mb-4 inline-block">search_off</span>
            <h2 class="text-2xl font-bold text-gray-900 mb-2 font-['Plus_Jakarta_Sans']">No pets found</h2>
            <p class="text-gray-600">Try adjusting your filters or search terms</p>
        </div>
        <?php endif; ?>

        <?php if (count($pets) > 0): ?>
        <div class="mt-12 flex justify-center">
            <button class="px-10 py-3 rounded-full border-2 border-teal-700 text-teal-700 font-bold hover:bg-teal-700 hover:text-white transition-all text-sm">Load More Stories</button>
        </div>
        <?php endif; ?>
    </section>
</main>

<!-- Footer (Shared Component) -->
<footer class="bg-white border-t border-gray-200 w-full py-8 px-6 mt-auto font-['Be_Vietnam_Pro'] text-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 max-w-7xl mx-auto">
        <div class="flex flex-col gap-1">
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-gray-900 text-base">Paw's Home</span>
            <p class="text-gray-500 text-xs">© 2026 Paw's Home. Every pet deserves a story.</p>
        </div>
        <div class="flex gap-8 text-xs">
            <a class="text-gray-600 hover:text-teal-700 underline transition-all" href="#">Privacy Policy</a>
            <a class="text-gray-600 hover:text-teal-700 underline transition-all" href="#">Terms of Service</a>
            <a class="text-gray-600 hover:text-teal-700 underline transition-all" href="#">Contact Support</a>
            <a class="text-gray-600 hover:text-teal-700 underline transition-all" href="#">About Us</a>
        </div>
    </div>
</footer>

<script>
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast';
    
    const icon = type === 'success' ? 'favorite' : 'close';
    const iconColor = type === 'success' ? '#ba1a1a' : '#666';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined toast-icon" style="color: ${iconColor}; font-variation-settings: 'FILL' 1;">${icon}</span>
        <span class="toast-message">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('removing');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filters on change for type and age
    document.querySelectorAll('select[name="type"], select[name="age"]').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Handle favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const petId = this.getAttribute('data-pet-id');
            const icon = this.querySelector('.material-symbols-outlined');
            const isFavorited = icon.classList.contains('text-red-500');

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
                        icon.classList.add('fill-icon', 'text-red-500');
                        icon.classList.remove('text-gray-400');
                        showToast('Added to favorites ❤️');
                    } else {
                        icon.classList.remove('fill-icon', 'text-red-500');
                        icon.classList.add('text-gray-400');
                        showToast('Removed from favorites');
                    }
                } else {
                    showToast('Error: ' + result.error, 'error');
                }
            } catch (error) {
                showToast('Network error: ' + error.message, 'error');
            }
        });
    });

    // Apply per-image focal positions for pets that have `data-focus` (e.g., Luna)
    document.querySelectorAll('[data-focus]').forEach(group => {
        const focus = group.getAttribute('data-focus');
        const img = group.querySelector('img.pet-img');
        if (!img || !focus) return;

        const applyFocus = () => {
            img.style.objectPosition = focus;
            img.classList.add('focus-top');
        };

        if (img.complete) applyFocus();
        else img.addEventListener('load', applyFocus);
    });
});
</script>
</body>
</html>
