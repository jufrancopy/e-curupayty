<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta Fundacional Oficial — Ensamble Curupayty</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=EB+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #2b2b2b;
            font-family: 'EB Garamond', serif;
            color: #1a1a1a;
            padding: 2rem 1rem;
        }
        .screen-toolbar {
            max-width: 900px;
            margin: 0 auto 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e1e1e;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
        }
        .btn {
            background: #e5a93c;
            color: #111;
            border: none;
            padding: 0.6rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .acta-sheet {
            background: #fff;
            max-width: 900px;
            margin: 0 auto;
            padding: 4rem 3.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border: 1px solid #ddd;
            position: relative;
            line-height: 1.65;
            font-size: 1.05rem;
        }
        .acta-header {
            text-align: center;
            border-bottom: 2px double #8a6a27;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }
        .national-emblem {
            font-size: 2.2rem;
            color: #8a6a27;
            margin-bottom: 0.5rem;
        }
        .acta-header h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            letter-spacing: 0.08em;
            color: #111;
            margin-bottom: 0.3rem;
        }
        .acta-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.15rem;
            color: #8a6a27;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .acta-meta-info {
            font-size: 0.85rem;
            color: #666;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .acta-body p {
            margin-bottom: 1.2rem;
            text-align: justify;
            text-indent: 1.5rem;
        }
        .acta-article {
            margin: 1.5rem 0;
        }
        .article-title {
            font-weight: 700;
            color: #8a6a27;
            font-family: 'Cinzel', serif;
            display: block;
            margin-bottom: 0.3rem;
            text-indent: 0;
        }
        .model-summary-box {
            background: #faf8f3;
            border: 1px solid #dfd7ca;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .model-summary-box ul {
            list-style: square;
            padding-left: 1.5rem;
            margin-top: 0.5rem;
        }
        .acta-signatures-section {
            margin-top: 3rem;
            border-top: 2px double #8a6a27;
            padding-top: 2rem;
            page-break-before: auto;
        }
        .section-title {
            font-family: 'Cinzel', serif;
            text-align: center;
            font-size: 1.25rem;
            color: #8a6a27;
            margin-bottom: 2rem;
        }
        .signers-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        .signer-box {
            border-top: 1px solid #333;
            padding-top: 0.5rem;
            text-align: center;
            font-size: 0.88rem;
            position: relative;
        }
        .signer-canvas-img {
            height: 48px;
            max-width: 160px;
            object-fit: contain;
            display: block;
            margin: 0 auto 0.25rem;
        }
        .signer-name {
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
        }
        .signer-details {
            font-size: 0.8rem;
            color: #555;
            font-family: 'Outfit', sans-serif;
        }
        .acta-footer {
            margin-top: 3rem;
            border-top: 1px solid #ccc;
            padding-top: 1rem;
            display: flex;
            justify-content: space-between;
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            color: #777;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .screen-toolbar {
                display: none;
            }
            .acta-sheet {
                box-shadow: none;
                border: none;
                padding: 2cm 1.5cm;
                max-width: 100%;
            }
            .signers-grid {
                page-break-inside: auto;
            }
            .signer-box {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="screen-toolbar">
        <div>
            <strong>Acta Fundacional Ensamble Curupayty · Atypu</strong>
            <span style="opacity:0.6; margin-left:0.5rem;">({{ $firmantes->count() }} Firmas Certificadas)</span>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <button onclick="window.print()" class="btn">
                <i class="fas fa-print"></i> Imprimir Documento Oficial (PDF)
            </button>
            <a href="{{ route('admin.firmantes.index') }}" class="btn btn-outline">
                Volver al Panel
            </a>
        </div>
    </div>

    <div class="acta-sheet">
        <div class="acta-header">
            <div class="national-emblem" style="margin-bottom: 1rem;">
                <img src="{{ asset('images/logo_curupayty.png') }}" alt="Ensamble Curupayty" style="height: 120px; width: 120px; object-fit: contain;">
            </div>
            <h1>Acta Fundacional y de Constitución</h1>
            <h2>ENSAMBLE CURUPAYTY · ATYPU</h2>
            <div class="acta-meta-info">
                Asunción, República del Paraguay · Protocolo Extraordinario de Creación Musical Colectiva
            </div>
        </div>

        <div class="acta-body">
            <p>
                En la ciudad de Asunción, capital de la República del Paraguay, a los trece días del mes de septiembre del año dos mil veintiséis, se reúnen en asamblea constituyente los ciudadanos, músicas, músicos, compositores e intérpretes cuyos nombres y firmas manuscritas electrónicas se consignan al pie del presente instrumento, con el propósito expreso y soberano de fundar la institución musical de autogestión sinfónica denominada <strong>ENSAMBLE CURUPAYTY · ATYPU</strong>.
            </p>

            <div class="acta-article">
                <span class="article-title">CLÁUSULA PRIMERA: DE LA DENOMINACIÓN Y CONDICIÓN DE SOCIO-AUTOR</span>
                <p>
                    Queda formalmente constituido un colectivo orquestal autogestionado de creadores-intérpretes. No un elenco que ejecuta repertorio ajeno, sino una comunidad que produce, en exclusiva, <strong>obra inédita de sus propios miembros</strong>. La condición de socio es la condición de autor: no existe categoría de "músico raso", y todo integrante activo tiene derecho pleno a presentar obra propia y liderar su propio proyecto.
                </p>
            </div>

            <div class="acta-article">
                <span class="article-title">CLÁUSULA SEGUNDA: DEL PRINCIPIO ECONÓMICO Y RÉGIMEN 40/25/10/25</span>
                <p>
                    El talento no se regala. Ninguna partitura, ninguna grabación y ningún arreglo sale del colectivo sin contraprestación económica definida. Los ingresos por venta de partituras, descargas, streaming y licencias audiovisuales se distribuyen estatutariamente bajo el régimen innegociable:
                </p>
                <div class="model-summary-box">
                    <strong>Régimen de Distribución Vinculante:</strong>
                    <ul>
                        <li><strong>40% (Cuarenta por ciento):</strong> Autor de la obra vendida o licenciada.</li>
                        <li><strong>25% (Veinticinco por ciento):</strong> Fondo de intérpretes que grabaron la obra.</li>
                        <li><strong>10% (Diez por ciento):</strong> Fondo de estímulo a nuevas producciones.</li>
                        <li><strong>25% (Veinticinco por ciento):</strong> Mantenimiento operativo (servidores, seguridad, pasarelas de pago).</li>
                    </ul>
                </div>
            </div>

            <div class="acta-article">
                <span class="article-title">CLÁUSULA TERCERA: DEL DESTIERRO DEL TENDOTA Y EL MODELO BRAINTRUST</span>
                <p>
                    Se abole el modelo del <em>tendota</em> (mando dueño, permanente y sin rendición de cuentas). El colectivo organiza su vida creativa en <strong>Proyectos rotativos (modelo Pixar)</strong>: durante el Proyecto, quien lo presenta es líder artístico absoluto de esa obra, respaldado plenamente por toda la comunidad y asesorado por el Braintrust (la crítica es sobre la obra, nunca sobre la persona; el colectivo sugiere, el autor decide). Toda obra es formalizada legalmente ante <strong>DINAPI, APA y AIE</strong>.
                </p>
            </div>

            <p style="font-style: italic; color: #333; margin-top: 1.5rem;">
                "Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro. Acá no venimos a interpretar el pasado. Venimos a firmar el futuro."
            </p>

            <p>
                En prueba de conformidad y ratificación solemne de todos y cada uno de los principios aquí consignados, estampan sus firmas digitales manuscritas los fundadores y primeros adherentes:
            </p>
        </div>

        <div class="acta-signatures-section">
            <h3 class="section-title">Nómina de Fundadores y Firmantes Adherentes</h3>

            <div class="signers-grid">
                @foreach($firmantes as $f)
                    <div class="signer-box">
                        @if($f->firma_digital_path)
                            <img src="{{ $f->firma_digital_path }}" alt="Firma" class="signer-canvas-img">
                        @else
                            <div style="height:48px; display:flex; align-items:center; justify-content:center; color:#999; font-style:italic;">
                                (Firma Registrada Electrónicamente)
                            </div>
                        @endif
                        <div class="signer-name">{{ $f->nombre }} {{ $f->apellido }}</div>
                        <div class="signer-details">
                            C.I. Nº {{ $f->cedula }} · {{ $f->instrumento_principal }} · {{ $f->ciudad ?? 'Asunción' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="acta-footer">
            <span>Certificado Criptográfico Oficial — Ensamble Curupayty (curupayty.com)</span>
            <span>Documento Válido para Presentación ante Escribanía Mayor de Gobierno</span>
        </div>
    </div>
</body>
</html>
