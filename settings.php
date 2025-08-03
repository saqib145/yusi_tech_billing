<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Dashboard - Settings</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9O5SmXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl8W2NfM5KDD+ptx" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Chart.js CDN for dynamic graphs (if needed elsewhere) -->
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
            width: 260px;
            background-color: var(--primary-color); /* Use primary color */
            color: #e2e8f0; /* Light gray text */
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

        /* Custom file input styling */
        .file-input-wrapper {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem; /* rounded-md */
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.2s ease-in-out;
        }

        .file-input-wrapper:hover {
            background-color: var(--primary-dark);
        }

        .file-input-wrapper input[type="file"] {
            display: none; /* Hide the default file input */
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

    <!-- PHP to fetch settings -->
    <?php
    require_once 'db_config.php'; // Include your database config

    $settings = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM company_settings WHERE id = 1");
        $stmt->execute();
        $settings = $stmt->fetch();
        // Provide default values if no settings are found (though initial SQL insert should prevent this)
        if (!$settings) {
            $settings = [
                'company_name' => 'Yusi Technologies, Inc.',
                'company_email' => 'info@yusi.com',
                'company_phone' => '+1 (555) 123-4567',
                'company_address' => '123 CRM St, Tech City, TX 78701',
                'logo_path' => 'https://placehold.co/500x252/0d7878/ffffff?text=Logo',
                'date_format' => 'MM/DD/YYYY',
                'time_format' => '12-hour',
                'currency' => 'USD'
            ];
        }
    } catch (PDOException $e) {
        // Log the error or display a generic message
        error_log("Error fetching settings: " . $e->getMessage());
        // Fallback to default values in case of DB error
        $settings = [
            'company_name' => 'Yusi Technologies, Inc.',
            'company_email' => 'info@yusi.com',
            'company_phone' => '+1 (555) 123-4567',
            'company_address' => '123 CRM St, Tech City, TX 78701',
            'logo_path' => 'https://placehold.co/500x252/0d7878/ffffff?text=Logo',
            'date_format' => 'MM/DD/YYYY',
            'time_format' => '12-hour',
            'currency' => 'USD'
        ];
    }
    ?>

    <!-- Sidebar -->
    <aside class="sidebar flex flex-col p-6">
        <div class="text-white mb-8">
            <div class="flex items-center justify-between text-xl font-bold mb-1">
                <span>Yusi Technologies, Inc.</span>
                <!-- Dropdown arrow icon (similar to screenshot) -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="text-sm text-gray-300">Owner</div>
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
                    <a href="new_invoice.html" class="nav-link">
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
                    <a href="new_client.html" class="nav-link">
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
                    <a href="settings.php" class="nav-link active">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                        Settings
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" class="nav-link">
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
            <h1 class="text-3xl font-bold text-gray-800">Settings</h1>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="button" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
            </div>
        </header>
   
     <!-- Settings Content -->
        <section id="settings" class="tab-content">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Company Settings</h2>
            <div class="card p-6">
                <!-- Add a message box for feedback -->
                <div id="messageBox" class="message-box"></div>

                <form id="settingsForm" enctype="multipart/form-data">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Profile Logo</h3>
                        <div class="flex items-center space-x-4">
                           <img id="company-logo-preview" src="<?php echo htmlspecialchars($settings['logo_path']); ?>" alt="Company Logo" class="w-90 h-30 rectangle-full object-cover border-2 border-gray-300">
                        </div>
                        <label class="file-input-wrapper">
                                <input type="file" id="logo-upload" name="logo_upload" accept="image/*">
                                Upload New Logo
                        </label>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Company Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="company-name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                <input type="text" id="company-name" name="company_name" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($settings['company_name']); ?>">
                            </div>
                            <div>
                                <label for="company-email" class="block text-sm font-medium text-gray-700 mb-1">Company Email</label>
                                <input type="email" id="company-email" name="company_email" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($settings['company_email']); ?>">
                            </div>
                            <div>
                                <label for="company-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="company-phone" name="company_phone" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($settings['company_phone']); ?>">
                            </div>
                            <div>
                                <label for="company-address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" id="company-address" name="company_address" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($settings['company_address']); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- New: Date & Time Format Settings -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Date & Time Format</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="date-format-select" class="block text-sm font-medium text-gray-700 mb-1">Date Format</label>
                                <select id="date-format-select" name="date_format" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="MM/DD/YYYY" <?php echo ($settings['date_format'] == 'MM/DD/YYYY') ? 'selected' : ''; ?>>MM/DD/YYYY (07/24/2025)</option>
                                    <option value="DD/MM/YYYY" <?php echo ($settings['date_format'] == 'DD/MM/YYYY') ? 'selected' : ''; ?>>DD/MM/YYYY (24/07/2025)</option>
                                    <option value="YYYY-MM-DD" <?php echo ($settings['date_format'] == 'YYYY-MM-DD') ? 'selected' : ''; ?>>YYYY-MM-DD (2025-07-24)</option>
                                    <option value="MMM DD, YYYY" <?php echo ($settings['date_format'] == 'MMM DD, YYYY') ? 'selected' : ''; ?>>MMM DD, YYYY (Jul 24, 2025)</option>
                                    <option value="DD MMMM YYYY" <?php echo ($settings['date_format'] == 'DD MMMM YYYY') ? 'selected' : ''; ?>>DD MMMM YYYY (24 July 2025)</option>
                                </select>
                            </div>
                            <div>
                                <label for="time-format-select" class="block text-sm font-medium text-gray-700 mb-1">Time Format</label>
                                <select id="time-format-select" name="time_format" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="12-hour" <?php echo ($settings['time_format'] == '12-hour') ? 'selected' : ''; ?>>12-Hour (e.g., 06:29 PM)</option>
                                    <option value="24-hour" <?php echo ($settings['time_format'] == '24-hour') ? 'selected' : ''; ?>>24-Hour (e.g., 18:29)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Currency Settings</h3>
                        <div>
                            <label for="currency-select" class="block text-sm font-medium text-gray-700 mb-1">Select Currency</label>
                            <select id="currency-select" name="currency" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>USD - United States Dollar</option>
                                <option value="EUR" <?php echo ($settings['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR - Euro</option>
                                <option value="JPY" <?php echo ($settings['currency'] == 'JPY') ? 'selected' : ''; ?>>JPY - Japanese Yen</option>
                                <option value="GBP" <?php echo ($settings['currency'] == 'GBP') ? 'selected' : ''; ?>>GBP - British Pound</option>
                                <option value="AUD" <?php echo ($settings['currency'] == 'AUD') ? 'selected' : ''; ?>>AUD - Australian Dollar</option>
                                <option value="CAD" <?php echo ($settings['currency'] == 'CAD') ? 'selected' : ''; ?>>CAD - Canadian Dollar</option>
                                <option value="CHF" <?php echo ($settings['currency'] == 'CHF') ? 'selected' : ''; ?>>CHF - Swiss Franc</option>
                                <option value="CNY" <?php echo ($settings['currency'] == 'CNY') ? 'selected' : ''; ?>>CNY - Chinese Yuan</option>
                                <option value="SEK" <?php echo ($settings['currency'] == 'SEK') ? 'selected' : ''; ?>>SEK - Swedish Krona</option>
                                <option value="NZD" <?php echo ($settings['currency'] == 'NZD') ? 'selected' : ''; ?>>NZD - New Zealand Dollar</option>
                                <option value="MXN" <?php echo ($settings['currency'] == 'MXN') ? 'selected' : ''; ?>>MXN - Mexican Peso</option>
                                <option value="SGD" <?php echo ($settings['currency'] == 'SGD') ? 'selected' : ''; ?>>SGD - Singapore Dollar</option>
                                <option value="HKD" <?php echo ($settings['currency'] == 'HKD') ? 'selected' : ''; ?>>HKD - Hong Kong Dollar</option>
                                <option value="NOK" <?php echo ($settings['currency'] == 'NOK') ? 'selected' : ''; ?>>NOK - Norwegian Krone</option>
                                <option value="KRW" <?php echo ($settings['currency'] == 'KRW') ? 'selected' : ''; ?>>KRW - South Korean Won</option>
                                <option value="TRY" <?php echo ($settings['currency'] == 'TRY') ? 'selected' : ''; ?>>TRY - Turkish Lira</option>
                                <option value="RUB" <?php echo ($settings['currency'] == 'RUB') ? 'selected' : ''; ?>>RUB - Russian Ruble</option>
                                <option value="INR" <?php echo ($settings['currency'] == 'INR') ? 'selected' : ''; ?>>INR - Indian Rupee</option>
                                <option value="BRL" <?php echo ($settings['currency'] == 'BRL') ? 'selected' : ''; ?>>BRL - Brazilian Real</option>
                                <option value="ZAR" <?php echo ($settings['currency'] == 'ZAR') ? 'selected' : ''; ?>>ZAR - South African Rand</option>
                                <option value="AED" <?php echo ($settings['currency'] == 'AED') ? 'selected' : ''; ?>>AED - UAE Dirham</option>
                                <option value="SAR" <?php echo ($settings['currency'] == 'SAR') ? 'selected' : ''; ?>>SAR - Saudi Riyal</option>
                                <option value="PKR" <?php echo ($settings['currency'] == 'PKR') ? 'selected' : ''; ?>>PKR - Pakistani Rupee</option>
                                <option value="EGP" <?php echo ($settings['currency'] == 'EGP') ? 'selected' : ''; ?>>EGP - Egyptian Pound</option>
                                <option value="THB" <?php echo ($settings['currency'] == 'THB') ? 'selected' : ''; ?>>THB - Thai Baht</option>
                                <option value="IDR" <?php echo ($settings['currency'] == 'IDR') ? 'selected' : ''; ?>>IDR - Indonesian Rupiah</option>
                                <option value="PHP" <?php echo ($settings['currency'] == 'PHP') ? 'selected' : ''; ?>>PHP - Philippine Peso</option>
                                <option value="MYR" <?php echo ($settings['currency'] == 'MYR') ? 'selected' : ''; ?>>MYR - Malaysian Ringgit</option>
                                <option value="VND" <?php echo ($settings['currency'] == 'VND') ? 'selected' : ''; ?>>VND - Vietnamese Dong</option>
                                <option value="CLP" <?php echo ($settings['currency'] == 'CLP') ? 'selected' : ''; ?>>CLP - Chilean Peso</option>
                                <option value="COP" <?php echo ($settings['currency'] == 'COP') ? 'selected' : ''; ?>>COP - Colombian Peso</option>
                                <option value="ARS" <?php echo ($settings['currency'] == 'ARS') ? 'selected' : ''; ?>>ARS - Argentine Peso</option>
                                <option value="PLN" <?php echo ($settings['currency'] == 'PLN') ? 'selected' : ''; ?>>PLN - Polish Zloty</option>
                                <option value="CZK" <?php echo ($settings['currency'] == 'CZK') ? 'selected' : ''; ?>>CZK - Czech Koruna</option>
                                <option value="HUF" <?php echo ($settings['currency'] == 'HUF') ? 'selected' : ''; ?>>HUF - Hungarian Forint</option>
                                <option value="DKK" <?php echo ($settings['currency'] == 'DKK') ? 'selected' : ''; ?>>DKK - Danish Krone</option>
                                <option value="ILS" <?php echo ($settings['currency'] == 'ILS') ? 'selected' : ''; ?>>ILS - Israeli New Shekel</option>
                                <option value="KWD" <?php echo ($settings['currency'] == 'KWD') ? 'selected' : ''; ?>>KWD - Kuwaiti Dinar</option>
                                <option value="QAR" <?php echo ($settings['currency'] == 'QAR') ? 'selected' : ''; ?>>QAR - Qatari Riyal</option>
                                <option value="BHD" <?php echo ($settings['currency'] == 'BHD') ? 'selected' : ''; ?>>BHD - Bahraini Dinar</option>
                                <option value="OMR" <?php echo ($settings['currency'] == 'OMR') ? 'selected' : ''; ?>>OMR - Omani Rial</option>
                                <option value="JOD" <?php echo ($settings['currency'] == 'JOD') ? 'selected' : ''; ?>>JOD - Jordanian Dinar</option>
                                <option value="LBP" <?php echo ($settings['currency'] == 'LBP') ? 'selected' : ''; ?>>LBP - Lebanese Pound</option>
                                <option value="SYP" <?php echo ($settings['currency'] == 'SYP') ? 'selected' : ''; ?>>SYP - Syrian Pound</option>
                                <option value="IQD" <?php echo ($settings['currency'] == 'IQD') ? 'selected' : ''; ?>>IQD - Iraqi Dinar</option>
                                <option value="DZD" <?php echo ($settings['currency'] == 'DZD') ? 'selected' : ''; ?>>DZD - Algerian Dinar</option>
                                <option value="MAD" <?php echo ($settings['currency'] == 'MAD') ? 'selected' : ''; ?>>MAD - Moroccan Dirham</option>
                                <option value="TND" <?php echo ($settings['currency'] == 'TND') ? 'selected' : ''; ?>>TND - Tunisian Dinar</option>
                                <option value="NGN" <?php echo ($settings['currency'] == 'NGN') ? 'selected' : ''; ?>>NGN - Nigerian Naira</option>
                                <option value="KES" <?php echo ($settings['currency'] == 'KES') ? 'selected' : ''; ?>>KES - Kenyan Shilling</option>
                                <option value="GHS" <?php echo ($settings['currency'] == 'GHS') ? 'selected' : ''; ?>>GHS - Ghanaian Cedi</option>
                                <option value="UGX" <?php echo ($settings['currency'] == 'UGX') ? 'selected' : ''; ?>>UGX - Ugandan Shilling</option>
                                <option value="TZS" <?php echo ($settings['currency'] == 'TZS') ? 'selected' : ''; ?>>TZS - Tanzanian Shilling</option>
                                <option value="RWF" <?php echo ($settings['currency'] == 'RWF') ? 'selected' : ''; ?>>RWF - Rwandan Franc</option>
                                <option value="XAF" <?php echo ($settings['currency'] == 'XAF') ? 'selected' : ''; ?>>XAF - CFA Franc BEAC</option>
                                <option value="XOF" <?php echo ($settings['currency'] == 'XOF') ? 'selected' : ''; ?>>XOF - CFA Franc BCEAO</option>
                                <option value="CDF" <?php echo ($settings['currency'] == 'CDF') ? 'selected' : ''; ?>>CDF - Congolese Franc</option>
                                <option value="ETB" <?php echo ($settings['currency'] == 'ETB') ? 'selected' : ''; ?>>ETB - Ethiopian Birr</option>
                                <option value="SDG" <?php echo ($settings['currency'] == 'SDG') ? 'selected' : ''; ?>>SDG - Sudanese Pound</option>
                                <option value="MZN" <?php echo ($settings['currency'] == 'MZN') ? 'selected' : ''; ?>>MZN - Mozambican Metical</option>
                                <option value="AOA" <?php echo ($settings['currency'] == 'AOA') ? 'selected' : ''; ?>>AOA - Angolan Kwanza</option>
                                <option value="XCD" <?php echo ($settings['currency'] == 'XCD') ? 'selected' : ''; ?>>XCD - East Caribbean Dollar</option>
                                <option value="BBD" <?php echo ($settings['currency'] == 'BBD') ? 'selected' : ''; ?>>BBD - Barbadian Dollar</option>
                                <option value="JMD" <?php echo ($settings['currency'] == 'JMD') ? 'selected' : ''; ?>>JMD - Jamaican Dollar</option>
                                <option value="TTD" <?php echo ($settings['currency'] == 'TTD') ? 'selected' : ''; ?>>TTD - Trinidad and Tobago Dollar</option>
                                <option value="BSD" <?php echo ($settings['currency'] == 'BSD') ? 'selected' : ''; ?>>BSD - Bahamian Dollar</option>
                                <option value="BZD" <?php echo ($settings['currency'] == 'BZD') ? 'selected' : ''; ?>>BZD - Belize Dollar</option>
                                <option value="GYD" <?php echo ($settings['currency'] == 'GYD') ? 'selected' : ''; ?>>GYD - Guyanese Dollar</option>
                                <option value="SRD" <?php echo ($settings['currency'] == 'SRD') ? 'selected' : ''; ?>>SRD - Surinamese Dollar</option>
                                <option value="HTG" <?php echo ($settings['currency'] == 'HTG') ? 'selected' : ''; ?>>HTG - Haitian Gourde</option>
                                <option value="DOP" <?php echo ($settings['currency'] == 'DOP') ? 'selected' : ''; ?>>DOP - Dominican Peso</option>
                                <option value="CUC" <?php echo ($settings['currency'] == 'CUC') ? 'selected' : ''; ?>>CUC - Cuban Convertible Peso</option>
                                <option value="CUP" <?php echo ($settings['currency'] == 'CUP') ? 'selected' : ''; ?>>CUP - Cuban Peso</option>
                                <option value="CRC" <?php echo ($settings['currency'] == 'CRC') ? 'selected' : ''; ?>>CRC - Costa Rican Colón</option>
                                <option value="GTQ" <?php echo ($settings['currency'] == 'GTQ') ? 'selected' : ''; ?>>GTQ - Guatemalan Quetzal</option>
                                <option value="HNL" <?php echo ($settings['currency'] == 'HNL') ? 'selected' : ''; ?>>HNL - Honduran Lempira</option>
                                <option value="NIO" <?php echo ($settings['currency'] == 'NIO') ? 'selected' : ''; ?>>NIO - Nicaraguan Córdoba</option>
                                <option value="PAB" <?php echo ($settings['currency'] == 'PAB') ? 'selected' : ''; ?>>PAB - Panamanian Balboa</option>
                                <option value="SVC" <?php echo ($settings['currency'] == 'SVC') ? 'selected' : ''; ?>>SVC - Salvadoran Colón</option>
                                <option value="PYG" <?php echo ($settings['currency'] == 'PYG') ? 'selected' : ''; ?>>PYG - Paraguayan Guarani</option>
                                <option value="UYU" <?php echo ($settings['currency'] == 'UYU') ? 'selected' : ''; ?>>UYU - Uruguayan Peso</option>
                                <option value="VES" <?php echo ($settings['currency'] == 'VES') ? 'selected' : ''; ?>>VES - Venezuelan Bolívar Soberano</option>
                                <option value="BOB" <?php echo ($settings['currency'] == 'BOB') ? 'selected' : ''; ?>>BOB - Bolivian Boliviano</option>
                                <option value="PEN" <?php echo ($settings['currency'] == 'PEN') ? 'selected' : ''; ?>>PEN - Peruvian Sol</option>
                                <option value="GEL" <?php echo ($settings['currency'] == 'GEL') ? 'selected' : ''; ?>>GEL - Georgian Lari</option>
                                <option value="AMD" <?php echo ($settings['currency'] == 'AMD') ? 'selected' : ''; ?>>AMD - Armenian Dram</option>
                                <option value="AZN" <?php echo ($settings['currency'] == 'AZN') ? 'selected' : ''; ?>>AZN - Azerbaijani Manat</option>
                                <option value="KZT" <?php echo ($settings['currency'] == 'KZT') ? 'selected' : ''; ?>>KZT - Kazakhstani Tenge</option>
                                <option value="UZS" <?php echo ($settings['currency'] == 'UZS') ? 'selected' : ''; ?>>UZS - Uzbekistan Som</option>
                                <option value="TJS" <?php echo ($settings['currency'] == 'TJS') ? 'selected' : ''; ?>>TJS - Tajikistani Somoni</option>
                                <option value="KGS" <?php echo ($settings['currency'] == 'KGS') ? 'selected' : ''; ?>>KGS - Kyrgyzstani Som</option>
                                <option value="AFN" <?php echo ($settings['currency'] == 'AFN') ? 'selected' : ''; ?>>AFN - Afghan Afghani</option>
                                <option value="IRR" <?php echo ($settings['currency'] == 'IRR') ? 'selected' : ''; ?>>IRR - Iranian Rial</option>
                                <option value="LKR" <?php echo ($settings['currency'] == 'LKR') ? 'selected' : ''; ?>>LKR - Sri Lankan Rupee</option>
                                <option value="NPR" <?php echo ($settings['currency'] == 'NPR') ? 'selected' : ''; ?>>NPR - Nepalese Rupee</option>
                                <option value="BDT" <?php echo ($settings['currency'] == 'BDT') ? 'selected' : ''; ?>>BDT - Bangladeshi Taka</option>
                                <option value="MMK" <?php echo ($settings['currency'] == 'MMK') ? 'selected' : ''; ?>>MMK - Burmese Kyat</option>
                                <option value="LAK" <?php echo ($settings['currency'] == 'LAK') ? 'selected' : ''; ?>>LAK - Lao Kip</option>
                                <option value="KHR" <?php echo ($settings['currency'] == 'KHR') ? 'selected' : ''; ?>>KHR - Cambodian Riel</option>
                                <option value="MNT" <?php echo ($settings['currency'] == 'MNT') ? 'selected' : ''; ?>>MNT - Mongolian Tögrög</option>
                                <option value="PGK" <?php echo ($settings['currency'] == 'PGK') ? 'selected' : ''; ?>>PGK - Papua New Guinean Kina</option>
                                <option value="FJD" <?php echo ($settings['currency'] == 'FJD') ? 'selected' : ''; ?>>FJD - Fijian Dollar</option>
                                <option value="SBD" <?php echo ($settings['currency'] == 'SBD') ? 'selected' : ''; ?>>SBD - Solomon Islands Dollar</option>
                                <option value="VUV" <?php echo ($settings['currency'] == 'VUV') ? 'selected' : ''; ?>>VUV - Vanuatu Vatu</option>
                                <option value="WST" <?php echo ($settings['currency'] == 'WST') ? 'selected' : ''; ?>>WST - Samoan Tala</option>
                                <option value="TOP" <?php echo ($settings['currency'] == 'TOP') ? 'selected' : ''; ?>>TOP - Tongan Paʻanga</option>
                                <option value="XPF" <?php echo ($settings['currency'] == 'XPF') ? 'selected' : ''; ?>>XPF - CFP Franc</option>
                                <option value="GIP" <?php echo ($settings['currency'] == 'GIP') ? 'selected' : ''; ?>>GIP - Gibraltar Pound</option>
                                <option value="IMP" <?php echo ($settings['currency'] == 'IMP') ? 'selected' : ''; ?>>IMP - Isle of Man Pound</option>
                                <option value="JEP" <?php echo ($settings['currency'] == 'JEP') ? 'selected' : ''; ?>>JEP - Jersey Pound</option>
                                <option value="FKP" <?php echo ($settings['currency'] == 'FKP') ? 'selected' : ''; ?>>FKP - Falkland Islands Pound</option>
                                <option value="SHP" <?php echo ($settings['currency'] == 'SHP') ? 'selected' : ''; ?>>SHP - Saint Helena Pound</option>
                                <option value="TVD" <?php echo ($settings['currency'] == 'TVD') ? 'selected' : ''; ?>>TVD - Tuvaluan Dollar</option>
                                <option value="XDR" <?php echo ($settings['currency'] == 'XDR') ? 'selected' : ''; ?>>XDR - Special Drawing Rights</option>
                                <option value="XAU" <?php echo ($settings['currency'] == 'XAU') ? 'selected' : ''; ?>>XAU - Gold (troy ounce)</option>
                                <option value="XAG" <?php echo ($settings['currency'] == 'XAG') ? 'selected' : ''; ?>>XAG - Silver (troy ounce)</option>
                                <option value="XPT" <?php echo ($settings['currency'] == 'XPT') ? 'selected' : ''; ?>>XPT - Platinum (troy ounce)</option>
                                <option value="XPD" <?php echo ($settings['currency'] == 'XPD') ? 'selected' : ''; ?>>XPD - Palladium (troy ounce)</option>
                                <option value="BTN" <?php echo ($settings['currency'] == 'BTN') ? 'selected' : ''; ?>>BTN - Bhutanese Ngultrum</option>
                                <option value="CUC" <?php echo ($settings['currency'] == 'CUC') ? 'selected' : ''; ?>>CUC - Cuban Convertible Peso</option>
                                <option value="ERN" <?php echo ($settings['currency'] == 'ERN') ? 'selected' : ''; ?>>ERN - Eritrean Nakfa</option>
                                <option value="GGP" <?php echo ($settings['currency'] == 'GGP') ? 'selected' : ''; ?>>GGP - Guernsey Pound</option>
                                <option value="JOD" <?php echo ($settings['currency'] == 'JOD') ? 'selected' : ''; ?>>JOD - Jordanian Dinar</option>
                                <option value="KPW" <?php echo ($settings['currency'] == 'KPW') ? 'selected' : ''; ?>>KPW - North Korean Won</option>
                                <option value="LYD" <?php echo ($settings['currency'] == 'LYD') ? 'selected' : ''; ?>>LYD - Libyan Dinar</option>
                                <option value="MRU" <?php echo ($settings['currency'] == 'MRU') ? 'selected' : ''; ?>>MRU - Mauritanian Ouguiya</option>
                                <option value="SSP" <?php echo ($settings['currency'] == 'SSP') ? 'selected' : ''; ?>>SSP - South Sudanese Pound</option>
                                <option value="STN" <?php echo ($settings['currency'] == 'STN') ? 'selected' : ''; ?>>STN - São Tomé and Príncipe Dobra</option>
                                <option value="TMT" <?php echo ($settings['currency'] == 'TMT') ? 'selected' : ''; ?>>TMT - Turkmenistan Manat</option>
                                <option value="UZS" <?php echo ($settings['currency'] == 'UZS') ? 'selected' : ''; ?>>UZS - Uzbekistan Som</option>
                                <option value="YER" <?php echo ($settings['currency'] == 'YER') ? 'selected' : ''; ?>>YER - Yemeni Rial</option>
                                <option value="ZMW" <?php echo ($settings['currency'] == 'ZMW') ? 'selected' : ''; ?>>ZMW - Zambian Kwacha</option>
                                <option value="ZWL" <?php echo ($settings['currency'] == 'ZWL') ? 'selected' : ''; ?>>ZWL - Zimbabwean Dollar</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-600 hover:scale-[1.02] transition-all">Save</button>
                </form>
            </div>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const settingsForm = document.getElementById('settingsForm');
            const logoUploadInput = document.getElementById('logo-upload');
            const companyLogoPreview = document.getElementById('company-logo-preview');
            const messageBox = document.getElementById('messageBox');

            // Function to display messages
            function showMessage(message, type = 'success') {
                messageBox.textContent = message;
                messageBox.className = 'message-box show'; // Reset classes
                if (type === 'error') {
                    messageBox.classList.add('error');
                } else {
                    messageBox.classList.remove('error');
                }
                setTimeout(() => {
                    messageBox.classList.remove('show');
                }, 3000); // Hide after 3 seconds
            }

            // Live logo preview
            logoUploadInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        companyLogoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle form submission via AJAX
            settingsForm.addEventListener('submit', async function(event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(settingsForm); // Get form data, including files

                try {
                    const response = await fetch('settings_handler.php', {
                        method: 'POST',
                        body: formData // Send FormData directly
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage(result.message, 'success');
                        // If a new logo was uploaded, update the preview with the new path
                        if (result.new_logo_path) {
                            companyLogoPreview.src = result.new_logo_path;
                        }
                    } else {
                        showMessage(result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showMessage('An unexpected error occurred. Please try again.', 'error');
                }
            });
        });
    </script>
</body>
</html>
