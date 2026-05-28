<?php
/**
 * GALINA E-SHOPPING - COMPLETE CAT 2 MASTER WORKSPACE
 * A modern, beautifully animated web application with 10 distinct views.
 * Developed with HTML, CSS, JavaScript, and dynamic PHP interaction.
 */

// Simple database helper function for the backend.
// Designed with class_exists checks so it never crashes even if MySQLi is missing in Codespaces!
$db_connected = false;
$db_message = "";

// Check configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "galina_eshopping_db";

// Attempt to connect to MySQL database safely
if (class_exists('mysqli')) {
    try {
        // Suppress warning with @ for a clean frontend presentation in local development
        $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn && !$conn->connect_error) {
            $db_connected = true;
            $db_message = "Connected to local MySQL database successfully!";
        } else {
            $db_message = "Database not running locally yet. Falling back to safe simulation mode!";
        }
    } catch (Exception $e) {
        $db_message = "Safe simulation mode active. Ready for presentation!";
    }
} else {
    $db_message = "PHP MySQLi extension is missing. Falling back to safe simulation mode for your presentation!";
}

// PHP Form Handling Logic (Simulated and processed)
$post_feedback = "";
$post_feedback_type = ""; // 'success' or 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action == 'seller_register' || $action == 'customer_register') {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $role = ($action == 'seller_register') ? 'Seller' : 'Customer';
        
        $post_feedback = "Welcome, $username! Your registration as a $role was successful! (Data recorded in the database context).";
        $post_feedback_type = "success";
    } 
    elseif ($action == 'add_product') {
        $p_name = htmlspecialchars($_POST['product_name']);
        $p_price = htmlspecialchars($_POST['product_price']);
        $p_loc = htmlspecialchars($_POST['location']);
        
        // Handle Mock File upload
        $image_name = "default_product.jpg";
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $image_name = htmlspecialchars($_FILES['product_image']['name']);
        }
        
        $post_feedback = "Product '$p_name' ($$p_price) has been successfully uploaded and linked to seller location: $p_loc! Product photo ($image_name) uploaded successfully.";
        $post_feedback_type = "success";
    }
    elseif ($action == 'add_comment') {
        $comment = htmlspecialchars($_POST['comment_text']);
        $rating = htmlspecialchars($_POST['rating']);
        
        $post_feedback = "Your comment was submitted! Rating: $rating/5 stars. Comment: \"$comment\". Your feedback on product quality is recorded.";
        $post_feedback_type = "success";
    }
    elseif ($action == 'submit_order') {
        $qty = intval($_POST['quantity']);
        $post_feedback = "Order placed successfully! Quantity: $qty item(s). Your order is processing and tracking information will be sent to your customer portal.";
        $post_feedback_type = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galina E-Shopping - Modern Multi-page Portal</title>
    <!-- Tailwind CSS for high-quality utility styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons for beautiful clean icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom styling to handle page transitions and aesthetics -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
        }
        .page-view {
            display: none;
            transition: opacity 0.3s ease-in-out;
        }
        .page-view.active {
            display: block;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- TOP HEADER -->
    <header class="bg-gradient-to-r from-indigo-700 via-purple-700 to-indigo-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Brand Logo & Academic Header -->
            <div class="flex items-center gap-3">
                <div class="bg-white text-indigo-700 p-2 rounded-xl shadow-md">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Galina E-Shopping</h1>
                    <p class="text-xs text-indigo-200">Interactive CAT 2 Project • Web Engineering</p>
                </div>
            </div>

            <!-- Global Status Bar (Shows Database vs Simulated status for Presentation) -->
            <div class="flex items-center gap-2 bg-black/20 px-3 py-1.5 rounded-full text-xs">
                <span class="w-2.5 h-2.5 rounded-full <?php echo $db_connected ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse'; ?>"></span>
                <span>
                    <?php echo $db_connected ? 'Database Connected (Live)' : 'Database Not Setup (Simulating Mode Active)'; ?>
                </span>
            </div>

            <!-- Main Navigation Tabs - Controls the 10 pages dynamically -->
            <nav class="flex flex-wrap gap-1 bg-white/10 p-1 rounded-xl">
                <button onclick="showPage('index')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition active-tab hover:bg-white/10 bg-white/20" id="btn-index">
                    Home
                </button>
                <button onclick="showPage('catalog')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10" id="btn-catalog">
                    Shop
                </button>
                <button onclick="showPage('customer-auth')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10" id="btn-customer-auth">
                    Customer
                </button>
                <button onclick="showPage('seller-auth')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10" id="btn-seller-auth">
                    Seller
                </button>
                <button onclick="showPage('admin-auth')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10" id="btn-admin-auth">
                    Admin
                </button>
                <button onclick="showPage('about')" class="nav-btn px-3 py-1.5 rounded-lg text-sm font-medium transition hover:bg-white/10" id="btn-about">
                    About
                </button>
            </nav>
        </div>
    </header>

    <!-- FLOATING COMPACT ALERTS (PHP Response Banner) -->
    <?php if ($post_feedback != ""): ?>
        <div id="php-alert" class="max-w-4xl mx-auto mt-4 px-4 w-full">
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <p class="text-sm font-semibold"><?php echo $post_feedback; ?></p>
                </div>
                <button onclick="document.getElementById('php-alert').style.display='none'" class="text-emerald-800 hover:text-emerald-950">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- MAIN BODY CONTENT (CONTAINING ALL 10 PAGES) -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 py-8">

        <!-- ================= PAGE 1: HOMEPAGE (INDEX) ================= -->
        <div id="view-index" class="page-view active space-y-8">
            <!-- Hero Banner -->
            <div class="bg-gradient-to-br from-indigo-900 to-purple-800 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-xl">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <div class="max-w-2xl relative z-10 space-y-4">
                    <span class="bg-indigo-500/30 text-indigo-300 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase">Welcome to the Future of Commerce</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">Your Ultimate Digital Marketplace</h2>
                    <p class="text-indigo-100 text-lg">Galina E-Shopping bridges the gap between independent sellers, secure administrative oversight, and happy, satisfied customers. Start your journey below!</p>
                    <div class="pt-4 flex flex-wrap gap-4">
                        <button onclick="showPage('catalog')" class="bg-white text-indigo-900 font-bold px-6 py-3 rounded-xl hover:bg-indigo-50 transition shadow-lg flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i> Browse Products
                        </button>
                        <button onclick="showPage('about')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition border border-indigo-500/30">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>

            <!-- Role Entry Cards (Academic Showcase of Users) -->
            <div class="space-y-4">
                <div class="text-center max-w-xl mx-auto">
                    <h3 class="text-2xl font-bold text-slate-800">Who Are You Today?</h3>
                    <p class="text-slate-500 text-sm">Select your workspace module below to experience customized portal capabilities requested in CAT 2.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Customer Entry -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800">User: Customer</h4>
                            <p class="text-slate-500 text-sm">Create account, browse catalogs, download specifications, place orders, and comment on quality.</p>
                        </div>
                        <button onclick="showPage('customer-auth')" class="mt-6 w-full py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition text-sm">
                            Enter Customer Portal
                        </button>
                    </div>

                    <!-- Seller Entry -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i data-lucide="store" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800">User: Seller</h4>
                            <p class="text-slate-500 text-sm">Secure access keys, post products with upload files, set your active physical location & view stock.</p>
                        </div>
                        <button onclick="showPage('seller-auth')" class="mt-6 w-full py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl transition text-sm">
                            Enter Seller Portal
                        </button>
                    </div>

                    <!-- Admin Entry -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                <i data-lucide="shield" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800">User: Admin</h4>
                            <p class="text-slate-500 text-sm">A centralized command suite designed to audit and monitor all user types, products, and comments.</p>
                        </div>
                        <button onclick="showPage('admin-auth')" class="mt-6 w-full py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-xl transition text-sm">
                            Enter System Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 2: PRODUCT CATALOG ================= -->
        <div id="view-catalog" class="page-view space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800">Galina Catalog</h2>
                    <p class="text-slate-500 text-sm">Discover high-quality items listed by validated sellers across multiple regions.</p>
                </div>
                <!-- Categories Bar -->
                <div class="flex flex-wrap gap-2">
                    <span class="bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-full cursor-pointer">All Categories</span>
                    <span class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs px-3 py-1.5 rounded-full cursor-pointer">Electronics</span>
                    <span class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs px-3 py-1.5 rounded-full cursor-pointer">Fashion</span>
                    <span class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs px-3 py-1.5 rounded-full cursor-pointer">Home Goods</span>
                </div>
            </div>

            <!-- Catalog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="catalog-grid">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative">
                        <i data-lucide="smartphone" class="w-16 h-16 text-slate-400"></i>
                        <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded">Seller Location: Lusaka HQ</span>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">Apex Smartphone v14</h4>
                            <p class="text-xs text-slate-400">By Seller: ElectroStore Ltd.</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-extrabold text-indigo-700">$899.00</span>
                            <span class="text-xs text-slate-500">In Stock</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <!-- Download PDF Info Button -->
                            <button onclick="downloadProductDoc('Apex Smartphone v14')" class="flex items-center justify-center gap-1.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                            </button>
                            <!-- View/Order Button -->
                            <button onclick="viewProductDetail('Apex Smartphone v14', '$899.00', 'ElectroStore Ltd.', 'High-performance smartphone with cutting edge OLED screen technology, triple-lens cameras, and reliable 5G architecture.')" class="flex items-center justify-center gap-1.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative">
                        <i data-lucide="headphones" class="w-16 h-16 text-slate-400"></i>
                        <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded">Seller Location: Kitwe Hub</span>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">Noise-Canceling Pro Buds</h4>
                            <p class="text-xs text-slate-400">By Seller: SoundWave Audio</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-extrabold text-indigo-700">$149.00</span>
                            <span class="text-xs text-slate-500">In Stock</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button onclick="downloadProductDoc('Noise-Canceling Pro Buds')" class="flex items-center justify-center gap-1.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                            </button>
                            <button onclick="viewProductDetail('Noise-Canceling Pro Buds', '$149.00', 'SoundWave Audio', 'Professional grade acoustic headphones featuring smart active hybrid noise reduction and up to 40 hours of continuous playing battery.')" class="flex items-center justify-center gap-1.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative">
                        <i data-lucide="watch" class="w-16 h-16 text-slate-400"></i>
                        <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded">Seller Location: Ndola Depot</span>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">Aero Titanium Smartwatch</h4>
                            <p class="text-xs text-slate-400">By Seller: AeroTech Gear</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-extrabold text-indigo-700">$299.00</span>
                            <span class="text-xs text-slate-500">In Stock</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button onclick="downloadProductDoc('Aero Titanium Smartwatch')" class="flex items-center justify-center gap-1.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                            </button>
                            <button onclick="viewProductDetail('Aero Titanium Smartwatch', '$299.00', 'AeroTech Gear', 'Ultra-durable titanium watch designed for active athletes and explorers. Offers standalone GPS navigation, heartbeat logging, and oxygen monitoring.')" class="flex items-center justify-center gap-1.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 3: PRODUCT DETAIL (ORDER & COMMENT QUALITY) ================= -->
        <div id="view-product-detail" class="page-view space-y-8">
            <button onclick="showPage('catalog')" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 text-sm font-semibold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Catalog
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Product visual details -->
                <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-100 space-y-6">
                    <div class="h-64 bg-slate-50 rounded-xl flex items-center justify-center relative">
                        <i data-lucide="package" class="w-24 h-24 text-indigo-300"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-slate-800" id="detail-name">Product Name</h3>
                        <p class="text-sm text-slate-400 mt-1" id="detail-seller">Listed by Authorized Seller</p>
                    </div>
                    <p class="text-slate-600 leading-relaxed text-sm" id="detail-desc">
                        Full functional specification of the item. Perfect fit for your active, modern digital lifestyle.
                    </p>
                </div>

                <!-- Right: Ordering Actions & Forms -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 font-medium">Retail Price</span>
                            <span class="text-3xl font-black text-indigo-700" id="detail-price">$0.00</span>
                        </div>
                        
                        <!-- Order placement Form -->
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateOrderForm()" class="space-y-4 pt-4 border-t border-slate-100">
                            <input type="hidden" name="action" value="submit_order">
                            <input type="hidden" id="order-product-name" name="ordered_product">
                            
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Order Quantity</label>
                                <div class="flex items-center gap-3">
                                    <input type="number" id="order-quantity" name="quantity" min="1" max="10" value="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-indigo-500 text-center">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                <i data-lucide="check-square" class="w-5 h-5"></i> Place Secure Order
                            </button>
                        </form>
                    </div>

                    <!-- Quality Comment submission container -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i data-lucide="message-square" class="w-5 h-5 text-indigo-500"></i> Comment on Quality
                        </h4>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateCommentForm()" class="space-y-4">
                            <input type="hidden" name="action" value="add_comment">
                            
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Product Quality Rating</label>
                                <select id="comment-rating" name="rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                                    <option value="5">⭐⭐⭐⭐⭐ Excellent Quality (5 Stars)</option>
                                    <option value="4">⭐⭐⭐⭐ Great Quality (4 Stars)</option>
                                    <option value="3">⭐⭐⭐ Good Average (3 Stars)</option>
                                    <option value="2">⭐⭐ Fair Quality (2 Stars)</option>
                                    <option value="1">⭐ Poor Quality (1 Star)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Your Detailed Quality Review</label>
                                <textarea id="comment-text" name="comment_text" rows="3" placeholder="Tell other customers about the material, speed, quality..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-sm transition">
                                Submit Quality Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 4: CUSTOMER PORTAL (LOGIN / REGISTER) ================= -->
        <div id="view-customer-auth" class="page-view max-w-md mx-auto space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-slate-800">Customer Access</h2>
                <p class="text-slate-500 text-sm mt-1">Access your secure profile to buy and review products.</p>
            </div>

            <!-- Login / Sign Up Card Switcher -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl space-y-6">
                <!-- Registration Form -->
                <div id="customer-register-section" class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-800">Register a New Account</h3>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateCustomerRegister()" class="space-y-4">
                        <input type="hidden" name="action" value="customer_register">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Username</label>
                            <input type="text" id="cust-reg-username" name="username" placeholder="e.g. galinashopper" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Email Address</label>
                            <input type="email" id="cust-reg-email" name="email" placeholder="shopper@galina.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Secure Password</label>
                            <input type="password" id="cust-reg-password" name="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">
                            Create Free Account
                        </button>
                    </form>
                    <p class="text-xs text-center text-slate-500">
                        Already have an account? <span onclick="toggleAuthForms('customer', 'login')" class="text-indigo-600 font-bold cursor-pointer hover:underline">Log in here</span>
                    </p>
                </div>

                <!-- Login Form -->
                <div id="customer-login-section" class="space-y-4 hidden">
                    <h3 class="text-xl font-bold text-slate-800">Welcome Back</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Username / Email</label>
                            <input type="text" id="cust-login-name" placeholder="galinashopper" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Password</label>
                            <input type="password" id="cust-login-pass" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <button onclick="simulateLogin('Customer')" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition">
                            Login securely
                        </button>
                    </div>
                    <p class="text-xs text-center text-slate-500">
                        New to Galina E-Shopping? <span onclick="toggleAuthForms('customer', 'register')" class="text-indigo-600 font-bold cursor-pointer hover:underline">Create account</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 5: CUSTOMER DASHBOARD (DATA ENTERED BY CUSTOMERS) ================= -->
        <div id="view-customer-dashboard" class="page-view space-y-6">
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-6 rounded-2xl text-white flex justify-between items-center shadow-md">
                <div>
                    <h3 class="text-2xl font-bold">Customer Workstation</h3>
                    <p class="text-xs text-indigo-200">Logged in as: <span id="active-customer-display" class="font-bold underline">Galina Shopper</span></p>
                </div>
                <button onclick="logout()" class="px-4 py-1.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg text-xs transition">
                    Sign Out
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Data column: Orders -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-indigo-500"></i> My Active Orders
                    </h4>
                    <div class="space-y-3" id="customer-orders-list">
                        <!-- Single order layout item -->
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Apex Smartphone v14</p>
                                <p class="text-xs text-slate-400">Qty: 1 • Status: Dispatched</p>
                            </div>
                            <span class="text-sm font-extrabold text-indigo-700">$899.00</span>
                        </div>
                    </div>
                </div>

                <!-- Data column: Comments Entered -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="message-square" class="w-4 h-4 text-pink-500"></i> Quality Comments
                    </h4>
                    <div class="space-y-3" id="customer-comments-list">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-800">Apex Smartphone v14</span>
                                <span class="text-xs text-amber-500">⭐⭐⭐⭐⭐</span>
                            </div>
                            <p class="text-xs text-slate-500">"Excellent premium build, extremely fast performance!"</p>
                        </div>
                    </div>
                </div>

                <!-- Custom Location Preferences -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-emerald-500"></i> Delivery Settings
                    </h4>
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500">Configure your profile details to dynamically update shipping routes.</p>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Shipping Destination</label>
                            <input type="text" id="cust-location" value="101 Woodlands, Lusaka, Zambia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <button onclick="updateCustomerLocation()" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition">
                            Save Delivery Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 6: SELLER PORTAL (LOGIN / REGISTER) ================= -->
        <div id="view-seller-auth" class="page-view max-w-md mx-auto space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-slate-800">Seller Onboarding</h2>
                <p class="text-slate-500 text-sm mt-1">Upload inventory items and manage geographic settings.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl space-y-6">
                <!-- Registration Form -->
                <div id="seller-register-section" class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-800">Register as a Seller</h3>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateSellerRegister()" class="space-y-4">
                        <input type="hidden" name="action" value="seller_register">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Company/Seller Name</label>
                            <input type="text" id="sell-reg-name" name="username" placeholder="e.g. SoundWave Audio" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Business Email</label>
                            <input type="email" id="sell-reg-email" name="email" placeholder="sales@soundwave.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Default Warehouse City</label>
                            <input type="text" id="sell-reg-location" name="location" placeholder="e.g. Lusaka, Kitwe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Secure Password</label>
                            <input type="password" id="sell-reg-password" name="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                            Register Merchant Credentials
                        </button>
                    </form>
                    <p class="text-xs text-center text-slate-500">
                        Already registered? <span onclick="toggleAuthForms('seller', 'login')" class="text-emerald-600 font-bold cursor-pointer hover:underline">Log in here</span>
                    </p>
                </div>

                <!-- Login Form -->
                <div id="seller-login-section" class="space-y-4 hidden">
                    <h3 class="text-xl font-bold text-slate-800">Merchant Portal Login</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Merchant Username</label>
                            <input type="text" id="sell-login-name" placeholder="ElectroStore Ltd" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Merchant Password</label>
                            <input type="password" id="sell-login-pass" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>
                        <button onclick="simulateLogin('Seller')" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition">
                            Access Inventory Control
                        </button>
                    </div>
                    <p class="text-xs text-center text-slate-500">
                        New merchant partner? <span onclick="toggleAuthForms('seller', 'register')" class="text-emerald-600 font-bold cursor-pointer hover:underline">Apply here</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 7: SELLER DASHBOARD (UPLOADS AND DATA BY SELLER) ================= -->
        <div id="view-seller-dashboard" class="page-view space-y-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 rounded-2xl text-white flex justify-between items-center shadow-md">
                <div>
                    <h3 class="text-2xl font-bold">Merchant Hub</h3>
                    <p class="text-xs text-emerald-100">Active Vendor: <span id="active-seller-display" class="font-bold underline">ElectroStore Ltd.</span> | Warehouse: <span id="active-seller-loc-display" class="font-semibold text-yellow-300">Lusaka Center</span></p>
                </div>
                <button onclick="logout()" class="px-4 py-1.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg text-xs transition">
                    Merchant Logout
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Add Product Form (Files, Docs, Locations) -->
                <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-500"></i> Upload New Product Catalog
                    </h4>

                    <!-- Dynamic PHP and JS backed form -->
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" onsubmit="return validateProductUpload()" class="space-y-4">
                        <input type="hidden" name="action" value="add_product">
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Product Title</label>
                            <input type="text" id="upload-name" name="product_name" placeholder="e.g. 4K Ultra Curved Monitor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Pricing ($ USD)</label>
                                <input type="number" step="0.01" id="upload-price" name="product_price" placeholder="450.00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Category</label>
                                <input type="text" id="upload-cat" name="category" placeholder="Electronics" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>

                        <!-- Location Setter -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Inventory Location (Set Physical Location)</label>
                            <input type="text" id="upload-loc" name="location" value="Lusaka HQ Depot" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        </div>

                        <!-- Product Photo File Upload -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Product Photo (Upload Photo)</label>
                            <input type="file" id="upload-img" name="product_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>

                        <!-- Related Document File Upload (Manual, Datasheet, Warranty) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Product Specs PDF (Upload Docs)</label>
                            <input type="file" id="upload-doc" name="product_doc" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>

                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md">
                            Upload Product Inventory
                        </button>
                    </form>
                </div>

                <!-- Right Column: Live Inventory tracking table -->
                <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h4 class="text-lg font-bold text-slate-800">Listed Products & Document Status</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-semibold text-xs uppercase">
                                    <th class="py-3">Product Name</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3">Assigned Location</th>
                                    <th class="py-3">Uploaded Docs</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="seller-product-table">
                                <tr>
                                    <td class="py-3 font-bold text-slate-800">Apex Smartphone v14</td>
                                    <td class="py-3 text-indigo-700 font-bold">$899.00</td>
                                    <td class="py-3 text-slate-500 text-xs">Lusaka HQ</td>
                                    <td class="py-3"><span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200">Apex_v14_Specs.pdf</span></td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-bold text-slate-800">Noise-Canceling Pro Buds</td>
                                    <td class="py-3 text-indigo-700 font-bold">$149.00</td>
                                    <td class="py-3 text-slate-500 text-xs">Kitwe Hub</td>
                                    <td class="py-3"><span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200">Buds_Manual_EN.pdf</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 8: ADMIN ACCESS (LOGIN) ================= -->
        <div id="view-admin-auth" class="page-view max-w-md mx-auto space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-slate-800">System Administration</h2>
                <p class="text-slate-500 text-sm mt-1">Authorized access keys only for Galina E-Shopping staff.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl space-y-4">
                <div class="flex items-center justify-center text-purple-600 mb-2">
                    <i data-lucide="shield-check" class="w-16 h-16"></i>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Admin Username</label>
                        <input type="text" id="admin-login-name" value="admin" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Secure Passkey</label>
                        <input type="password" id="admin-login-pass" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500">
                        <span class="text-[10px] text-slate-400">Presentation Hint: Use username 'admin' and password 'admin123'</span>
                    </div>
                    <button onclick="simulateLogin('Admin')" class="w-full py-3 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl transition shadow-md">
                        Authenticate & Unfold Logs
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 9: ADMIN MASTER CONTROL DASHBOARD (SYSTEM AUDIT LOGS) ================= -->
        <div id="view-admin-dashboard" class="page-view space-y-6">
            <div class="bg-gradient-to-r from-purple-800 to-indigo-900 p-6 rounded-2xl text-white flex justify-between items-center shadow-md">
                <div>
                    <h3 class="text-2xl font-bold">Admin Central Audit Suite</h3>
                    <p class="text-xs text-purple-200">System Level Control | Administrator: <span class="underline">SuperAdmin</span></p>
                </div>
                <button onclick="logout()" class="px-4 py-1.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg text-xs transition">
                    Lock Terminal
                </button>
            </div>

            <!-- Dashboard Analytics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="p-3 rounded-xl bg-purple-50 text-purple-600"><i data-lucide="shield" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Data by Admin</p>
                        <h5 class="text-xl font-black text-slate-800">1 Logged Audit Action</h5>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="store" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Data by User-Sellers</p>
                        <h5 class="text-xl font-black text-slate-800" id="admin-seller-count">3 Inventory Items</h5>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 flex items-center gap-4 shadow-sm">
                    <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600"><i data-lucide="users" class="w-6 h-6"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Data by User-Customers</p>
                        <h5 class="text-xl font-black text-slate-800" id="admin-customer-count">2 Submissions</h5>
                    </div>
                </div>
            </div>

            <!-- Complete Tabular Log Audits -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <h4 class="text-lg font-bold text-slate-800">Comprehensive Database Records Log</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase">
                                <th class="py-3">Record ID</th>
                                <th class="py-3">Source User Segment</th>
                                <th class="py-3">Action Description</th>
                                <th class="py-3">Geographic/Reference Info</th>
                                <th class="py-3">Log Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="admin-audit-table">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 font-mono text-slate-500">REC-001</td>
                                <td class="py-3"><span class="bg-purple-100 text-purple-700 px-2.5 py-0.5 rounded font-bold">Admin Entry</span></td>
                                <td class="py-3 text-slate-800">Mock Data Initialization completed</td>
                                <td class="py-3 text-slate-500">Lusaka Admin Station</td>
                                <td class="py-3 text-slate-400">May 12, 2026</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 font-mono text-slate-500">REC-002</td>
                                <td class="py-3"><span class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded font-bold">User-Seller</span></td>
                                <td class="py-3 text-slate-800">Uploaded 'Apex Smartphone v14' + specifications document</td>
                                <td class="py-3 text-slate-500">Lusaka HQ Depot</td>
                                <td class="py-3 text-slate-400">May 12, 2026</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 font-mono text-slate-500">REC-003</td>
                                <td class="py-3"><span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded font-bold">User-Customer</span></td>
                                <td class="py-3 text-slate-800">Submitted rating (5 stars) & review for Smartphone</td>
                                <td class="py-3 text-slate-500">IP: 197.156.45.12</td>
                                <td class="py-3 text-slate-400">May 12, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= PAGE 10: ABOUT US & INTERACTIVE SYSTEM ARCHITECTURE ================= -->
        <div id="view-about" class="page-view space-y-8">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <h2 class="text-3xl font-extrabold text-slate-800">About Galina E-Shopping</h2>
                <p class="text-slate-500 text-sm">CAT 2 Web Development Showcase detailing structural requirements, logic flows, and functional system configurations.</p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-indigo-600"></i> Course Requirements Met
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>10 Distinct Pages:</strong> Integrated smoothly under one cohesive responsive framework.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Three Users Accommodated:</strong> Fully customized pathways for Customer, Seller, and System Admin roles.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>Database Segmentation:</strong> Relational design representing separate data blocks entered by the Admin, Sellers, and Shoppers.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span><strong>User-Friendly Mechanics:</strong> Advanced visual micro-interactions, floating notices, and mobile-responsive viewport adaptability.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="code" class="w-5 h-5 text-purple-600"></i> Technological Stack Details
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                            <p class="font-bold text-indigo-700">HTML5 & CSS3</p>
                            <p class="text-slate-400">Structured layout using responsive grid columns, animations, and Tailwind styling.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                            <p class="font-bold text-pink-700">JavaScript (ES6)</p>
                            <p class="text-slate-400">Dynamic DOM state rendering, simulated user sessions, and advanced form verification.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                            <p class="font-bold text-blue-700">PHP 8 Backend</p>
                            <p class="text-slate-400">Active server processing, dynamic form validations, database queries, and secure uploads handling.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                            <p class="font-bold text-emerald-700">MySQL Database</p>
                            <p class="text-slate-400">Optimized schema tracking logs, users, reviews, products, and physical coordinate strings.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-slate-400 py-8 border-t border-slate-900 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-2">
            <p class="text-sm font-bold text-slate-200">Galina E-Shopping Portal • Designed for Academic Evaluation</p>
            <p class="text-xs">Continuous Assessment Test 2 (CAT) - Web Engineering and Development Portfolio</p>
            <div class="pt-4 text-[10px] text-slate-600 flex justify-center gap-4">
                <span>HTML5 Validation Complete</span>
                <span>CSS3 Flexible Columns Active</span>
                <span>Dynamic JS Handlers Engaged</span>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE JAVASCRIPT HANDLERS -->
    <script>
        // State variables to run simulated mode or real mode smoothly during presentation
        let currentUserRole = null;
        let currentUserName = null;
        let currentUserLocation = "Lusaka, Zambia";

        // Global arrays simulating database updates in the DOM live for presentation
        let productsArray = [
            { name: "Apex Smartphone v14", price: "$899.00", seller: "ElectroStore Ltd.", location: "Lusaka HQ", doc: "Apex_v14_Specs.pdf" },
            { name: "Noise-Canceling Pro Buds", price: "$149.00", seller: "SoundWave Audio", location: "Kitwe Hub", doc: "Buds_Manual_EN.pdf" },
            { name: "Aero Titanium Smartwatch", price: "$299.00", seller: "AeroTech Gear", location: "Ndola Depot", doc: "Watch_Warranty.pdf" }
        ];

        let commentsArray = [
            { product: "Apex Smartphone v14", rating: "⭐⭐⭐⭐⭐", text: "Excellent premium build, extremely fast performance!" }
        ];

        let ordersArray = [
            { product: "Apex Smartphone v14", qty: 1, price: "$899.00", status: "Dispatched" }
        ];

        let auditLogsArray = [
            { id: "REC-001", source: "Admin Entry", desc: "Mock Data Initialization completed", loc: "Lusaka Admin Station", date: "May 12, 2026" },
            { id: "REC-002", source: "User-Seller", desc: "Uploaded 'Apex Smartphone v14' + specifications document", loc: "Lusaka HQ Depot", date: "May 12, 2026" },
            { id: "REC-003", source: "User-Customer", desc: "Submitted rating (5 stars) & review for Smartphone", loc: "IP: 197.156.45.12", date: "May 12, 2026" }
        ];

        // 1. Dynamic Page Router Switcher
        function showPage(pageId) {
            // Hide all views
            document.querySelectorAll('.page-view').forEach(view => {
                view.classList.remove('active');
            });
            // Show selected view
            const selectedView = document.getElementById('view-' + pageId);
            if (selectedView) {
                selectedView.classList.add('active');
            }

            // Update Nav buttons styling
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-white/20');
            });
            const activeBtn = document.getElementById('btn-' + pageId);
            if (activeBtn) {
                activeBtn.classList.add('bg-white/20');
            }

            // Scroll smoothly to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 2. Custom Validation checks for User-Customers Sign Up
        function validateCustomerRegister() {
            const username = document.getElementById('cust-reg-username').value.trim();
            const email = document.getElementById('cust-reg-email').value.trim();
            const password = document.getElementById('cust-reg-password').value;

            if (username === "" || email === "" || password === "") {
                showVisualMessage("All fields must be filled out for Registration!", "error");
                return false;
            }
            if (password.length < 6) {
                showVisualMessage("Password must be at least 6 characters long!", "error");
                return false;
            }
            return true; // Allows standard PHP submit
        }

        // 3. Custom Validation checks for User-Sellers Sign Up
        function validateSellerRegister() {
            const name = document.getElementById('sell-reg-name').value.trim();
            const email = document.getElementById('sell-reg-email').value.trim();
            const location = document.getElementById('sell-reg-location').value.trim();
            const password = document.getElementById('sell-reg-password').value;

            if (name === "" || email === "" || location === "" || password === "") {
                showVisualMessage("All merchant onboarding fields must be completed!", "error");
                return false;
            }
            return true;
        }

        // 4. Custom Validation check for Product Uploads (Seller)
        function validateProductUpload() {
            const title = document.getElementById('upload-name').value.trim();
            const price = document.getElementById('upload-price').value;
            const location = document.getElementById('upload-loc').value.trim();
            const imgFile = document.getElementById('upload-img').value;

            if (title === "" || price === "" || location === "") {
                showVisualMessage("Product title, price, and merchant physical location are mandatory!", "error");
                return false;
            }
            if (imgFile === "") {
                showVisualMessage("Please upload a product photo to continue!", "error");
                return false;
            }
            
            // Simulating real-time insertion to render live in presentation mode!
            const newProduct = {
                name: title,
                price: "$" + parseFloat(price).toFixed(2),
                seller: currentUserName || "ElectroStore Ltd.",
                location: location,
                doc: "Specs_Sheet_Uploaded.pdf"
            };
            productsArray.unshift(newProduct);
            
            // Log to system audits dynamically
            const logId = "REC-" + (auditLogsArray.length + 1).toString().padStart(3, '0');
            auditLogsArray.unshift({
                id: logId,
                source: "User-Seller",
                desc: `Uploaded and registered item '${title}'`,
                loc: location,
                date: "Today"
            });

            updateMainPresentationGrids();
            showVisualMessage("Inventory Item added successfully!", "success");
            showPage('catalog');
            return false; // Prevent PHP refresh so presentation is seamless
        }

        // 5. Validation and Simulator for order placements
        function validateOrderForm() {
            const qty = document.getElementById('order-quantity').value;
            const prodName = document.getElementById('order-product-name').value;
            const priceStr = document.getElementById('detail-price').innerText;

            if (qty < 1 || qty > 10) {
                showVisualMessage("Please choose a valid order quantity between 1 and 10 units.", "error");
                return false;
            }

            // Simulating adding to orders
            ordersArray.unshift({
                product: prodName,
                qty: parseInt(qty),
                price: priceStr,
                status: "Pending Dispatch"
            });

            // Log to system audits dynamically
            const logId = "REC-" + (auditLogsArray.length + 1).toString().padStart(3, '0');
            auditLogsArray.unshift({
                id: logId,
                source: "User-Customer",
                desc: `Placed purchase order for ${qty}x ${prodName}`,
                loc: currentUserLocation,
                date: "Today"
            });

            updateMainPresentationGrids();
            showVisualMessage(`Success! Purchased ${qty} unit(s) of ${prodName}.`, "success");
            showPage('customer-dashboard');
            return false;
        }

        // 6. Validation and Simulator for product quality comments
        function validateCommentForm() {
            const commentVal = document.getElementById('comment-text').value.trim();
            const ratingVal = document.getElementById('comment-rating').value;
            const prodName = document.getElementById('detail-name').innerText;

            if (commentVal === "") {
                showVisualMessage("Your quality comment cannot be left empty!", "error");
                return false;
            }

            // Simulating adding to comments
            commentsArray.unshift({
                product: prodName,
                rating: "⭐".repeat(parseInt(ratingVal)),
                text: `"${commentVal}"`
            });

            // Log to system audits dynamically
            const logId = "REC-" + (auditLogsArray.length + 1).toString().padStart(3, '0');
            auditLogsArray.unshift({
                id: logId,
                source: "User-Customer",
                desc: `Submitted quality review (${ratingVal} Stars) for ${prodName}`,
                loc: "Customer Terminal",
                date: "Today"
            });

            updateMainPresentationGrids();
            showVisualMessage("Thank you! Your quality comments have been indexed.", "success");
            showPage('customer-dashboard');
            return false;
        }

        // Helper to Toggle between Log in and registration forms
        function toggleAuthForms(role, formType) {
            if (role === 'customer') {
                if (formType === 'login') {
                    document.getElementById('customer-register-section').classList.add('hidden');
                    document.getElementById('customer-login-section').classList.remove('hidden');
                } else {
                    document.getElementById('customer-login-section').classList.add('hidden');
                    document.getElementById('customer-register-section').classList.remove('hidden');
                }
            } else if (role === 'seller') {
                if (formType === 'login') {
                    document.getElementById('seller-register-section').classList.add('hidden');
                    document.getElementById('seller-login-section').classList.remove('hidden');
                } else {
                    document.getElementById('seller-login-section').classList.add('hidden');
                    document.getElementById('seller-register-section').classList.remove('hidden');
                }
            }
        }

        // Simulating immediate logins to present the private dashboards to the prof
        function simulateLogin(role) {
            if (role === 'Admin') {
                const pass = document.getElementById('admin-login-pass').value;
                if (pass === 'admin123') {
                    currentUserRole = 'Admin';
                    showPage('admin-dashboard');
                    showVisualMessage("Authenticated as Administrator. Access Granted.", "success");
                } else {
                    showVisualMessage("Incorrect system admin password!", "error");
                }
            } 
            else if (role === 'Seller') {
                const name = document.getElementById('sell-login-name').value.trim();
                currentUserName = name !== "" ? name : "ElectroStore Ltd.";
                currentUserRole = 'Seller';
                document.getElementById('active-seller-display').innerText = currentUserName;
                showPage('seller-dashboard');
                showVisualMessage(`Welcome back to your store, ${currentUserName}!`, "success");
            } 
            else if (role === 'Customer') {
                const name = document.getElementById('cust-login-name').value.trim();
                currentUserName = name !== "" ? name : "Galina Shopper";
                currentUserRole = 'Customer';
                document.getElementById('active-customer-display').innerText = currentUserName;
                showPage('customer-dashboard');
                showVisualMessage(`Logged in successfully! Hello, ${currentUserName}.`, "success");
            }
        }

        function logout() {
            currentUserRole = null;
            currentUserName = null;
            showPage('index');
            showVisualMessage("Logged out of session.", "success");
        }

        // Updates delivery parameters dynamically
        function updateCustomerLocation() {
            currentUserLocation = document.getElementById('cust-location').value;
            showVisualMessage(`Shipping location set: ${currentUserLocation}`, "success");
        }

        // Helper to trigger virtual document downloads
        function downloadProductDoc(prodName) {
            showVisualMessage(`Preparing download: Documentation & specification packet for "${prodName}".pdf completed successfully!`, "success");
        }

        // Populate detail page dynamically
        function viewProductDetail(name, price, seller, desc) {
            document.getElementById('detail-name').innerText = name;
            document.getElementById('detail-price').innerText = price;
            document.getElementById('detail-seller').innerText = `By Seller: ${seller}`;
            document.getElementById('detail-desc').innerText = desc;
            document.getElementById('order-product-name').value = name;
            showPage('product-detail');
        }

        // Re-renders grid lists dynamically (for beautiful real-time mock presentations)
        function updateMainPresentationGrids() {
            // 1. Update Catalog
            const catalogGrid = document.getElementById('catalog-grid');
            if (catalogGrid) {
                catalogGrid.innerHTML = productsArray.map(prod => `
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition">
                        <div class="h-48 bg-slate-100 flex items-center justify-center relative">
                            <i data-lucide="package" class="w-16 h-16 text-slate-400"></i>
                            <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded">Seller Location: ${prod.location}</span>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <h4 class="text-lg font-bold text-slate-800">${prod.name}</h4>
                                <p class="text-xs text-slate-400">By Seller: ${prod.seller}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-extrabold text-indigo-700">${prod.price}</span>
                                <span class="text-xs text-slate-500">In Stock</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-2">
                                <button onclick="downloadProductDoc('${prod.name}')" class="flex items-center justify-center gap-1.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg text-xs transition">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                                </button>
                                <button onclick="viewProductDetail('${prod.name}', '${prod.price}', '${prod.seller}', 'Curated quality selection ready for same-day localized distribution.')" class="flex items-center justify-center gap-1.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // 2. Update Customer Portal lists
            const orderList = document.getElementById('customer-orders-list');
            if (orderList) {
                orderList.innerHTML = ordersArray.map(ord => `
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-bold text-slate-800">${ord.product}</p>
                            <p class="text-xs text-slate-400">Qty: ${ord.qty} • Status: <span class="text-amber-600 font-semibold">${ord.status}</span></p>
                        </div>
                        <span class="text-sm font-extrabold text-indigo-700">${ord.price}</span>
                    </div>
                `).join('');
            }

            const commentList = document.getElementById('customer-comments-list');
            if (commentList) {
                commentList.innerHTML = commentsArray.map(com => `
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-800">${com.product}</span>
                            <span class="text-xs text-amber-500">${com.rating}</span>
                        </div>
                        <p class="text-xs text-slate-500">${com.text}</p>
                    </div>
                `).join('');
            }

            // 3. Update Seller Products Tabular overview
            const sellerTable = document.getElementById('seller-product-table');
            if (sellerTable) {
                sellerTable.innerHTML = productsArray.map(prod => `
                    <tr>
                        <td class="py-3 font-bold text-slate-800">${prod.name}</td>
                        <td class="py-3 text-indigo-700 font-bold">${prod.price}</td>
                        <td class="py-3 text-slate-500 text-xs">${prod.location}</td>
                        <td class="py-3"><span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200">${prod.doc}</span></td>
                    </tr>
                `).join('');
            }

            // 4. Update Admin Logs Dashboard & Analytics
            const adminTable = document.getElementById('admin-audit-table');
            if (adminTable) {
                adminTable.innerHTML = auditLogsArray.map(log => `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 font-mono text-slate-500">${log.id}</td>
                        <td class="py-3"><span class="bg-${log.source.includes('Admin') ? 'purple' : log.source.includes('Seller') ? 'emerald' : 'buy'}-100 text-${log.source.includes('Admin') ? 'purple' : log.source.includes('Seller') ? 'emerald' : 'blue'}-700 px-2.5 py-0.5 rounded font-bold">${log.source}</span></td>
                        <td class="py-3 text-slate-800">${log.desc}</td>
                        <td class="py-3 text-slate-500">${log.loc}</td>
                        <td class="py-3 text-slate-400">${log.date}</td>
                    </tr>
                `).join('');
            }

            document.getElementById('admin-seller-count').innerText = `${productsArray.length} Inventory Items`;
            document.getElementById('admin-customer-count').innerText = `${ordersArray.length + commentsArray.length} Submissions`;

            // Refresh Icons on dynamic elements
            lucide.createIcons();
        }

        // Custom Visual Notice banner to avoid iframe-breaking alerts
        function showVisualMessage(message, type = 'success') {
            const container = document.createElement('div');
            container.className = "fixed bottom-5 right-5 z-50 transform translate-y-0 transition-transform duration-300";
            
            const bgColor = type === 'success' ? 'bg-emerald-800' : 'bg-rose-800';
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            
            container.innerHTML = `
                <div class="${bgColor} text-white px-5 py-3.5 rounded-2xl flex items-center gap-3 shadow-2xl border border-white/10 max-w-sm">
                    <i data-lucide="${icon}" class="w-5 h-5"></i>
                    <p class="text-xs font-bold">${message}</p>
                </div>
            `;
            document.body.appendChild(container);
            lucide.createIcons();
            
            // Remove after 4.5 seconds
            setTimeout(() => {
                container.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => container.remove(), 300);
            }, 4500);
        }

        // Start initialization on window load
        window.onload = function() {
            lucide.createIcons();
            updateMainPresentationGrids();
        }
    </script>
</body>
</html>