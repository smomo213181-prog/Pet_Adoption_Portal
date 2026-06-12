<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=my_pets.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM pets WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$pets = $stmt->fetchAll();
$totalPets = count($pets);
$pendingRequests = 0;
$adoptedPets = 0;
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>My Pets | Paw's Home</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Be_Vietnam_Pro:wght@300;400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        body {
            font-family: 'Be_Vietnam_Pro', sans-serif;
            background-color: #f8fafa;
        }
        .font-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            animation: slideIn 0.28s ease-out;
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        .toast-icon {
            font-size: 22px;
            color: #0f766e;
        }
        .toast-message {
            color: #0f172a;
            font-weight: 500;
        }
        @keyframes slideIn {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100px); opacity: 0; }
        }
        .toast.removing { animation: slideOut 0.28s ease-in forwards; }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "secondary-fixed-dim": "#ffb783",
                      "secondary-fixed": "#ffdcc5",
                      "surface-tint": "#006970",
                      "on-tertiary": "#ffffff",
                      "on-primary-container": "#f5feff",
                      "on-tertiary-container": "#fffbff",
                      "surface-bright": "#f8fafa",
                      "secondary": "#944a00",
                      "primary": "#00666d",
                      "on-primary": "#ffffff",
                      "on-secondary": "#ffffff",
                      "surface-container-lowest": "#ffffff",
                      "primary-fixed": "#8df2fc",
                      "on-primary-fixed": "#002022",
                      "surface-variant": "#e1e3e3",
                      "inverse-surface": "#2e3131",
                      "on-secondary": "#ffffff",
                      "on-error": "#ffffff",
                      "on-secondary-fixed": "#301400",
                      "on-surface": "#191c1d",
                      "error": "#ba1a1a",
                      "primary-container": "#00818a",
                      "inverse-on-surface": "#eff1f1",
                      "on-secondary-fixed-variant": "#713700",
                      "on-secondary-container": "#6c3400",
                      "surface": "#f8fafa",
                      "surface-container-low": "#f2f4f4",
                      "background": "#f8fafa",
                      "secondary-container": "#ff9742",
                      "on-primary-fixed-variant": "#004f54",
                      "on-error-container": "#93000a",
                      "outline": "#6d797a",
                      "outline-variant": "#bdc9ca",
                      "on-surface-variant": "#3d494a",
                      "tertiary-fixed": "#ffddb6",
                      "surface-container-highest": "#e1e3e3",
                      "tertiary": "#815200",
                      "inverse-primary": "#70d6df"
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
</head>
<body class="text-on-surface bg-surface">
<?php require_once 'header.php'; ?>
<main class="pt-32 pb-20 px-8 max-w-screen-2xl mx-auto">
<header class="flex flex-col md:flex-row justify-between items-end md:items-center mb-12 gap-6">
<div>
<h1 class="text-5xl font-extrabold font-headline text-on-surface tracking-tight mb-3">My Pets</h1>
<p class="text-on-surface-variant text-lg">Manage every pet listing you’ve added to the portal.</p>
</div>
<a href="add_pet.php" class="flex items-center gap-2 px-8 py-4 bg-gradient-to-br from-primary to-primary-container text-on-primary rounded-full font-bold shadow-lg hover:opacity-90 transition-all active:scale-95">
<span class="material-symbols-outlined">add</span>
Add New Pet
</a>
</header>
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
<div class="bg-surface-container-lowest p-8 rounded-xl flex items-center justify-between group hover:bg-white transition-all shadow-[0px_12px_32px_rgba(25,28,29,0.04)]">
<div>
<p class="text-on-surface-variant font-label text-sm font-semibold uppercase tracking-wider mb-1">Total Pets</p>
<h3 class="text-4xl font-headline font-bold text-on-surface"><?php echo $totalPets; ?></h3>
</div>
<div class="w-14 h-14 bg-primary-container/10 rounded-full flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-3xl">pets</span>
</div>
</div>
<div class="bg-surface-container-lowest p-8 rounded-xl flex items-center justify-between group hover:bg-white transition-all shadow-[0px_12px_32px_rgba(25,28,29,0.04)]">
<div>
<p class="text-on-surface-variant font-label text-sm font-semibold uppercase tracking-wider mb-1">Pending Requests</p>
<h3 class="text-4xl font-headline font-bold text-on-surface"><?php echo $pendingRequests; ?></h3>
</div>
<div class="w-14 h-14 bg-secondary-container/10 rounded-full flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-3xl">pending_actions</span>
</div>
</div>
<div class="bg-surface-container-lowest p-8 rounded-xl flex items-center justify-between group hover:bg-white transition-all shadow-[0px_12px_32px_rgba(25,28,29,0.04)]">
<div>
<p class="text-on-surface-variant font-label text-sm font-semibold uppercase tracking-wider mb-1">Adopted Pets</p>
<h3 class="text-4xl font-headline font-bold text-on-surface"><?php echo $adoptedPets; ?></h3>
</div>
<div class="w-14 h-14 bg-tertiary-container/10 rounded-full flex items-center justify-center text-tertiary">
<span class="material-symbols-outlined text-3xl">verified</span>
</div>
</div>
</section>
<section class="mb-10 flex border-b border-outline-variant/20 overflow-x-auto whitespace-nowrap">
<button class="px-6 py-4 font-headline font-semibold text-primary border-b-2 border-primary">All</button>
<button class="px-6 py-4 font-headline font-medium text-on-surface-variant hover:text-primary transition-colors">Available</button>
<button class="px-6 py-4 font-headline font-medium text-on-surface-variant hover:text-primary transition-colors">Pending</button>
<button class="px-6 py-4 font-headline font-medium text-on-surface-variant hover:text-primary transition-colors">Adopted</button>
</section>
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
<?php if ($totalPets > 0): ?>
    <?php foreach ($pets as $pet): ?>
        <?php
            $imgSrc = (!empty($pet['image']) && is_file(__DIR__ . '/' . $pet['image'])) ? $pet['image'] : 'images/home-hero.jpg';
        ?>
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(25,28,29,0.06)] flex flex-col group">
            <div class="relative h-64 overflow-hidden">
                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>"/>
                <span class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold font-label">Available</span>
            </div>
            <div class="p-6 flex-grow">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="text-2xl font-headline font-bold"><?php echo htmlspecialchars($pet['name']); ?></h4>
                    <div class="flex gap-1">
                        <a href="edit_pet.php?id=<?php echo $pet['id']; ?>" class="p-2 hover:bg-primary/5 rounded-full text-on-surface-variant hover:text-primary" title="Edit pet">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                        <form method="post" action="backend/delete_pet.php" onsubmit="return confirm('Delete this pet listing?');" class="inline">
                            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>"/>
                            <button type="submit" class="p-2 hover:bg-error/5 rounded-full text-on-surface-variant hover:text-error" title="Delete pet">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-on-surface-variant text-sm mb-4"><?php echo htmlspecialchars($pet['breed']); ?> • <?php echo ucfirst($pet['age_category']); ?> • <?php echo ucfirst($pet['gender']); ?></p>
                <button class="w-full py-3 bg-surface-container-low text-primary font-bold rounded-full hover:bg-primary-container hover:text-on-primary transition-all">View Requests</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-span-full bg-white rounded-3xl p-12 text-center shadow-sm">
        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">pets</span>
        <h2 class="text-3xl font-bold mb-2">No pets yet</h2>
        <p class="text-slate-600 mb-6">You haven’t added any pets yet. Click the button above to list your first pet.</p>
        <a href="add_pet.php" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-full font-bold hover:bg-primary-container transition-all">Add Your First Pet</a>
    </div>
