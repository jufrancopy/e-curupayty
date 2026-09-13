#!/usr/bin/env python3
"""
CLI de Generación de Video para Ensamble Curupayty.
Uso:
  python3 main.py --preset teaser --format vertical
  python3 main.py --preset completo --format horizontal
  python3 main.py --list-presets
"""

import os
import sys
import shutil
import asyncio
import argparse
import tempfile
import subprocess
from video_generator import CurupaytyVideoGenerator, create_procedural_background_music

def parse_args():
    parser = argparse.ArgumentParser(description="Generador de Videos para Ensamble Curupayty")
    parser.add_argument("--scenes", default="scenes.json", help="Ruta al archivo JSON de escenas")
    parser.add_argument("--preset", default="locucion_real", help="Preset de escenas a renderizar (ej: locucion_real, teaser, completo, ruptura, economia)")
    parser.add_argument("--format", default="vertical", choices=["vertical", "horizontal", "square"], help="Formato de video (vertical: 9:16 reels, horizontal: 16:9 youtube)")
    parser.add_argument("--voice", default="es-PY-MarioNeural", help="Voz neural de Edge-TTS (ej: es-PY-MarioNeural, es-PY-TaniaNeural, es-AR-TomasNeural)")
    parser.add_argument("--voice-audio", default=None, help="Ruta a archivo de voz grabado real (ej: voz.m4a, voz.mp3). Si existe voz.m4a o voz.mp3 en la carpeta, se detecta automáticamente.")
    parser.add_argument("--vocal-profile", default="grave", choices=["natural", "grave", "profundo", "transmision"], help="Perfil de timbre para proteger identidad (natural, grave, profundo, transmision)")
    parser.add_argument("--music", default=None, help="Ruta a archivo de música de fondo (MP3 o WAV). Si no se indica, se genera una pista cinemática sutil.")
    parser.add_argument("--fps", type=int, default=30, help="Fotogramas por segundo")
    parser.add_argument("--output", default=None, help="Nombre del archivo MP4 resultante")
    parser.add_argument("--list-presets", action="store_true", help="Listar presets disponibles y salir")
    return parser.parse_args()

def clean_and_normalize_voice(input_path, output_path, profile="grave"):
    """Limpia ruido de fondo, modifica timbre para anonimizar y normaliza el volumen."""
    filter_chain = "highpass=f=80,lowpass=f=12000"
    
    if profile == "grave":
        # Narrador cinematográfico maduro, baja ~2 semitonos, anonimiza con naturalidad
        filter_chain += ",rubberband=pitch=0.89,equalizer=f=120:t=q:w=1:g=3"
    elif profile == "profundo":
        # Voz misteriosa, muy profunda, totalmente irreconocible
        filter_chain += ",rubberband=pitch=0.83,equalizer=f=150:t=q:w=1:g=4"
    elif profile == "transmision":
        # Estilo comunicado insurgente / radio trinchera
        filter_chain += ",rubberband=pitch=0.92,highpass=f=250,lowpass=f=4200,equalizer=f=1800:t=q:w=1:g=3"

    filter_chain += ",loudnorm=I=-16:TP=-1.5:LRA=11"

    cmd = [
        "ffmpeg", "-y",
        "-i", input_path,
        "-af", filter_chain,
        "-c:a", "libmp3lame",
        "-b:a", "192k",
        output_path
    ]
    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

