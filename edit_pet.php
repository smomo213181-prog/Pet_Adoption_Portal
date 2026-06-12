<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=edit_pet.php');
    exit;
}

$petId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

if ($petId <= 0) {
    header('Location: my_pets.php?error=' . urlencode('Pet not found.'));
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM pets WHERE id = ? AND user_id = ?');
$stmt->execute([$petId, $user_id]);
$pet = $stmt->fetch();

if (!$pet) {
    header('Location: my_pets.php?error=' . urlencode('Pet not found or permission denied.'));
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Pet | Paw's Home</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #fcfdfd; color: #191c1d; }
    .toast { position: fixed; top: 20px; right: 20px; background: white; padding: 16px 22px; border-radius: 14px; box-shadow: 0 18px 40px rgba(0,0,0,0.08); display: flex; align-items: center; gap: 12px; z-index: 9999; animation: slideIn 0.28s ease-out; }
    .toast-icon { font-size: 22px; color: #0f766e; }
    .toast-message { font-weight: 500; color: #0f172a; }
    @keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100px); opacity: 0; } }
    .toast.removing { animation: slideOut 0.28s ease-in forwards; }
</style>
</head>
<body class="bg-background text-on-surface">
<?php require_once 'header.php'; ?>
<main class="max-w-3xl mx-auto pt-24 bg-white p-8 rounded-3xl shadow-sm">
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-3">Edit Pet</h1>
        <p class="text-slate-500">Update your pet listing details and save the changes.</p>
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
    <form action="backend/edit_pet.php" method="post" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>"/>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Pet Name</span>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($pet['name']); ?>" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Type</span>
                <select name="type" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="dog" <?php echo $pet['type'] === 'dog' ? 'selected' : ''; ?>>Dog</option>
                    <option value="cat" <?php echo $pet['type'] === 'cat' ? 'selected' : ''; ?>>Cat</option>
                </select>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Breed</span>
                <input type="text" name="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Age Category</span>
                <select name="age_category" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="puppy" <?php echo $pet['age_category'] === 'puppy' ? 'selected' : ''; ?>>Puppy</option>
                    <option value="adult" <?php echo $pet['age_category'] === 'adult' ? 'selected' : ''; ?>>Adult</option>
                    <option value="senior" <?php echo $pet['age_category'] === 'senior' ? 'selected' : ''; ?>>Senior</option>
                </select>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Gender</span>
                <select name="gender" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500">
                    <option value="">Choose</option>
                    <option value="female" <?php echo $pet['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="male" <?php echo $pet['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                </select>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Location</span>
                <input type="text" name="location" required value="<?php echo htmlspecialchars($pet['location']); ?>" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
        </div>
        <label class="block">
            <span class="font-semibold text-slate-700">Description</span>
            <textarea name="description" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"><?php echo htmlspecialchars($pet['description']); ?></textarea>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <label class="block">
                <span class="font-semibold text-slate-700">Image File</span>
                <input type="file" name="image_file" accept="image/*" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
            </label>
            <label class="block">
                <span class="font-semibold text-slate-700">Image URL</span>
                <input type="url" name="image" placeholder="images/pet-card-3.jpg" value="<?php echo htmlspecialchars($pet['image']); ?>" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none focus:border-teal-500"/>
                <p class="text-sm text-slate-500 mt-2">Upload a new image to replace the current one.</p>
            </label>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button type="submit" class="w-full rounded-2xl bg-teal-700 px-6 py-4 text-white font-bold hover:bg-teal-800">Save Changes</button>
            <a href="my_pets.php" class="w-full inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-4 text-slate-700 font-semibold hover:bg-slate-100">Cancel</a>
        </div>
    </form>
</main>
<script>
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<span class="material-symbols-outlined toast-icon">check_circle</span><span class="toast-message"></span>`;
    toast.querySelector('.toast-message').textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.classList.add('removing'); setTimeout(() => toast.remove(), 300); }, 3000);
}
<?php if ($success): ?>
showToast(<?php echo json_encode($success); ?>);
<?php endif; ?>
</script>
</body>
</html>
