<?php
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Paws Home - Join Our Community</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Be+Vietnam+Pro:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        h1, h2, h3, .font-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error-container": "#ffdad6",
                        "surface-tint": "#006970",
                        "on-background": "#191c1d",
                        "inverse-surface": "#2e3131",
                        "secondary-container": "#ff9742",
                        "on-primary-container": "#f5feff",
                        "surface-container-low": "#f2f4f4",
                        "tertiary-fixed-dim": "#ffb95a",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#643f00",
                        "tertiary-fixed": "#ffddb6",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#002022",
                        "on-tertiary-fixed": "#2a1800",
                        "on-secondary-fixed-variant": "#713700",
                        "on-surface-variant": "#3d494a",
                        "on-error": "#ffffff",
                        "outline-variant": "#bdc9ca",
                        "surface-variant": "#e1e3e3",
                        "surface-bright": "#f8fafa",
                        "on-tertiary-container": "#fffbff",
                        "primary-fixed-dim": "#70d6df",
                        "on-secondary": "#ffffff",
                        "secondary": "#944a00",
                        "tertiary-container": "#a26800",
                        "secondary-fixed-dim": "#ffb783",
                        "surface-container": "#eceeee",
                        "primary-container": "#00818a",
                        "background": "#f8fafa",
                        "on-primary-fixed-variant": "#004f54",
                        "primary-fixed": "#8df2fc",
                        "on-secondary-container": "#6c3400",
                        "inverse-on-surface": "#eff1f1",
                        "primary": "#00666d",
                        "surface": "#f8fafa",
                        "surface-container-highest": "#e1e3e3",
                        "on-surface": "#191c1d",
                        "outline": "#6d797a",
                        "surface-dim": "#d8dada",
                        "on-secondary-fixed": "#301400",
                        "surface-container-high": "#e6e8e9",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#815200",
                        "inverse-primary": "#70d6df",
                        "secondary-fixed": "#ffdcc5",
                        "on-primary": "#ffffff"
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
<body class="bg-background text-on-surface min-h-screen flex items-center justify-center p-6 md:p-12">
<!-- Auth Shell Suppression: No TopNavBar or SideNavBar for Signup -->
<div class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-12 gap-0 overflow-hidden bg-surface-container-lowest rounded-xl shadow-sm">
<!-- Left Side: Editorial Visual & Context -->
<div class="hidden lg:flex lg:col-span-5 relative bg-primary overflow-hidden min-h-[800px] flex-col justify-end p-12">
<div class="absolute inset-0 z-0">
<img class="w-full h-full object-cover opacity-80 mix-blend-multiply" data-alt="Close-up of a happy golden retriever and a fluffy cat sitting together in a sunlit living room with high-end editorial lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAd_LtcxmDwC4yWhl15oIkCKQx8U3-ATOka2xqHeYcFGS_-46hVegPDdcWmsF_7VS81H4uShJN7PfuyZ1TWGZw8UQMS3gP3kMOCrNq_XafZF0cO4NfpCBK0To5ImDPPHHeW2-ieQiRh1xmevIuS4X4LN_axEC1MUIKr1VQu2jJArjBn4STiVJhE8VB4F5gyYzhvtyy4YSQ_Y6sS9tRWo9RIvgKgOoWBnxywGZmpCCUSYwf37DzQ2pTzg0YZEO5svpLvzRJvPdnpzdWC"/>
<div class="absolute inset-0 bg-gradient-to-t from-primary via-transparent to-transparent opacity-60"></div>
</div>
<div class="relative z-10 space-y-5">
<h2 class="text-4xl font-extrabold text-on-primary-container tracking-tighter leading-tight">Every story deserves a beautiful chapter.</h2>
<p class="text-on-primary-container/80 text-lg max-w-sm">Join a loving community where pets find their perfect families. Browse, connect, and give a pet their second chance at happiness.</p>
<div class="pt-10">
<span class="text-sm font-medium text-on-primary-container/90">Trusted by 2,000+ fosters &amp; owners</span>
</div>
</div>
</div>
<!-- Right Side: Signup Form -->
<div class="lg:col-span-7 p-8 md:p-16 lg:p-24 flex flex-col justify-center">
<!-- Brand Logo -->
<div class="mb-8">
<span class="text-sm font-semibold uppercase tracking-[0.32em] text-primary">Paws Home</span>
</div>
<div class="max-w-md w-full mx-auto lg:mx-0">
<h1 class="text-3xl font-bold text-on-surface mb-3 tracking-tight">Create your account</h1>
<p class="text-on-surface-variant mb-8">Start your journey toward finding or rehoming a friend.</p>
<?php if ($error): ?>
<div class="rounded-3xl border border-error/20 bg-error-container/10 p-4 text-sm text-error font-medium mb-6">
    <?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>
