<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta Fundacional — Ensamble Curupayty · Atypu</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #06080c; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #06080c; color: #cbd5e1;">

    <!-- Wrapper Principal -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #06080c; padding: 30px 10px;">
        <tr>
            <td align="center">
                
                <!-- Contenedor Central (Máximo 650px) -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 650px; background-color: #0d1118; border: 1px solid #e5a93c; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.8);">
                    
                    <!-- Header Dorado -->
                    <tr>
                        <td align="center" style="padding: 35px 30px 25px; background: radial-gradient(circle at 50% 0%, rgba(229,169,60,0.22), transparent 75%), #111722; border-bottom: 1px solid #e5a93c;">
                            <div style="font-size: 11px; letter-spacing: 0.2em; color: #e5a93c; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">
                                REPÚBLICA DEL PARAGUAY · ASAMBLEA CONSTITUYENTE
                            </div>
                            <h1 style="margin: 0; font-size: 24px; color: #ffffff; font-weight: 800; letter-spacing: 0.05em;">
                                ENSAMBLE CURUPAYTY · ATYPU
                            </h1>
                            <div style="margin-top: 6px; font-size: 13px; color: #f6d28b; letter-spacing: 0.12em; text-transform: uppercase;">
                                Comunidad Soberana de Creadores-Intérpretes
                            </div>
                        </td>
                    </tr>

                    <!-- Cuerpo del Correo -->
                    <tr>
                        <td style="padding: 35px 35px 25px;">
                            
                            <h2 style="margin: 0 0 15px; font-size: 20px; color: #ffffff; font-weight: 700;">
                                ¡Bienvenido/a, {{ $firmante->nombre }} {{ $firmante->apellido }}!
                            </h2>

                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.7; color: #cbd5e1;">
                                Tu adhesión solemne al <strong>Acta Fundacional del Ensamble Curupayty · Atypu</strong> ha sido asentada formalmente en nuestro padrón oficial bajo el código de verificación único:
                            </p>

                            <!-- Código Destacado -->
                            <div style="background-color: #090c12; border: 1px solid #e5a93c; border-radius: 8px; padding: 14px 20px; text-align: center; margin-bottom: 25px;">
                                <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 4px;">Código de Verificación Oficial:</span>
                                <span style="font-size: 20px; font-weight: 800; color: #f6d28b; font-family: monospace; letter-spacing: 0.15em;">{{ $firmante->codigo_verificacion }}</span>
                            </div>

                            <!-- Resumen del Registro -->
                            <table border="0" cellpadding="8" cellspacing="0" width="100%" style="margin-bottom: 25px; background-color: #090c12; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); font-size: 13px;">
                                <tr>
                                    <td width="40%" style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Cédula de Identidad:</strong></td>
                                    <td style="color: #ffffff; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->cedula }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Instrumento / Disciplina:</strong></td>
                                    <td style="color: #e5a93c; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->instrumento }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Ciudad de Residencia:</strong></td>
                                    <td style="color: #ffffff; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->ciudad }}, Paraguay</td>
                                </tr>
                                <tr>
                                    <td style="color: #94a3b8;"><strong>Fecha de Asentamiento:</strong></td>
                                    <td style="color: #ffffff;">{{ $firmante->created_at ? $firmante->created_at->format('d/m/Y H:i:s') : date('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>

                            <!-- Declaración y Manifiesto -->
                            <blockquote style="margin: 0 0 25px; padding: 15px 20px; background-color: rgba(229,169,60,0.07); border-left: 3px solid #e5a93c; color: #f1f5f9; font-style: italic; font-size: 14px; line-height: 1.6;">
                                "Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro. Acá no venimos a interpretar el pasado. Venimos a firmar el futuro."
                            </blockquote>

                            <!-- Diploma / Imagen del Acta Adjunta -->
                            <div style="text-align: center; margin: 30px 0 25px;">
                                <p style="font-size: 13px; color: #f6d28b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px;">
                                    📜 Certificado Oficial del Acta con tu Rúbrica Digital:
                                </p>
                                @if(isset($message) && file_exists($imagePath))
                                    <img src="{{ $message->embed($imagePath) }}" alt="Acta Fundacional Firmada" width="100%" style="max-width: 580px; border: 2px solid #e5a93c; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.9);">
                                @endif
                                <p style="font-size: 12px; color: #94a3b8; margin-top: 8px;">
                                    (También encontrarás el archivo de alta resolución adjunto a este mensaje para imprimirlo o enmarcarlo).
                                </p>
                            </div>

                            <!-- Botones de Acción -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('catalogo.index') }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #e5a93c, #cf9128); color: #07090c; font-weight: 800; font-size: 14px; text-decoration: none; padding: 14px 28px; border-radius: 6px; letter-spacing: 0.05em; margin: 5px;">
                                            Explorar Catálogo de Partituras
                                        </a>
                                        <a href="{{ route('pagina.show', 'estatuto') }}" target="_blank" style="display: inline-block; background: transparent; border: 1px solid #e5a93c; color: #f6d28b; font-weight: 700; font-size: 14px; text-decoration: none; padding: 13px 26px; border-radius: 6px; letter-spacing: 0.05em; margin: 5px;">
                                            Leer Estatuto (11 Artículos)
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer del Correo -->
                    <tr>
                        <td align="center" style="padding: 25px 30px; background-color: #07090c; border-top: 1px solid rgba(255,255,255,0.08); font-size: 12px; color: #64748b; line-height: 1.6;">
                            <strong style="color: #e5a93c;">ENSAMBLE CURUPAYTY · ATYPU</strong><br>
                            Registro de Propiedad Intelectual DINAPI · APA · AIE<br>
                            Asunción, República del Paraguay · www.curupayty.com<br>
                            <span style="font-size: 11px; color: #475569;">Este correo electrónico certifica legalmente tu adhesión constituyente ante la Comisión Fundacional.</span>
                        </td>
                    </tr>

                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>
