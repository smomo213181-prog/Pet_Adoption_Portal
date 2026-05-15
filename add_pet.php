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
<title>Add Pet | The Editorial Sanctuary</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Be+Vietnam+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Be Vietnam Pro', sans-serif; background: #f8fafc; color: #0f172a; }
    h1,h2,h3,.font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
</head>
<body class="min-h-screen bg-slate-50">
<nav class="bg-white shadow-sm py-4 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <a class="text-xl font-bold text-teal-700" href="index.php">The Editorial Sanctuary</a>
        <div class="flex items-center gap-4">
            <a class="text-slate-600 hover:text-teal-700" href="index.php">Home</a>
            <a class="text-slate-600 hover:text-teal-700" href="favorites.php">Favorites</a>
            <a class="text-slate-600 hover:text-teal-700" href="logout.php">Logout</a>
        </div>
    </div>
</nav>
<main class="max-w-3xl mx-auto mt-12 bg-white p-8 rounded-3xl shadow-sm">
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
    <form action="backend/add_pet.php" method="post" class="space-y-5">
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
        <label class="block">
            <span class="font-semibold text-slate-700">Image URL</span>
            <input type="url" name="image" placeholder="images/pet-card-3.jpg" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
        </label>
        <button type="submit" class="w-full rounded-2xl bg-teal-700 px-6 py-4 text-white font-bold hover:bg-teal-800">Add Pet</button>
    </form>
</main>
</body>
</html>
