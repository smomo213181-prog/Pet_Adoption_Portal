<?php
session_start();
require_once 'db.php';

$registered = isset($_GET['registered']) && $_GET['registered'] == 1;
$error = isset($_GET['error']) ? $_GET['error'] : '';
$allowedRedirects = ['index.php', 'favorites.php', 'add_pet.php'];
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'index.php';
if (!in_array($redirect, $allowedRedirects, true)) {
    $redirect = 'index.php';
}
$loginPrompt = isset($_GET['redirect']);

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: ' . $redirect);
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }

    if (!empty($errors)) {
        $error = implode(' ', $errors);
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Login & Signup | Paw's Home</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Be+Vietnam+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
      .glass-card {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.8);
      }
      .editorial-shadow {
        box-shadow: 0px 12px 32px rgba(25, 28, 29, 0.06);
      }
    </style>
</head>
<body class="bg-background font-body text-on-surface min-h-screen selection:bg-primary-fixed selection:text-on-primary-fixed">
<main class="min-h-screen flex items-stretch">
<section class="hidden lg:flex w-1/2 relative overflow-hidden bg-primary p-16 flex-col justify-between">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover opacity-80" data-alt="A warm close-up of a happy golden retriever looking into the camera with soft natural sunlight and a cozy home background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCa86UATMJdrnA-hQO5zSengc8zO1Xz69sXkEXpyziPmy25NpzN7NFAFAhDWY7jsFqhCXpHZWwE_7zydDCxDUOSjQ8vCVsHLqyjiJ-pfxeDQ72tBZPOn-JebxXq2ZrmkMuQX7QDhHln0Tc59VS2vsQIenorvvYd9pLvoIe7TGHjI2VEg-PKglDeanuoFj0N1goQMApKH3C7p-pK-wA6cActW7Hdh2FOCjdNHJAbMnwvJHU0Ud5NZUqMH91PctVH9QAHRc29fkMiaGYU"/>
<div class="absolute inset-0 bg-gradient-to-br from-primary/60 via-transparent to-tertiary/20"></div>
</div>
<div class="relative z-10">
<span class="text-2xl font-bold tracking-tight text-white font-headline">Paw's Home</span>
</div>
<div class="relative z-10 max-w-lg">
<h1 class="text-5xl font-extrabold tracking-tight text-white font-headline mb-6 leading-tight">
                    Every pet deserves a legacy, not just a kennel.
                </h1>
<p class="text-lg text-on-primary-container/90 font-medium">
                    Join our curated community of compassionate hearts and help us write the next chapter for thousands of waiting companions.
                </p>
</div>
<div class="relative z-10 flex gap-4">
<div class="glass-card p-6 rounded-lg editorial-shadow flex items-center gap-4 max-w-xs">
<div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container-highest">
<img class="w-full h-full object-cover" data-alt="A small white dog sitting on a rug looking curious and friendly" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDmOVWfzufRCOhO1h0q7QjkABgHcRnkJxYioeztp3ZX7mC0lQytB_9oUS3230zuO3hAsYLcLw_jxQNhqqmBMf8w-jR3udJtg8IGaXL9KoMgzUP7qeJRsmsb6WvmjZs0CxvqO_KlSvOeIrTGUAq2ih9b7qBtOwA-31Nztv6KbG-XVBMwqbxXs-LcAQuZg_GutsfBPArfVkkxeMUW2ORKM5LTp17-ItfkYwYT5VAiHhHb85Dwww377iGZHx0tw6GvHt1atibvKEak2GkG"/>
</div>
<div>
<p class="text-on-surface font-bold text-sm">"Adopted last week!"</p>
<p class="text-on-surface-variant text-xs">Mochi and her new family.</p>
</div>
</div>
</div>
</section>
<section class="w-full lg:w-1/2 bg-background flex items-center justify-center p-8 md:p-12">
<div class="w-full max-w-md space-y-10">
<div class="lg:hidden mb-8">
<span class="text-xl font-bold tracking-tight text-primary font-headline">Paw's Home</span>
</div>
<div id="auth-header">
<h2 class="text-3xl font-bold tracking-tight text-on-surface font-headline">Welcome back</h2>
<p class="text-on-surface-variant mt-2">Sign in to continue your journey with us.</p>
</div>
<?php if ($registered): ?>
<div class="rounded-3xl border border-primary/20 bg-primary-container/10 p-4 text-sm text-primary font-medium">
    Registration complete. You can now log in.
