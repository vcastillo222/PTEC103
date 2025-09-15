        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.innerHTML = navLinks.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add scroll animation to sections
        const sections = document.querySelectorAll('section');

        function checkScroll() {
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (sectionTop < windowHeight - 100) {
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                }
            });
        }

        // Initialize sections with fade-in effect
        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(50px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        });

        window.addEventListener('scroll', checkScroll);
        window.addEventListener('load', checkScroll);

        // Add 3D effect to logo on scroll
        const logoImg = document.querySelector('.logo-img');
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            logoImg.style.transform = 'rotateY(' + (scrollPosition / 10) + 'deg)';
        });

        // Add glow effect to profile image
        const profileImgs = document.querySelectorAll('.profile-img');
        profileImgs.forEach(img => {
            img.addEventListener('mousemove', (e) => {
                const rect = img.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const angle = Math.atan2(y - centerY, x - centerX) * 180 / Math.PI;
                img.style.boxShadow = (Math.cos(angle * Math.PI / 180) * 20) + "px " 
                + (Math.sin(angle * Math.PI / 180) * 20) + "px 40px rgba(255, 95, 31, 0.4)";
            });

            img.addEventListener('mouseleave', () => {
                img.style.boxShadow = '0 20px 50px rgba(0, 0, 0, 0.3)';
            });
        });
