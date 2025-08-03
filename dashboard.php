<?php
// dashboard.php
require_once __DIR__ . '/auth_config.php'; // Fix: Using __DIR__ for absolute path
requireLogin(); // Ensure user is logged in to access this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9O5SmXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl8W2NfM5KDD+ptx" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Chart.js CDN for dynamic graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #0d7878;
            --primary-dark: #065e5e;
            --primary-light: #16a6a6;
            --secondary-color: #2C3E50;
            --accent-color: #08821c;
            --success-color: #27AE60;
            --warning-color: #F39C12;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6; /* Light gray background */
        }
        .sidebar {
            width: 260px; /* Consistent width for sidebar */
            background-color: var(--primary-color); /* Use primary color */
            color: #e2e8f0; /* Light gray text */
            flex-shrink: 0; /* Prevent sidebar from shrinking on smaller screens */
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem; /* Rounded corners */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease-in-out;
        }
        .nav-link:hover {
            background-color: var(--primary-dark); /* Darker primary on hover */
        }
        .nav-link.active {
            background-color: var(--primary-light); /* Lighter primary for active link */
            font-weight: 600;
        }
        .tab-content {
            display: block; /* Default to block, will be hidden/shown by new JS */
        }
        .logout-btn {
            padding: 10px 20px;
            border-radius: 25px; /* Rounded button */
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px; /* Space between icon and text */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Subtle shadow */
            transition: all 0.3s ease;

            /* Apply new colors */
            background-color: var(--primary-color);
            color: white; /* Ensure text is readable */
            border-color: var(--primary-color); /* Match border color */
        }
        .logout-btn:hover {
            transform: translateY(-2px); /* Slight lift on hover */
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
            background-color: var(--primary-dark); /* Darker shade on hover */
            border-color: var(--primary-dark);
        }
        .logout-btn .fa-sign-out-alt {
            font-size: 1.2em; /* Slightly larger icon */
        }

        /* Dashboard specific styles for stats cards */
        .stat-card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1rem 1.5rem; /* Adjusted padding for rectangular shape */
            display: flex;
            flex-direction: row; /* Arrange items in a row */
            justify-content: space-between; /* Space out content and icon */
            align-items: center; /* Vertically align items */
            transition: transform 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon-wrapper {
            /* No background color or border-radius for the wrapper itself */
            padding: 0; /* Remove padding from wrapper */
            font-size: 2.5rem; /* Larger icon size */
            margin-bottom: 0; /* Remove margin-bottom */
        }

        .stat-card .stat-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Align text to the left */
        }

        .stat-card .stat-value {
            font-size: 2.25rem; /* text-4xl */
            font-weight: 700; /* font-bold */
            /* Color will be applied via Tailwind classes directly in HTML */
        }

        .stat-card .stat-sub-label { /* Renamed from stat-label */
            font-size: 0.875rem; /* text-sm */
            color: #4a5568; /* gray-700 */
            margin-top: 0.25rem; /* Small margin between value and label */
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar flex flex-col p-6">
        <div class="text-white mb-8">
            <div class="flex items-center justify-between text-xl font-bold mb-1">
                <span>Yusi Technologies, Inc.</span>
                <!-- Dropdown arrow icon (similar to screenshot) -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="text-sm text-gray-300">Logged in as: <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Guest'); ?></div>
        </div>
        <nav class="flex-grow">
            <ul>
                <li class="mb-2">
                    <a href="dashboard.php" class="nav-link active">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m-6-10v10a1 1 0 001 1h3"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="new_invoice.php" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Invoices
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="quotes.html" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Quotes 
                    </a>
                </li>
                     <li class="mb-2">
                    <a href="#" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Payments
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="new_client.php" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.134-.01-.265-.029-.395M18.42 15.653A9.985 9.985 0 0118 12c0-4.418-3.582-8-8-8s-8 3.582-8 8c0 1.02.21 1.99.605 2.894M12 10a2 2 0 110-4 2 2 0 010 4zm7.657-2.343A7.98 7.98 0 0121 12c0 4.418-3.582 8-8 8"></path></svg>
                        Clients
                    </a>
                </li>
           
                <li class="mb-2">
                    <a href="expenses.html" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Expenses
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="project.html" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Projects
                    </a>
                </li>
                <li class="mb-2">
                    <a href="time_management.html" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Time Management
 
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M17 20v-2c0-.134-.01-.265-.029-.395M18.42 15.653A9.985 9.985 0 0118 12c0-4.418-3.582-8-8-8s-8 3.582-8 8c0 1.02.21 1.99.605 2.894M12 10a2 2 0 110-4 2 2 0 010 4zm7.657-2.343A7.98 7.98 0 0121 12c0 4.418-3.582 8-8 8"></path></svg>
                        Payroll
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Accounting
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Analytics & Reports
 
                    </a>
                </li>
                
                <li class="mb-2">
                    <a href="settings.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                        Settings
                    </a>
                </li>
                <li class="mb-2">
                    <a href="logout.php" class="nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <a href="logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </header>

        <!-- Quick Stats Section -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Stats</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Today Invoices -->
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value text-pink-500" id="todayInvoices">2</div>
                        <div class="stat-sub-label">Today Invoices</div>
                    </div>
                    <div class="icon-wrapper text-pink-500">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>

                <!-- Card 2: This Month Invoices -->
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value text-green-500" id="thisMonthInvoices">20</div>
                        <div class="stat-sub-label">This Month Invoices</div>
                    </div>
                    <div class="icon-wrapper text-green-500">
                        <i class="fas fa-copy"></i>
                    </div>
                </div>

                <!-- Card 3: Today Sales -->
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value text-orange-500" id="todaySales">$ 2,975.12</div>
                        <div class="stat-sub-label">Today Sales</div>
                    </div>
                    <div class="icon-wrapper text-orange-500">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>

                <!-- Card 4: This Month Sales -->
                <div class="stat-card">
                    <div class="stat-info">
                        <div class="stat-value text-blue-500" id="thisMonthSales">$ 26,419.1</div>
                        <div class="stat-sub-label">This Month Sales</div>
                    </div>
                    <div class="icon-wrapper text-blue-500">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Analytics Content with Charts -->
        <section id="analytics" class="tab-content mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Sales Analytics</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Performance (Last 6 Months)</h3>
                    <div class="h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Lead Conversion Rate</h3>
                    <div class="h-64">
                        <canvas id="leadConversionChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Activity Section -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Activity</h2>
            <div class="card p-6">
                <ul class="divide-y divide-gray-200">
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-invoice text-blue-500 mr-3"></i>
                            <p class="text-gray-800 font-medium mb-0">New Invoice #000004 created for Acme Corp.</p>
                        </div>
                        <span class="text-sm text-gray-500">2 hours ago</span>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-500 mr-3"></i>
                            <p class="text-gray-800 font-medium mb-0">New client, Jane Doe, added to the system.</p>
                        </div>
                        <span class="text-sm text-gray-500">1 day ago</span>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-money-bill-wave text-red-500 mr-3"></i>
                            <p class="text-gray-800 font-medium mb-0">Expense for "Office Supplies" ($75.00) logged.</p>
                        </div>
                        <span class="text-sm text-gray-500">3 days ago</span>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-project-diagram text-purple-500 mr-3"></i>
                            <p class="text-gray-800 font-medium mb-0">Project "Website Redesign" status updated to In Progress.</p>
                        </div>
                        <span class="text-sm text-gray-500">1 week ago</span>
                    </li>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-signature text-yellow-500 mr-3"></i>
                            <p class="text-gray-800 font-medium mb-0">Quote #000002 sent to Global Solutions.</p>
                        </div>
                        <span class="text-sm text-gray-500">1 week ago</span>
                    </li>
                    <!-- More recent activities can be added here -->
                </ul>
            </div>
        </section>

    </main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eE7q8dE8B" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for Sales Performance Chart
            const salesData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales Revenue ($)',
                    data: [12000, 15000, 10000, 18000, 22000, 19000],
                    backgroundColor: 'rgba(22, 166, 166, 0.6)', // var(--primary-light) with alpha
                    borderColor: 'rgba(22, 166, 166, 1)',
                    borderWidth: 1,
                    fill: true,
                    tension: 0.3 // Smooth the line
                }]
            };

            // Configuration for Sales Performance Chart
            const salesConfig = {
                type: 'line',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allow canvas to fill container
                    plugins: {
                        title: {
                            display: false, // Title is in H3 tag
                        },
                        legend: {
                            display: false // Hide legend if only one dataset
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Month'
                            }
                        }
                    }
                }
            };

            // Render Sales Performance Chart
            const salesChartCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesChartCtx, salesConfig);

            // Data for Lead Conversion Rate Chart
            const leadConversionData = {
                labels: ['Website Leads', 'Referrals', 'Cold Outreach', 'Social Media'],
                datasets: [{
                    label: 'Conversion Rate (%)',
                    data: [25, 40, 15, 20], // Example percentages
                    backgroundColor: [
                        'rgba(8, 130, 28, 0.6)', // var(--accent-color)
                        'rgba(22, 166, 166, 0.6)', // var(--primary-light)
                        'rgba(13, 120, 120, 0.6)', // var(--primary-color)
                        'rgba(6, 94, 94, 0.6)' // var(--primary-dark)
                    ],
                    borderColor: [
                        'rgba(8, 130, 28, 1)',
                        'rgba(22, 166, 166, 1)',
                        'rgba(13, 120, 120, 1)',
                        'rgba(6, 94, 94, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Configuration for Lead Conversion Rate Chart
            const leadConversionConfig = {
                type: 'doughnut', // Doughnut chart for percentages
                data: leadConversionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allow canvas to fill container
                    plugins: {
                        title: {
                            display: false, // Title is in H3 tag
                        },
                        legend: {
                            position: 'right', // Position legend to the right
                            labels: {
                                usePointStyle: true, // Use circular points for legend items
                            }
                        }
                    }
                }
            };

            // Render Lead Conversion Rate Chart
            const leadConversionChartCtx = document.getElementById('leadConversionChart').getContext('2d');
            new Chart(leadConversionChartCtx, leadConversionConfig);
        });
    </script>
</body>
</html>
