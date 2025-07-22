<?php
// Updated sidebar with absolute paths
?>
<!-- Responsive Sidebar Navigation -->
<style>
/* Sidebar Styles */
#sidebarMenu {
    min-height: 100vh;
    background: #87CEEB;
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    width: 230px;
    z-index: 1000;
    transition: left 0.3s;
}
#sidebarMenu .sidebar-header {
    padding: 1.5rem 1rem;
    font-size: 1.8rem;
    background: #87CEEB;
    text-align: left;
    font-weight: bold;
    letter-spacing: 1px;
	color:darkblue;
}
#sidebarMenu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
#sidebarMenu ul li {
    border-bottom: 1px solid #1976d2;
}
#sidebarMenu ul li a {
    display: block;
    color: #fff;
    padding: 1rem 1.5rem;
    text-decoration: none;
    transition: background 0.2s;
}
#sidebarMenu ul li a:hover, #sidebarMenu ul li.active > a {
    background: rgb(255, 255, 255);
    color: #515a71ff;
}
#sidebarMenu .sidebar-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80%;
    background: #626e7bff;
    padding: 1rem 1.5rem;
    font-size: 0.95rem;
    color: #f7f8f9ff;
}

/* Toggle Button for smaller screens */
#sidebarToggle {
    display: none;
    position: fixed;
    left: 10px;
    top: 10px;
    background: #212529;
    color: #fff;
    border: none;
    z-index: 1100;
    width: 45px;
    height: 45px;
    border-radius: 6px;
    font-size: 2rem;
    align-items: center;
    justify-content: center;
}

/* Responsive */
@media (max-width: 992px) {
    #sidebarMenu {
        left: -240px;
    }
    #sidebarMenu.active {
        left: 0;
    }
    #sidebarToggle {
        display: flex;
    }
    body.sidebar-open {
        overflow: hidden;
    }
}
@media (max-width: 576px) {
    #sidebarMenu {
        width: 90vw;
        min-width: 0;
    }
}
body.with-sidebar {
    margin-left: 230px;
    transition: margin-left 0.3s;
}
@media (max-width: 992px) {
    body.with-sidebar {
        margin-left: 0;
    }
}
</style>

<button id="sidebarToggle" title="Open Menu">&#9776;</button>

<nav id="sidebarMenu">
    <div class="sidebar-header">
        <span>Menu</span>
    </div>
    <ul>
        <li><a href="/school_management/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="/school_management/staff/addstaff.php"><i class="bi bi-person-plus"></i> Add Staff</a></li>
        <li><a href="/school_management/students/addlearner.php"><i class="bi bi-person-plus-fill"></i> Add Learner</a></li>
        <li><a href="/school_management/subjects/addsubject.php"><i class="bi bi-book"></i> Add Subject</a></li>
        <li><a href="/school_management/exams/addexam.php"><i class="bi bi-pencil-square"></i> Add Exam</a></li>
        <li><a href="/school_management/class/addclass.php"><i class="bi bi-diagram-3"></i> Add Class</a></li>
        <li><a href="/school_management/attendance/markattendance.php"><i class="bi bi-check2-square"></i> Mark Attendance</a></li>
        <li><a href="/school_management/enrollment/enrollstudent.php"><i class="bi bi-box-arrow-in-right"></i> Enroll Student</a></li>
        <li><a href="/school_management/events/addevent.php"><i class="bi bi-calendar-event"></i> Add Event</a></li>
        <li><a href="/school_management/grades/entergrades.php"><i class="bi bi-clipboard-data"></i> Enter Grades</a></li>
        <li><a href="/school_management/dashboard.php"><i class="bi bi-person-badge"></i> Admin Dashboard</a></li>
    </ul>
    <div class="sidebar-footer">
        <div>
            <p></p>
            <button><a href="logout.php"></a>Logout</button>
        </div>
    </div>
</nav>

<!-- Bootstrap Icons CDN (for icons, optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebarMenu');
    var toggleBtn = document.getElementById('sidebarToggle');

    // Show/hide sidebar on small screens
    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        document.body.classList.toggle('sidebar-open');
    });

    // Hide sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 993 && sidebar.classList.contains('active')) {
            if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                sidebar.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // Add "with-sidebar" class to body for spacing on large screens
    function updateSidebarSpacing() {
        if (window.innerWidth >= 993) {
            document.body.classList.add('with-sidebar');
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        } else {
            document.body.classList.remove('with-sidebar');
        }
    }
    updateSidebarSpacing();
    window.addEventListener('resize', updateSidebarSpacing);
});
</script>
