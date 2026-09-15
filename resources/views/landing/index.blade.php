@extends('layouts.app')

@section('title', 'Ensamble Curupayty — Manifiesto Audiovisual & Acta Fundacional')
@section('hide_navbar', 'true')

@section('content')
<div class="landing-cinematic-page">

    <!-- 1. EXPERIENCIA TOTAL DE VIDEO AL INGRESAR (100% Pantalla) -->
    <section id="videoTheatre" class="fullscreen-video-theatre">
        <!-- Ambient blurred background video for widescreen monitors -->
        <video 
            id="ambientVideo"
            class="video-ambient-bg" 
            autoplay 
            muted 
            loop 
            playsinline 
            preload="auto">
            <source src="{{ asset('curupaytu_promocion.mp4') }}" type="video/mp4">
        </video>

        <!-- Main Sharp Video Player -->
        <div class="main-video-wrapper" id="mainVideoContainer">
            <video 
                id="promoVideo" 
                class="main-promotional-video" 
                playsinline 
                preload="auto">
                <source src="{{ asset('curupaytu_promocion.mp4') }}" type="video/mp4">
                Tu navegador no soporta reproducción de video HTML5.
            </video>

            <!-- Video Top Floating Navigation (Solo logo y control durante el video) -->
            <div class="video-top-bar">
                <div class="video-brand-badge">
                    <img src="{{ asset('images/logo_curupayty.png') }}" alt="Logo Curupayty" class="video-badge-logo">
                    <div class="video-badge-texts">
                        <span class="video-badge-title">ENSAMBLE CURUPAYTY</span>
                        <span class="video-badge-sub">Atypu · Manifiesto 2026</span>
                    </div>
                </div>

                <div class="video-top-actions">
                    <button type="button" id="btnToggleAudio" class="btn-sound-control">
                        <span id="soundIcon">🔊</span> <span id="soundLabel">Activar Sonido</span>
                    </button>
                    <button type="button" id="btnSkipDirectToActa" class="btn-skip-to-acta">
                        Saltar al Acta <i class="fas fa-forward"></i>
                    </button>
                </div>
            </div>

            <!-- Botón Central de Entrada con Sonido (Resuelve la política de silencio del navegador) -->
            <div id="audioStartPrompt" class="audio-start-prompt" style="display: none;">
                <div class="prompt-content">
                    <img src="{{ asset('images/logo_curupayty.png') }}" alt="Curupayty" class="prompt-logo-img">
                    <h3 class="prompt-title">ENSAMBLE CURUPAYTY</h3>
                    <p class="prompt-subtitle">Manifiesto Audiovisual del Nuevo Sinfonismo</p>
                    <button type="button" id="btnEnterWithSound" class="btn-enter-sound-pulse">
                        <i class="fas fa-play"></i> INGRESAR CON AUDIO
                    </button>
                    <span class="prompt-hint">Tocá para escuchar con la orquesta de cuerdas y locución oficial</span>
                </div>
            </div>

            <!-- Video Bottom Progress Bar -->
            <div class="video-progress-bar-wrap">
                <div class="video-progress-bar-fill" id="videoProgressFill"></div>
            </div>

            <!-- 2. OVERLAY REVEAL AL TÉRMINO DEL VIDEO (Requerimiento Clave) -->
            <div id="videoEndOverlay" class="video-end-curtain">
                <div class="end-curtain-card">
                    <div class="end-logo-wrap">
                        <img src="{{ asset('images/logo_curupayty.png') }}" alt="Ensamble Curupayty" class="end-curtain-logo">
                    </div>
                    <span class="end-badge">MANIFIESTO PROCLAMADO</span>
                    <h2 class="end-title">HA LLEGADO LA HORA DE LA RUPTURA</h2>
                    <p class="end-description">
                        El manifiesto ha concluido. Ahora la historia la escribís vos con tu adhesión al nuevo modelo <strong>40/25/10/25</strong>.
                    </p>
                    
                    <div class="end-cta-wrap">
                        <button type="button" id="btnFirmarActaMonumental" class="btn-cta-monumental">
                            ✍️ FIRMAR EL ACTA FUNDACIONAL
                        </button>
                        <span class="end-cta-hint">Presioná para desbloquear y rubricar el documento con firma táctil</span>
                    </div>

                    <div class="end-replay-wrap">
                        <button type="button" id="btnReplayVideo" class="btn-replay-link">
                            <i class="fas fa-redo"></i> Volver a reproducir el video
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. RESTO DEL SITIO (OCULTO AL ENTRAR — SE REVELA ÚNICAMENTE AL FINALIZAR EL VIDEO O AL SALTARLO) -->
    <div id="siteContentAfterVideo" class="site-content-after-video" style="display: none;">
        
        <!-- SECCIÓN DEL ACTA FUNDACIONAL CON FIRMA TÁCTIL -->
        <section id="acta" class="acta-section">
            <div class="container-medium">
                <div class="acta-paper-card" id="actaPaperCard">
                    
                    <div class="acta-header">
                        <div class="acta-crest-wrap">
                            <img src="{{ asset('images/logo_curupayty.png') }}" alt="Sello Oficial Curupayty" class="acta-crest-img">
                        </div>
                        <span class="acta-pre-title">REPÚBLICA DEL PARAGUAY · ASAMBLEA CONSTITUYENTE</span>
                        <h2 class="acta-title">ACTA FUNDACIONAL DE ADHESIÓN</h2>
                        <p class="acta-sub">Ensamble Curupayty · Atypu — Sociedad de Creadores-Intérpretes</p>
                        <div class="acta-divider"></div>
                        
                        <p class="acta-compromiso">
                            <em>
                                "Por medio de la presente rúbrica manuscrita digital táctil, declaro mi adhesión solemne 
                                y voluntaria a la sociedad económica de creadores <strong>CURUPAYTY · ATYPU</strong>, consagrada exclusivamente a la 
                                creación, formalización legal ante DINAPI/APA/AIE, producción y distribución de 
                                <strong>obra inédita</strong> bajo el modelo <strong>40/25/10/25</strong>. Con este registro, paso a formar parte del padrón oficial 
                                para la Asamblea General Fundacional. <strong>Acá no venimos a interpretar el pasado. Venimos a firmar el futuro.</strong>"
                            </em>
                        </p>
                    </div>

                    <!-- Formulario Oficial de Adhesión -->
                    <form action="{{ route('acta.firmar') }}" method="POST" id="firmaForm" class="firma-form">
                        @csrf
                        <input type="hidden" name="firma_digital" id="firmaDigitalInput">

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="nombre">Nombre *</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ej: Mateo" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="apellido">Apellido *</label>
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido') }}" placeholder="Ej: Benítez" required>
                            </div>
                        </div>

                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="form-label" for="cedula">Cédula de Identidad *</label>
                                <input type="text" name="cedula" id="cedula" class="form-control" value="{{ old('cedula') }}" placeholder="Ej: 4.852.190" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="telefono">
                                    <i class="fab fa-whatsapp" style="color: #25D366;"></i> Celular / WhatsApp (Obligatorio) *
                                </label>
                                <input type="tel" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej: 0981 123456" required>
                                <small style="font-size:0.72rem; color:var(--text-muted); display:block; margin-top:3px;">Requerido para coordinar la asamblea e incorporarte al grupo oficial.</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Correo Electrónico (será tu usuario oficial) *</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="tu.email@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label" for="ciudad">Ciudad de Residencia *</label>
                                <input type="text" name="ciudad" id="ciudad" class="form-control" value="{{ old('ciudad') }}" placeholder="Ej: Asunción / Luque / Encarnación" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="direccion">Dirección *</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Calle, Barrio o Número" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="instrumento">Instrumento o Rol que interpretás *</label>
                            <select name="instrumento" id="instrumento" class="form-control" required>
                                <option value="">-- Seleccioná tu instrumento o disciplina principal --</option>
                                <option value="Violín I">Violín I</option>
                                <option value="Violín II">Violín II</option>
                                <option value="Viola">Viola</option>
                                <option value="Violonchelo">Violonchelo</option>
                                <option value="Contrabajo">Contrabajo</option>
                                <option value="Compositor / Orquestador">Compositor / Orquestador</option>
                                <option value="Arreglador Musical">Arreglador Musical</option>
                                <option value="Director Musical">Director Musical</option>
                                <option value="Vientos Madera (Flauta, Clarinete, Oboe, Fagot)">Vientos Madera</option>
                                <option value="Vientos Metal (Cornos, Trompetas, Trombones)">Vientos Metal</option>
                                <option value="Percusión Sinfónica">Percusión Sinfónica</option>
                                <option value="Clavecín / Piano / Teclados">Clavecín / Piano / Teclados</option>
                                <option value="Músico Popular / Folklore">Músico Popular / Folklore</option>
                                <option value="Gestor Cultural / Productor">Gestor Cultural / Productor</option>
                                <option value="Estudiante Avanzado de Música">Estudiante Avanzado de Música</option>
                                <option value="Ciudadano Adherente / Audiófilo">Ciudadano Adherente / Audiófilo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sueno_musical">¿Cuál es tu sueño musical o visión para este colectivo? *</label>
                            <textarea name="sueno_musical" id="sueno_musical" rows="3" class="form-control" placeholder="Contanos tu anhelo artístico, qué te gustaría componer, interpretar o cambiar en la escena sinfónica..." required>{{ old('sueno_musical') }}</textarea>
                        </div>

                        <!-- MÓDULO DE FIRMA TÁCTIL (TOUCH CANVAS) -->
                        <div class="firma-touch-module">
                            <div class="firma-touch-header">
                                <div>
                                    <label class="form-label" style="color: var(--accent-gold); font-weight: 700; margin-bottom: 0.15rem;">
                                        ✍️ Firma Manuscrita Digital (Táctil) *
                                    </label>
                                    <p class="firma-touch-instruction">
                                        Dibujá tu firma directamente con el dedo desde tu teléfono o con el mouse:
                                    </p>
                                </div>
                                <button type="button" id="btnClearCanvas" class="btn-clear-canvas" title="Borrar y volver a firmar">
                                    ↺ Limpiar Trazo
                                </button>
                            </div>

                            <div class="canvas-wrapper" id="canvasWrapper">
                                <canvas id="signatureCanvas" class="signature-canvas"></canvas>
                                <div class="canvas-placeholder" id="canvasPlaceholder">
                                    <i class="fas fa-pen-nib"></i>
                                    <span>Firmá aquí sobre la línea dorada</span>
                                </div>
                                <div class="canvas-line"></div>
                            </div>

                            <div class="firma-legal-footer">
                                <span><i class="fas fa-shield-alt"></i> Firma criptográfica protegida con timestamp y dirección IP</span>
                                <span class="secure-tag"><i class="fas fa-lock"></i> Protocolo Seguro</span>
                            </div>
                        </div>

                        <!-- CLÁUSULA DE HOMOLOGACIÓN Y SALVAGUARDA COMUNITARIA -->
                        <div class="salvaguarda-box" style="margin: 1.5rem 0 1.25rem; background: rgba(229,169,60,0.04); border: 1px solid rgba(229,169,60,0.25); border-radius: 8px; padding: 1.15rem 1.25rem;">
                            <div style="display: flex; gap: 0.85rem; align-items: flex-start;">
                                <input type="checkbox" id="check_salvaguarda" name="terminos_salvaguarda" required checked style="margin-top: 0.25rem; accent-color: #e5a93c; width: 18px; height: 18px; cursor: pointer; flex-shrink: 0;">
                                <label for="check_salvaguarda" style="font-size: 0.83rem; color: #cbd5e1; line-height: 1.6; cursor: pointer;">
                                    <strong style="color: #f6d28b; font-size: 0.88rem; display: block; margin-bottom: 0.2rem;">
                                        <i class="fas fa-balance-scale"></i> Cláusula de Homologación & Salvaguarda Comunitaria *
                                    </strong>
                                    Declaro bajo fe de juramento la veracidad de los datos consignados y acepto que la presente adhesión al Acta Fundacional constituye una postulación sujeta a la revisión, validación y homologación soberana de la <strong>Comisión Fundacional y la Asamblea Constituyente del Ensamble Curupayty · Atypu</strong>, la cual se reserva expresamente el derecho estatutario de admisión, ratificación o revocación de miembros para salvaguardar los principios éticos, artísticos y la convivencia armónica de la orquesta.
                                </label>
                            </div>
                        </div>

                        <div class="form-submit-wrap">
                            <button type="submit" class="btn-submit-acta" id="btnSubmitForm">
                                <i class="fas fa-stamp"></i> REGISTRAR Y ESTAMPAR FIRMA OFICIAL
                            </button>
                            <p class="submit-note">
                                Al enviar, se generará tu <strong>Certificado Digital de Adhesión</strong> con código de verificación QR.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- PILARES FUNDACIONALES -->
        <section class="pilares-section">
            <div class="container">
                <div class="section-badge">ESTATUTO Y PRINCIPIOS SOBERANOS</div>
                <h2 class="section-title">El Nuevo Paradigma Curupayty · Atypu</h2>
                <p class="section-subtitle" style="text-align: center; color: var(--gold-light); margin-top: -1rem; margin-bottom: 3rem; font-style: italic;">
                    "Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro."
                </p>
                
                <div class="pilares-grid">
                    <div class="pilar-card">
                        <div class="pilar-num">01</div>
                        <h3 class="pilar-title">Condición de Socio = Autor</h3>
                        <p class="pilar-desc">
                            Colectivo autogestionado de creadores-intérpretes. No hay "músico raso": todo socio tiene derecho pleno a presentar obra propia. Producimos en exclusiva obra inédita de nuestros miembros.
                        </p>
                    </div>
                    <div class="pilar-card highlight">
                        <div class="pilar-num">02</div>
                        <h3 class="pilar-title">Todo se Remunera (40/25/10/25)</h3>
                        <p class="pilar-desc">
                            El talento no se regala. <strong>40%</strong> al autor, <strong>25%</strong> al fondo de intérpretes que grabaron, <strong>10%</strong> a estímulo de nuevas producciones y <strong>25%</strong> a mantenimiento operativo.
                        </p>
                    </div>
                    <div class="pilar-card">
                        <div class="pilar-num">03</div>
                        <h3 class="pilar-title">Destierro del Tendota (Braintrust)</h3>
                        <p class="pilar-desc">
                            Se abole el poder vitalicio y sin cuentas. La vida creativa se organiza en <em>Proyectos rotativos (modelo Pixar)</em>: quien propone lidera su obra con todo el respaldo de la comunidad.
                        </p>
                    </div>
                </div>

                <!-- Showcase Manifiesto & Estatuto -->
                <div class="manifesto-showcase-box" style="margin-top: 4rem; background: linear-gradient(135deg, rgba(229,169,60,0.08), rgba(7,9,12,0.95)); border: 1px solid var(--border-gold); border-radius: 14px; padding: 3rem 2.5rem; text-align: center;">
                    <span style="color: var(--accent-gold); font-size: 0.8rem; letter-spacing: 0.15em; font-weight: 700; text-transform: uppercase;">MANIFIESTO DE RUPTURA & ESTATUTO FUNDACIONAL</span>
                    <h3 style="color: #fff; font-family: 'Cinzel', serif; font-size: 1.8rem; margin: 0.75rem 0 1.25rem;">
                        "Acá no venimos a interpretar el pasado. Venimos a firmar el futuro."
                    </h3>
                    <p style="color: #cbd5e1; max-width: 780px; margin: 0 auto 2rem; line-height: 1.8; font-size: 1.05rem;">
                        La música académica paraguaya no necesita un director más en el escalafón: necesita una comunidad de creadores que se deban respeto técnico, formalicen sus obras en DINAPI/APA/AIE, y cobren por cada partitura, grabación y sincronización audiovisual.
                    </p>
                    <div style="display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('pagina.show', 'manifiesto') }}" class="btn btn-gold">
                            <i class="fas fa-bullhorn"></i> Leer Manifiesto de Ruptura
                        </a>
                        <a href="{{ route('pagina.show', 'estatuto') }}" class="btn btn-outline">
                            <i class="fas fa-balance-scale"></i> Leer Estatuto Completo (11 Artículos)
                        </a>
                    </div>
                </div>

            </div>
        </section>

    </div>

