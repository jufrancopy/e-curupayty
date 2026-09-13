"""
Curupayty Video Generator Engine
Genera videos de alta calidad basados en el Manifiesto y Estatuto de Curupayty.
Soporta formatos Vertical (9:16 para Reels/TikTok) y Horizontal (16:9).
"""

import os
import sys
import json
import math
import random
import asyncio
import subprocess
import numpy as np
from PIL import Image, ImageDraw, ImageFont

# Fuentes del sistema
FONT_CANDIDATES = {
    "title_bold": [
        "/System/Library/Fonts/Supplemental/Georgia Bold.ttf",
        "/System/Library/Fonts/Supplemental/Arial Black.ttf",
        "/System/Library/Fonts/Supplemental/Impact.ttf",
    ],
    "sans_bold": [
        "/System/Library/Fonts/Supplemental/Arial Bold.ttf",
        "/System/Library/Fonts/Supplemental/Arial Black.ttf",
    ],
    "sans_regular": [
        "/System/Library/Fonts/Supplemental/Arial.ttf",
        "/System/Library/Fonts/Supplemental/Georgia.ttf",
    ],
    "serif": [
        "/System/Library/Fonts/Supplemental/Georgia.ttf",
        "/System/Library/Fonts/Supplemental/Georgia Bold.ttf",
    ]
}

def resolve_font(category, size):
    for path in FONT_CANDIDATES.get(category, []):
        if os.path.exists(path):
            try:
                return ImageFont.truetype(path, size)
            except Exception:
                continue
    return ImageFont.load_default()

def hex_to_rgb(hex_str):
    hex_str = hex_str.lstrip('#')
    return tuple(int(hex_str[i:i+2], 16) for i in (0, 2, 4))

def draw_rounded_rectangle(draw, bounds, radius, fill=None, outline=None, width=1):
    draw.rounded_rectangle(bounds, radius=radius, fill=fill, outline=outline, width=width)

def wrap_text(text, font, max_width, draw):
    lines = []
    for paragraph in text.split('\n'):
        words = paragraph.split(' ')
        current_line = []
        for word in words:
            test_line = ' '.join(current_line + [word])
            bbox = draw.textbbox((0, 0), test_line, font=font)
            w = bbox[2] - bbox[0]
            if w <= max_width:
                current_line.append(word)
            else:
                if current_line:
                    lines.append(' '.join(current_line))
                    current_line = [word]
                else:
                    lines.append(word)
        if current_line:
            lines.append(' '.join(current_line))
    return lines

async def generate_voice_file(text, voice, output_path):
    import edge_tts
    communicate = edge_tts.Communicate(text, voice)
    await communicate.save(output_path)

def get_audio_duration(file_path):
    cmd = [
        "ffprobe", "-v", "error", "-show_entries", "format=duration",
        "-of", "default=noprint_wrappers=1:nokey=1", file_path
    ]
    result = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
    try:
        return float(result.stdout.strip())
    except Exception:
        return 5.0

def create_procedural_background_music(duration, output_path):
    """Genera un drone cinemático con pulso grave sutil usando filtros nativos de ffmpeg."""
    cmd = [
        "ffmpeg", "-y",
        "-f", "lavfi", "-i", f"sine=frequency=48:duration={duration},volume=0.20",
        "-f", "lavfi", "-i", f"sine=frequency=72:duration={duration},volume=0.12",
        "-f", "lavfi", "-i", f"anoisesrc=d={duration}:c=brown:r=44100:a=0.04,lowpass=f=180",
        "-filter_complex",
        f"[0:a][1:a][2:a]amix=inputs=3,afade=t=in:ss=0:d=2,afade=t=out:st={max(0, duration - 3)}:d=3[out]",
        "-map", "[out]",
        output_path
    ]
    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

