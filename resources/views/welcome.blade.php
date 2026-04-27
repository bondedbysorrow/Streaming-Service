<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pandora Streaming - Acceso</title>
    <link rel="icon" href="{{ asset('images/logoauf.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@900&family=Poppins:wght@400;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --bg-dark-space: #00000a;
            --primary-neon-purple: #a855f7;
            --accent-glow-cyan: #22d3ee;
            --success-green: #00ff89;
            --text-white: #e5e7eb;
            --text-muted: #9ca3af;
            --input-bg: rgba(30, 30, 50, 0.5);
        }
        html, body {
            height: 100%; margin: 0; overflow: hidden;
            cursor: none; background-color: var(--bg-dark-space);
        }
        body { color: var(--text-white); font-family: 'Poppins', sans-serif; }

        #custom-cursor { position: fixed; width: 20px; height: 20px; border: 2px solid var(--accent-glow-cyan); border-radius: 50%; pointer-events: none; transform: translate(-50%, -50%); transition: width 0.3s, height 0.3s, border-radius 0.3s, background-color 0.3s; z-index: 9999; box-shadow: 0 0 15px var(--accent-glow-cyan); }
        #custom-cursor.hover { width: 40px; height: 40px; background-color: rgba(34, 211, 238, 0.1); }
        #custom-cursor.pointer { width: 8px; height: 8px; background-color: var(--accent-glow-cyan); }

        #tsparticles { position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 1; }
        .main-container { display: flex; width: 100%; height: 100vh; position: relative; z-index: 2; }
        .animation-column, .form-column { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; flex: 1; box-sizing: border-box; position: relative; padding: 1rem; }
        .company-title { font-family: 'Orbitron', sans-serif; font-size: 3rem; font-weight: 900; color: var(--text-white); text-shadow: 0 0 10px var(--accent-glow-cyan), 0 0 20px var(--primary-neon-purple); position: relative; z-index: 3; text-align: center; padding: 0 1rem; }
        #threejs-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; cursor: grab; }
        #threejs-canvas.grabbing { cursor: grabbing; }
        
        /* === ESTILOS UNIFICADOS DEL FORMULARIO === */
        .auth-wrapper {
            display: flex; flex-direction: column; align-items: center; justify-content: flex-start; 
            width: 100%; max-width: 400px;
            background: rgba(10, 0, 20, 0.6); backdrop-filter: blur(5px);
            border: 1.5px solid var(--primary-neon-purple);
            box-shadow: 0 0 25px rgba(168, 85, 247, 0.5);
            border-radius: 16px; padding: 1.5rem 2rem 2rem; 
            box-sizing: border-box; height: auto;
        }
        .auth-wrapper.blurred { filter: blur(5px) brightness(0.5); }
        .tabs { display: flex; width: 100%; max-width: 320px; justify-content: center; margin-bottom: 2.5rem; }
        .tab-btn { flex: 1; font-family: 'Orbitron', sans-serif; background: none; border: none; color: var(--text-muted); font-size: 1.1rem; padding: 0.7rem 0; cursor: none; transition: color 0.3s; border-bottom: 2.5px solid transparent; } .tab-btn.active { color: var(--primary-neon-purple); border-bottom: 2.5px solid var(--primary-neon-purple); text-shadow: 0 0 5px var(--primary-neon-purple); }
        .auth-form-container { position: relative; width: 100%; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .auth-form { width: 100%; display: none; flex-direction: column; align-items: center; } .auth-form.active { display: flex; animation: fadeIn 0.5s ease-out forwards; } 
        
        .auth-form h2 { 
            font-family: 'Orbitron', sans-serif; font-size: 1.8rem; margin-bottom: 1.5rem; 
            text-shadow: 0 0 8px var(--accent-glow-cyan); text-align: center;
            display: flex; align-items: center; justify-content: center; white-space: nowrap; 
        }
        #loginForm h2 {
             font-size: 2rem; margin-bottom: 2rem;
        }

        .form-group { position: relative; margin-bottom: 1.2rem; width: 100%; max-width: 320px; }
        #registerForm .form-group { margin-bottom: 1rem; }
        
        .form-group input { cursor: none; border: 1.5px solid transparent; background: var(--input-bg); border-radius: 12px; width: 100%; padding: 1.2rem 1.2rem 0.6rem 3.2rem; font-size: 1rem; color: var(--text-white); outline: none; transition: background-color 0.3s; box-sizing: border-box; }
        .form-group input:-webkit-autofill, .form-group input:-webkit-autofill:hover, .form-group input:-webkit-autofill:focus, .form-group input:-webkit-autofill:active { -webkit-box-shadow: 0 0 0 40px var(--bg-dark-space) inset !important; -webkit-text-fill-color: var(--text-white) !important; caret-color: var(--text-white); }
        .form-group .icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--primary-neon-purple); transition: color 0.3s; z-index: 2; } 
        .form-group label { position: absolute; left: 3.2rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; transition: all 0.3s ease; z-index: 2; } 
        .form-group:focus-within label, .form-group input:not(:placeholder-shown) + label { top: 0.5rem; transform: translateY(0); font-size: 0.75rem; color: var(--accent-glow-cyan); } 
        .form-group:focus-within .icon { color: var(--accent-glow-cyan); }
        .password-toggle { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: none; font-size: 1.1rem; z-index: 2; }
        .password-criteria { width: 100%; max-width: 320px; padding: 0; margin: -0.5rem 0 1rem 0; list-style: none; } 
        .password-criteria li { color: var(--text-muted); font-size: 0.7rem; margin-bottom: 0.1rem; transition: all 0.4s ease; opacity: 0.7; } 
        .password-criteria li::before { content: '⨂'; display: inline-block; margin-right: 0.5rem; color: #ff5f6d; font-weight: bold; transition: all 0.4s ease; } 
        .password-criteria li.valid { color: var(--success-green); opacity: 1; } .password-criteria li.valid::before { content: '✓'; color: var(--success-green); text-shadow: 0 0 5px var(--success-green); }
        
        .form-options { display: flex; justify-content: space-between; align-items: center; width: 100%; max-width: 320px; margin-bottom: 1.5rem; font-size: 0.9rem; } 
        .form-options__forgot { color: var(--accent-glow-cyan); text-decoration: none; font-weight: 600; cursor: none; } 
        .form-options__remember { display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); cursor: none; } 
        .form-options__remember input[type="checkbox"] { display: none; } 
        .form-options__remember .checkbox-span { display: inline-block; width: 18px; height: 18px; border: 2px solid var(--primary-neon-purple); border-radius: 4px; transition: all 0.3s; position: relative; } 
        .form-options__remember input[type="checkbox"]:checked + .checkbox-span { background-color: var(--primary-neon-purple); box-shadow: 0 0 10px var(--primary-neon-purple); }
        
        .form-options__remember input[type="checkbox"]:checked + .checkbox-span::after { 
            content: ''; display: block; position: absolute;
            width: 5px; height: 10px; border: solid white;
            border-width: 0 3px 3px 0; top: 0px; left: 5px;
            transform: rotate(45deg);
        }

        .neon-button { width: 100%; max-width: 320px; padding: 1rem; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; text-transform: uppercase; letter-spacing: 2px; cursor: none; background: var(--primary-neon-purple); color: var(--text-white); transition: transform 0.2s, box-shadow 0.2s; } 
        .neon-button:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 0 40px var(--primary-neon-purple); } .neon-button:disabled { opacity: 0.6; cursor: not-allowed; } 
        .neon-button .button-text { font-family: 'Share Tech Mono', monospace; }
        
        #password-reset-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; visibility: hidden; background-color: rgba(0,0,0,0.7); transition: opacity 0.4s, visibility 0.4s; }
        #password-reset-modal.visible { opacity: 1; visibility: visible; }
        .modal-content { background: rgba(10, 0, 20, 0.8); backdrop-filter: blur(10px); border-radius: 16px; border: 1.5px solid var(--accent-glow-cyan); box-shadow: 0 0 30px var(--accent-glow-cyan); padding: 2.5rem; width: 90%; max-width: 400px; text-align: center; transform: scale(0.9); transition: transform 0.4s; display: flex; flex-direction: column; align-items: center; position: relative; }
        #password-reset-modal.visible .modal-content { transform: scale(1); }
        .modal-content h3 { font-family: 'Orbitron', sans-serif; margin-top: 0; margin-bottom: 1.5rem; }
        .modal-close-btn { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: none; }
        .modal-content .form-group { max-width: 100%; }
        .modal-content .neon-button { max-width: 240px; width: 100%; margin-top: 1rem; }

        @media (max-width: 900px) {
            html, body { overflow-x: hidden; overflow-y: auto; cursor: auto; }
            #custom-cursor { display: none; }
            .main-container { flex-direction: column-reverse; height: auto; min-height: 100vh; }
            .animation-column { flex: 1 0 50vh; min-height: 300px; }
            .form-column { flex: 1 0 auto; padding: 4rem 1rem; }
            .auth-wrapper { width: 100%; padding: 2rem 1.5rem; }
            .company-title { font-size: 2.2rem; }
            #threejs-canvas { cursor: grab; }
        }
    </style>
