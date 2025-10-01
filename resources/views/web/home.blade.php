<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincón Chaqueño - Restaurante Tradicional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #8B0000;
            --dark-red: #A52A2A;
            --light-red: #DC143C;
            --accent-brown: #8B4513;
        }
        body { font-family: 'Georgia', serif; background-color: #000; color: white; }
        .navbar { background-color: var(--primary-red) !important; border-bottom: 2px solid var(--accent-brown); }
        .navbar-brand { font-weight: bold; font-size: 1.8em; color: white !important; }
        .nav-link { color: white !important; font-weight: 500; margin: 0 15px; transition: color 0.3s ease; }
        .nav-link:hover { color: #FFD700 !important; }
        .btn-ingresar { background-color: var(--light-red); border: none; color: white; padding: 8px 20px; border-radius: 25px; font-weight: bold; transition: all 0.3s ease; }
        .btn-ingresar:hover { background-color: var(--accent-brown); transform: translateY(-2px); }
        .hero-section { background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url("{{ asset('images/fondo.jpg') }}"); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; display: flex; align-items: center; position: relative; }
        .hero-content { text-align: center; z-index: 2; }
        .hero-title { font-size: 4rem; font-weight: bold; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); }
        .hero-subtitle { font-size: 1.5rem; margin-bottom: 2rem; color: #FFD700; text-shadow: 1px 1px 2px rgba(0,0,0,0.8); }
        .featured-section { padding: 80px 0; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); }
        .featured-title { text-align: center; font-size: 3rem; font-weight: bold; margin-bottom: 1rem; color: white; }
        .featured-subtitle { text-align: center; font-size: 1.2rem; margin-bottom: 4rem; color: #ccc; }
        .featured-card { background: rgba(255,255,255,0.1); border-radius: 15px; overflow: hidden; margin-bottom: 30px; transition: transform 0.3s ease, box-shadow 0.3s ease; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        .featured-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .featured-image { height: 250px; object-fit: cover; width: 100%; }
        .featured-overlay { background: rgba(255,255,255,0.9); color: #333; padding: 20px; }
        .featured-overlay h3 { font-weight: bold; margin-bottom: 10px; color: var(--primary-red); }
        .featured-overlay p { color: #666; margin-bottom: 15px; }
        .btn-featured { background: var(--accent-brown); color: white; border: none; padding: 10px 25px; border-radius: 25px; font-weight: bold; transition: all 0.3s ease; }
        .btn-featured:hover { background: var(--primary-red); transform: translateY(-2px); }
        .reservations-card { background: var(--primary-red); color: white; padding: 30px; border-radius: 15px; text-align: center; height: 100%; }
        .reservations-card h3 { font-weight: bold; margin-bottom: 15px; }
        .reservations-card p { margin-bottom: 20px; opacity: 0.9; }
        .btn-reservar { background: var(--accent-brown); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-weight: bold; transition: all 0.3s ease; }
        .btn-reservar:hover { background: var(--dark-red); transform: translateY(-2px); }
        .footer { background-color: var(--primary-red); color: white; padding: 30px 0; border-top: 2px solid var(--accent-brown); }
        .footer a { color: white; text-decoration: none; margin: 0 15px; transition: color 0.3s ease; }
        .footer a:hover { color: #FFD700; }
        @media (max-width: 768px) { .hero-title { font-size: 2.5rem; } .featured-title { font-size: 2rem; } }
    </style>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-utensils me-2"></i>
            Rincón Chaqueño
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('public.menu') }}">Menú</a></li>
                <li class="nav-item"><a class="nav-link" href="#nosotros">Sobre Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-danger">Acceder como administrador</a></li>
            </ul>
        </div>
    </div>
</nav>

    <section id="inicio" class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">PLATOS TÍPICOS</h1>
                <p class="hero-subtitle">DEGUSTE DE LOS DELICIOSOS PLATOS DE LA CASA</p>
            </div>
        </div>
    </section>

    <section class="featured-section">
        <div class="container">
            <h2 class="featured-title">Nuestros Platos Destacados</h2>
            <p class="featured-subtitle">Sabores auténticos que cuentan la historia de la región chaqueña</p>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="featured-card">
                        <img src="{{ asset('images/pollo.jpg') }}" alt="Pollo a la Leña" class="featured-image">
                        <div class="featured-overlay">
                            <h3>POLLO A LA LEÑA</h3>
                            <p>Disfruta del mejor pollo al fuego lento con sabor tradicional.</p>
                            <button class="btn btn-featured">Comienza a disfrutar</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="reservations-card">
                        <h3>RESERVACIONES</h3>
                        <p>Reserva tu mesa para una experiencia única.</p>
                        <button class="btn btn-reservar">Reserva ya</button>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="featured-card">
                        <img src="{{ asset('images/chancho-0000.jpg') }}" alt="Chancho a la Cruz" class="featured-image">
                        <div class="featured-overlay">
                            <h3>CHANCHO A LA CRUZ</h3>
                            <p>Cerdo asado a la cruz, tradición chaqueña auténtica.</p>
                            <button class="btn btn-featured">Hacer Pedido</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="nosotros" class="py-5" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-4 fw-bold mb-4 text-white">Nuestra Historia</h2>
                    <p class="lead text-light mb-4">El Rincón Chaqueño nació de la pasión por preservar y compartir los sabores auténticos de la región chaqueña. Desde 1995, hemos estado sirviendo platos tradicionales que cuentan la historia de nuestra tierra a través de la gastronomía.</p>
                    <p class="text-light">Nuestros ingredientes son seleccionados cuidadosamente de productores locales, garantizando frescura y calidad en cada plato que servimos.</p>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/chancho-0000.jpg') }}" alt="Chancho a la Cruz" class="featured-image">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-white">Nuestro Menú</h2>
                <p class="lead text-light">Sabores que cuentan historias</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="featured-card text-center">
                        <div class="featured-overlay">
                            <h4 class="text-danger">Chancho a la Cruz</h4>
                            <p class="text-muted">Cerdo asado a la cruz, tradición chaqueña</p>
                            <div class="badge bg-danger fs-6">Bs. 9.200</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="featured-card text-center">
                        <div class="featured-overlay">
                            <h4 class="text-danger">Pollo a la Leña</h4>
                            <p class="text-muted">Pollo entero cocinado lentamente al fuego de leña</p>
                            <div class="badge bg-danger fs-6">Bs. 8.500</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="featured-card text-center">
                        <div class="featured-overlay">
                            <h4 class="text-warning">Bife de Chorizo</h4>
                            <p class="text-muted">Corte premium a la parrilla</p>
                            <div class="badge bg-warning text-dark fs-6">Bs. 6.800</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="featured-card text-center">
                        <div class="featured-overlay">
                            <h4 class="text-info">Refresco Frutal</h4>
                            <p class="text-muted">Bebida natural de frutas</p>
                            <div class="badge bg-info text-dark fs-6">Bs. 17</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('public.menu') }}" class="btn btn-lg btn-danger px-5"><i class="fas fa-utensils me-2"></i>Ver Menú Completo</a>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-5" style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-white">Contáctanos</h2>
                <p class="lead text-light">Estamos aquí para servirte</p>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="featured-card p-4">
                        <h4 class="text-danger mb-4"><i class="fas fa-map-marker-alt me-2"></i>Ubicación</h4>
                        <p class="text-light mb-3"><strong>Dirección:</strong><br>Av. Principal 123, Zona Centro<br>Ciudad, Departamento<br>Bolivia</p>
                        <h4 class="text-warning mb-4 mt-4"><i class="fas fa-clock me-2"></i>Horarios de Atención</h4>
                        <p class="text-light mb-3"><strong>Lunes a Domingo:</strong><br>Almuerzo: 12:00 - 15:00<br>Cena: 19:00 - 22:00</p>
                        <h4 class="text-success mb-4 mt-4"><i class="fas fa-phone me-2"></i>Contacto</h4>
                        <p class="text-light mb-3"><strong>Teléfono:</strong> +591 123 456 789<br><strong>WhatsApp:</strong> +591 987 654 321<br><strong>Email:</strong> info@rinconchaqueno.com</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="featured-card p-4">
                        <h4 class="text-primary mb-4"><i class="fas fa-envelope me-2"></i>Envíanos un Mensaje</h4>
                        <form id="contact-form">
                            <div class="mb-3">
                                <label for="nombre" class="form-label text-light">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-light">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label text-light">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono">
                            </div>
                            <div class="mb-3">
                                <label for="mensaje" class="form-label text-light">Mensaje</label>
                                <textarea class="form-control" id="mensaje" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-paper-plane me-2"></i>Enviar Mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <h5 class="text-light mb-3">Síguenos en Redes Sociales</h5>
                <div class="social-links">
                    <a href="#" title="Facebook" class="btn btn-outline-light btn-lg mx-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" title="Instagram" class="btn btn-outline-light btn-lg mx-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="WhatsApp" class="btn btn-outline-light btn-lg mx-2"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" title="YouTube" class="btn btn-outline-light btn-lg mx-2"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Rincón Chaqueño. Todos los derechos reservados.</p>
            <p class="mb-0 mt-2">
                <a href="#">Política de Privacidad</a> |
                <a href="#">Términos de Uso</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            });
        });
        const form = document.getElementById('contact-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('¡Gracias por tu mensaje! Te contactaremos pronto.');
                form.reset();
            });
        }
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) { nav.style.backgroundColor = 'rgba(139, 0, 0, 0.95)'; }
            else { nav.style.backgroundColor = 'var(--primary-red)'; }
        });
    </script>
</body>
</html>