async def main():
    args = parse_args()
    
    if args.list_presets:
        print("\nPresets disponibles en scenes.json:")
        print("  - teaser:   5 escenas dinámicas (~45s) enfocadas en la visión, el principio económico y la convocatoria.")
        print("  - completo: Las 6 escenas completas del manifiesto, incluyendo Braintrust Pixar y desglose 40/25/10/25.")
        print("  - ruptura:  3 escenas directas sobre el fin del director-dueño y el nuevo modelo.")
        print("  - economia: 3 escenas sobre el principio económico, el catálogo comercial y el reparto.")
        return

    # Detección automática de voz grabada si existe Track7_VozAudio.m4a, voz.m4a o voz.mp3
    real_voice_path = args.voice_audio
    if not real_voice_path:
        for candidate in ["Track7_VozAudio.m4a", "Track7_VozAudio.mp3", "voz.m4a", "voz.mp3", "voz.wav", "audio_voz.m4a", "audio_voz.mp3"]:
            if os.path.exists(candidate):
                real_voice_path = candidate
                break

    os.makedirs("output", exist_ok=True)
    temp_dir = tempfile.mkdtemp(prefix="curupayty_")
    
    try:
        print("=" * 60)
        print("   ENSAMBLE CURUPAYTY — GENERADOR DE VIDEO")
        print("=" * 60)
        print(f"🎬 Formato: {args.format.upper()}")
        print(f"📋 Preset:  {args.preset}")
        if real_voice_path:
            print(f"🎙️  Voz:     HUMANA REAL ({real_voice_path})")
        else:
            print(f"🎙️  Voz:     Sintética Neural ({args.voice})")
        print(f"📁 Temp:    {temp_dir}")
        print("-" * 60)

        gen = CurupaytyVideoGenerator(scenes_file=args.scenes, format_type=args.format, fps=args.fps)
        
        # Si se detecta Track7_VozAudio y preset no fue forzado o es locucion_real
        if real_voice_path and args.preset == "teaser" and "locucion_real" in gen.data.get("presets", {}):
            args.preset = "locucion_real"

        # Filtrar escenas según preset
        preset_ids = gen.data.get("presets", {}).get(args.preset, [])
        scenes_map = {s["id"]: s for s in gen.data.get("scenes", [])}
        scenes = [scenes_map[sid] for sid in preset_ids if sid in scenes_map]
        
        if not scenes:
            print("❌ Error: No se encontraron escenas para el preset seleccionado.")
            return

        print(f"Procesando {len(scenes)} escenas para preset '{args.preset}'...")
        
        # 1. Preparar audio
        durations = []
        scene_audios = []
        
        if real_voice_path and os.path.exists(real_voice_path):
            print(f"🎙️  Procesando locución real (perfil: {args.vocal_profile}): {real_voice_path}...")
            cleaned_voice = os.path.join(temp_dir, "cleaned_voice.mp3")
            clean_and_normalize_voice(real_voice_path, cleaned_voice, profile=args.vocal_profile)
            from video_generator import get_audio_duration
            total_duration = get_audio_duration(cleaned_voice)
            print(f"⏱️  Duración de la voz real: {total_duration:.1f} segundos.")
            
            # Verificar si las escenas tienen cortes manuales definidos
            has_explicit_cuts = all("cut_start" in sc and "cut_end" in sc for sc in scenes)
            
            if has_explicit_cuts:
                print("🎯 Utilizando cortes de escena calibrados al audio real...")
                for idx, sc in enumerate(scenes, 1):
                    c_start = sc["cut_start"]
                    c_end = min(sc["cut_end"], total_duration)
                    dur = max(1.0, c_end - c_start)
                    durations.append(dur)
                    part_audio = os.path.join(temp_dir, f"scene_{idx}_voice.mp3")
                    cmd = [
                        "ffmpeg", "-y",
                        "-ss", f"{c_start:.2f}",
                        "-t", f"{dur:.2f}",
                        "-i", cleaned_voice,
                        "-c", "copy",
                        part_audio
                    ]
                    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
                    scene_audios.append(part_audio)
            else:
                # Ponderar duración de escenas según cantidad de palabras en el guion
                weights = []
                for sc in scenes:
                    txt = sc.get("voice_script", sc.get("body", sc.get("title", "")))
                    weights.append(max(10, len(txt.split())))
                total_w = sum(weights)
                durations = [(w / total_w) * total_duration for w in weights]
                cur_start = 0.0
                for idx, dur in enumerate(durations, 1):
                    part_audio = os.path.join(temp_dir, f"scene_{idx}_voice.mp3")
                    cmd = [
                        "ffmpeg", "-y",
                        "-ss", f"{cur_start:.2f}",
                        "-t", f"{dur:.2f}",
                        "-i", cleaned_voice,
                        "-c", "copy",
                        part_audio
                    ]
                    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
                    scene_audios.append(part_audio)
                    cur_start += dur
        else:
            for idx, sc in enumerate(scenes, 1):
                print(f"  [{idx}/{len(scenes)}] Generando voz en off para '{sc['title']}'...")
                a_path, dur = await gen.build_scene_audio(sc, temp_dir, voice=args.voice)
                scene_audios.append(a_path)
                durations.append(dur)
            total_duration = sum(durations)
            print(f"⏱️  Duración total estimada: {total_duration:.1f} segundos.")
        
        # 2. Renderizar video por escena
        scene_videos = []
        current_elapsed = 0.0
        
        for idx, (sc, a_path, dur) in enumerate(zip(scenes, scene_audios, durations), 1):
            start_p = current_elapsed / total_duration
            end_p = (current_elapsed + dur) / total_duration
            current_elapsed += dur
            
            print(f"  [{idx}/{len(scenes)}] Renderizando frames para '{sc['title']}' ({dur:.1f}s)...")
            raw_v = gen.render_scene_video(sc, dur, start_p, end_p, temp_dir)
            
            combined_sc = os.path.join(temp_dir, f"scene_{idx}_final.mp4")
            gen.combine_scene(raw_v, a_path, dur, combined_sc)
            scene_videos.append(combined_sc)
            
        # 3. Concatenar todas las escenas
        concat_output = os.path.join(temp_dir, "video_unido.mp4")
        print("🎞️  Concatenando escenas...")
        gen.concatenate_scenes(scene_videos, concat_output)
        
        # 4. Música de fondo y mezcla final
        if args.music and os.path.exists(args.music):
            music_file = args.music
            print(f"🎵 Mezclando con pista de música externa: {music_file}")
        else:
            print("🎵 Generando fondo sonoro cinemático con bajos y textura armónica...")
            music_file = os.path.join(temp_dir, "ambient_drone.wav")
            create_procedural_background_music(total_duration + 2.0, music_file)

        final_name = args.output or f"output/curupayty_{args.preset}_{args.format}.mp4"
        print("🎚️  Realizando masterización de audio y exportación final...")
        gen.add_soundtrack(concat_output, music_file, final_name)
        
        print("=" * 60)
        print(f"✅ VIDEO GENERADO CON ÉXITO:")
        print(f"   📹 {os.path.abspath(final_name)}")
        print(f"   📐 Dimensiones: {gen.width}x{gen.height}")
        print(f"   ⏱️  Duración:     {total_duration:.1f}s")
        print("=" * 60)

    finally:
        shutil.rmtree(temp_dir, ignore_errors=True)

if __name__ == "__main__":
    asyncio.run(main())
