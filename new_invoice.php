<?php
// new_invoice.php
require_once __DIR__ . '/auth_config.php';
require_once __DIR__ . '/db_config.php';
requireLogin(); // Ensure user is logged in to access this page

// Fetch company settings
$company_settings = [];
try {
    $stmt = $pdo->query("SELECT company_name, company_email, company_phone, company_address, logo_path FROM company_settings WHERE id = 1 LIMIT 1");
    $company_settings = $stmt->fetch();
    if (!$company_settings) {
        // Fallback if no settings found
        $company_settings = [
            'company_name' => 'Your Company Name',
            'company_email' => 'info@example.com',
            'company_phone' => '123-456-7890',
            'company_address' => '123 Business Street, City, Country',
            'logo_path' => 'https://placehold.co/150x50/cccccc/white?text=Logo' // Placeholder logo
        ];
    }
} catch (PDOException $e) {
    error_log("Error fetching company settings: " . $e->getMessage());
    // Fallback if database error
    $company_settings = [
        'company_name' => 'Your Company Name',
        'company_email' => 'info@example.com',
        'company_phone' => '123-456-7890',
        'company_address' => '123 Business Street, City, Country',
        'logo_path' => 'https://placehold.co/150x50/cccccc/white?text=Logo' // Placeholder logo
    ];
}

// Fetch clients for the dropdown/search suggestion
$clients = [];
try {
    $stmt = $pdo->query("SELECT id, client_name, client_email, client_phone, client_address FROM clients ORDER BY client_name ASC");
    $clients = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching clients: " . $e->getMessage());
    // Handle error gracefully, e.g., show an empty list or an error message
}

// Generate a simple invoice number (e.g., INV-YYYYMMDD-XXXX)
// In a real application, this would be more robust (e.g., auto-increment from DB, check for uniqueness)
$invoice_prefix = 'INV-';
$current_date_formatted = date('Ymd');
// Fetch the last invoice number to increment (simple example)
try {
    $stmt = $pdo->query("SELECT invoice_number FROM invoices ORDER BY id DESC LIMIT 1");
    $last_invoice = $stmt->fetch();
    if ($last_invoice) {
        // Extract the numeric part and increment
        preg_match('/-(\d+)$/', $last_invoice['invoice_number'], $matches);
        $last_num = isset($matches[1]) ? (int)$matches[1] : 0;
        $next_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        $invoice_number = $invoice_prefix . $current_date_formatted . '-' . $next_num;
    } else {
        $invoice_number = $invoice_prefix . $current_date_formatted . '-0001';
    }
} catch (PDOException $e) {
    error_log("Error generating invoice number: " . $e->getMessage());
    $invoice_number = $invoice_prefix . $current_date_formatted . '-0001'; // Fallback
}


