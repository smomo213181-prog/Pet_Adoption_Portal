<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER['PHP_SELF']);
$user_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl flex justify-between items-center px-8 py-4 max-w-full mx-auto" style="box-shadow: 0px 12px 32px rgba(25, 28, 29, 0.04);">
<div class="flex items-center gap-12">
    <a href="index.php" class="text-2xl font-bold tracking-tight text-primary dark:text-primary-fixed">Paw's Home</a>
    <div class="hidden md:flex items-center gap-8 font-label text-sm tracking-tight">
        <a href="index.php" class="<?php echo $current === 'index.php' ? 'text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-primary'; ?> transition-colors duration-300">Home</a>
        <a href="browse_pets.php" class="<?php echo $current === 'browse_pets.php' ? 'text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-primary'; ?> transition-colors duration-300">Browse Pets</a>
        <a href="my_pets.php" class="<?php echo $current === 'my_pets.php' ? 'text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-primary'; ?> transition-colors duration-300">My Pets</a>
        <a href="favorites.php" class="<?php echo $current === 'favorites.php' ? 'text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-primary'; ?> transition-colors duration-300">Favorites</a>
        <a href="add_pet.php" class="<?php echo $current === 'add_pet.php' ? 'text-primary dark:text-primary-fixed border-b-2 border-primary font-semibold' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-primary'; ?> transition-colors duration-300">Add Pet</a>
        <a href="#" class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300">Requests</a>
        <a href="#" class="text-slate-600 dark:text-slate-400 font-medium hover:text-primary transition-colors duration-300">Profile</a>
    </div>
</div>
<div class="flex items-center gap-6">
    <div class="flex items-center gap-4 text-primary dark:text-primary-fixed">
        <?php if ($user_logged_in): ?>
            <span class="text-sm font-medium">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
            <button class="hover:text-primary-container transition-colors duration-300" onclick="window.location.href='logout.php'">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
            </button>
        <?php else: ?>
            <button class="hover:text-primary-container transition-colors duration-300" onclick="window.location.href='login.php'">
                <span class="material-symbols-outlined" data-icon="login">login</span>
            </button>
        <?php endif; ?>
        <button class="hover:text-primary-container transition-colors duration-300">
            <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
        </button>
        <button class="hover:text-primary-container transition-colors duration-300">
            <span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
        </button>
    </div>
</div>
</nav>
