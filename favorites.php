<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=favorites.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch user's favorites
$stmt = $pdo->prepare('
    SELECT p.* FROM pets p
    INNER JOIN favorites f ON p.id = f.pet_id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
');
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll();
?>
<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Favorites - Paw's Home</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;family=Be+Vietnam+Pro:ital,wght@0,100..900;1,100..900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "error": "#ba1a1a",
              "surface-container-lowest": "#ffffff",
              "on-primary": "#ffffff",
              "outline": "#6d797a",
              "surface-variant": "#e1e3e3",
              "on-secondary-fixed": "#301400",
              "on-surface": "#191c1d",
              "surface-container-highest": "#e1e3e3",
              "primary-fixed": "#8df2fc",
              "secondary-fixed-dim": "#ffb783",
              "on-error-container": "#93000a",
              "on-primary-container": "#f5feff",
              "error-container": "#ffdad6",
              "inverse-primary": "#70d6df",
              "inverse-surface": "#2e3131",
              "primary-container": "#00818a",
              "tertiary-fixed": "#ffddb6",
              "on-secondary": "#ffffff",
              "on-primary-fixed-variant": "#004f54",
              "surface-dim": "#d8dada",
              "surface-bright": "#f8fafa",
              "surface-container": "#eceeee",
              "on-tertiary-container": "#fffbff",
              "on-primary-fixed": "#002022",
              "tertiary-fixed-dim": "#ffb95a",
              "secondary-container": "#ff9742",
              "on-tertiary-fixed-variant": "#643f00",
              "on-secondary-fixed-variant": "#713700",
              "background": "#f8fafa",
              "surface-tint": "#006970",
              "on-tertiary-fixed": "#2a1800",
              "on-error": "#ffffff",
              "inverse-on-surface": "#eff1f1",
              "outline-variant": "#bdc9ca",
              "tertiary-container": "#a26800",
              "surface": "#f8fafa",
              "on-tertiary": "#ffffff",
              "on-background": "#191c1d",
              "primary": "#00666d",
              "on-surface-variant": "#3d494a",
              "secondary-fixed": "#ffdcc5",
              "tertiary": "#815200",
              "secondary": "#944a00",
              "surface-container-high": "#e6e8e9",
              "surface-container-low": "#f2f4f4",
              "on-secondary-container": "#6c3400",
              "primary-fixed-dim": "#70d6df"
            },
            fontFamily: {
              "headline": ["Plus Jakarta Sans"],
              "body": ["Be Vietnam Pro"],
              "label": ["Plus Jakarta Sans"]
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      body { font-family: 'Be Vietnam Pro', sans-serif; }
      h1, h2, h3, .nav-link { font-family: 'Plus Jakarta Sans', sans-serif; }
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
<body class="bg-background text-on-surface">
<?php require_once 'header.php'; ?>
<?php require_once 'header.php'; ?>
<main class="pt-24 pb-20 px-8 max-w-7xl mx-auto">
<!-- Hero Heading -->
<section class="mb-12">
<h1 class="text-5xl font-bold tracking-tight text-on-surface mb-4">Your Sanctuary Favorites</h1>
<p class="text-on-surface-variant text-lg max-w-2xl font-body">A curated collection of souls waiting for a home. Take your time—every connection is a story in the making.</p>
</section>
<!-- Favorites Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<?php if (empty($favorites)): ?>
<div class="col-span-full text-center py-12">
<div class="w-24 h-24 mx-auto mb-6 rounded-full bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-4xl text-outline">favorite</span>
</div>
<h3 class="text-2xl font-bold text-on-surface mb-2">No favorites yet</h3>
<p class="text-on-surface-variant mb-6">Start exploring pets and add them to your favorites!</p>
<a href="index.php" class="bg-primary text-on-primary px-6 py-3 rounded-full font-bold hover:bg-primary-container transition-colors">Browse Pets</a>
</div>
<?php else: ?>
<?php foreach ($favorites as $pet): ?>
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(25,28,29,0.06)] group">
<div class="relative h-72">
<img class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($pet['name']); ?>" src="<?php echo htmlspecialchars($pet['image']); ?>"/>
<div class="absolute top-4 right-4">
<button class="remove-favorite bg-white/90 backdrop-blur p-2 rounded-full text-error hover:scale-110 transition-transform" data-pet-id="<?php echo $pet['id']; ?>">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">favorite</span>
</button>
</div>
</div>
<div class="p-8">
<div class="flex justify-between items-start mb-4">
<div>
<h3 class="text-2xl font-bold text-on-surface"><?php echo htmlspecialchars($pet['name']); ?></h3>
<p class="text-on-surface-variant font-label text-sm"><?php echo htmlspecialchars($pet['breed'] ? $pet['breed'] : 'Mixed Breed'); ?> • <?php echo htmlspecialchars(ucfirst($pet['age_category'])); ?></p>
</div>
<span class="bg-tertiary-container/20 text-on-tertiary-fixed-variant px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"><?php echo htmlspecialchars(ucfirst($pet['type'])); ?></span>
</div>
<p class="text-on-surface-variant text-sm mb-6 font-body leading-relaxed"><?php echo htmlspecialchars($pet['description'] ?: 'A wonderful companion waiting for a loving home.'); ?></p>
<div class="flex gap-3">
<button class="flex-1 bg-gradient-to-br from-primary to-primary-container text-on-primary py-3 rounded-full font-semibold transition-transform active:scale-95">Send Request</button>
<button class="remove-favorite px-4 py-3 rounded-full border border-outline-variant/30 text-error hover:bg-error-container/10 transition-colors" data-pet-id="<?php echo $pet['id']; ?>">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<!-- Featured Section with Asymmetry -->
<section class="mt-24 bg-surface-container-low rounded-xl p-12 relative overflow-hidden">
<div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
<div>
<span class="text-tertiary font-bold tracking-widest text-xs uppercase mb-4 block">Recommendation</span>
<h2 class="text-4xl font-bold mb-6">Still looking for that perfect match?</h2>
<p class="text-on-surface-variant font-body mb-8 leading-relaxed">Our sanctuary coordinators can help you find a pet that fits your lifestyle perfectly. Sometimes the best connections are the ones we didn't expect.</p>
<button class="bg-tertiary-container text-on-tertiary-fixed px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-xl transition-shadow scale-95 active:scale-90">Talk to a Coordinator</button>
</div>
<div class="relative">
<img class="rounded-xl w-full h-[400px] object-cover shadow-2xl -rotate-3 scale-110 translate-x-4" data-alt="Golden labrador looking up with soulful eyes at its owner, focusing on the emotional bond and warm indoor lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCQ__LYjLq_Uxs4b9dwkoZ7k1te6kto010S295dmmWBdmjZCLqX5r0Koy5tek3IyhE4ZprQmurgkB896WrlSCaka8XZR-MXQkAdi9WSLgO_sjKnUfbektkQv61c2kYI8CVYtcZTPlS68yzs5lCWD2CSVyF6A_r9SvdhHkd02_WPQU8YReFuZ0BnQZkNznm_ry6PzZcz-tVqI0D2EAFrD_oIbPLAxKaQwLfjbpD43sR0T7syW5XZW-5iahaGDxbeqZEtPVYNym-NyPFI"/>
</div>
</div>
<div class="absolute top-0 right-0 w-64 h-64 bg-tertiary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
</section>
</main>
<!-- Footer -->
<footer class="bg-slate-50 dark:bg-slate-950 w-full py-12 px-8">
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-center border-t border-slate-100 dark:border-slate-800 pt-12">
<div>
<span class="text-xl font-bold text-teal-900 dark:text-teal-100">The Editorial Sanctuary</span>
<p class="text-sm font-body text-slate-500 dark:text-slate-500 mt-2">Every pet deserves a story.</p>
</div>
<div class="flex justify-center gap-8">
<a class="text-slate-500 dark:text-slate-500 font-medium hover:text-teal-500 underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">About</a>
<a class="text-slate-500 dark:text-slate-500 font-medium hover:text-teal-500 underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Contact</a>
<a class="text-slate-500 dark:text-slate-500 font-medium hover:text-teal-500 underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
<a class="text-slate-500 dark:text-slate-500 font-medium hover:text-teal-500 underline underline-offset-4 transition-opacity opacity-80 hover:opacity-100" href="#">Terms of Service</a>
</div>
<div class="md:text-right">
<p class="text-sm font-body text-slate-500 dark:text-slate-500">© 2024 The Editorial Sanctuary.</p>
<p class="text-sm font-body text-slate-500 dark:text-slate-500">© 2024 Paw's Home.</p>
</div>
</div>
</footer>
<!-- Mobile Navigation Shell -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-slate-100 flex justify-around py-3 px-4 z-50">
<a class="flex flex-col items-center gap-1 text-slate-500" href="#">
<span class="material-symbols-outlined">home</span>
<span class="text-[10px] font-bold">Home</span>
</a>
<a class="flex flex-col items-center gap-1 text-teal-600" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">favorite</span>
<span class="text-[10px] font-bold">Favorites</span>
</a>
<a class="flex flex-col items-center gap-1 text-slate-500" href="#">
<span class="material-symbols-outlined">pets</span>
<span class="text-[10px] font-bold">My Pets</span>
</a>
<a class="flex flex-col items-center gap-1 text-slate-500" href="#">
<span class="material-symbols-outlined">person</span>
<span class="text-[10px] font-bold">Profile</span>
</a>
</nav>
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
    // Handle remove favorite buttons
    document.querySelectorAll('.remove-favorite').forEach(btn => {
        btn.addEventListener('click', async function() {
            const petId = this.getAttribute('data-pet-id');

            try {
                const response = await fetch('backend/favorites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        pet_id: petId,
                        action: 'remove'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Remove the card from the DOM
                    this.closest('.group').remove();
                    showToast('Removed from favorites');
                    // If no favorites left, reload to show empty state
                    if (document.querySelectorAll('.group').length === 0) {
                        setTimeout(() => location.reload(), 500);
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
</body></html>