</head>
<body>
    <div id="custom-cursor"></div>
    <div id="tsparticles"></div>

    <div class="main-container">
        <div class="animation-column">
            <h1 class="company-title">Pandora Streaming</h1>
            <canvas id="threejs-canvas"></canvas>
        </div>
        <div class="form-column">
            <div class="auth-wrapper" id="auth-wrapper">
                <div class="tabs">
                    <button class="tab-btn active" data-form="loginForm">Iniciar Sesión</button>
                    <button class="tab-btn" data-form="registerForm">Registrarse</button>
                </div>
                <div class="auth-form-container">
                    <form method="POST" action="{{ route('login') }}" class="auth-form active" id="loginForm" novalidate>
                        @csrf
                        <h2><i class="fa-solid fa-rocket" style="margin-right: 0.8rem;"></i> Iniciar Sesión <i class="fa-solid fa-rocket" style="margin-left: 0.8rem;"></i></h2>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope icon"></i>
                            <input type="email" id="login-email" name="email" required autocomplete="email" placeholder=" ">
                            <label for="login-email">Correo Electrónico</label>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock icon"></i>
                            <input type="password" id="login-password" name="password" required autocomplete="current-password" placeholder=" ">
                            <label for="login-password">Contraseña</label>
                            <button type="button" class="password-toggle"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <div class="form-options">
                            <label for="remember" class="form-options__remember">
                                <input id="remember" type="checkbox" name="remember">
                                <span class="checkbox-span"></span>Recordarme
                            </label>
                            <a href="#" class="form-options__forgot" id="forgot-password-link">¿Olvidaste?</a>
                        </div>
                        <button type="submit" class="neon-button"><span class="button-text">Entrar</span></button>
                    </form>
                    
                    <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm" novalidate>
                        @csrf
                        <h2><i class="fa-solid fa-user-plus" style="margin-right: 0.8rem;"></i> Crear Cuenta <i class="fa-solid fa-user-plus" style="margin-left: 0.8rem;"></i></h2>
                        <div class="form-group">
                            <i class="fa-solid fa-user icon"></i>
                            <input type="text" id="register-name" name="name" required minlength="3" placeholder=" ">
                            <label for="register-name">Nombre completo</label>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-envelope icon"></i>
                            <input type="email" id="register-email" name="email" required autocomplete="email" placeholder=" ">
                            <label for="register-email">Correo Electrónico</label>
                        </div>
                        <div class="form-group">
                            <i class="fa-solid fa-lock icon"></i>
                            <input type="password" id="register-password" name="password" required minlength="8" autocomplete="new-password" placeholder=" ">
                            <label for="register-password">Contraseña</label>
                            <button type="button" class="password-toggle"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <ul class="password-criteria">
                            <li data-criterion="length">8+ Caracteres</li>
                            <li data-criterion="uppercase">1 Mayúscula (A-Z)</li>
                            <li data-criterion="number">1 Número (0-9)</li>
                            <li data-criterion="special">1 Símbolo (!@#$)</li>
                        </ul>
                        <div class="form-group">
                            <i class="fa-solid fa-check-double icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder=" ">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                        </div>
                        <button type="submit" class="neon-button"><span class="button-text">Crear Cuenta</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="password-reset-modal">
        <div class="modal-content">
            <button class="modal-close-btn" id="modal-close-btn">&times;</button>
            <h3>Recuperar Acceso</h3>
            <form id="password-reset-form" style="width: 100%;">
                <div class="form-group">
                    <i class="fa-solid fa-envelope icon"></i>
                    <input type="email" id="reset-email" name="email" required placeholder=" ">
                    <label for="reset-email">Correo Electrónico</label>
                </div>
                <button type="submit" class="neon-button"><span class="button-text">Enviar Enlace</span></button>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tone/14.7.77/Tone.js"></script>
    
    <script>
    // --- SCRIPT PARA TSPARTICLES (FONDO) ---
    tsParticles.load("tsparticles", { fpsLimit: 60, interactivity: { events: { onHover: { enable: true, mode: "grab" }, onClick: { enable: true, mode: "push" }, resize: true }, modes: { grab: { distance: 200, links: { opacity: 1, color: "#22d3ee" } }, push: { quantity: 4 } } }, particles: { color: { value: "#ffffff" }, links: { color: "#ffffff", distance: 150, enable: true, opacity: 0.4, width: 1 }, collisions: { enable: true }, move: { direction: "none", enable: true, outModes: { default: "bounce" }, random: false, speed: 1, straight: false }, number: { density: { enable: true, area: 800 }, value: 80 }, opacity: { value: 0.5 }, shape: { type: "circle" }, size: { value: { min: 1, max: 5 } } }, detectRetina: true });

    // --- SCRIPT PARA THREE.JS (ESFERA CON ARRASTRE) ---
    const canvas = document.getElementById('threejs-canvas');
    if (canvas) {
        const container = document.querySelector('.animation-column');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        const sphereParticleCount = 4000;
        const spherePositions = new Float32Array(sphereParticleCount * 3);
        const sphereGeometry = new THREE.BufferGeometry();
        for (let i = 0; i < sphereParticleCount; i++) { const i3 = i * 3; const phi = Math.acos(-1 + (2 * i) / sphereParticleCount); const theta = Math.sqrt(sphereParticleCount * Math.PI) * phi; spherePositions[i3] = 2 * Math.cos(theta) * Math.sin(phi); spherePositions[i3 + 1] = 2 * Math.sin(theta) * Math.sin(phi); spherePositions[i3 + 2] = 2 * Math.cos(phi); }
        sphereGeometry.setAttribute('position', new THREE.BufferAttribute(spherePositions, 3));
        const sphereMaterial = new THREE.PointsMaterial({ size: 0.03, color: 0xa855f7, transparent: true, blending: THREE.AdditiveBlending });
        const sphere = new THREE.Points(sphereGeometry, sphereMaterial);
        scene.add(sphere);
        camera.position.z = 5;
        let isDragging = false, previousMousePosition = { x: 0, y: 0 }, rotationVelocity = { x: 0, y: 0 };
        const dampingFactor = 0.95;
        const onPointerDown = (event) => { isDragging = true; canvas.classList.add('grabbing'); previousMousePosition = { x: event.clientX || event.touches[0].clientX, y: event.clientY || event.touches[0].clientY }; rotationVelocity = { x: 0, y: 0 }; }
        const onPointerMove = (event) => { if (!isDragging) return; const currentMousePosition = { x: event.clientX || event.touches[0].clientX, y: event.clientY || event.touches[0].clientY }; const deltaMove = { x: currentMousePosition.x - previousMousePosition.x, y: currentMousePosition.y - previousMousePosition.y }; const sensitivity = 0.005; rotationVelocity.y = deltaMove.x * sensitivity; rotationVelocity.x = deltaMove.y * sensitivity; sphere.rotation.y += rotationVelocity.y; sphere.rotation.x += rotationVelocity.x; previousMousePosition = currentMousePosition; }
        const onPointerUp = () => { isDragging = false; canvas.classList.remove('grabbing'); }
        canvas.addEventListener('mousedown', onPointerDown); canvas.addEventListener('touchstart', onPointerDown, { passive: true }); window.addEventListener('mousemove', onPointerMove); window.addEventListener('touchmove', onPointerMove, { passive: true }); window.addEventListener('mouseup', onPointerUp); window.addEventListener('touchend', onPointerUp);
        window.addEventListener('resize', () => { if(container.clientWidth > 0 && container.clientHeight > 0){ camera.aspect = container.clientWidth / container.clientHeight; camera.updateProjectionMatrix(); renderer.setSize(container.clientWidth, container.clientHeight); } });
        const clock = new THREE.Clock();
        function animate() { requestAnimationFrame(animate); const elapsedTime = clock.getElapsedTime(); if (!isDragging) { sphere.rotation.y += rotationVelocity.y; sphere.rotation.x += rotationVelocity.x; rotationVelocity.y *= dampingFactor; rotationVelocity.x *= dampingFactor; } const scale = 1 + Math.sin(elapsedTime * 0.7) * 0.1; sphere.scale.set(scale, scale, scale); renderer.render(scene, camera); };
        animate();
    }
    
    // --- SCRIPT DE LÓGICA DEL FORMULARIO ---
    document.addEventListener('DOMContentLoaded', () => {
        const sound = { isStarted: false, focus: new Tone.Synth({ oscillator: { type: 'sine' }, envelope: { attack: 0.005, decay: 0.1, sustain: 0.3, release: 1 } }).toDestination(), swoosh: new Tone.NoiseSynth({ noise: { type: 'white' }, envelope: { attack: 0.005, decay: 0.2, sustain: 0 } }).toDestination(), click: new Tone.MembraneSynth().toDestination(), success: new Tone.PolySynth(Tone.Synth).toDestination(), error: new Tone.MetalSynth({ frequency: 50, envelope: { attack: 0.001, decay: 0.4, release: 0.2 }, harmonicity: 5.1, modulationIndex: 32, resonance: 4000, octaves: 1.5 }).toDestination(), play(soundName) { if (!this.isStarted) return; try { switch(soundName) { case 'focus': this.focus.triggerAttackRelease('C5', '8n'); break; case 'swoosh': this.swoosh.triggerAttackRelease('4n'); break; case 'click': this.click.triggerAttackRelease('C2', '8n'); break; case 'success': this.success.triggerAttackRelease(['C4', 'E4', 'G4'], '8n'); break; case 'error': this.error.triggerAttackRelease('C2', '4n'); break; } } catch (e) { console.error("Tone.js error:", e); } } }; document.body.addEventListener('mousedown', () => { if (!sound.isStarted) { Tone.start(); sound.isStarted = true; } }, { once: true });
        const cursor = document.getElementById('custom-cursor'); if (window.matchMedia("(pointer: fine)").matches) { const interactiveElements = document.querySelectorAll('button, a, input, .checkbox-span'); window.addEventListener('mousemove', e => { cursor.style.left = e.clientX + 'px'; cursor.style.top = e.clientY + 'px'; }); interactiveElements.forEach(el => { el.addEventListener('mouseenter', () => { if (el.tagName === 'INPUT') cursor.classList.add('pointer'); else cursor.classList.add('hover'); }); el.addEventListener('mouseleave', () => cursor.classList.remove('hover', 'pointer')); }); } else { cursor.style.display = 'none'; }
        const tabs = document.querySelectorAll('.tab-btn'); const loginForm = document.getElementById('loginForm'); const registerForm = document.getElementById('registerForm'); let currentForm = loginForm;
        
        function switchForm(targetFormId) { const newForm = document.getElementById(targetFormId); if (newForm === currentForm) return; sound.play('swoosh'); currentForm.classList.remove('active'); newForm.classList.add('active'); currentForm = newForm; }
        tabs.forEach(tab => { tab.addEventListener('click', (e) => { const targetFormId = e.currentTarget.dataset.form; if(document.getElementById(targetFormId) === currentForm) return; tabs.forEach(t => t.classList.remove('active')); e.currentTarget.classList.add('active'); switchForm(targetFormId); }); });
        
        const modal = document.getElementById('password-reset-modal'); const authWrapper = document.getElementById('auth-wrapper'); if(document.getElementById('forgot-password-link')) { document.getElementById('forgot-password-link').addEventListener('click', (e) => { e.preventDefault(); sound.play('swoosh'); authWrapper.classList.add('blurred'); modal.classList.add('visible'); }); } if(document.getElementById('modal-close-btn')) { document.getElementById('modal-close-btn').addEventListener('click', () => { sound.play('swoosh'); authWrapper.classList.remove('blurred'); modal.classList.remove('visible'); }); }
        const forms = document.querySelectorAll('.auth-form, #password-reset-form'); const buttonIntervals = new Map(); document.querySelectorAll('.password-toggle').forEach(toggle => toggle.addEventListener('click', (e) => { sound.play('click'); const input = e.currentTarget.parentElement.querySelector('input'); input.type = input.type === 'password' ? 'text' : 'password'; e.currentTarget.querySelector('i').classList.toggle('fa-eye-slash'); })); document.querySelectorAll('input, .checkbox-span').forEach(el => { el.addEventListener('focus', () => sound.play('focus')); if(el.classList.contains('checkbox-span')) el.addEventListener('click', () => sound.play('click')); }); const pwInput = document.getElementById('register-password'); const criteria = { length: { el: document.querySelector('[data-criterion="length"]'), regex: /.{8,}/ }, uppercase: { el: document.querySelector('[data-criterion="uppercase"]'), regex: /[A-Z]/ }, number: { el: document.querySelector('[data-criterion="number"]'), regex: /[0-9]/ }, special: { el: document.querySelector('[data-criterion="special"]'), regex: /[^A-Za-z0-9]/ }, }; if (pwInput) pwInput.addEventListener('input', () => { for (const key in criteria) { const { el, regex } = criteria[key]; if (regex.test(pwInput.value)) el.classList.add('valid'); else el.classList.remove('valid'); } }); const setLoading = (btn, loading, originalText) => { const buttonTextSpan = btn.querySelector('.button-text'); if (buttonIntervals.has(btn)) { clearInterval(buttonIntervals.get(btn)); buttonIntervals.delete(btn); } btn.disabled = loading; if (loading) { const seq = ["PROCESANDO...", "ENVIANDO...", "ESPERE..."]; let i = 0; buttonTextSpan.textContent = seq[i]; const id = setInterval(() => { i = (i + 1) % seq.length; buttonTextSpan.textContent = seq[i]; }, 700); buttonIntervals.set(btn, id); } else { buttonTextSpan.textContent = originalText; } }; forms.forEach(form => { form.addEventListener('submit', async function(e) { e.preventDefault(); const submitButton = form.querySelector('button[type="submit"]'); const originalButtonText = submitButton.querySelector('.button-text').textContent; setLoading(submitButton, true, originalButtonText); try { const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } }); const data = await response.json(); setLoading(submitButton, false, originalButtonText); if (!response.ok) { sound.play('error'); handleServerErrors(response, data); } else { sound.play('success'); if (form.id === 'password-reset-form') { showToast(data.message || 'Enlace enviado.', 'success'); modal.classList.remove('visible'); authWrapper.classList.remove('blurred'); } else { submitButton.querySelector('.button-text').textContent = "ACCESO OK"; showToast(data.message, 'success'); if (data.redirect) { setTimeout(() => window.location.href = data.redirect, 1000); } } } } catch (error) { sound.play('error'); setLoading(submitButton, false, originalButtonText); showToast('Error de conexión.', 'error'); } }); }); function showToast(message, type = 'info') { const colors = { success: 'linear-gradient(to right, #00b09b, #96c93d)', error: 'linear-gradient(to right, #ff5f6d, #ffc371)', info: 'linear-gradient(to right, #8e2de2, #4a00e0)'}; Toastify({ text: message, duration: 3000, close: true, gravity: "top", position: "right", stopOnFocus: true, style: { background: colors[type] || colors.info, borderRadius: "8px" } }).showToast(); } function handleServerErrors(response, data) { if (response.status === 419) { showToast('Sesión expirada. Refresca.', 'error'); } else if (response.status === 422 && data.errors) { showToast(Object.values(data.errors)[0][0], 'error'); } else { showToast(data.message || 'Error inesperado.', 'error'); } }
    });
    </script>
</body>
</html>