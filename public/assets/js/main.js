// Timer functionality for test
let timeLeft = TEST_DURATION || 1200;
let testId = TEST_ID || null;

function formatTime(seconds) {
    let minutes = Math.floor(seconds / 60);
    let secs = seconds % 60;
    return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

function updateTimer() {
    timeLeft--;

    let display = document.getElementById('timer');
    if (display) {
        display.innerText = formatTime(timeLeft);

        // Add warning class when time is running out
        if (timeLeft <= 300) {
            display.classList.add('warning');
        }

        // Auto submit when time is up
        if (timeLeft <= 0) {
            alert('Time is up! Your test will be submitted.');
            let form = document.getElementById('testForm');
            if (form) {
                form.submit();
            }
        }
    }
}

// Start timer only if on test page
if (document.getElementById('timer')) {
    setInterval(updateTimer, 1000);
}

// ==========================================
// Mobile Navigation
// ==========================================

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const navOverlay = document.getElementById('navOverlay');
    const body = document.body;

    if (menuToggle && navMenu && navOverlay) {
        closeMobileMenu();

        // Overlay click to close
        navOverlay.addEventListener('click', closeMobileMenu);
        // Toggle mobile menu
        menuToggle.addEventListener('click', function() {
            const isActive = navMenu.classList.contains('active');
            
            if (isActive) {
                // Close menu
                closeMobileMenu();
            } else {
                // Open menu
                openMobileMenu();
            }
        });

        // Close menu when clicking close button
        const navCloseBtn = document.getElementById('navCloseBtn');
        if (navCloseBtn) {
            navCloseBtn.addEventListener('click', function() {
                closeMobileMenu();
            });
        }

        // Close menu when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        // Close menu when window is resized above mobile breakpoint
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900 && navMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        function openMobileMenu() {
            navOverlay.classList.add('active');
            navMenu.classList.add('active');
            menuToggle.classList.add('active');
            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.setAttribute('aria-label', 'Close menu');
            body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            navOverlay.classList.remove('active');
            navMenu.classList.remove('active');
            menuToggle.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Open menu');
            body.style.overflow = '';
        }

        // Add smooth scroll for navigation links
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Close menu after navigation
                setTimeout(() => {
                    closeMobileMenu();
                }, 300);
            });
        });
    }

});

// ==========================================
// Anti-Cheating Features
// ==========================================

// Prevent right-click
document.addEventListener('contextmenu', event => {
    if (document.getElementById('testForm')) {
        event.preventDefault();
        alert('Right-click is disabled during test');
    }
});

// Prevent copy
document.addEventListener('copy', e => {
    if (document.getElementById('testForm')) {
        e.preventDefault();
        alert('Copy is disabled during test');
    }
});

// Prevent cut
document.addEventListener('cut', e => {
    if (document.getElementById('testForm')) {
        e.preventDefault();
        alert('Cut is disabled during test');
    }
});

// Prevent paste
document.addEventListener('paste', e => {
    if (document.getElementById('testForm')) {
        e.preventDefault();
        alert('Paste is disabled during test');
    }
});

// Detect tab switching
let tabSwitchWarnings = 0;
document.addEventListener('visibilitychange', function() {
    if (document.getElementById('testForm')) {
        if (document.hidden) {
            tabSwitchWarnings++;
            
            if (tabSwitchWarnings >= 3) {
                alert('Test terminated due to multiple tab switches!');
                document.getElementById('testForm').submit();
            } else {
                alert(`Warning: Do not leave the test page! (${tabSwitchWarnings}/3 warnings)`);
            }
        }
    }
});

// Prevent back button on test page
if (document.getElementById('testForm')) {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
}

// Prevent F12 developer tools
// document.onkeydown = function(event) {
//     if (event.key === 'F12' || event.ctrlKey && event.shiftKey && event.key === 'I') {
//         event.preventDefault();
//         alert('Developer tools are not allowed during test');
//     }
// };

// ==========================================
// Test Functionality
// ==========================================

// Auto-save answers
let autoSaveInterval = null;

function autoSaveAnswers() {
    let testForm = document.getElementById('testForm');
    if (!testForm) return;

    let formData = new FormData(testForm);
    let answers = {};

    // Extract answers from form
    formData.forEach((value, key) => {
        if (key.startsWith('answers[')) {
            answers[key] = value;
        }
    });

    // Save to local storage for recovery on page reload
    if (Object.keys(answers).length > 0) {
        localStorage.setItem('test_answers_' + testId, JSON.stringify(answers));
    }
}

// Start auto-save every 30 seconds
if (document.getElementById('testForm')) {
    autoSaveInterval = setInterval(autoSaveAnswers, 30000);
}

// Restore answers on page load
window.addEventListener('load', function() {
    let testForm = document.getElementById('testForm');
    if (!testForm) return;

    let saved = localStorage.getItem('test_answers_' + testId);
    if (saved) {
        let answers = JSON.parse(saved);
        Object.keys(answers).forEach(key => {
            let radio = testForm.querySelector(`input[name="${key}"][value="${answers[key]}"]`);
            if (radio) {
                radio.checked = true;
            }
        });
    }
});

// Clear saved answers on successful submission
function onTestSubmit() {
    localStorage.removeItem('test_answers_' + testId);
    return true;
}

// ==========================================
// Form Validation
// ==========================================

function validateForm(formId) {
    let form = document.getElementById(formId);
    if (!form) return true;

    let inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        let error = input.parentElement.querySelector('.form-error');
        
        if (input.value.trim() === '') {
            if (!error) {
                error = document.createElement('div');
                error.className = 'form-error';
                input.parentElement.appendChild(error);
            }
            error.innerText = 'This field is required';
            isValid = false;
        } else {
            if (error) {
                error.remove();
            }
        }
    });

    return isValid;
}

// ==========================================
// Smooth scrolling
// ==========================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        let target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
