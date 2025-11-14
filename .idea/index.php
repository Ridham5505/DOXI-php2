<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Your Health, Our Priority</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        // Apply theme early before styles load
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
    <meta name="description" content="DOXI - Comprehensive healthcare management platform. Your Health, Our Priority. Streamline medical records, appointments, and patient care.">
    <style>
        /* Dark theme overrides for landing page */
        [data-theme="dark"] {
            --white: #0f172a;
            --gray-50: #0b1220;
            --gray-100: #111827;
            --gray-200: #1f2937;
            --gray-300: #374151;
            --gray-400: #6b7280;
            --gray-500: #9ca3af;
            --gray-600: #d1d5db;
            --gray-700: #e5e7eb;
            --gray-800: #f3f4f6;
            --gray-900: #ffffff;
        }
        
        [data-theme="dark"] body {
            background: var(--gray-50);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .hero {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .features,
        [data-theme="dark"] .about,
        [data-theme="dark"] .success-metrics {
            background: var(--gray-50);
        }
        
        [data-theme="dark"] .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
        }
        
        [data-theme="dark"] .feature-card,
        [data-theme="dark"] .benefit-card,
        [data-theme="dark"] .metric-box,
        [data-theme="dark"] .step-box {
            background: var(--white);
            border-color: var(--gray-200);
            color: var(--gray-900);
        }
        
        .theme-toggle {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            font-size: 0.875rem;
        }
        
        .theme-toggle:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        
        [data-theme="dark"] .theme-toggle {
            background: var(--gray-200);
            border-color: var(--gray-300);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .theme-toggle:hover {
            background: var(--gray-300);
        }
    </style>
    
    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "MedicalOrganization",
        "name": "DOXI",
        "url": "https://doxi.com",
        "description": "DOXI is committed to prioritizing your health by offering comprehensive medical services and healthcare management solutions.",
        "medicalSpecialty": "Healthcare Management",
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "availableLanguage": "English"
        }
    }
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand" aria-label="DOXI logo">
                <img src="public/assets/doxi-icon.svg" alt="DOXI icon" class="nav-icon" width="40" height="40">
                <span class="nav-title">DOXI</span>
                <span class="tagline nav-tagline">Your Health, Our Priority</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#features">Features</a></li>
                <li><a href="#about">About</a></li>
                <li>
                    <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
                        <span id="theme-icon">🌙</span>
                        <span id="theme-text">Dark</span>
                    </button>
                </li>
                <li><a href="login.php" class="btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="highlight">Your Health,</span><br>
                    <span class="highlight">Our Priority</span>
                </h1>
                <p class="hero-description">
                    Comprehensive healthcare management platform designed to streamline medical records, 
                    appointments, and patient care. Built with modern technology and security at its core.
                </p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary">Get Started</a>
                    <a href="#features" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
            <div class="hero-visual">
                <img src="public/assets/hospital-hero.svg" alt="Modern Hospital Facility" class="hero-image">
            </div>
        </div>
        <div class="hero-wave"></div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Comprehensive Healthcare Solutions</h2>
            <p class="section-subtitle">Built on a robust MongoDB schema with modern architecture</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📋</div>
                    <h3>Medical Records</h3>
                    <p>Secure, comprehensive patient record management with real-time updates and seamless access.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Appointment Scheduling</h3>
                    <p>Intelligent scheduling system that optimizes provider availability and patient preferences.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Patient Management</h3>
                    <p>Complete patient lifecycle management from registration to follow-up care.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Security & Compliance</h3>
                    <p>HIPAA-compliant platform with enterprise-grade security and data protection.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Analytics & Reporting</h3>
                    <p>Comprehensive analytics dashboard for insights into patient care and operational efficiency.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🤝</div>
                    <h3>Care Coordination</h3>
                    <p>Collaborative workflows that connect providers and support staff for seamless patient care.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <div class="section-badge">About Us</div>
                    <h2>About DOXI</h2>
                    <p class="lead-text">
                        DOXI is a next-generation healthcare management platform designed to revolutionize 
                        how medical practices operate. We combine cutting-edge technology with deep healthcare 
                        expertise to deliver solutions that truly make a difference.
                    </p>
                    <p>
                        From patient management to appointment scheduling, every aspect of DOXI has been 
                        meticulously crafted to prioritize patient care and streamline healthcare operations. 
                        Our platform empowers healthcare providers to focus on what matters most—delivering 
                        exceptional patient care.
                    </p>
                    <div class="about-stats">
                        <div class="stat-card">
                            <div class="stat-icon">⚡</div>
                            <span class="stat-number">99.9%</span>
                            <span class="stat-label">Uptime</span>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">🔒</div>
                            <span class="stat-number">HIPAA</span>
                            <span class="stat-label">Compliant</span>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">💬</div>
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support</span>
                        </div>
                    </div>
                </div>
                <div class="about-visual">
                    <div class="about-image-wrapper">
                        <img src="public/assets/healthcare-technology.svg" alt="Digital Healthcare Technology - Doctor and Patient Interaction" class="about-image" onerror="this.onerror=null; this.src='public/assets/clinic-about.svg';">
                        <div class="image-decoration"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="logo">DOXI</span>
                    <p>Your Health, Our Priority</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="#security">Security</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="#about">About</a></li>
                            <li><a href="#careers">Careers</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Resources</h4>
                        <ul>
                            <li><a href="#docs">Documentation</a></li>
                            <li><a href="#support">Support</a></li>
                            <li><a href="#blog">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 DOXI. All rights reserved. | HIPAA Compliant | Privacy Policy</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Theme init and toggle
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();
        
        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeToggle(next);
        }
        
        function updateThemeToggle(theme) {
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            if (themeIcon && themeText) {
                if (theme === 'dark') {
                    themeIcon.textContent = '☀️';
                    themeText.textContent = 'Light';
                } else {
                    themeIcon.textContent = '🌙';
                    themeText.textContent = 'Dark';
                }
            }
        }

    </script>
    
</body>
</html>
