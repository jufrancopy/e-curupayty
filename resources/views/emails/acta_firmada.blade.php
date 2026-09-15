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
                            
                            <h2 style="margin: 0 0 15px; font-size: 22px; color: #ffffff; font-weight: 800; letter-spacing: 0.02em;">
                                ¡Muchas gracias por firmar el Acta, {{ $firmante->nombre }}!
                            </h2>

                            <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.7; color: #cbd5e1;">
                                Agradecemos profundamente tu adhesión y compromiso histórico con la creación del <strong>Ensamble Curupayty · Atypu</strong>. Tu firma forma parte del grupo pionero de músicos y adherentes que dan vida a esta comunidad soberana.
                            </p>

                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.7; color: #cbd5e1;">
                                Tu registro formal ha sido asentado en el padrón oficial bajo el siguiente código de verificación:
                            </p>

                            <!-- Código Destacado -->
                            <div style="background-color: #090c12; border: 1px solid #e5a93c; border-radius: 8px; padding: 14px 20px; text-align: center; margin-bottom: 25px;">
                                <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 4px;">Código de Verificación Oficial:</span>
                                <span style="font-size: 22px; font-weight: 800; color: #f6d28b; font-family: monospace; letter-spacing: 0.15em;">{{ $firmante->codigo_verificacion }}</span>
                            </div>

                            <!-- Credenciales de Acceso al Sistema -->
                            <div style="background: linear-gradient(145deg, #141b27, #0c1017); border: 1px solid #e5a93c; border-radius: 10px; padding: 22px 24px; margin-bottom: 25px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                                <div style="font-size: 15px; font-weight: 800; color: #f6d28b; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px;">
                                    🔐 Credenciales de Acceso al Sistema
                                </div>
                                <p style="margin: 0 0 14px; font-size: 13px; color: #94a3b8; line-height: 1.6;">
                                    Hemos creado tu cuenta de usuario para que puedas ingresar al sistema, acceder a partituras exclusivas, actas y participar en la gestión de la comunidad:
                                </p>
                                <table border="0" cellpadding="8" cellspacing="0" width="100%" style="background-color: #080b10; border-radius: 6px; border: 1px dashed rgba(229,169,60,0.4); margin-bottom: 15px; font-size: 13px;">
                                    <tr>
                                        <td width="38%" style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Usuario / Correo:</strong></td>
                                        <td style="color: #ffffff; font-family: monospace; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->user->email ?? $firmante->email }}</td>
                                    </tr>
                                    @if(isset($temporaryPassword) && $temporaryPassword)
                                    <tr>
                                        <td style="color: #94a3b8;"><strong>Clave Única de Acceso:</strong></td>
                                        <td style="color: #f6d28b; font-family: monospace; font-size: 15px; font-weight: 800; letter-spacing: 0.1em;">
                                            <span style="background-color: rgba(229,169,60,0.15); padding: 4px 10px; border-radius: 4px; border: 1px solid rgba(229,169,60,0.3); display: inline-block;">
                                                {{ $temporaryPassword }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                <p style="margin: 0 0 16px; font-size: 12px; color: #cbd5e1; line-height: 1.5;">
                                    <strong style="color: #e5a93c;">* Importante:</strong> Esta clave única de acceso es provisional. Por tu seguridad, podrás cambiarla fácilmente al ingresar a tu perfil.
                                </p>
                                <div style="text-align: center;">
                                    <a href="{{ route('login') }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #e5a93c, #cf9128); color: #07090c; font-weight: 800; font-size: 13px; text-decoration: none; padding: 11px 26px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.06em;">
                                        Ingresar al Sistema →
                                    </a>
                                </div>
                            </div>

                            <!-- Aviso de WhatsApp Obligatorio -->
                            <div style="background-color: rgba(37, 211, 102, 0.08); border: 1px solid rgba(37, 211, 102, 0.35); border-radius: 8px; padding: 14px 18px; margin-bottom: 25px; font-size: 13px; color: #e2e8f0; line-height: 1.6;">
                                <strong style="color: #25d366; font-size: 14px;">💬 Contacto y Grupo Oficial de WhatsApp</strong><br>
                                Tu número telefónico <strong>{{ $firmante->telefono ?? 'Registrado' }}</strong> ha sido validado satisfactoriamente. Te incorporaremos al grupo oficial de WhatsApp para coordinar la primera asamblea y los ensayos del ensamble.
                            </div>

                            <!-- Resumen del Registro -->
                            <table border="0" cellpadding="8" cellspacing="0" width="100%" style="margin-bottom: 25px; background-color: #090c12; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); font-size: 13px;">
                                <tr>
                                    <td width="40%" style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Cédula de Identidad:</strong></td>
                                    <td style="color: #ffffff; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->cedula }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.05);"><strong>Celular / WhatsApp:</strong></td>
                                    <td style="color: #25d366; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.05);">{{ $firmante->telefono ?? 'No especificado' }}</td>
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
