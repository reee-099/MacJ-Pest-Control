document.addEventListener('DOMContentLoaded', function() {
    // Current date display
    const currentDateElement = document.getElementById('currentDate');
    const today = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    currentDateElement.textContent = today.toLocaleDateString('en-US', options);
    
    // Sidebar navigation
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const menuItems = document.querySelectorAll('.sidebar-menu li');
    
    // Toggle sidebar
    toggleSidebarBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    
    // Close sidebar
    closeSidebarBtn.addEventListener('click', function() {
        sidebar.classList.remove('active');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(e.target) && 
            e.target !== toggleSidebarBtn && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    });
    
    // Page navigation
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all menu items
            menuItems.forEach(i => i.classList.remove('active'));
            
            // Add active class to clicked menu item
            this.classList.add('active');
            
            // Get the page to show
            const pageId = this.getAttribute('data-page');
            
            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Show the selected page
            if (pageId !== 'logout') {
                document.getElementById(pageId).classList.add('active');
                
                // Close sidebar on mobile after navigation
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            } else {
                // Handle logout
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = 'logout.php';
                }
            }
        });
    });
    
    // Job card click handlers
    const jobCards = document.querySelectorAll('.job-card');
    const jobDetailModal = document.getElementById('jobDetailModal');
    const closeModalBtn = document.querySelector('.close-modal');
    
    jobCards.forEach(card => {
        const viewBtn = card.querySelector('.btn-view');
        viewBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            openJobDetailModal(card.getAttribute('data-job-id'));
        });
        
        card.addEventListener('click', function() {
            openJobDetailModal(this.getAttribute('data-job-id'));
        });
    });
    
    closeModalBtn.addEventListener('click', function() {
        jobDetailModal.style.display = 'none';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === jobDetailModal) {
            jobDetailModal.style.display = 'none';
        }
        if (e.target === reportFormModal) {
            reportFormModal.style.display = 'none';
        }
    });
    
    // Function to open job detail modal
    function openJobDetailModal(jobId) {
        // In a real app, this would fetch job details from an API
        // For this demo, we'll use the hardcoded data
        
        // Update modal with job details
        document.getElementById('modalJobId').textContent = jobId;
        
        // Show the modal
        jobDetailModal.style.display = 'block';
        
        // Set up action buttons
        setupJobActionButtons(jobId);
    }
    
    // Set up job action buttons
    function setupJobActionButtons(jobId) {
        const startJobBtn = document.getElementById('startJobBtn');
        const completeJobBtn = document.getElementById('completeJobBtn');
        const viewMapBtn = document.getElementById('viewMapBtn');
        const callClientBtn = document.getElementById('callClientBtn');
        
        // Start Job button
        startJobBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to start this job?')) {
                // Update job status
                document.getElementById('modalJobStatus').textContent = 'In Progress';
                document.getElementById('modalJobStatus').className = 'status pending';
                
                // Hide start button, show complete button
                startJobBtn.style.display = 'none';
                completeJobBtn.style.display = 'inline-block';
                
                // In a real app, this would update the job status in the database
                alert('Job started. The timer has begun.');
            }
        });
        
        // Complete Job button
        completeJobBtn.addEventListener('click', function() {
            // Close job detail modal
            jobDetailModal.style.display = 'none';
            
            // Open report form modal
            openReportFormModal(jobId);
        });
        
        // View Map button
        viewMapBtn.addEventListener('click', function() {
            // In a real app, this would open a map with directions
            alert('Opening map with directions to client location.');
        });
        
        // Call Client button
        callClientBtn.addEventListener('click', function() {
            // In a real app, this would initiate a phone call
            alert('Calling client at (555) 123-4567');
        });
    }
    
    // Report Form Modal
    const reportFormModal = document.getElementById('reportFormModal');
    const closeReportModalBtn = document.querySelector('.close-report-modal');
    const jobReportForm = document.getElementById('jobReportForm');
    const followUpNeeded = document.getElementById('followUpNeeded');
    const followUpDateContainer = document.querySelector('.follow-up-date');
    
    closeReportModalBtn.addEventListener('click', function() {
        reportFormModal.style.display = 'none';
    });
    
    // Function to open report form modal
    function openReportFormModal(jobId) {
        // Update report job ID
        document.getElementById('reportJobId').textContent = jobId;
        
        // Set default times
        const now = new Date();
        document.getElementById('actualStartTime').value = formatTime(now.getHours(), now.getMinutes());
        
        const endTime = new Date(now.getTime() + 60 * 60 * 1000); // 1 hour later
        document.getElementById('actualEndTime').value = formatTime(endTime.getHours(), endTime.getMinutes());
        
        // Show the modal
        reportFormModal.style.display = 'block';
    }
    
    // Format time for input fields (HH:MM)
    function formatTime(hours, minutes) {
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    }
    
    // Follow-up needed toggle
    followUpNeeded.addEventListener('change', function() {
        console.log('Follow-up needed changed:', this.value);
        if (this.value === 'Yes') {
            followUpDateContainer.style.display = 'block';
        } else {
            followUpDateContainer.style.display = 'none';
        }
    });
    
    // Photo upload preview
    const photoInputs = document.querySelectorAll('input[type="file"]');
    
    photoInputs.forEach(input => {
        const previewId = input.id + 'Preview';
        const preview = document.getElementById(previewId);
        
        preview.addEventListener('click', function() {
            console.log('Preview clicked:', previewId);
            input.click();
        });
        
        input.addEventListener('change', function() {
            console.log('File input changed:', this.files);
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.style.backgroundImage = `url(${e.target.result})`;
                    preview.innerHTML = '';
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // Signature Pad
    const canvas = document.getElementById('signaturePad');
    const clearBtn = document.getElementById('clearSignatureBtn');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    
    // Set up signature pad
    ctx.lineWidth = 2;
    ctx.strokeStyle = '#000';
    
    // Start drawing
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('touchstart', startDrawing);
    
    // Draw
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('touchmove', draw);
    
    // Stop drawing
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Clear signature
    clearBtn.addEventListener('click', clearSignature);
    
    function startDrawing(e) {
        isDrawing = true;
        draw(e);
    }
    
    function draw(e) {
        if (!isDrawing) return;
        
        e.preventDefault();
        
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX || e.touches[0].clientX) - rect.left;
        const y = (e.clientY || e.touches[0].clientY) - rect.top;
        
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }
    
    function stopDrawing() {
        isDrawing = false;
        ctx.beginPath();
    }
    
    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
    
    // Form submission
    jobReportForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // In a real app, this would submit the form data to a server
        // For this demo, we'll just show a success message
        
        // Close the report form modal
        reportFormModal.style.display = 'none';
        
        // Show success notification
        const notification = document.getElementById('successNotification');
        notification.classList.add('show');
        
        // Hide notification after 3 seconds
        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
        
        // Update job status in the UI
        const jobId = document.getElementById('reportJobId').textContent;
        updateJobStatus(jobId, 'completed');
    });
    
    // Save as Draft button
    const saveAsDraftBtn = document.getElementById('saveAsDraftBtn');
    saveAsDraftBtn.addEventListener('click', function() {
        alert('Report saved as draft. You can complete it later.');
        reportFormModal.style.display = 'none';
    });
    
    // Function to update job status in the UI
    function updateJobStatus(jobId, status) {
        // Update in job cards
        const jobCards = document.querySelectorAll(`.job-card[data-job-id="${jobId}"]`);
        jobCards.forEach(card => {
            const statusElement = card.querySelector('.status');
            statusElement.textContent = status === 'completed' ? 'Completed' : 'Pending';
            statusElement.className = `status ${status}`;
        });
        
        // Update stats
        updateDashboardStats();
    }
    
    // Function to update dashboard stats
    function updateDashboardStats() {
        // Count completed and pending jobs
        const completedJobs = document.querySelectorAll('.job-card .status.completed').length;
        const pendingJobs = document.querySelectorAll('.job-card .status.pending').length;
        const totalJobs = completedJobs + pendingJobs;
        
        // Update stats
        document.querySelector('.stat-card:nth-child(1) .stat-number').textContent = totalJobs;
        document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = completedJobs;
        document.querySelector('.stat-card:nth-child(3) .stat-number').textContent = pendingJobs;
        
        // Update task summary
        document.querySelector('.task-category:nth-child(1) .task-count').textContent = pendingJobs;
        document.querySelector('.task-category:nth-child(2) .task-count').textContent = completedJobs;
    }
    
    // Search functionality
    const jobSearch = document.getElementById('jobSearch');
    jobSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const jobCards = document.querySelectorAll('#jobs .job-card');
        
        jobCards.forEach(card => {
            const jobTitle = card.querySelector('h4').textContent.toLowerCase();
            const clientName = card.querySelector('.client-name').textContent.toLowerCase();
            const address = card.querySelector('.client-address').textContent.toLowerCase();
            
            if (jobTitle.includes(searchTerm) || clientName.includes(searchTerm) || address.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    const jobFilter = document.getElementById('jobFilter');
    jobFilter.addEventListener('change', function() {
        const filterValue = this.value;
        const jobCards = document.querySelectorAll('#jobs .job-card');
        
        jobCards.forEach(card => {
            const status = card.querySelector('.status').textContent.toLowerCase();
            const date = card.querySelector('.date').textContent.toLowerCase();
            
            switch (filterValue) {
                case 'all':
                    card.style.display = 'flex';
                    break;
                case 'pending':
                    card.style.display = status === 'pending' ? 'flex' : 'none';
                    break;
                case 'completed':
                    card.style.display = status === 'completed' ? 'flex' : 'none';
                    break;
                case 'today':
                    card.style.display = date === 'today' ? 'flex' : 'none';
                    break;
                case 'week':
                    // In a real app, this would check if the date is within the current week
                    card.style.display = (date === 'today' || date === 'tomorrow') ? 'flex' : 'none';
                    break;
            }
        });
    });
    
    // Report search and filter
    const reportSearch = document.getElementById('reportSearch');
    const reportFilter = document.getElementById('reportFilter');
    
    reportSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const reportCards = document.querySelectorAll('.report-card');
        
        reportCards.forEach(card => {
            const reportTitle = card.querySelector('h4').textContent.toLowerCase();
            const clientName = card.querySelector('.client-name').textContent.toLowerCase();
            
            if (reportTitle.includes(searchTerm) || clientName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    reportFilter.addEventListener('change', function() {
        // In a real app, this would filter reports by date
        alert('Filtering reports by: ' + this.value);
    });
});