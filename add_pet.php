<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=add_pet.php');
    exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add Pet | Paw's Home</title>
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
        /* Improve pet image centering and subtle enhancement */
        .pet-img {
            object-position: center center;
            image-rendering: auto;
            -webkit-filter: contrast(1.03) saturate(1.02);
            filter: contrast(1.03) saturate(1.02);
            transition: transform 0.5s ease, filter 0.3s ease;
        }
        /* Force top-focused images when needed (e.g., Luna) */
        .pet-img.focus-top {
            object-position: top center !important;
        }
    </style>
</head>
<body class="bg-background text-on-surface">
<?php require_once 'header.php'; ?>
<main class="max-w-3xl mx-auto pt-24 bg-white p-8 rounded-3xl shadow-sm">
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-3">Add a New Pet</h1>
        <p class="text-slate-500">Add a companion profile and it will appear on the home page.</p>
    </div>
    <?php if ($success): ?>
    <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-red-800">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    <form action="backend/add_pet.php" method="post" enctype="multipart/form-data" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Pet Name</span>
                <input type="text" name="name" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Type</span>
                <select name="type" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                </select>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Breed</span>
                <input type="text" name="breed" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Age Category</span>
                <select name="age_category" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="puppy">Puppy</option>
                    <option value="adult">Adult</option>
                    <option value="senior">Senior</option>
                </select>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Gender</span>
                <select name="gender" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                </select>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Location</span>
                <input type="text" name="location" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
        </div>
        <label class="block">
            <span class="font-semibold text-slate-700">Description</span>
            <textarea name="description" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"></textarea>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Image File</span>
                <input type="file" name="image_file" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Image URL</span>
                <input type="url" name="image" placeholder="images/pet-card-3.jpg" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
                <p class="text-sm text-slate-500 mt-2">Upload an image file or use a local image URL.</p>
            </label>
        </div>
        <button type="submit" class="w-full rounded-2xl bg-teal-700 px-6 py-4 text-white font-bold hover:bg-teal-800">Add Pet</button>
    </form>
</main>
</body>
</html>