<?php endif; ?>
</section>
</main>
<footer class="w-full border-t border-slate-100 bg-slate-50">
<div class="flex flex-col md:flex-row justify-between items-center px-12 py-10 w-full max-w-screen-2xl mx-auto">
<div class="font-['Plus_Jakarta_Sans'] font-bold text-teal-900 text-xl mb-6 md:mb-0">Paw's Home</div>
<div class="flex space-x-8 mb-6 md:mb-0">
<a class="text-slate-500 hover:text-teal-600 font-['Be_Vietnam_Pro'] text-sm transition-opacity hover:opacity-80" href="#">About</a>
<a class="text-slate-500 hover:text-teal-600 font-['Be_Vietnam_Pro'] text-sm transition-opacity hover:opacity-80" href="#">Contact</a>
<a class="text-slate-500 hover:text-teal-600 font-['Be_Vietnam_Pro'] text-sm transition-opacity hover:opacity-80" href="#">Privacy</a>
</div>
<div class="text-slate-500 font-['Be_Vietnam_Pro'] text-sm">© 2026 Paw's Home. All rights reserved.</div>
</div>
</footer>
<script>
function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
        <span class="material-symbols-outlined toast-icon">${isError ? 'error' : 'check_circle'}</span>
        <span class="toast-message"></span>
    `;
    toast.querySelector('.toast-message').textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('removing');
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}
<?php if ($success): ?>
showToast(<?php echo json_encode($success); ?>);
<?php endif; ?>
<?php if ($error): ?>
showToast(<?php echo json_encode($error); ?>, true);
<?php endif; ?>
</script>
</body>
</html>
