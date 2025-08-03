<?php
// new_client.php
require_once __DIR__ . '/auth_config.php';
require_once __DIR__ . '/db_config.php';
requireLogin(); // Ensure user is logged in to access this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Client - CRM Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9O5SmXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl8W2NfM5KDD+ptx" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            width: 260px;
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
        /* Initially hide all tab contents except the dashboard, which will be shown by default */
        #leads, #customers, #tasks, #analytics {
            display: none;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem; /* Rounded corners */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            border: 1px solid #d1d5db; /* gray-300 */
            border-radius: 0.375rem; /* rounded-md */
            padding: 0.5rem 0.75rem;
            width: 100%;
            font-size: 0.875rem; /* text-sm */
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
            outline: none;
            border-color: var(--primary-light); /* Use primary-light for focus ring */
            box-shadow: 0 0 0 2px rgba(22, 166, 166, 0.2); /* Custom ring color */
        }
        .setting-item {
            padding: 0.75rem 0;
            transition: background-color 0.2s ease-in-out;
            cursor: pointer;
        }
        .setting-item:hover {
            background-color: #f9fafb; /* gray-50 */
            border-radius: 0.5rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }
        .card h2{
            margin-bottom: 20px;
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
        #cancel_save{
            margin-top: 50px;
        }
        #cancel_btn{
            background-color: rgb(207, 44, 44);
            color: white;

        }
        /* Message box styling */
        .message-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green for success */
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none; /* Hidden by default */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .message-box.error {
            background-color: #f44336; /* Red for error */
        }

        .message-box.show {
            display: block;
            opacity: 1;
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
                    <a href="dashboard.php" class="nav-link">
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
                    <a href="new_client.php" class="nav-link active">
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
            <h1 class="text-3xl font-bold text-gray-800">Add New Client</h1>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <a href="logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </header>

        <!-- Message Box for feedback -->
        <div id="messageBox" class="message-box"></div>

        <!-- Client Details Section -->
        <section class="card p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Client Information</h2>
            <form id="clientForm">
                <!-- Info Message -->
                <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded-md flex items-center mb-6 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info mr-3">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                    <p class="text-sm">Either First and Last Name or Company Name is required to save</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="firstName" name="first_name" class="w-full rounded">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="lastName" name="last_name" class="w-full rounded">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="companyName" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" id="companyName" name="company_name" class="w-full rounded">
                </div>

                <div class="mb-6">
                    <label for="emailAddress" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="emailAddress" name="client_email" class="w-full rounded">
                </div>

                <div class="mb-6">
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="tel" id="phoneNumber" name="client_phone" class="w-full rounded">
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea id="address" name="client_address" rows="3" class="w-full rounded"></textarea>
                </div>

                <div class="space-y-4">
                    <!-- These links would typically trigger JS to add more fields, but for simplicity, they are placeholders -->
                    <a href="#" class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium hover:bg-blue-50 rounded-md p-2 -ml-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        Add Business Phone
                    </a>
                    <a href="#" class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium hover:bg-blue-50 rounded-md p-2 -ml-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        Add Mobile Phone
                    </a>
                    <a href="#" class="flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium hover:bg-blue-50 rounded-md p-2 -ml-2 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus mr-2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                        Add Address
                    </a>
                </div>

                <!-- Client Settings Section (moved inside form for submission if needed) -->
                <div class="card p-6 mt-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Client Settings</h2>

                    <ul class="space-y-2">
                        <li class="flex items-center justify-between setting-item">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-ring mr-3 text-gray-500">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                                    <path d="M10.36 18.36a3.5 3.5 0 1 0 3.28 0"/>
                                    <path d="M22 11c.07-2.3-1.33-4.47-3.5-5"/>
                                    <path d="M2 11c-.07-2.3 1.33-4.47 3.5-5"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Send Reminders</p>
                                    <p class="text-xs text-gray-500">At Customizable Intervals</p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                NO
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </div>
                        </li>

                        <li class="flex items-center justify-between setting-item">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card mr-3 text-gray-500">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/>
                                    <path d="M2 10h20"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Charge Late Fees</p>
                                    <p class="text-xs text-gray-500">Percentage or Flat-Rate Fees</p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                NO
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </div>
                        </li>

                        <li class="flex items-center justify-between setting-item">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe mr-3 text-gray-500">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                                    <path d="M2 12h20"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Currency & Language</p>
                                    <p class="text-xs text-gray-500">USD, English (United States)</p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </div>
                        </li>

                        <li class="flex items-center justify-between setting-item">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-3 text-gray-500">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                                    <path d="M10 9H8"/>
                                    <path d="M16 13H8"/>
                                    <path d="M16 17H8"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Invoice Attachments</p>
                                    <p class="text-xs text-gray-500">Attach PDF copy to emails</p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600 text-sm font-medium">
                                NO
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </div>
                        </li>
                    </ul>
                </div>

                <div id="cancel_save" class="flex justify-end mt-6 space-x-4">
                    <button type="button" id="cancel_btn" class="text-gray-600 px-4 py-2 rounded-md hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 hover:scale-[1.02] transition-all">Save Client</button>
                </div>
            </form>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eE7q8dE8B" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clientForm = document.getElementById('clientForm');
            const messageBox = document.getElementById('messageBox');
            const cancelBtn = document.getElementById('cancel_btn');

            function showMessage(message, type = 'error') {
                messageBox.textContent = message;
                messageBox.className = 'message-box show'; // Reset classes
                if (type === 'success') {
                    messageBox.classList.add('success');
                } else {
                    messageBox.classList.remove('success');
                }
                setTimeout(() => {
                    messageBox.classList.remove('show');
                }, 3000); // Hide after 3 seconds
            }

            clientForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(clientForm);

                // Basic validation for either (first_name & last_name) OR company_name
                const firstName = formData.get('first_name').trim();
                const lastName = formData.get('last_name').trim();
                const companyName = formData.get('company_name').trim();
                const email = formData.get('client_email').trim();

                if ((firstName === '' && lastName === '') && companyName === '') {
                    showMessage('Either First Name & Last Name or Company Name is required.', 'error');
                    return;
                }

                if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    return;
                }

                try {
                    const response = await fetch('client_handler.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage(result.message, 'success');
                        // Clear form fields on success
                        clientForm.reset();
                        // Optionally, redirect after a delay
                        // setTimeout(() => { window.location.href = 'clients_list.php'; }, 2000);
                    } else {
                        showMessage(result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An unexpected error occurred. Please try again.', 'error');
                }
            });

            cancelBtn.addEventListener('click', function() {
                window.location.href = 'dashboard.php'; // Or any other appropriate page
            });
        });
    </script>
</body>
</html>
