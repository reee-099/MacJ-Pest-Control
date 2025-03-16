<?php
session_start();
if ($_SESSION['role'] !== 'technician') {
    header("Location: SignIn.php");
    exit;
}

// Get technician's username from database
$conn = new mysqli("localhost", "root", "", "MacJ_Pest_Control");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$stmt = $conn->prepare("SELECT username FROM technicians WHERE technician_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacJ Pest Control - Technician Portal</title>
    <link rel="stylesheet" href="technician.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h1>MacJ Pest Control</h1>
        <p>Technician Portal</p>
        <button id="closeSidebar" class="close-sidebar"><i class="fas fa-times"></i></button>
    </div>
    <ul class="sidebar-menu">
        <li class="active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
        <li data-page="jobs"><i class="fas fa-clipboard-list"></i> Job Orders</li>
        <li data-page="reports"><i class="fas fa-file-alt"></i> Assessment</li>
        <li data-page="logout"><i class="fas fa-sign-out-alt"></i> Logout</li>
    </ul>
</nav>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="top-bar">
                <button id="toggleSidebar" class="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                </div>
            </header>

            <!-- Dashboard Page -->
            <section id="dashboard" class="page active">
                <div class="page-header">
                    <h2>Dashboard</h2>
                    <p class="date">Today: <span id="currentDate"></span></p>
                </div>

                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                        <div class="stat-info">
                            <h3>Today's Jobs</h3>
                            <p class="stat-number">5</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-info">
                            <h3>Completed</h3>
                            <p class="stat-number">2</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div class="stat-info">
                            <h3>Pending</h3>
                            <p class="stat-number">3</p>
                        </div>
                    </div>
                </div>

                <div class="section-header">
                    <h3>Today's Schedule</h3>
                </div>

                <div class="schedule-container">
                    <div class="job-card" data-job-id="1001">
                        <div class="job-time">
                            <span class="time">09:00 AM</span>
                            <span class="status completed">Completed</span>
                        </div>
                        <div class="job-details">
                            <h4>General Pest Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Sarah Johnson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 123 Main St, Apt 4B</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Cockroach Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1002">
                        <div class="job-time">
                            <span class="time">11:30 AM</span>
                            <span class="status completed">Completed</span>
                        </div>
                        <div class="job-details">
                            <h4>Rodent Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Michael Brown</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 456 Oak Ave</p>
                            <p class="service-type"><i class="fas fa-mouse"></i> Mice Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card active" data-job-id="1003">
                        <div class="job-time">
                            <span class="time">02:00 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Termite Treatment</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Emily Wilson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 789 Pine St</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Termite Inspection</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1004">
                        <div class="job-time">
                            <span class="time">04:30 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Mosquito Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> David Clark</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 321 Elm St</p>
                            <p class="service-type"><i class="fas fa-mosquito"></i> Yard Treatment</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1005">
                        <div class="job-time">
                            <span class="time">06:00 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Bed Bug Treatment</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Jennifer Lee</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 555 Maple Ave, Unit 7</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Bed Bug Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>

                <div class="section-header">
                    <h3>Task Summary</h3>
                </div>

                <div class="task-summary">
                    <div class="task-category">
                        <h4>Pending Tasks</h4>
                        <p class="task-count">3</p>
                    </div>
                    <div class="task-category">
                        <h4>Completed Today</h4>
                        <p class="task-count">2</p>
                    </div>
                    <div class="task-category">
                        <h4>Overdue</h4>
                        <p class="task-count">0</p>
                    </div>
                </div>
            </section>

            <!-- Job Orders Page -->
            <section id="jobs" class="page">

                <div class="jobs-list">
                    <div class="job-card" data-job-id="1001">
                        <div class="job-time">
                            <span class="date">Today</span>
                            <span class="time">09:00 AM</span>
                            <span class="status completed">Completed</span>
                        </div>
                        <div class="job-details">
                            <h4>General Pest Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Sarah Johnson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 123 Main St, Apt 4B</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Cockroach Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1002">
                        <div class="job-time">
                            <span class="date">Today</span>
                            <span class="time">11:30 AM</span>
                            <span class="status completed">Completed</span>
                        </div>
                        <div class="job-details">
                            <h4>Rodent Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Michael Brown</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 456 Oak Ave</p>
                            <p class="service-type"><i class="fas fa-mouse"></i> Mice Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1003">
                        <div class="job-time">
                            <span class="date">Today</span>
                            <span class="time">02:00 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Termite Treatment</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Emily Wilson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 789 Pine St</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Termite Inspection</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1004">
                        <div class="job-time">
                            <span class="date">Today</span>
                            <span class="time">04:30 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Mosquito Control</h4>
                            <p class="client-name"><i class="fas fa-user"></i> David Clark</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 321 Elm St</p>
                            <p class="service-type"><i class="fas fa-mosquito"></i> Yard Treatment</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1005">
                        <div class="job-time">
                            <span class="date">Today</span>
                            <span class="time">06:00 PM</span>
                            <span class="status pending">Pending</span>
                        </div>
                        <div class="job-details">
                            <h4>Bed Bug Treatment</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Jennifer Lee</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 555 Maple Ave, Unit 7</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Bed Bug Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="job-card" data-job-id="1006">
                        <div class="job-time">
                            <span class="date">Tomorrow</span>
                            <span class="time">10:00 AM</span>
                            <span class="status scheduled">Scheduled</span>
                        </div>
                        <div class="job-details">
                            <h4>Termite Treatment</h4>
                            <p class="client-name"><i class="fas fa-user"></i> Robert Taylor</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 888 Cedar Ln</p>
                            <p class="service-type"><i class="fas fa-bug"></i> Termite Infestation</p>
                        </div>
                        <div class="job-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Job Detail Modal -->
            <div id="jobDetailModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <div class="modal-header">
                        <h2>Job Details</h2>
                        <span class="job-id">Job #<span id="modalJobId">1003</span></span>
                    </div>
                    
                    <div class="job-status-bar">
                        <span class="status-label">Status:</span>
                        <span id="modalJobStatus" class="status pending">Pending</span>
                    </div>
                    
                    <div class="client-info-section">
                        <h3>Client Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Name:</label>
                                <p id="modalClientName">Emily Wilson</p>
                            </div>
                            <div class="info-item">
                                <label>Phone:</label>
                                <p id="modalClientPhone">(555) 123-4567</p>
                            </div>
                            <div class="info-item">
                                <label>Email:</label>
                                <p id="modalClientEmail">emily.wilson@example.com</p>
                            </div>
                            <div class="info-item">
                                <label>Address:</label>
                                <p id="modalClientAddress">789 Pine St, Anytown, ST 12345</p>
                            </div>
                            <div class="info-item">
                                <label>Client Type:</label>
                                <p id="modalClientType">New Client</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-info-section">
                        <h3>Service Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Service Type:</label>
                                <p id="modalServiceType">Termite Treatment</p>
                            </div>
                            <div class="info-item">
                                <label>Date:</label>
                                <p id="modalServiceDate">June 15, 2025</p>
                            </div>
                            <div class="info-item">
                                <label>Time:</label>
                                <p id="modalServiceTime">2:00 PM</p>
                            </div>
                            <div class="info-item">
                                <label>Estimated Duration:</label>
                                <p id="modalServiceDuration">1.5 hours</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tools-section">
                        <h3>Required Tools & Chemicals</h3>
                        <ul id="modalToolsList" class="tools-list">
                            <li><i class="fas fa-check-circle"></i> Termite inspection tools</li>
                            <li><i class="fas fa-check-circle"></i> Moisture meter</li>
                            <li><i class="fas fa-check-circle"></i> Drill and injection equipment</li>
                            <li><i class="fas fa-check-circle"></i> Termiticide (2 gallons)</li>
                            <li><i class="fas fa-check-circle"></i> Protective gear (gloves, mask, goggles)</li>
                        </ul>
                    </div>
                    
                    <div class="notes-section">
                        <h3>Notes</h3>
                        <p id="modalNotes">Client reported seeing termite damage in the basement. Initial inspection required before full treatment plan. Client has a dog that should be kept away during inspection.</p>
                    </div>
                    
                    <div class="history-section" id="historySection">
                        <h3>Service History</h3>
                        <p class="new-client-message">This is a new client. No previous service history.</p>
                        <!-- Service history would be populated here for returning clients -->
                    </div>
                    
                    <div class="action-buttons">
                        <button id="startJobBtn" class="btn-primary"><i class="fas fa-play"></i> Start Job</button>
                        <button id="completeJobBtn" class="btn-success" style="display: none;"><i class="fas fa-check"></i> Complete Job</button>
                        <button id="viewMapBtn" class="btn-secondary"><i class="fas fa-map-marked-alt"></i> View Map</button>
                        <button id="callClientBtn" class="btn-secondary"><i class="fas fa-phone"></i> Call Client</button>
                    </div>
                </div>
            </div>

            <!-- Report Form Modal -->
            <div id="reportFormModal" class="modal">
                <div class="modal-content">
                    <span class="close-report-modal">&times;</span>
                    <div class="modal-header">
                        <h2>Job Completion Report</h2>
                        <span class="job-id">Job #<span id="reportJobId">1003</span></span>
                    </div>
                    
                    <form id="jobReportForm">
                        <div class="form-section">
                            <h3>Service Details</h3>
                            <div class="form-group">
                                <label for="actualStartTime">Actual Start Time:</label>
                                <input type="time" id="actualStartTime" required>
                            </div>
                            <div class="form-group">
                                <label for="actualEndTime">Actual End Time:</label>
                                <input type="time" id="actualEndTime" required>
                            </div>
                            <div class="form-group">
                                <label for="serviceArea">Service Area (sq. meters):</label>
                                <input type="number" id="serviceArea" required>
                            </div>
                        </div>
                        
                        <div class="form-section" id="chemicalUsageSection">
                            <h3>Chemical Usage</h3>
                            <div class="chemical-list" id="chemicalList">
                                <div class="chemical-item">
                                    <div class="form-group">
                                        <label for="chemicalType1">Chemical Type:</label>
                                        <select id="chemicalType1" class="chemical-type" required>
                                            <option value="">Select Chemical</option>
                                            <option value="Termiticide">Termiticide</option>
                                            <option value="Insecticide">Insecticide</option>
                                            <option value="Rodenticide">Rodenticide</option>
                                            <option value="Fungicide">Fungicide</option>
                                            <option value="Herbicide">Herbicide</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="chemicalAmount1">Amount Used:</label>
                                        <input type="number" id="chemicalAmount1" class="chemical-amount" step="0.01" required>
                                        <select id="chemicalUnit1" class="chemical-unit">
                                            <option value="liters">Liters</option>
                                            <option value="gallons">Gallons</option>
                                            <option value="kg">Kilograms</option>
                                            <option value="grams">Grams</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addChemicalBtn" class="btn-secondary"><i class="fas fa-plus"></i> Add Another Chemical</button>
                        </div>
                        
                        <div class="form-section">
                            <h3>Pest Observation</h3>
                            <div class="form-group">
                                <label for="pestObserved">Pests Observed:</label>
                                <select id="pestObserved" multiple>
                                    <option value="Termites">Termites</option>
                                    <option value="Cockroaches">Cockroaches</option>
                                    <option value="Ants">Ants</option>
                                    <option value="Rodents">Rodents</option>
                                    <option value="Mosquitoes">Mosquitoes</option>
                                    <option value="Bed Bugs">Bed Bugs</option>
                                    <option value="Flies">Flies</option>
                                    <option value="Spiders">Spiders</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="infestationLevel">Infestation Level:</label>
                                <select id="infestationLevel" required>
                                    <option value="">Select Level</option>
                                    <option value="Low">Low</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Severe">Severe</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3>Photo Documentation</h3>
                            <div class="photo-upload-container">
                                <div class="photo-upload">
                                    <label for="beforePhoto">Before Treatment:</label>
                                    <input type="file" id="beforePhoto" accept="image/*" capture="camera">
                                    <div class="preview" id="beforePreview">
                                        <i class="fas fa-camera"></i>
                                        <span>Take Photo</span>
                                    </div>
                                </div>
                                <div class="photo-upload">
                                    <label for="afterPhoto">After Treatment:</label>
                                    <input type="file" id="afterPhoto" accept="image/*" capture="camera">
                                    <div class="preview" id="afterPreview">
                                        <i class="fas fa-camera"></i>
                                        <span>Take Photo</span>
                                    </div>
                                </div>
                                <div class="photo-upload">
                                    <label for="additionalPhoto">Additional (Optional):</label>
                                    <input type="file" id="additionalPhoto" accept="image/*" capture="camera">
                                    <div class="preview" id="additionalPreview">
                                        <i class="fas fa-camera"></i>
                                        <span>Take Photo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3>Notes & Recommendations</h3>
                            <div class="form-group">
                                <label for="treatmentNotes">Treatment Notes:</label>
                                <textarea id="treatmentNotes" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="recommendations">Recommendations for Future:</label>
                                <textarea id="recommendations" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="followUpNeeded">Follow-up Needed:</label>
                                <select id="followUpNeeded" required>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            <div class="form-group follow-up-date" style="display: none;">
                                <label for="followUpDate">Recommended Follow-up Date:</label>
                                <input type="date" id="followUpDate">
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3>Client Signature</h3>
                            <div class="signature-pad-container">
                                <canvas id="signaturePad" width="300" height="150"></canvas>
                                <button type="button" id="clearSignatureBtn" class="btn-secondary">Clear</button>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Submit Report</button>
                            <button type="button" id="saveAsDraftBtn" class="btn-secondary">Save as Draft</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reports Page -->
            <section id="reports" class="page">
                <div class="page-header">
                    <h2>Reports</h2>
                    <div class="filter-container">
                        <select id="reportFilter" class="filter-dropdown">
                            <option value="all">All Reports</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>

                <div class="search-container">
                    <input type="text" id="reportSearch" placeholder="Search reports...">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                </div>

                <div class="reports-list">
                    <div class="report-card">
                        <div class="report-header">
                            <div class="report-info">
                                <h4>Termite Treatment</h4>
                                <p class="report-date">June 15, 2025</p>
                            </div>
                            <span class="report-status completed">Completed</span>
                        </div>
                        <div class="report-details">
                            <p class="client-name"><i class="fas fa-user"></i> Emily Wilson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 789 Pine St</p>
                            <div class="report-stats">
                                <span><i class="fas fa-spray-can"></i> 2 chemicals used</span>
                                <span><i class="fas fa-ruler-combined"></i> 150 sq.m</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-header">
                            <div class="report-info">
                                <h4>General Pest Control</h4>
                                <p class="report-date">June 15, 2025</p>
                            </div>
                            <span class="report-status completed">Completed</span>
                        </div>
                        <div class="report-details">
                            <p class="client-name"><i class="fas fa-user"></i> Sarah Johnson</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 123 Main St, Apt 4B</p>
                            <div class="report-stats">
                                <span><i class="fas fa-spray-can"></i> 1 chemical used</span>
                                <span><i class="fas fa-ruler-combined"></i> 75 sq.m</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-header">
                            <div class="report-info">
                                <h4>Rodent Control</h4>
                                <p class="report-date">June 15, 2025</p>
                            </div>
                            <span class="report-status completed">Completed</span>
                        </div>
                        <div class="report-details">
                            <p class="client-name"><i class="fas fa-user"></i> Michael Brown</p>
                            <p class="client-address"><i class="fas fa-map-marker-alt"></i> 456 Oak Ave</p>
                            <div class="report-stats">
                                <span><i class="fas fa-trap"></i> 8 traps placed</span>
                                <span><i class="fas fa-ruler-combined"></i> 120 sq.m</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-view"><i class="fas fa-eye"></i> View</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Success Notification -->
            <div id="successNotification" class="notification">
                <i class="fas fa-check-circle"></i>
                <p>Report submitted successfully!</p>
            </div>
        </main>
    </div>

    <script src="technician.js"></script>
</body>
</html>