<form class="space-y-6" action="backend/signup.php" method="post">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="space-y-1.5">
<label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1" for="first_name">First Name</label>
<input class="w-full px-6 py-4 rounded-xl bg-surface-container-low border border-surface-container-high focus:ring-2 focus:ring-primary/30 focus:bg-white transition-all outline-none text-on-surface placeholder:text-outline/50" id="first_name" name="first_name" placeholder="Alex" type="text" required/>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1" for="last_name">Last Name</label>
<input class="w-full px-6 py-4 rounded-xl bg-surface-container-low border border-surface-container-high focus:ring-2 focus:ring-primary/30 focus:bg-white transition-all outline-none text-on-surface placeholder:text-outline/50" id="last_name" name="last_name" placeholder="Rivera" type="text" required/>
</div>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1" for="email">Email Address</label>
<input class="w-full px-6 py-4 rounded-xl bg-surface-container-low border border-surface-container-high focus:ring-2 focus:ring-primary/30 focus:bg-white transition-all outline-none text-on-surface placeholder:text-outline/50" id="email" name="email" placeholder="alex@example.com" type="email" required/>
</div>
<div class="space-y-1.5">
<label class="text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1" for="password">Password</label>
<div class="relative">
<input class="w-full px-6 py-4 rounded-xl bg-surface-container-low border border-surface-container-high focus:ring-2 focus:ring-primary/30 focus:bg-white transition-all outline-none text-on-surface placeholder:text-outline/50" id="password" name="password" placeholder="••••••••" type="password" required/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary" type="button">
<span class="material-symbols-outlined text-xl">visibility</span>
</button>
</div>
</div>
<div class="flex items-start gap-3 px-1">
<input class="mt-1 rounded border border-outline-variant text-primary focus:ring-primary transition-all" id="terms" type="checkbox" required/>
<label class="text-sm text-on-surface-variant leading-relaxed" for="terms">
                            I agree to the <a class="text-primary font-semibold hover:underline" href="#">Terms of Service</a> and <a class="text-primary font-semibold hover:underline" href="#">Privacy Policy</a>.
                        </label>
</div>
<button class="w-full py-5 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-full shadow-lg hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2" type="submit">
                        Create Account
                        <span class="material-symbols-outlined">arrow_forward</span>
</button>
</form>
<div class="mt-10 text-center text-sm text-on-surface-variant">
                        Already have an account? <a class="text-primary font-semibold hover:underline" href="login.php">Sign In</a>
</div>
<div class="mt-10 flex items-center gap-4">
<div class="h-px bg-surface-container-highest flex-1"></div>
<span class="text-xs font-bold text-outline uppercase tracking-widest">Social Signup</span>
<div class="h-px bg-surface-container-highest flex-1"></div>
</div>
<div class="mt-6 grid grid-cols-2 gap-4">
<button class="flex items-center justify-center gap-3 py-3 rounded-full border border-outline-variant/30 hover:bg-surface-container-low transition-colors" type="button">
<svg class="w-5 h-5" viewbox="0 0 24 24" aria-hidden="true">
<path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="currentColor"></path>
<path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="currentColor"></path>
<path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="currentColor"></path>
<path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="currentColor"></path>
</svg>
<span class="text-sm font-semibold">Google</span>
</button>
<button class="flex items-center justify-center gap-3 py-3 rounded-full border border-outline-variant/30 hover:bg-surface-container-low transition-colors" type="button">
<svg class="w-5 h-5" viewbox="0 0 24 24" aria-hidden="true">
<path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.341-3.369-1.341-.454-1.152-1.11-1.459-1.11-1.459-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.161 22 16.416 22 12z" fill="currentColor"></path>
</svg>
<span class="text-sm font-semibold">Facebook</span>
</button>
</div>
</div>
<div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none opacity-20">
<div class="absolute top-[10%] left-[5%] w-64 h-64 bg-primary rounded-full blur-[120px]"></div>
<div class="absolute bottom-[10%] right-[5%] w-96 h-96 bg-secondary-container rounded-full blur-[150px]"></div>
</div>
</body></html>
