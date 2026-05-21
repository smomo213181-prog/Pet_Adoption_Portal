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

// Get pet ID from URL
$pet_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch pet details
$stmt = $pdo->prepare('
    SELECT p.*, 
           CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorited 
    FROM pets p 
    LEFT JOIN favorites f ON p.id = f.pet_id AND f.user_id = ?
    WHERE p.id = ?
');
$stmt->execute([$user_id, $pet_id]);
$pet = $stmt->fetch();

if (!$pet) {
    header('Location: browse_pets.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($pet['name']); ?> | Paw's Home</title>
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
    </style>
</head>
<body class="bg-background font-body text-on-surface">
<?php require_once 'header.php'; ?>

<main class="pt-24 pb-20">
    <section class="max-w-screen-2xl mx-auto px-8">
        <!-- Back Button -->
        <a href="browse_pets.php" class="inline-flex items-center gap-2 text-primary font-bold mb-8 hover:text-primary-container transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Browse
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Pet Image -->
            <div class="lg:col-span-2">
                <div class="relative bg-surface-container-high rounded-2xl overflow-hidden h-96 lg:h-full">
                    <?php if ($pet['image']): ?>
                        <img alt="<?php echo htmlspecialchars($pet['name']); ?>" class="w-full h-full object-cover object-top" src="<?php echo htmlspecialchars($pet['image']); ?>"/>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-outline">
                            <span class="material-symbols-outlined text-6xl">pets</span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="backend/favorites.php" class="absolute top-8 right-8">
                        <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>"/>
                        <button type="button" class="favorite-btn h-16 w-16 bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-error transition-all text-4xl" data-pet-id="<?php echo $pet['id']; ?>">
                            <span class="material-symbols-outlined text-4xl <?php echo $pet['is_favorited'] ? 'fill-icon' : ''; ?>">favorite</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pet Details -->
            <div class="lg:col-span-1 flex flex-col gap-8">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-5xl font-headline font-bold text-on-surface"><?php echo htmlspecialchars($pet['name']); ?></h1>
                        <span class="text-2xl font-bold text-primary capitalize"><?php echo ucfirst($pet['type']); ?></span>
                    </div>
                    
                    <div class="space-y-4 text-lg">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            <span class="text-on-surface-variant"><?php echo htmlspecialchars($pet['location']); ?></span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">cake</span>
                            <span class="text-on-surface-variant"><?php echo ucfirst($pet['age_category']); ?></span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <span class="text-on-surface-variant capitalize"><?php echo ucfirst($pet['gender']); ?></span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            <span class="text-on-surface-variant"><?php echo htmlspecialchars($pet['breed']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="space-y-4">
                    <button class="w-full bg-primary text-white px-8 py-4 rounded-full font-bold hover:bg-primary-container transition-all text-lg">
                        Start Adoption Process
                    </button>
                    <button class="w-full border-2 border-primary text-primary px-8 py-4 rounded-full font-bold hover:bg-primary hover:text-white transition-all text-lg">
                        Send Message
                    </button>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <section class="mt-20 bg-surface-container-low rounded-2xl p-12">
            <h2 class="text-3xl font-headline font-bold text-on-surface mb-6">About <?php echo htmlspecialchars($pet['name']); ?></h2>
            <p class="text-lg text-on-surface-variant leading-relaxed mb-8">
                <?php echo htmlspecialchars($pet['description']) ?: "No description available for this pet."; ?>
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-surface-container-lowest rounded-xl p-6 text-center">
                    <span class="material-symbols-outlined text-4xl text-primary mx-auto mb-2">medical_information</span>
                    <p class="text-sm text-on-surface-variant">Health Certified</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-6 text-center">
                    <span class="material-symbols-outlined text-4xl text-primary mx-auto mb-2">shield_check</span>
                    <p class="text-sm text-on-surface-variant">Vaccinated</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-6 text-center">
                    <span class="material-symbols-outlined text-4xl text-primary mx-auto mb-2">favorite</span>
                    <p class="text-sm text-on-surface-variant">Well Socialized</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-6 text-center">
                    <span class="material-symbols-outlined text-4xl text-primary mx-auto mb-2">verified_user</span>
                    <p class="text-sm text-on-surface-variant">Verified</p>
                </div>
            </div>
        </section>
    </section>
</main>

<!-- Footer -->
<footer class="bg-slate-50 dark:bg-slate-950 w-full py-12 px-8 mt-auto font-['Be_Vietnam_Pro'] text-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 max-w-screen-2xl mx-auto">
        <div class="flex flex-col gap-2">
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-teal-900 dark:text-teal-100 text-xl">The Editorial Sanctuary</span>
            <p class="text-slate-500">© 2024 The Editorial Sanctuary. Every pet deserves a story.</p>
        </div>
        <div class="flex gap-8">
            <a class="text-slate-500 hover:text-teal-600 underline transition-all" href="#">Privacy Policy</a>
            <a class="text-slate-500 hover:text-teal-600 underline transition-all" href="#">Terms of Service</a>
            <a class="text-slate-500 hover:text-teal-600 underline transition-all" href="#">Contact Support</a>
            <a class="text-slate-500 hover:text-teal-600 underline transition-all" href="#">About Us</a>
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
    // Handle favorite buttons
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const petId = this.getAttribute('data-pet-id');
            const icon = this.querySelector('.material-symbols-outlined');
            const isFavorited = icon.classList.contains('fill-icon');

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
                        icon.classList.add('fill-icon');
                        showToast('Added to favorites ❤️');
                    } else {
                        icon.classList.remove('fill-icon');
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
});
</script>
</body>
</html>
