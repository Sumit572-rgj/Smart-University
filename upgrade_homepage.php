<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/index.php';

$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(\$title) ?> - CIT UMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cit_ums/css/style.css?v=<?= time() ?>">
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        /* --- Animations --- */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }
        .animate-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }

        /* --- Navbar --- */
        .navbar {
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nav-links a:hover {
            color: #f97316;
        }
        .login-btn-left {
            background: #f97316;
            color: #ffffff;
            padding: 0.6rem 1.5rem;
            border-radius: 9999px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(249,115,22,0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .login-btn-left:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px -2px rgba(249,115,22,0.4);
            background: #ea580c;
        }
        
        /* --- Hero Section --- */
        .hero {
            padding: 6rem 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.5rem;
            letter-spacing: -0.05em;
            line-height: 1.1;
            max-width: 900px;
        }
        .hero h1 span {
            color: #f97316;
        }
        .hero p {
            font-size: 1.25rem;
            color: #475569;
            margin-bottom: 3rem;
            max-width: 600px;
        }

        /* --- Features Section --- */
        .features-section {
            padding: 5rem 5%;
            background: #ffffff;
        }
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3rem;
        }
        .floating-cards {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .feature-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 300px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-align: center;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            border-color: #f97316;
        }

        /* --- Footer --- */
        .footer {
            background: #0f172a;
            color: #f8fafc;
            padding: 4rem 5% 2rem;
            margin-top: auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .footer h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #f97316;
        }
        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer ul li {
            margin-bottom: 0.75rem;
        }
        .footer ul li a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer ul li a:hover {
            color: #ffffff;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #64748b;
            font-size: 0.875rem;
        }

        /* --- Mobile --- */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            .hero h1 { font-size: 2.5rem; }
            .hero { padding: 4rem 1rem; }
            .feature-card { width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <!-- Login button on the LEFT -->
        <a href="/cit_ums/auth/login" class="login-btn-left">
            <span>🔒</span> Login to Portal
        </a>
        
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#features">Features</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>

        <div style="font-weight: 800; font-size: 1.5rem; color: #0f172a; letter-spacing: -1px;">
            CIT<span>UMS</span>
        </div>
    </nav>

    <!-- Hero -->
    <main class="hero">
        <h1 class="animate-on-scroll">Next-Gen <span>University</span> Management</h1>
        <p class="animate-on-scroll delay-100">Experience the most powerful, unified platform for students, faculty, and administration. Seamlessly integrated, brilliantly fast.</p>
        <div class="animate-on-scroll delay-200">
            <a href="/cit_ums/auth/login" class="login-btn-left" style="padding: 1rem 3rem; font-size: 1.25rem;">Get Started Now &rarr;</a>
        </div>
    </main>

    <!-- Features -->
    <section id="features" class="features-section">
        <h2 class="section-title animate-on-scroll">Everything you need</h2>
        <div class="floating-cards">
            <div class="feature-card animate-on-scroll delay-100">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎓</div>
                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Student Portal</h3>
                <p style="font-size: 0.95rem; color: #64748b;">Access grades, attendance, and course materials instantly from anywhere.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-200">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👨‍🏫</div>
                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Faculty Tools</h3>
                <p style="font-size: 0.95rem; color: #64748b;">Effortlessly manage classes, assignments, and track student progress.</p>
            </div>
            <div class="feature-card animate-on-scroll delay-300">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🛡️</div>
                <h3 style="font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Administration</h3>
                <p style="font-size: 0.95rem; color: #64748b;">Complete oversight with powerful real-time analytics and global reporting.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="footer-grid">
            <div class="animate-on-scroll">
                <h4>CIT UMS</h4>
                <p style="color: #94a3b8; line-height: 1.6; font-size: 0.9rem;">
                    The ultimate enterprise resource planning platform designed exclusively for modern educational institutions.
                </p>
            </div>
            <div class="animate-on-scroll delay-100">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="/cit_ums/auth/login">Login Portal</a></li>
                </ul>
            </div>
            <div class="animate-on-scroll delay-200">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="animate-on-scroll delay-300">
                <h4>Contact</h4>
                <ul>
                    <li><a href="#">support@citums.edu</a></li>
                    <li><a href="#">+91 800 123 4567</a></li>
                    <li><a href="#">Chennai, India</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom animate-on-scroll">
            &copy; <?= date('Y') ?> Chennai Institute of Technology. All rights reserved.
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        // Optional: stop observing once animated
                        // observer.unobserve(entry.target); 
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            });

            document.querySelectorAll('.animate-on-scroll').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
HTML;

file_put_contents($f, $content);
echo "Homepage upgraded with animations, navbar, and footer.";