class CurupaytyVideoGenerator:
    def __init__(self, scenes_file="scenes.json", format_type="vertical", fps=30):
        with open(scenes_file, "r", encoding="utf-8") as f:
            self.data = json.load(f)
        
        self.format_type = format_type
        self.fps = fps
        
        if format_type == "vertical":
            self.width = 1080
            self.height = 1920
        elif format_type == "horizontal":
            self.width = 1920
            self.height = 1080
        elif format_type == "square":
            self.width = 1080
            self.height = 1080
        else:
            self.width = 1080
            self.height = 1920
            
        self.theme = self.data.get("theme", {})
        self.c_bg = hex_to_rgb(self.theme.get("bg_dark", "#0a0c10"))
        self.c_card = hex_to_rgb(self.theme.get("bg_card", "#131722"))
        self.c_border = hex_to_rgb(self.theme.get("card_border", "#2c3444"))
        self.c_gold = hex_to_rgb(self.theme.get("accent_gold", "#e5a93c"))
        self.c_gold_glow = hex_to_rgb(self.theme.get("accent_gold_glow", "#f39c12"))
        self.c_red = hex_to_rgb(self.theme.get("accent_red", "#e74c3c"))
        self.c_white = hex_to_rgb(self.theme.get("text_primary", "#f8f9fa"))
        self.c_gray = hex_to_rgb(self.theme.get("text_secondary", "#95a5a6"))
        self.c_muted = hex_to_rgb(self.theme.get("text_muted", "#57606f"))

        # Precomputar fondo base degradado radial sutil
        self.base_bg = self._create_ambient_background()

    def _create_ambient_background(self):
        """Crea un fondo degradado cinematográfico oscuro."""
        w, h = self.width, self.height
        bg = Image.new("RGB", (w, h), self.c_bg)
        draw = ImageDraw.Draw(bg, "RGBA")
        
        # Viñeta oscura en bordes y resplandor sutil en el centro
        cx, cy = w // 2, h // 2
        max_radius = int(math.hypot(cx, cy))
        
        # Resplandor dorado/ámbar muy sutil en el cuadrante superior
        glow_color = (self.c_gold[0], self.c_gold[1], self.c_gold[2], 8)
        for r in range(max_radius, 50, -40):
            alpha = int(14 * (1 - r / max_radius))
            c = (self.c_gold[0], self.c_gold[1], self.c_gold[2], alpha)
            draw.ellipse([cx - r, int(cy * 0.7) - r, cx + r, int(cy * 0.7) + r], fill=c)
            
        return bg

    def render_frame(self, scene, progress, total_progress, frame_idx):
        """Renderiza un frame completo con efectos de animación, tipografía y visualizador."""
        w, h = self.width, self.height
        img = self.base_bg.copy()
        draw = ImageDraw.Draw(img, "RGBA")
        
        # Efecto de cámara sutil (Ken Burns: leve escalado visual)
        zoom = 1.0 + (0.03 * progress)
        
        # 1. Barra de progreso general arriba
        bar_h = 8
        bar_w = int(w * total_progress)
        draw.rectangle([0, 0, w, bar_h], fill=(30, 35, 45, 255))
        draw.rectangle([0, 0, bar_w, bar_h], fill=self.c_gold)
        
        # 2. Header / Marca superior
        font_brand = resolve_font("sans_bold", int(h * 0.016))
        brand_text = "ENSAMBLE CURUPAYTY  ·  ATYPU ARANDU"
        draw.text((w // 2, int(h * 0.05)), brand_text, font=font_brand, fill=self.c_muted, anchor="mm")
        
        # 3. Tag Pill animado (e.g. "[ HISTORIA & ACTITUD · 1866 ]")
        tag_text = scene.get("tag", "").upper()
        if tag_text:
            font_tag = resolve_font("sans_bold", int(h * 0.018))
            bbox = draw.textbbox((0, 0), tag_text, font=font_tag)
            tw = bbox[2] - bbox[0]
            th = bbox[3] - bbox[1]
            tag_y = int(h * 0.11)
            pill_pad_x = 28
            pill_pad_y = 10
            pill_rect = [
                (w - tw) // 2 - pill_pad_x,
                tag_y - th // 2 - pill_pad_y,
                (w + tw) // 2 + pill_pad_x,
                tag_y + th // 2 + pill_pad_y
            ]
            draw_rounded_rectangle(draw, pill_rect, radius=20, fill=(self.c_card[0], self.c_card[1], self.c_card[2], 220), outline=self.c_gold, width=2)
            draw.text((w // 2, tag_y), tag_text, font=font_tag, fill=self.c_gold, anchor="mm")

        # 4. Tarjeta central de contenido
        margin_x = int(w * 0.08)
        content_w = w - (margin_x * 2)
        
        # Layouts específicos
        layout = scene.get("layout", "hero")
        
        if layout == "stats":
            self._draw_stats_layout(draw, scene, w, h, content_w, margin_x, progress)
        else:
            self._draw_standard_layout(draw, scene, w, h, content_w, margin_x, progress, layout)

        # 5. Visualizador de audio reactivo procedural en la parte inferior
        self._draw_audio_visualizer(draw, w, h, frame_idx, progress)
        
        # 6. Sello inferior
        font_sub = resolve_font("sans_regular", int(h * 0.015))
        draw.text((w // 2, int(h * 0.94)), "SOCIEDAD ECONÓMICA DE CREADORES-INTÉRPRETES", font=font_sub, fill=(120, 130, 145, 180), anchor="mm")

        return np.array(img)

    def _draw_standard_layout(self, draw, scene, w, h, content_w, margin_x, progress, layout):
        # Animación de entrada suave (alpha fade-in rápido en los primeros 10% del tiempo)
        fade = min(1.0, progress / 0.10) if progress < 0.10 else 1.0
        
        # 1. Preparar textos y fuentes
        title = scene.get("title", "")
        font_title_size = int(h * 0.054) if self.format_type == "vertical" else int(h * 0.075)
        font_title = resolve_font("title_bold", font_title_size)
        title_lines = wrap_text(title, font_title, int(content_w * 0.96), draw)
        title_line_h = int(font_title_size * 1.12)
        total_title_h = len(title_lines) * title_line_h

        subtitle = scene.get("subtitle", "")
        font_sub_size = int(h * 0.020)
        font_sub = resolve_font("sans_bold", font_sub_size)
        total_sub_h = font_sub_size + 14 if subtitle else 0

        emphasis = scene.get("emphasis", "")
        font_emp_size = int(h * 0.026) if self.format_type == "vertical" else int(h * 0.035)
        font_emp = resolve_font("sans_bold", font_emp_size)
        emp_lines = wrap_text(emphasis, font_emp, int(content_w * 0.90), draw) if emphasis else []
        box_pad_y = 18
        total_emp_h = (len(emp_lines) * int(font_emp_size * 1.25) + box_pad_y * 2) if emphasis else 0

        body = scene.get("body", "")
        font_body_size = int(h * 0.020) if self.format_type == "vertical" else int(h * 0.026)
        font_body = resolve_font("sans_regular", font_body_size)
        body_lines = wrap_text(body, font_body, int(content_w * 0.92), draw) if body else []
        body_line_h = int(font_body_size * 1.30)
        total_body_h = (len(body_lines) * body_line_h) if body else 0

        # Gaps compactos (sin distancias excesivas)
        gap_title_sub = 12 if subtitle else 0
        gap_sub_emp = 18 if (subtitle and emphasis) else (14 if emphasis else 0)
        gap_emp_body = 18 if (emphasis and body) else (14 if body else 0)

        total_block_h = total_title_h + gap_title_sub + total_sub_h + gap_sub_emp + total_emp_h + gap_emp_body + total_body_h

        # Centrar el bloque en el eje vertical (entre header y visualizador)
        area_top = int(h * 0.16)
        area_bottom = int(h * 0.86)
        cur_y = area_top + (area_bottom - area_top - total_block_h) // 2

        # Dibujar Título
        for line in title_lines:
            draw.text((w // 2 + 2, cur_y + font_title_size // 2 + 2), line, font=font_title, fill=(0, 0, 0, int(190 * fade)), anchor="mm")
            draw.text((w // 2, cur_y + font_title_size // 2), line, font=font_title, fill=(self.c_white[0], self.c_white[1], self.c_white[2], int(255 * fade)), anchor="mm")
            cur_y += title_line_h

        # Dibujar Subtítulo
        if subtitle:
            cur_y += gap_title_sub
            draw.text((w // 2, cur_y + font_sub_size // 2), subtitle.upper(), font=font_sub, fill=(self.c_gold[0], self.c_gold[1], self.c_gold[2], int(230 * fade)), anchor="mm")
            cur_y += total_sub_h

        # Dibujar Frase de Énfasis
        if emphasis:
            cur_y += gap_sub_emp
            box_w = min(content_w, int(w * 0.90))
            box_rect = [
                (w - box_w) // 2,
                cur_y,
                (w + box_w) // 2,
                cur_y + total_emp_h
            ]
            accent_col = self.c_red if layout == "warning" else self.c_gold
            draw_rounded_rectangle(draw, box_rect, radius=12, fill=(16, 21, 32, int(230 * fade)), outline=accent_col, width=2)
            
            ey = cur_y + box_pad_y + (font_emp_size // 2)
            for eline in emp_lines:
                draw.text((w // 2, ey), eline, font=font_emp, fill=(accent_col[0], accent_col[1], accent_col[2], int(255 * fade)), anchor="mm")
                ey += int(font_emp_size * 1.25)
            cur_y += total_emp_h

        # Dibujar Texto de Cuerpo
        if body:
            cur_y += gap_emp_body
            for bline in body_lines:
                draw.text((w // 2, cur_y + font_body_size // 2), bline, font=font_body, fill=(self.c_gray[0], self.c_gray[1], self.c_gray[2], int(220 * fade)), anchor="mm")
                cur_y += body_line_h

    def _draw_stats_layout(self, draw, scene, w, h, content_w, margin_x, progress):
        fade = min(1.0, progress / 0.15) if progress < 0.15 else 1.0
        
        # Título y subtítulo
        title = scene.get("title", "")
        font_title = resolve_font("title_bold", int(h * 0.048))
        draw.text((w // 2, int(h * 0.23)), title, font=font_title, fill=self.c_white, anchor="mm")
        
        sub = scene.get("subtitle", "")
        font_sub = resolve_font("sans_bold", int(h * 0.020))
        draw.text((w // 2, int(h * 0.28)), sub, font=font_sub, fill=self.c_gold, anchor="mm")
        
        # Tarjetas de estadísticas (4 cuadrantes / filas)
        stats = scene.get("stats", [])
        start_y = int(h * 0.35)
        card_h = int(h * 0.09)
        gap = int(h * 0.02)
        
        font_val = resolve_font("sans_bold", int(h * 0.042))
        font_lbl = resolve_font("sans_bold", int(h * 0.020))
        
        for i, s in enumerate(stats):
            cy = start_y + i * (card_h + gap)
            card_rect = [margin_x, cy, w - margin_x, cy + card_h]
            draw_rounded_rectangle(draw, card_rect, radius=12, fill=(self.c_card[0], self.c_card[1], self.c_card[2], int(230 * fade)), outline=self.c_border, width=1)
            
            # Valor porcentual a la izquierda
            val_x = margin_x + int(content_w * 0.16)
            draw.text((val_x, cy + card_h // 2), s["value"], font=font_val, fill=self.c_gold, anchor="mm")
            
            # Separador vertical
            sep_x = margin_x + int(content_w * 0.30)
            draw.line([(sep_x, cy + 15), (sep_x, cy + card_h - 15)], fill=self.c_border, width=2)
            
            # Etiqueta a la derecha
            lbl_x = sep_x + 25
            draw.text((lbl_x, cy + card_h // 2), s["label"], font=font_lbl, fill=self.c_white, anchor="lm")
            
        # Nota final de Braintrust
        font_note = resolve_font("sans_regular", int(h * 0.019))
        draw.text((w // 2, int(h * 0.82)), "MODELO PIXAR: La crítica es sobre la obra, nunca sobre el ego.", font=font_note, fill=self.c_gray, anchor="mm")

    def _draw_audio_visualizer(self, draw, w, h, frame_idx, progress):
        """Barras de espectro de audio animadas en la base."""
        num_bars = 32
        bar_width = max(6, int(w * 0.012))
        spacing = max(4, int(w * 0.008))
        total_w = num_bars * (bar_width + spacing) - spacing
        start_x = (w - total_w) // 2
        base_y = int(h * 0.90)
        max_h = int(h * 0.06)
        
        for i in range(num_bars):
            # Oscilación armónica simulada con frecuencias naturales
            wave = (
                math.sin((frame_idx * 0.15) + (i * 0.4)) * 0.4 +
                math.sin((frame_idx * 0.08) - (i * 0.2)) * 0.3 +
                math.cos((frame_idx * 0.22) + (i * 0.8)) * 0.3
            )
            # Picos en el centro (forma de espectrograma de voz)
            center_weight = 1.0 - abs(i - (num_bars / 2)) / (num_bars / 2) * 0.5
            bar_val = max(0.12, abs(wave) * center_weight)
            bar_height = int(bar_val * max_h)
            
            bx = start_x + i * (bar_width + spacing)
            by = base_y - bar_height
            
            # Color con gradiente hacia dorado
            bar_color = (
                int(self.c_gold[0] * 0.8 + 40),
                int(self.c_gold[1] * 0.8 + 30),
                int(self.c_gold[2] * 0.8),
                180
            )
            draw_rounded_rectangle(draw, [bx, by, bx + bar_width, base_y], radius=3, fill=bar_color)

    async def build_scene_audio(self, scene, temp_dir, voice="es-PY-MarioNeural"):
        """Genera el audio de la voz en off para una escena y devuelve su duración."""
        audio_path = os.path.join(temp_dir, f"{scene['id']}_voice.mp3")
        text = scene.get("voice_script", scene.get("body", scene.get("title", "")))
        
        await generate_voice_file(text, voice, audio_path)
        duration = get_audio_duration(audio_path)
        
        # Agregar 0.6 segundos de respiro para que no corte abrupto
        total_duration = max(3.5, duration + 0.8)
        return audio_path, total_duration

    def render_scene_video(self, scene, duration, total_progress_start, total_progress_end, temp_dir):
        """Renderiza una escena a video MP4 usando tubería a ffmpeg."""
        scene_mp4 = os.path.join(temp_dir, f"{scene['id']}_video.mp4")
        num_frames = int(duration * self.fps)
        
        cmd = [
            "ffmpeg", "-y",
            "-f", "rawvideo",
            "-vcodec", "rawvideo",
            "-s", f"{self.width}x{self.height}",
            "-pix_fmt", "rgb24",
            "-r", str(self.fps),
            "-i", "-",
            "-an",
            "-vcodec", "libx264",
            "-pix_fmt", "yuv420p",
            "-preset", "veryfast",
            "-crf", "18",
            scene_mp4
        ]
        
        proc = subprocess.Popen(cmd, stdin=subprocess.PIPE, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        
        for f_idx in range(num_frames):
            prog = f_idx / max(1, num_frames - 1)
            tot_prog = total_progress_start + (total_progress_end - total_progress_start) * prog
            frame_arr = self.render_frame(scene, prog, tot_prog, f_idx)
            proc.stdin.write(frame_arr.tobytes())
            
        proc.stdin.close()
        proc.wait()
        return scene_mp4

    def combine_scene(self, video_path, audio_path, duration, output_path):
        """Une video y voz en off para una escena."""
        cmd = [
            "ffmpeg", "-y",
            "-i", video_path,
            "-i", audio_path,
            "-c:v", "copy",
            "-c:a", "aac",
            "-b:a", "192k",
            "-shortest",
            output_path
        ]
        subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

    def concatenate_scenes(self, scene_files, output_path):
        """Concatena todas las escenas."""
        concat_txt = os.path.join(os.path.dirname(output_path), "concat.txt")
        with open(concat_txt, "w") as f:
            for fpath in scene_files:
                f.write(f"file '{os.path.abspath(fpath)}'\n")
                
        cmd = [
            "ffmpeg", "-y",
            "-f", "concat",
            "-safe", "0",
            "-i", concat_txt,
            "-c", "copy",
            output_path
        ]
        subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

    def add_soundtrack(self, video_path, music_path, output_path):
        """Mezcla la voz principal con la música de fondo."""
        cmd = [
            "ffmpeg", "-y",
            "-i", video_path,
            "-stream_loop", "-1", "-i", music_path,
            "-filter_complex",
            "[1:a]volume=0.22,afade=t=out:st=1000:d=2[bgm];[0:a][bgm]amix=inputs=2:duration=first:dropout_transition=2[aout]",
            "-map", "0:v",
            "-map", "[aout]",
            "-c:v", "copy",
            "-c:a", "aac",
            "-b:a", "192k",
            output_path
        ]
        subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