$today = date('Y-m-d');
$due_date = date('Y-m-d', strtotime('+20 days')); // Due in 20 days by default as per screenshot
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Invoice - CRM Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        /* Container for main invoice content and right sidebar */
        .invoice-and-settings-container {
            /* Now handled by Tailwind classes: flex flex-col lg:flex-row lg:space-x-8 */
            flex-grow: 1; /* Take remaining vertical space */
            padding: 0 0 2rem 0; /* Adjusted padding for internal spacing via Tailwind */
            overflow-y: auto; /* Allow scrolling for this section */
        }

        /* Main Invoice Content Area */
        .main-invoice-content {
            /* Now handled by Tailwind classes: w-full lg:w-2/3 */
            /* margin-right: 2rem; Removed as space-x handles it */
        }

        /* Right Settings Sidebar */
        .right-settings-sidebar {
            /* Now handled by Tailwind classes: w-full lg:w-1/3 mt-6 lg:mt-0 */
            /* width: 300px; Removed fixed width */
            /* flex-shrink: 0; Moved to Tailwind class on element */
        }
        .right-settings-sidebar .card {
            width: 100%; /* Make the card fill the sidebar width */
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
        /* Styles for client search suggestions */
        #client-suggestions {
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            width: calc(100% - 2rem); /* Adjust width to match input, considering padding */
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
        }
        #client-suggestions div {
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        #client-suggestions div:hover {
            background-color: #f0f4f8;
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
                    <a href="new_invoice.php" class="nav-link active">
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
            <h1 class="text-3xl font-bold text-gray-800">New Invoice</h1>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <a href="logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </header>

        <!-- Message Box for feedback -->
        <div id="messageBox" class="message-box"></div>

        <form id="invoiceForm" class="flex flex-col lg:flex-row lg:space-x-8" enctype="multipart/form-data">
            <!-- Main Invoice Content Area -->
            <div class="main-invoice-content w-full lg:w-2/3">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-6">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-6">
                                <img src="<?php echo htmlspecialchars($company_settings['logo_path']); ?>" alt="Yusi Technologies Logo" class="img-fluid rounded" style="max-width: 150px;">
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-0"><strong><?php echo htmlspecialchars($company_settings['company_name']); ?></strong></p>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($company_settings['company_address'])); ?></p>
                                <p class="mb-0"><?php echo htmlspecialchars($company_settings['company_phone']); ?></p>
                                <p class="mb-0"><?php echo htmlspecialchars($company_settings['company_email']); ?></p>
                                <a href="settings.php" class="btn btn-sm btn-link text-primary rounded">Edit Business Information</a>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="search-client" class="form-label">Billed To</label>
                                    <input type="text" id="search-client" name="search_client" class="form-control rounded" placeholder="Enter Customer Name or Mobile Number to search">
                                    <input type="hidden" id="client-id" name="client_id">
                                    <div id="client-suggestions" class="absolute bg-white border border-gray-300 rounded-md mt-1 w-full z-10 hidden"></div>
                                    <a href="new_client.html" class="btn btn-sm btn-link text-primary mt-2 rounded">+ Add a Client</a>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-lg font-medium text-gray-800 mb-2">Client Details</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="client-name" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                                            <input type="text" id="client-name" name="client_name" class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" readonly>
                                        </div>
                                        <div>
                                            <label for="client-email" class="block text-sm font-medium text-gray-700 mb-1">Client Email</label>
                                            <input type="email" id="client-email" name="client_email" class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" readonly>
                                        </div>
                                        <div>
                                            <label for="client-phone" class="block text-sm font-medium text-gray-700 mb-1">Client Phone</label>
                                            <input type="tel" id="client-phone" name="client_phone" class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" readonly>
                                        </div>
                                        <div>
                                            <label for="client-address" class="block text-sm font-medium text-gray-700 mb-1">Client Address</label>
                                            <textarea id="client-address" name="client_address" rows="2" class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dateOfIssue" class="form-label">Date of Issue</label>
                                        <input type="date" class="form-control rounded" id="dateOfIssue" name="invoice_date" value="<?php echo htmlspecialchars($today); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                                        <input type="text" class="form-control rounded" id="invoiceNumber" name="invoice_number" value="<?php echo htmlspecialchars($invoice_number); ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dueDate" class="form-label">Due Date</label>
                                        <input type="date" class="form-control rounded" id="dueDate" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="reference" class="form-label">Reference</label>
                                        <input type="text" class="form-control rounded" id="reference" name="reference" placeholder="Enter value (e.g. PO #)">
                                    </div>
                                </div>
                                <div class="text-md-end mb-3">
                                    <label class="form-label mb-0">Amount Due (USD)</label>
                                    <h2 class="text-primary fw-bold">$<span id="amountDue">0.00</span></h2>
                                    <input type="hidden" name="grand_total" id="grand_total_hidden" value="0.00">
                                </div>
                            </div>
                        </div>

                        <h5>Line Items</h5>
                        <div class="overflow-x-auto mb-4">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">Description</th>
                                        <th class="py-3 px-6 text-left">Rate</th>
                                        <th class="py-3 px-6 text-left">Qty</th>
                                        <th class="py-3 px-6 text-left">Tax(%)</th>
                                        <th class="py-3 px-6 text-left">Tax Amount</th>
                                        <th class="py-3 px-6 text-left">Discount</th>
                                        <th class="py-3 px-6 text-left">Amount</th>
                                        <th class="py-3 px-6 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="lineItemsContainer" class="text-gray-600 text-sm font-light">
                                    <!-- Initial row will be added by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mb-4 rounded" id="addLineItemBtn">+ Add a Line</button>

                        <div class="row justify-content-end mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm invoice-summary rounded">
                                    <tbody>
                                        <tr>
                                            <td>Subtotal</td>
                                            <td class="text-end">$<span id="subtotal">0.00</span></td>
                                            <input type="hidden" name="subtotal_hidden" value="0.00">
                                        </tr>
                                        <tr>
                                            <td><a href="#" class="text-primary rounded" onclick="alert('Add Discount feature')">Add a Discount</a></td>
                                            <td class="text-end"></td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td class="text-end">$<span id="tax">0.00</span></td>
                                            <input type="hidden" name="total_tax" value="0.00">
                                        </tr>
                                        <tr>
                                            <td>Shipping</td>
                                            <td class="text-end">
                                                <input type="number" id="shipping" name="shipping" class="form-control text-end" value="0.00" step="0.01">
                                            </td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Total</td>
                                            <td class="text-end">$<span id="total">0.00</span></td>
                                            <input type="hidden" name="total_amount_hidden" value="0.00">
                                        </tr>
                                        <tr>
                                            <td>Amount Paid</td>
                                            <td class="text-end">$<span id="amountPaid">0.00</span></td>
                                            <input type="hidden" name="amount_paid_hidden" value="0.00">
                                        </tr>
                                        <tr class="table-primary fw-bold">
                                            <td>Amount Due (USD)</td>
                                            <td class="text-end">$<span id="finalAmountDue">0.00</span></td>
                                            <!-- This is the same as grand_total_hidden, but kept for clarity -->
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">
                                                <a href="#" class="btn btn-sm btn-link text-primary rounded" onclick="alert('Request a Deposit feature')">Request a Deposit</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control rounded" id="notes" name="invoice_note" rows="3" placeholder="Enter notes (optional)"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="terms" class="form-label">Terms</label>
                            <textarea class="form-control rounded" id="terms" name="payment_terms" rows="3">Kindly pay the invoice via ACH to the specified account number or via Zelle. We appreciate your business. Thank you for paying the invoice.</textarea>
                        </div>

                        <h5>Attachments</h5>
                        <div class="card p-3 d-flex justify-content-center align-items-center border-dashed rounded">
                            <p class="text-muted mb-0">+ Add an attachment</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Settings Sidebar -->
            <aside class="right-settings-sidebar w-full lg:w-1/3 mt-6 lg:mt-0">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 text-secondary">Settings</h6>
                        <small class="text-muted">For This Invoice</small>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center rounded-top">
                            <div>
                                <h6 class="mb-0">Accept Online Payments</h6>
                                <div class="payment-icons mt-1">
                                    <!-- Using Bootstrap Icons for general credit card/paypal -->
                                    <i class="bi bi-credit-card-fill text-muted me-1"></i>
                                    <i class="bi bi-paypal text-muted"></i>
                                    <!-- Using Icons8 for specific card types, consider local hosting for production -->
                                    <img src="https://img.icons8.com/color/24/000000/visa.png" onerror="this.onerror=null;this.src='https://placehold.co/24x24/cccccc/white?text=Visa';" alt="Visa" class="payment-icon">
                                    <img src="https://img.icons8.com/color/24/000000/mastercard.png" onerror="this.onerror=null;this.src='https://placehold.co/24x24/cccccc/white?text=MC';" alt="Mastercard" class="payment-icon">
                                    <img src="https://img.icons8.com/color/24/000000/american-express.png" onerror="this.onerror=null;this.src='https://placehold.co/24x24/cccccc/white?text=Amex';" alt="Amex" class="payment-icon">
                                    <img src="https://img.icons8.com/color/24/000000/discover.png" onerror="this.onerror=null;this.src='https://placehold.co/24x24/cccccc/white?text=Disc';" alt="Discover" class="payment-icon">
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input rounded-pill" type="checkbox" id="acceptPaymentsToggle">
                                <label class="form-check-label" for="acceptPaymentsToggle"><span class="toggle-text">NO</span></label>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Customize Invoice Style</h6>
                                <small class="text-muted">Change Template, Color, and Font</small>
                            </div>
                            <a href="#" class="text-muted rounded"><i class="bi bi-chevron-right"></i></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center rounded-bottom">
                            <div>
                                <h6 class="mb-0">Make Recurring</h6>
                                <small class="text-muted">Bill your clients automatically</small>
                            </div>
                            <a href="#" class="text-muted rounded"><i class="bi bi-chevron-right"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-md shadow-md hover:bg-green-600 hover:scale-[1.02] transition-all text-lg font-semibold">
                        Generate Invoice
                    </button>
                </div>
            </aside>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eE7q8dE8B" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const invoiceForm = document.getElementById('invoiceForm');
            const addLineItemBtn = document.getElementById('addLineItemBtn');
            const lineItemsContainer = document.getElementById('lineItemsContainer'); // tbody for line items
            const searchClientInput = document.getElementById('search-client');
            const clientSuggestionsDiv = document.getElementById('client-suggestions');
            const clientIdInput = document.getElementById('client-id');
            const clientNameInput = document.getElementById('client-name');
            const clientEmailInput = document.getElementById('client-email');
            const clientPhoneInput = document.getElementById('client-phone');
            const clientAddressInput = document.getElementById('client-address');
            
            const subtotalSpan = document.getElementById('subtotal');
            const taxSpan = document.getElementById('tax');
            const totalSpan = document.getElementById('total');
            const amountPaidSpan = document.getElementById('amountPaid');
            const finalAmountDueSpan = document.getElementById('finalAmountDue');
            const shippingInput = document.getElementById('shipping');
            const notesTextarea = document.getElementById('notes');
            const termsTextarea = document.getElementById('terms');

            const acceptPaymentsToggle = document.getElementById('acceptPaymentsToggle');
            const toggleText = acceptPaymentsToggle.nextElementSibling.querySelector('.toggle-text');
            const messageBox = document.getElementById('messageBox');

            let itemRowCounter = 0; // To keep track of unique row IDs for naming inputs

            // Array to hold client data fetched from PHP
            const clients = <?php echo json_encode($clients); ?>;

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

            // Function to add a new item row
            function addLineItem(item = {}) {
                const newRow = document.createElement('tr');
                newRow.classList.add('line-item', 'border-b', 'border-gray-200', 'hover:bg-gray-100');
                newRow.setAttribute('data-row-id', itemRowCounter);

                newRow.innerHTML = `
                    <td class="py-3 px-6 text-left">
                        <input type="text" name="items[${itemRowCounter}][name]" class="form-control rounded" placeholder="Description" value="${item.item_name || ''}">
                        <textarea name="items[${itemRowCounter}][description]" rows="1" class="form-control rounded mt-1" placeholder="Optional long description">${item.item_description || ''}</textarea>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <input type="number" name="items[${itemRowCounter}][rate]" class="form-control text-end item-rate rounded" placeholder="Rate" value="${item.rate || 0.00}" step="0.01">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <input type="number" name="items[${itemRowCounter}][quantity]" class="form-control text-end item-qty rounded" placeholder="Qty" value="${item.quantity || 1}" min="1">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <input type="number" name="items[${itemRowCounter}][tax_percentage]" class="form-control text-end tax-percentage-input rounded" value="${item.tax_percentage || 0.00}" step="0.01" min="0" max="100">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="tax-amount-display">$ 0.00</span>
                        <input type="hidden" name="items[${itemRowCounter}][tax_amount]" class="tax-amount-input" value="${item.tax_amount || 0.00}">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <input type="number" name="items[${itemRowCounter}][discount]" class="form-control text-end discount-input rounded" value="${item.discount || 0.00}" step="0.01" min="0">
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="amount-display font-semibold">$ 0.00</span>
                        <input type="hidden" name="items[${itemRowCounter}][amount]" class="amount-input" value="${item.amount || 0.00}">
                    </td>
                    <td class="py-3 px-6 text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-line-item rounded">&times;</button>
                    </td>
                `;
                lineItemsContainer.appendChild(newRow);
                attachLineItemEventListeners(newRow);
                itemRowCounter++;
                updateTotals(); // Recalculate after adding new line
            }

            // Function to attach event listeners to a line item row
            function attachLineItemEventListeners(row) {
                const rateInput = row.querySelector('.item-rate');
                const qtyInput = row.querySelector('.item-qty');
                const taxPercentageInput = row.querySelector('.tax-percentage-input');
                const discountInput = row.querySelector('.discount-input');
                const removeButton = row.querySelector('.remove-line-item');

                [rateInput, qtyInput, taxPercentageInput, discountInput].forEach(input => {
                    input.addEventListener('input', calculateRowAmount);
                });

                removeButton.addEventListener('click', function() {
                    row.remove();
                    updateTotals(); // Recalculate after removing a line
                });
            }

            // Calculate amount for a single row
            function calculateRowAmount(event) {
                const row = event.target.closest('tr');
                const quantity = parseFloat(row.querySelector('.item-qty').value) || 0;
                const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
                const taxPercentage = parseFloat(row.querySelector('.tax-percentage-input').value) || 0;
                const discount = parseFloat(row.querySelector('.discount-input').value) || 0;

                let lineSubtotal = quantity * rate;
                let itemTaxAmount = 0;
                let itemAmount = lineSubtotal;

                // Apply item-level discount
                itemAmount -= discount;

                // Apply item-level tax
                itemTaxAmount = (itemAmount * taxPercentage) / 100;
                itemAmount += itemTaxAmount;


                row.querySelector('.tax-amount-display').textContent = `$ ${itemTaxAmount.toFixed(2)}`;
                row.querySelector('.tax-amount-input').value = itemTaxAmount.toFixed(2);
                row.querySelector('.amount-display').textContent = `$ ${itemAmount.toFixed(2)}`;
                row.querySelector('.amount-input').value = itemAmount.toFixed(2);

                updateTotals();
            }

            // Function to calculate and update all totals
            function updateTotals() {
                let subtotal = 0;
                let totalTax = 0;
                let totalDiscount = 0;
                let grandTotal = 0;
                const shipping = parseFloat(shippingInput.value) || 0;

                lineItemsContainer.querySelectorAll('.line-item').forEach(itemRow => {
                    const quantity = parseFloat(itemRow.querySelector('.item-qty').value) || 0;
                    const rate = parseFloat(itemRow.querySelector('.item-rate').value) || 0;
                    const itemDiscount = parseFloat(itemRow.querySelector('.discount-input').value) || 0;
                    const itemTaxAmount = parseFloat(itemRow.querySelector('.tax-amount-input').value) || 0;
                    const itemAmount = parseFloat(itemRow.querySelector('.amount-input').value) || 0;

                    subtotal += (quantity * rate); // Subtotal is sum of (qty * rate)
                    totalTax += itemTaxAmount;
                    totalDiscount += itemDiscount;
                    grandTotal += itemAmount; // Grand total is sum of final item amounts
                });

                grandTotal += shipping; // Add shipping to the grand total

                subtotalSpan.textContent = subtotal.toFixed(2);
                document.querySelector('input[name="subtotal_hidden"]').value = subtotal.toFixed(2);

                taxSpan.textContent = totalTax.toFixed(2);
                document.querySelector('input[name="total_tax"]').value = totalTax.toFixed(2);

                totalSpan.textContent = grandTotal.toFixed(2); // Total is essentially Grand Total before Amount Paid
                document.querySelector('input[name="total_amount_hidden"]').value = grandTotal.toFixed(2);

                const amountPaid = parseFloat(amountPaidSpan.textContent) || 0; // Assuming 0 for now
                const finalAmountDue = grandTotal - amountPaid;
                finalAmountDueSpan.textContent = finalAmountDue.toFixed(2);
                document.getElementById('grand_total_hidden').value = finalAmountDue.toFixed(2); // Final amount due is the grand total to be saved
            }

            // Event listeners for overall calculations
            shippingInput.addEventListener('input', updateTotals);

            // Client Search and Selection
            searchClientInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                clientSuggestionsDiv.innerHTML = '';
                if (searchTerm.length > 0) {
                    const filteredClients = clients.filter(client =>
                        client.client_name.toLowerCase().includes(searchTerm) ||
                        client.client_phone.includes(searchTerm) ||
                        client.client_email.toLowerCase().includes(searchTerm)
                    );
                    if (filteredClients.length > 0) {
                        filteredClients.forEach(client => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200', 'border-b', 'border-gray-100');
                            suggestionItem.textContent = `${client.client_name} (${client.client_phone || client.client_email})`;
                            suggestionItem.addEventListener('click', function() {
                                clientIdInput.value = client.id;
                                clientNameInput.value = client.client_name;
                                clientEmailInput.value = client.client_email;
                                clientPhoneInput.value = client.client_phone;
                                clientAddressInput.value = client.client_address;
                                searchClientInput.value = client.client_name; // Set search input to selected client name
                                clientSuggestionsDiv.classList.add('hidden'); // Hide suggestions
                            });
                            clientSuggestionsDiv.appendChild(suggestionItem);
                        });
                        clientSuggestionsDiv.classList.remove('hidden');
                    } else {
                        clientSuggestionsDiv.classList.add('hidden');
                    }
                } else {
                    clientSuggestionsDiv.classList.add('hidden');
                    // Clear client details if search input is empty
                    clientIdInput.value = '';
                    clientNameInput.value = '';
                    clientEmailInput.value = '';
                    clientPhoneInput.value = '';
                    clientAddressInput.value = '';
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(event) {
                if (!searchClientInput.contains(event.target) && !clientSuggestionsDiv.contains(event.target)) {
                    clientSuggestionsDiv.classList.add('hidden');
                }
            });

            // Toggle text for "Accept Online Payments"
            acceptPaymentsToggle.addEventListener('change', function() {
                if (this.checked) {
                    toggleText.textContent = 'YES';
                } else {
                    toggleText.textContent = 'NO';
                }
            });

            // Add initial row on page load
            addLineItem();

            // Add row button event listener
            addLineItemBtn.addEventListener('click', addLineItem);

            // Handle form submission
            invoiceForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                const formData = new FormData(invoiceForm);
                // Collect item details dynamically
                lineItemsContainer.querySelectorAll('.line-item').forEach((row, index) => {
                    formData.append(`items[${index}][name]`, row.querySelector('input[name^="items["][name$="[name]"]').value);
                    formData.append(`items[${index}][description]`, row.querySelector('textarea[name^="items["][name$="[description]"]').value);
                    formData.append(`items[${index}][quantity]`, row.querySelector('input[name^="items["][name$="[quantity]"]').value);
                    formData.append(`items[${index}][rate]`, row.querySelector('input[name^="items["][name$="[rate]"]').value);
                    formData.append(`items[${index}][tax_percentage]`, row.querySelector('input[name^="items["][name$="[tax_percentage]"]').value);
                    formData.append(`items[${index}][tax_amount]`, row.querySelector('input[name^="items["][name$="[tax_amount]"]').value);
                    formData.append(`items[${index}][discount]`, row.querySelector('input[name^="items["][name$="[discount]"]').value);
                    formData.append(`items[${index}][amount]`, row.querySelector('input[name^="items["][name$="[amount]"]').value);
                });
                
                // Append other form fields that are not part of the dynamic items
                formData.append('invoice_number', document.getElementById('invoiceNumber').value);
                formData.append('reference', document.getElementById('reference').value);
                formData.append('invoice_date', document.getElementById('dateOfIssue').value);
                formData.append('due_date', document.getElementById('dueDate').value);
                formData.append('client_id', document.getElementById('client-id').value);
                formData.append('warehouse', 'Main Warehouse'); // Hardcoded as per screenshot, or make dynamic if needed
                formData.append('tax_type', 'On'); // Hardcoded 'On' as per screenshot, or make dynamic
                formData.append('discount_type', 'Percentage'); // Hardcoded 'Percentage' as per screenshot, or make dynamic
                formData.append('invoice_note', notesTextarea.value);
                formData.append('total_tax', document.querySelector('input[name="total_tax"]').value);
                formData.append('total_discount', '0.00'); // Assuming no overall discount field for now
                formData.append('shipping', shippingInput.value);
                formData.append('grand_total', document.getElementById('grand_total_hidden').value);
                formData.append('payment_currency', 'USD'); // Hardcoded as per screenshot, or make dynamic
                formData.append('payment_terms', termsTextarea.value);


                try {
                    const response = await fetch('invoice_handler.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showMessage(result.message, 'success');
                        // Optionally, redirect or clear form
                        setTimeout(() => {
                            window.location.reload(); // Reload to clear form and get new invoice number
                        }, 2000);
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