</div>
<?php endif; ?>
<?php if ($loginPrompt): ?>
<div class="rounded-3xl border border-primary/20 bg-primary-container/10 p-4 text-sm text-primary font-medium">
    Please log in to continue to the site.
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="rounded-3xl border border-error/20 bg-error-container/10 p-4 text-sm text-error font-medium">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>
<div class="space-y-6">
<div class="grid grid-cols-2 gap-4">
<button class="flex items-center justify-center gap-3 px-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-full hover:bg-surface-container-low transition-all duration-300 editorial-shadow active:scale-95" type="button">
<img alt="Google" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBvZIMHt6I_IXxQsXQ6R3aPIT_TNhQYSvD1QJ_6XyqDx7LOBjdGpmQIy02sxk3Pd9KoGDoqCYQAgPboAC3W_ZIc02uP7KBzmTM5-sNLiOb76GhsTRr1yGW5BD6LvYzK8LDBVs_xac5F7w_9qol72ZwwGJatD27YY8S3FoI0aiY1ZE15IQ-pslZs2utjNU-OYeSnOAe_Mhxiq1xxAeW9FwBzDIGiU5fhpiWdXoP2zwnp8YGlmXJvYPxD30PCypHeNiOoC6tMDTLehk4L"/>
<span class="font-label text-sm font-semibold text-on-surface">Google</span>
</button>
<button class="flex items-center justify-center gap-3 px-4 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-full hover:bg-surface-container-low transition-all duration-300 editorial-shadow active:scale-95" type="button">
<span class="material-symbols-outlined text-[#1877F2]">social_leaderboard</span>
<span class="font-label text-sm font-semibold text-on-surface">Facebook</span>
</button>
</div>
<div class="relative flex items-center justify-center">
<hr class="w-full border-outline-variant/30"/>
<span class="absolute px-4 bg-background text-xs font-label uppercase tracking-widest text-on-surface-variant">or use email</span>
</div>
<form class="space-y-4" action="login.php" method="post">
<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>" />
<div class="space-y-1.5">
<label class="text-sm font-semibold text-on-surface ml-1 font-label">Email Address</label>
<input class="w-full px-5 py-4 bg-surface-container-low border-transparent rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 outline-none" placeholder="hello@example.com" type="email" name="email" required/>
</div>
<div class="space-y-1.5">
<div class="flex justify-between items-center px-1">
<label class="text-sm font-semibold text-on-surface font-label">Password</label>
<a class="text-xs font-bold text-primary hover:text-primary-container transition-colors" href="#">Forgot?</a>
</div>
<input class="w-full px-5 py-4 bg-surface-container-low border-transparent rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 outline-none" placeholder="••••••••" type="password" name="password" required/>
</div>
<div class="pt-2">
<button class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-full shadow-lg hover:opacity-90 active:scale-[0.98] transition-all duration-300" type="submit">
                                Sign In
                            </button>
</div>
</form>
</div>
<div class="space-y-8">
<p class="text-center text-sm text-on-surface-variant">
                        Don't have an account? 
                        <a class="font-bold text-primary hover:underline underline-offset-4 decoration-2" href="signup.php">Create an account</a>
</p>
</div>
<footer class="pt-8 text-center border-t border-outline-variant/20">
<p class="text-xs text-on-surface-variant leading-relaxed">
                        By continuing, you agree to Paw's Home's <br/>
<a class="text-on-surface font-medium hover:underline" href="#">Terms of Service</a> and <a class="text-on-surface font-medium hover:underline" href="#">Privacy Policy</a>.
                    </p>
</footer>
</div>
</section>
</main>
<div class="fixed bottom-6 right-6 lg:bottom-10 lg:right-10 group">
<button class="bg-tertiary-container text-on-tertiary-container w-16 h-16 rounded-full shadow-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 active:scale-90">
<span class="material-symbols-outlined text-3xl">question_answer</span>
</button>
<span class="absolute right-20 top-1/2 -translate-y-1/2 bg-on-surface text-surface py-2 px-4 rounded-lg text-sm font-label opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg">
            Need help choosing?
        </span>
</div>
</body></html>