</div>

@push('styles')
<style>
    /* BLOQUEAR SCROLL INICIALMENTE DURANTE EL VIDEO */
    body.video-mode-active {
        overflow: hidden !important;
        height: 100vh !important;
    }

    /* 1. FULLSCREEN VIDEO THEATRE */
    .fullscreen-video-theatre {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        min-height: 100svh;
        overflow: hidden;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.8s ease;
    }

    .fullscreen-video-theatre.released {
        position: relative;
        height: 100vh;
    }

    .video-ambient-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: blur(50px) brightness(0.25);
        opacity: 0.7;
        pointer-events: none;
        z-index: 1;
        transform: scale(1.1);
    }

    .main-video-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        max-width: 56.25vh; /* Formato 9:16 vertical cinematográfico */
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        box-shadow: 0 0 100px rgba(0, 0, 0, 0.9);
        background: #000;
    }

    @media (min-aspect-ratio: 9/16) {
        .main-video-wrapper {
            width: auto;
            height: 100%;
            aspect-ratio: 9/16;
        }
    }

    @media (max-aspect-ratio: 9/16) {
        .main-video-wrapper {
            width: 100%;
            height: 100%;
            max-width: 100vw;
        }
    }

    .main-promotional-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        background: #000;
        cursor: pointer;
    }

    /* Top Floating Video Controls */
    .video-top-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(180deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 100%);
        z-index: 10;
        pointer-events: auto;
    }

    .video-brand-badge {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-shadow: 0 2px 8px rgba(0,0,0,0.8);
    }

    .video-badge-logo {
        height: 56px;
        width: 56px;
        object-fit: contain;
        filter: drop-shadow(0 0 12px rgba(229,169,60,0.7));
    }

    .video-badge-texts {
        display: flex;
        flex-direction: column;
    }

    .video-badge-title {
        font-family: 'Cinzel', serif;
        font-weight: 800;
        font-size: 1.15rem;
        letter-spacing: 0.08em;
        color: #fff;
    }

    .video-badge-sub {
        font-size: 0.68rem;
        color: var(--accent-gold);
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .video-top-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-sound-control {
        background: rgba(16, 20, 29, 0.85);
        border: 1px solid var(--accent-gold);
        color: var(--accent-gold);
        padding: 0.45rem 0.9rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }

    .btn-sound-control:hover {
        background: var(--accent-gold);
        color: #000;
        transform: scale(1.04);
    }

    .btn-skip-to-acta {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        padding: 0.45rem 0.85rem;
        border-radius: 20px;
        font-size: 0.78rem;
        cursor: pointer;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-skip-to-acta:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
    }

    /* Prompt Central para Entrada con Sonido */
    .audio-start-prompt {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(7, 9, 12, 0.75);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 15;
        padding: 1.5rem;
        animation: fadeIn 0.4s ease;
    }

    .prompt-content {
        text-align: center;
        max-width: 380px;
    }

    .prompt-logo-img {
        height: 120px;
        width: 120px;
        object-fit: contain;
        filter: drop-shadow(0 0 25px rgba(229,169,60,0.7));
        margin-bottom: 1.25rem;
    }

    .prompt-title {
        font-family: 'Cinzel', serif;
        font-size: 1.45rem;
        color: #fff;
        margin-bottom: 0.35rem;
    }

    .prompt-subtitle {
        font-size: 0.88rem;
        color: var(--text-secondary);
        margin-bottom: 1.75rem;
    }

    .btn-enter-sound-pulse {
        background: linear-gradient(135deg, #e5a93c 0%, #cf9128 100%);
        color: #07090c;
        font-family: 'Cinzel', serif;
        font-weight: 800;
        font-size: 1.05rem;
        letter-spacing: 0.05em;
        padding: 1rem 2rem;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 10px 30px rgba(229, 169, 60, 0.4);
        animation: pulseButton 2s infinite;
        transition: transform 0.2s;
    }

    .btn-enter-sound-pulse:hover {
        transform: scale(1.05);
        background: #f39c12;
    }

    .prompt-hint {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 1rem;
    }

    /* Video Timeline Bar */
    .video-progress-bar-wrap {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: rgba(255, 255, 255, 0.2);
        z-index: 10;
    }

    .video-progress-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #e5a93c, #f39c12);
        transition: width 0.1s linear;
    }

    /* 2. OVERLAY REVEAL AL TÉRMINO DEL VIDEO */
    .video-end-curtain {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, rgba(16, 20, 29, 0.95) 0%, rgba(7, 9, 12, 0.98) 100%);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        z-index: 20;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .video-end-curtain.active {
        opacity: 1;
        pointer-events: auto;
    }

    .end-curtain-card {
        max-width: 440px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        animation: cardFadeUp 0.8s ease forwards;
    }

    @keyframes cardFadeUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .end-curtain-logo {
        height: 140px;
        width: 140px;
        object-fit: contain;
        filter: drop-shadow(0 0 30px rgba(229,169,60,0.7));
        margin-bottom: 1.5rem;
    }

    .end-badge {
        font-size: 0.72rem;
        color: var(--accent-gold);
        background: rgba(229, 169, 60, 0.12);
        border: 1px solid rgba(229, 169, 60, 0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        letter-spacing: 0.15em;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .end-title {
        font-family: 'Cinzel', serif;
        font-size: 1.5rem;
        color: #ffffff;
        letter-spacing: 0.06em;
        line-height: 1.3;
        margin-bottom: 0.85rem;
    }

    .end-description {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .btn-cta-monumental {
        background: linear-gradient(135deg, #e5a93c 0%, #cf9128 100%);
        color: #07090c;
        font-family: 'Cinzel', serif;
        font-weight: 800;
        font-size: 1.05rem;
        letter-spacing: 0.05em;
        padding: 1.1rem 2rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        box-shadow: 0 10px 30px rgba(229, 169, 60, 0.4);
        transition: all 0.25s ease;
        animation: pulseButton 2s infinite;
        width: 100%;
    }

    .btn-cta-monumental:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 40px rgba(229, 169, 60, 0.6);
        background: #f39c12;
        color: #000;
    }

    @keyframes pulseButton {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.025); }
    }

    .end-cta-hint {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.75rem;
    }

    .end-replay-wrap {
        margin-top: 1.75rem;
    }

    .btn-replay-link {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 0.82rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s;
    }

    .btn-replay-link:hover {
        color: var(--accent-gold);
    }

    /* 3. SECCIÓN DEL ACTA PAPER CARD */
    .acta-section {
        padding: 5rem 1.5rem 6rem;
        background: radial-gradient(ellipse at center top, rgba(229,169,60,0.06), transparent 70%), var(--bg-dark);
        border-top: 1px solid var(--border-color);
    }

    .container-medium {
        max-width: 860px;
        margin: 0 auto;
    }

    .acta-paper-card {
        background: var(--bg-surface);
        border: 1.5px solid rgba(229, 169, 60, 0.35);
        border-radius: 14px;
        padding: 3.5rem 3rem;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(229, 169, 60, 0.1);
        position: relative;
        overflow: hidden;
        transition: border-color 0.4s ease, box-shadow 0.4s ease;
    }

    .acta-paper-card.highlight-focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 50px rgba(229, 169, 60, 0.35);
    }

    .acta-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .acta-crest-wrap {
        margin-bottom: 1.25rem;
    }

    .acta-crest-img {
        height: 125px;
        width: 125px;
        object-fit: contain;
        filter: drop-shadow(0 0 20px rgba(229,169,60,0.6));
    }

    .acta-pre-title {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        color: var(--accent-gold);
        margin-bottom: 0.5rem;
    }

    .acta-title {
        font-family: 'Cinzel', serif;
        font-size: 2rem;
        letter-spacing: 0.08em;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }

    .acta-sub {
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .acta-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
        margin: 1.25rem auto;
        max-width: 320px;
    }

    .acta-compromiso {
        font-size: 0.92rem;
        color: #cbd5e1;
        line-height: 1.8;
        background: rgba(229, 169, 60, 0.04);
        border-left: 3px solid var(--accent-gold);
        border-right: 3px solid var(--accent-gold);
        padding: 1.25rem 1.75rem;
        border-radius: 6px;
        margin: 1.5rem 0;
        text-align: justify;
    }

    /* Form Fields */
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1.2fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 768px) {
        .form-grid-3 {
            grid-template-columns: 1fr;
            gap: 0;
        }
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #e2e8f0;
    }

    .form-control {
        width: 100%;
        background: #090c12;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        color: #ffffff;
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px var(--accent-gold-glow);
    }

    /* MÓDULO DE FIRMA TÁCTIL (CANVAS) */
    .firma-touch-module {
        background: #090c12;
        border: 1px solid rgba(229, 169, 60, 0.4);
        border-radius: 10px;
        padding: 1.5rem;
        margin: 2rem 0;
    }

    .firma-touch-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .firma-touch-instruction {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin: 0;
    }

    .btn-clear-canvas {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        font-size: 0.78rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-clear-canvas:hover {
        background: rgba(231, 76, 60, 0.15);
        color: #f87171;
        border-color: rgba(231, 76, 60, 0.3);
    }

    .canvas-wrapper {
        position: relative;
        background: #040507;
        border: 1.5px dashed rgba(229, 169, 60, 0.35);
        border-radius: 8px;
        height: 180px;
        width: 100%;
        overflow: hidden;
        cursor: crosshair;
        touch-action: none;
    }

    .signature-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        touch-action: none;
    }

    .canvas-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: rgba(229, 169, 60, 0.35);
        font-size: 0.88rem;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        text-align: center;
    }

    .canvas-placeholder i {
        font-size: 1.5rem;
    }

    .canvas-line {
        position: absolute;
        bottom: 30px;
        left: 10%;
        right: 10%;
        height: 1px;
        background: rgba(229, 169, 60, 0.2);
        pointer-events: none;
    }

    .firma-legal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
        font-size: 0.72rem;
        color: var(--text-muted);
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .secure-tag {
        color: #34d399;
    }

    .form-submit-wrap {
        text-align: center;
        margin-top: 2rem;
    }

    .btn-submit-acta {
        background: linear-gradient(135deg, #e5a93c 0%, #cf9128 100%);
        color: #07090c;
        font-family: 'Cinzel', serif;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: 0.05em;
        padding: 1.1rem 2.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        width: 100%;
        max-width: 500px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        box-shadow: 0 10px 25px rgba(229, 169, 60, 0.35);
        transition: all 0.25s ease;
    }

    .btn-submit-acta:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(229, 169, 60, 0.5);
        background: #f39c12;
    }

    .submit-note {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.75rem;
    }

    /* 4. PILARES */
    .pilares-section {
        padding: 5rem 1.5rem;
        background: #090c12;
        border-top: 1px solid var(--border-color);
        text-align: center;
    }

    .section-badge {
        font-size: 0.75rem;
        color: var(--accent-gold);
        letter-spacing: 0.15em;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .section-title {
        font-size: 1.85rem;
        color: #fff;
        margin-bottom: 3rem;
    }

    .pilares-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.75rem;
        max-width: 1100px;
        margin: 0 auto;
    }

    .pilar-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 2rem 1.75rem;
        text-align: left;
        position: relative;
    }

    .pilar-card.highlight {
        border-color: var(--accent-gold);
        box-shadow: 0 0 25px rgba(229,169,60,0.15);
    }

    .pilar-num {
        font-family: 'Cinzel', serif;
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--accent-gold);
        opacity: 0.4;
        margin-bottom: 0.75rem;
    }

    .pilar-title {
        font-size: 1.15rem;
        color: #fff;
        margin-bottom: 0.6rem;
    }

    .pilar-desc {
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .acta-paper-card {
            padding: 2rem 1.25rem;
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
        .acta-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Activar modo de pantalla completa solo-video al inicio
    document.body.classList.add('video-mode-active');

    const video = document.getElementById('promoVideo');
    const ambientVideo = document.getElementById('ambientVideo');
    const btnToggleAudio = document.getElementById('btnToggleAudio');
    const soundIcon = document.getElementById('soundIcon');
    const soundLabel = document.getElementById('soundLabel');
    const videoProgressFill = document.getElementById('videoProgressFill');
    const videoEndOverlay = document.getElementById('videoEndOverlay');
    const btnReplayVideo = document.getElementById('btnReplayVideo');
    const btnFirmarActaMonumental = document.getElementById('btnFirmarActaMonumental');
    const btnSkipDirectToActa = document.getElementById('btnSkipDirectToActa');
    const audioStartPrompt = document.getElementById('audioStartPrompt');
    const btnEnterWithSound = document.getElementById('btnEnterWithSound');
    const siteContentAfterVideo = document.getElementById('siteContentAfterVideo');
    const mainSiteNavbar = document.getElementById('mainSiteNavbar');
    const mainSiteFooter = document.getElementById('mainSiteFooter');
    const videoTheatre = document.getElementById('videoTheatre');

    let siteRevealed = false;

    // FUNCIÓN DE DESBLOQUEO Y REVELACIÓN DEL RESTO DEL SITIO
    function revealEntireSiteAndScrollToActa() {
        if (siteRevealed) {
            const actaSection = document.getElementById('acta');
            if (actaSection) actaSection.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        siteRevealed = true;

        // 1. Quitar bloqueo de scroll en el body
        document.body.classList.remove('video-mode-active');

        // 2. Liberar el teatro de video de position fixed a relative
        if (videoTheatre) {
            videoTheatre.classList.add('released');
        }

        // 3. Mostrar la barra de navegación con animación
        if (mainSiteNavbar) {
            mainSiteNavbar.style.display = 'block';
            mainSiteNavbar.style.animation = 'fadeInDown 0.6s ease';
        }

        // 4. Mostrar el contenido del acta, pilares y pie de página
        if (siteContentAfterVideo) {
            siteContentAfterVideo.style.display = 'block';
            siteContentAfterVideo.style.animation = 'fadeInUp 0.8s ease';
        }

        if (mainSiteFooter) {
            mainSiteFooter.style.display = 'block';
        }

        // 5. Inicializar / redibujar el canvas de firma con el tamaño real ahora que es visible
        setTimeout(function() {
            if (typeof resizeCanvas === 'function') {
                resizeCanvas();
            }
        }, 100);

        // 6. Desplazar la pantalla suavemente directo al Acta Fundacional
        setTimeout(function() {
            const actaSection = document.getElementById('acta');
            const actaCard = document.getElementById('actaPaperCard');
            const nombreInput = document.getElementById('nombre');

            if (actaSection) {
                actaSection.scrollIntoView({ behavior: 'smooth' });
            }
            if (actaCard) {
                actaCard.classList.add('highlight-focus');
            }
            if (nombreInput) {
                setTimeout(() => nombreInput.focus(), 600);
            }
        }, 300);
    }

    // MANEJO DE SONIDO & AUTOPLAY (RESPUESTA A LA POLÍTICA DEL NAVEGADOR)
    if (video) {
        // Intentar reproducción con audio al entrar
        video.muted = false;
        video.volume = 1.0;

        const playPromise = video.play();
        if (playPromise !== undefined) {
            playPromise.then(() => {
                // El navegador permitió reproducción con sonido directamente!
                soundIcon.textContent = "🔇";
                soundLabel.textContent = "Silenciar";
                if (audioStartPrompt) audioStartPrompt.style.display = 'none';
            }).catch(() => {
                // El navegador aplicó su política de silencio predeterminada.
                // Ponemos en mute temporal para que corra el video y mostramos botón elegante de ingreso con sonido
                video.muted = true;
                video.play().catch(() => {});
                soundIcon.textContent = "🔊";
                soundLabel.textContent = "Activar Sonido";
                if (audioStartPrompt) audioStartPrompt.style.display = 'flex';
            });
        }

        // Botón central "INGRESAR CON SONIDO"
        if (btnEnterWithSound) {
            btnEnterWithSound.addEventListener('click', function(e) {
                e.stopPropagation();
                video.muted = false;
                video.volume = 1.0;
                video.currentTime = 0;
                video.play();
                soundIcon.textContent = "🔇";
                soundLabel.textContent = "Silenciar";
                if (audioStartPrompt) audioStartPrompt.style.display = 'none';
            });
        }

        // Control manual de sonido en la barra superior
        function toggleSound() {
            if (video.muted) {
                video.muted = false;
                video.volume = 1.0;
                soundIcon.textContent = "🔇";
                soundLabel.textContent = "Silenciar";
                if (audioStartPrompt) audioStartPrompt.style.display = 'none';
            } else {
                video.muted = true;
                soundIcon.textContent = "🔊";
                soundLabel.textContent = "Activar Sonido";
            }
        }

        if (btnToggleAudio) {
            btnToggleAudio.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSound();
            });
        }

        // Tocar el video para activar sonido si está silenciado
        video.addEventListener('click', function() {
            if (video.muted) {
                toggleSound();
            }
        });

        // Sincronizar video ambiental
        if (ambientVideo) {
            video.addEventListener('play', () => ambientVideo.play().catch(() => {}));
            video.addEventListener('pause', () => ambientVideo.pause());
        }

        // Barra de progreso
        video.addEventListener('timeupdate', function() {
            if (video.duration) {
                const pct = (video.currentTime / video.duration) * 100;
                videoProgressFill.style.width = pct + '%';
            }
        });

        // REQUERIMIENTO PRINCIPAL: AL TÉRMINO DEL VIDEO, APARECE EL BOTÓN Y SE REVELA EL ACTA
        video.addEventListener('ended', function() {
            // Mostrar la cortina final con el botón monumental
            if (videoEndOverlay) {
                videoEndOverlay.classList.add('active');
            }
            // Habilitar la visibilidad de las secciones inferiores
            revealEntireSiteAndScrollToActa();
        });

        // Click en el botón monumental de firma
        if (btnFirmarActaMonumental) {
            btnFirmarActaMonumental.addEventListener('click', function(e) {
                e.preventDefault();
                revealEntireSiteAndScrollToActa();
            });
        }

        // Botón "Saltar al Acta" directo
        if (btnSkipDirectToActa) {
            btnSkipDirectToActa.addEventListener('click', function(e) {
                e.preventDefault();
                video.pause();
                revealEntireSiteAndScrollToActa();
            });
        }

        // Volver a reproducir video
        if (btnReplayVideo) {
            btnReplayVideo.addEventListener('click', function() {
                if (videoEndOverlay) videoEndOverlay.classList.remove('active');
                video.currentTime = 0;
                video.play();
                const videoTheat = document.getElementById('videoTheatre');
                if (videoTheat) videoTheat.scrollIntoView({ behavior: 'smooth' });
            });
        }
    }

    // 2. LIENZO DE FIRMA TÁCTIL (TOUCH SIGNATURE CANVAS)
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;
    const placeholder = document.getElementById('canvasPlaceholder');
    const btnClear = document.getElementById('btnClearCanvas');
    const firmaInput = document.getElementById('firmaDigitalInput');
    const form = document.getElementById('firmaForm');

    let isDrawing = false;
    let hasSigned = false;

    window.resizeCanvas = function() {
        if (!canvas || !ctx) return;
        const rect = canvas.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return;

        let tempCanvas = null;
        if (hasSigned) {
            tempCanvas = document.createElement('canvas');
            tempCanvas.width = canvas.width;
            tempCanvas.height = canvas.height;
            tempCanvas.getContext('2d').drawImage(canvas, 0, 0);
        }

        const dpr = window.devicePixelRatio || 1;
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        ctx.scale(dpr, dpr);

        ctx.strokeStyle = "#e5a93c"; // Oro Curupayty
        ctx.lineWidth = 2.5;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";

        if (tempCanvas && hasSigned) {
            ctx.drawImage(tempCanvas, 0, 0, rect.width, rect.height);
        }
    };

    window.addEventListener('resize', window.resizeCanvas);

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        if (e.touches && e.touches.length > 0) {
            return {
                x: e.touches[0].clientX - rect.left,
                y: e.touches[0].clientY - rect.top
            };
        }
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        hasSigned = true;
        if (placeholder) placeholder.style.display = 'none';
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        if (e.type.startsWith('touch')) {
            e.preventDefault();
        }
    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        if (e.type.startsWith('touch')) {
            e.preventDefault();
        }
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            firmaInput.value = canvas.toDataURL('image/png');
        }
    }

    if (canvas) {
        // Touch Events (Móviles)
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing, { passive: false });
        canvas.addEventListener('touchcancel', stopDrawing, { passive: false });

        // Mouse Events (PC)
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);
    }

    // Botón Limpiar
    if (btnClear) {
        btnClear.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            firmaInput.value = "";
            hasSigned = false;
            if (placeholder) placeholder.style.display = 'flex';
        });
    }

    // Validación antes de Enviar
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!hasSigned || !firmaInput.value) {
                e.preventDefault();
                alert("⚠️ Por favor, dibujá tu firma táctil en el recuadro dorado antes de enviar.");
                const canvasWrap = document.getElementById('canvasWrapper');
                if (canvasWrap) {
                    canvasWrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    canvasWrap.style.borderColor = "var(--accent-red)";
                }
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
