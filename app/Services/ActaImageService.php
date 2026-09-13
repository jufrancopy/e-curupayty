<?php

namespace App\Services;

use App\Models\FirmanteActa;
use Illuminate\Support\Facades\File;

class ActaImageService
{
    /**
     * Genera una imagen oficial de alta resolución del Acta Fundacional firmada.
     *
     * @param FirmanteActa $firmante
     * @return string Ruta absoluta del archivo PNG generado
     */
    public function generate(FirmanteActa $firmante): string
    {
        $width = 1200;
        $height = 1650;

        // 1. Crear lienzo
        $im = imagecreatetruecolor($width, $height);
        imagealphablending($im, true);
        imagesavealpha($im, true);

        // Paleta de colores
        $cBg = imagecolorallocate($im, 9, 12, 18); // #090c12
        $cCardBg = imagecolorallocate($im, 16, 22, 33); // #101621
        $cGold = imagecolorallocate($im, 229, 169, 60); // #e5a93c
        $cGoldLight = imagecolorallocate($im, 246, 210, 139); // #f6d28b
        $cGoldDark = imagecolorallocate($im, 150, 105, 30);
        $cWhite = imagecolorallocate($im, 255, 255, 255);
        $cSilver = imagecolorallocate($im, 203, 213, 225); // #cbd5e1
        $cMuted = imagecolorallocate($im, 148, 163, 184); // #94a3b8

        // Fondo
        imagefilledrectangle($im, 0, 0, $width, $height, $cBg);

        // 2. Marcos ornamentales dorados
        imagesetthickness($im, 3);
        imagerectangle($im, 30, 30, $width - 30, $height - 30, $cGold);
        imagesetthickness($im, 1);
        imagerectangle($im, 38, 38, $width - 38, $height - 38, $cGoldDark);
        imagerectangle($im, 44, 44, $width - 44, $height - 44, $cGold);

        // Esquinas ornamentales
        $corners = [
            [30, 30, 70, 70],
            [$width - 30, 30, $width - 70, 70],
            [30, $height - 30, 70, $height - 70],
            [$width - 30, $height - 30, $width - 70, $height - 70],
        ];
        imagesetthickness($im, 2);
        foreach ($corners as [$x1, $y1, $x2, $y2]) {
            imageline($im, $x1, $y1, $x2, $y1, $cGoldLight);
            imageline($im, $x1, $y1, $x1, $y2, $cGoldLight);
        }

        // Fuentes
        $fontTitle = '/System/Library/Fonts/Supplemental/Georgia Bold.ttf';
        $fontSerif = '/System/Library/Fonts/Supplemental/Georgia.ttf';
        $fontSansBold = '/System/Library/Fonts/Supplemental/Arial Bold.ttf';
        $fontSans = '/System/Library/Fonts/Supplemental/Arial.ttf';

        // Fallbacks si no existen
        if (!file_exists($fontTitle)) $fontTitle = $fontSansBold;
        if (!file_exists($fontSerif)) $fontSerif = $fontSans;

        // 3. Encabezado Oficial Soberano
        $txt1 = "REPÚBLICA DEL PARAGUAY";
        $this->drawCenteredText($im, 14, 0, 95, $cGold, $fontSansBold, $txt1);

        $txt2 = "ASAMBLEA CONSTITUYENTE FUNDACIONAL · PROTOCOLO EXTRAORDINARIO";
        $this->drawCenteredText($im, 11, 0, 125, $cMuted, $fontSansBold, $txt2);

        // 4. Logo Oficial Curupayty
        $logoPath = public_path('images/logo_curupayty.png');
        $logoY = 155;
        $logoSize = 160;
        if (file_exists($logoPath)) {
            $logo = @imagecreatefrompng($logoPath);
            if ($logo) {
                $srcW = imagesx($logo);
                $srcH = imagesy($logo);
                $logoX = (int)(($width - $logoSize) / 2);
                imagecopyresampled($im, $logo, $logoX, $logoY, 0, 0, $logoSize, $logoSize, $srcW, $srcH);
                imagedestroy($logo);
            }
        }

        // 5. Títulos Principales
        $y = $logoY + $logoSize + 45;
        $this->drawCenteredText($im, 26, 0, $y, $cGoldLight, $fontTitle, "ACTA FUNDACIONAL DE ADHESIÓN");

        $y += 40;
        $this->drawCenteredText($im, 22, 0, $y, $cWhite, $fontTitle, "ENSAMBLE CURUPAYTY · ATYPU");

        $y += 30;
        $this->drawCenteredText($im, 12, 0, $y, $cGold, $fontSansBold, "COMUNIDAD DE CREADORES-INTÉRPRETES DE OBRAS INÉDITAS");

        // Línea divisoria decorativa
        $y += 25;
        imagesetthickness($im, 1);
        imageline($im, 200, $y, $width - 200, $y, $cGoldDark);
        imagefilledellipse($im, (int)($width / 2), $y, 10, 10, $cGold);

        // 6. Tarjeta de Datos del Firmante
        $y += 35;
        $cardX1 = 80;
        $cardX2 = $width - 80;
        $cardY1 = $y;
        $cardH = 340;
        $cardY2 = $cardY1 + $cardH;

        imagefilledrectangle($im, $cardX1, $cardY1, $cardX2, $cardY2, $cCardBg);
        imagesetthickness($im, 1);
        imagerectangle($im, $cardX1, $cardY1, $cardX2, $cardY2, $cGoldDark);

        // Etiqueta superior de la tarjeta
        $this->drawCenteredText($im, 11, 0, $cardY1 + 35, $cGold, $fontSansBold, "CONSTANCIA OFICIAL DE SOCIO FUNDADOR REGISTRADO");

        // Nombre del firmante
        $nombreCompleto = mb_strtoupper("{$firmante->nombre} {$firmante->apellido}", 'UTF-8');
        $this->drawCenteredText($im, 24, 0, $cardY1 + 80, $cGoldLight, $fontTitle, $nombreCompleto);

        // Detalles en dos columnas
        $col1X = $cardX1 + 60;
        $col2X = $cardX1 + 560;
        $detY = $cardY1 + 140;
        $lineGap = 45;

        // Fila 1
        imagettftext($im, 11, 0, $col1X, $detY, $cMuted, $fontSansBold, "CÉDULA DE IDENTIDAD:");
        imagettftext($im, 13, 0, $col1X + 190, $detY, $cWhite, $fontSansBold, $firmante->cedula);

        imagettftext($im, 11, 0, $col2X, $detY, $cMuted, $fontSansBold, "CIUDAD / PAÍS:");
        imagettftext($im, 13, 0, $col2X + 135, $detY, $cWhite, $fontSansBold, "{$firmante->ciudad}, PY");

        // Fila 2
        $detY += $lineGap;
        imagettftext($im, 11, 0, $col1X, $detY, $cMuted, $fontSansBold, "INSTRUMENTO / ROL:");
        imagettftext($im, 13, 0, $col1X + 190, $detY, $cGoldLight, $fontSansBold, $firmante->instrumento);

        imagettftext($im, 11, 0, $col2X, $detY, $cMuted, $fontSansBold, "FECHA DE RÚBRICA:");
        $fechaTxt = $firmante->created_at ? $firmante->created_at->format('d/m/Y H:i') : date('d/m/Y H:i');
        imagettftext($im, 13, 0, $col2X + 175, $detY, $cWhite, $fontSansBold, $fechaTxt);

        // Fila 3
        $detY += $lineGap;
        imagettftext($im, 11, 0, $col1X, $detY, $cMuted, $fontSansBold, "CÓDIGO ÚNICO:");
        imagettftext($im, 13, 0, $col1X + 140, $detY, $cGold, $fontSansBold, $firmante->codigo_verificacion);

        imagettftext($im, 11, 0, $col2X, $detY, $cMuted, $fontSansBold, "ESTADO LEGAL:");
        imagettftext($im, 12, 0, $col2X + 140, $detY, $cGoldLight, $fontSansBold, "PADRÓN VINCULANTE");

        // Fila 4: Declaración de sueño musical
        $detY += $lineGap;
        $sueno = '"' . mb_substr($firmante->sueno_musical, 0, 95) . (mb_strlen($firmante->sueno_musical) > 95 ? '...' : '') . '"';
        imagettftext($im, 10, 0, $col1X, $detY, $cGold, $fontSansBold, "SUEÑO MUSICAL:");
        imagettftext($im, 11, 0, $col1X + 140, $detY, $cSilver, $fontSerif, $sueno);

        // 7. Texto del Compromiso y Principios
        $y = $cardY2 + 45;
        $p1 = "Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro.";
        $this->drawCenteredText($im, 12, 0, $y, $cGoldLight, $fontTitle, $p1);

        $y += 28;
        $p2 = "Por medio de la presente firma manuscrita digital, el socio declara su adhesión solemne a la";
        $this->drawCenteredText($im, 11, 0, $y, $cSilver, $fontSans, $p2);

        $y += 22;
        $p3 = "sociedad económica de creadores-intérpretes bajo el régimen innegociable de reparto 40 / 25 / 10 / 25.";
        $this->drawCenteredText($im, 11, 0, $y, $cSilver, $fontSans, $p3);

        $y += 26;
        $p4 = "Acá no venimos a interpretar el pasado. Venimos a firmar el futuro.";
        $this->drawCenteredText($im, 12, 0, $y, $cGold, $fontSansBold, $p4);

        // 8. Cuadro de Firma Manuscrita
        $y += 35;
        $sigW = 460;
        $sigH = 150;
        $sigX = (int)(($width - $sigW) / 2);
        $sigY = $y;

        // Fondo del cuadro de firma
        imagefilledrectangle($im, $sigX, $sigY, $sigX + $sigW, $sigY + $sigH, $cCardBg);
        imagesetthickness($im, 1);
        imagerectangle($im, $sigX, $sigY, $sigX + $sigW, $sigY + $sigH, $cGold);

        // Incrustar firma digital
        $this->embedSignature($im, $firmante->firma_digital, $sigX + 20, $sigY + 15, $sigW - 40, $sigH - 45);

        // Texto bajo la firma
        $sigLabel = "RÚBRICA DIGITAL VERIFICADA · " . $firmante->codigo_verificacion;
        $this->drawCenteredText($im, 9, 0, $sigY + $sigH - 12, $cGold, $fontSansBold, $sigLabel);

        // 9. Sellos y Pie de Página de Seguridad
        $y = $sigY + $sigH + 50;
        $footer1 = "REGISTRO DE PROPIEDAD INTELECTUAL · DINAPI · APA · AIE";
        $this->drawCenteredText($im, 10, 0, $y, $cGoldLight, $fontSansBold, $footer1);

        $y += 24;
        $footer2 = "Documento oficial generado y validado electrónicamente por el Sistema de Gestión Ensamble Curupayty.";
        $this->drawCenteredText($im, 9, 0, $y, $cMuted, $fontSans, $footer2);

        $y += 20;
        $footer3 = "Asunción, Paraguay · Todos los derechos reservados · www.curupayty.com";
        $this->drawCenteredText($im, 9, 0, $y, $cMuted, $fontSans, $footer3);

        // 10. Guardar archivo en disco
        $outputDir = storage_path('app/public/actas');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $filename = "acta_firmada_{$firmante->codigo_verificacion}.png";
        $fullPath = "{$outputDir}/{$filename}";

        imagepng($im, $fullPath, 8);
        imagedestroy($im);

        return $fullPath;
    }

    /**
     * Dibuja texto centrado horizontalmente
     */
    private function drawCenteredText($im, $size, $angle, $y, $color, $font, $text)
    {
        $bbox = imagettfbbox($size, $angle, $font, $text);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $x = (int)((imagesx($im) - $textWidth) / 2);
        imagettftext($im, $size, $angle, $x, $y, $color, $font, $text);
    }

    /**
     * Procesa la firma base64 y la estampa en el lienzo
     */
    private function embedSignature($im, $signatureData, $dstX, $dstY, $dstW, $dstH)
    {
        if (empty($signatureData)) return;

        // Quitar encabezado data:image/png;base64, si existe
        if (str_contains($signatureData, 'base64,')) {
            $parts = explode('base64,', $signatureData);
            $raw = base64_decode(end($parts));
        } else {
            $raw = base64_decode($signatureData);
        }

        if (!$raw) return;

        $sigImg = @imagecreatefromstring($raw);
        if (!$sigImg) return;

        $srcW = imagesx($sigImg);
        $srcH = imagesy($sigImg);

        // Copiar manteniendo canal alfa
        imagecopyresampled($im, $sigImg, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
        imagedestroy($sigImg);
    }
}
