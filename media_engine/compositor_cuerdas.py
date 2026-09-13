"""
Ensamble Curupayty — Compositor y Sintetizador de Cuerdas Orquestales
Compone obras para quinteto/orquesta de cuerdas:
- Violines I
- Violines II
- Violas
- Violonchelos
- Contrabajos

Genera:
1. Archivo MIDI multitrack (.mid) para MuseScore / Sibelius / Logic Pro
2. Audio orquestal (.wav y .mp3) con modelado acústico de cuerda frotada,
   paneo estéreo sinfónico y reverberación de sala de conciertos.
"""

import os
import math
import numpy as np
import scipy.signal
import scipy.io.wavfile
import mido
from mido import Message, MidiFile, MidiTrack, MetaMessage

# Constantes de audio
SAMPLE_RATE = 44100

def note_to_freq(midi_note):
    """Convierte nota MIDI a frecuencia en Hertz."""
    return 440.0 * (2.0 ** ((midi_note - 69.0) / 12.0))

def parse_note(name):
    """Convierte notación (ej: 'D4', 'C#3', 'Eb5') a número MIDI."""
    names = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
    enharmonics = {'Db': 'C#', 'Eb': 'D#', 'Gb': 'F#', 'Ab': 'G#', 'Bb': 'A#'}
    
    note = name[:-1]
    octave = int(name[-1])
    if note in enharmonics:
        note = enharmonics[note]
    
    semitone = names.index(note)
    return 12 * (octave + 1) + semitone

class BowedStringSynthesizer:
    """Sintetiza sonido de cuerda frotada (violín, viola, cello, contrabajo)."""
    def __init__(self, sample_rate=44100):
        self.sr = sample_rate

    def render_note(self, midi_note, duration_sec, velocity=80, instrument="violin"):
        f0 = note_to_freq(midi_note)
        num_samples = int(duration_sec * self.sr)
        if num_samples <= 0:
            return np.zeros((1, 2))
        
        t = np.linspace(0, duration_sec, num_samples, endpoint=False)
        
        # Vibrato acústico natural (aparece gradualmente a partir de los 150ms)
        vib_rate = 5.4 if instrument in ["violin", "viola"] else 4.6
        vib_delay = np.clip((t - 0.15) / 0.35, 0, 1)
        vib_depth = 0.009 * vib_delay
        pitch_mod = 1.0 + vib_depth * np.sin(2 * np.pi * vib_rate * t)
        
        phase = np.cumsum(2 * np.pi * f0 * pitch_mod / self.sr)
        
        # Síntesis espectral de cuerda con arco (armónicos pares e impares ricos)
        # Atenuación natural de armónicos: 1/n con caída en agudos
        signal = np.zeros(num_samples, dtype=np.float32)
        max_harmonics = min(28, int((self.sr / 2.2) / f0))
        
        for n in range(1, max_harmonics + 1):
            harm_freq = f0 * n
            if harm_freq >= self.sr / 2:
                break
            
            # Formantes de madera según instrumento
            formant_boost = 1.0
            if instrument == "violin":
                # Resonancia en 550 Hz y 2500 Hz (puente)
                formant_boost += 1.2 * np.exp(-((harm_freq - 550) ** 2) / (2 * 180 ** 2))
                formant_boost += 0.8 * np.exp(-((harm_freq - 2500) ** 2) / (2 * 600 ** 2))
            elif instrument == "viola":
                formant_boost += 1.4 * np.exp(-((harm_freq - 380) ** 2) / (2 * 150 ** 2))
                formant_boost += 0.7 * np.exp(-((harm_freq - 1900) ** 2) / (2 * 500 ** 2))
            elif instrument == "cello":
                formant_boost += 1.6 * np.exp(-((harm_freq - 220) ** 2) / (2 * 100 ** 2))
                formant_boost += 0.9 * np.exp(-((harm_freq - 1100) ** 2) / (2 * 400 ** 2))
            elif instrument == "contrabass":
                formant_boost += 2.0 * np.exp(-((harm_freq - 90) ** 2) / (2 * 60 ** 2))
                formant_boost += 0.8 * np.exp(-((harm_freq - 600) ** 2) / (2 * 250 ** 2))
            
            amp = (1.0 / (n ** 0.88)) * formant_boost
            signal += amp * np.sin(n * phase)
        
        # Envolvente de arco (Attack-Swell-Decay-Release)
        att_time = 0.08 if instrument in ["violin", "viola"] else 0.12
        rel_time = 0.12
        
        att_samples = int(att_time * self.sr)
        rel_samples = int(rel_time * self.sr)
        
        env = np.ones(num_samples, dtype=np.float32)
        if att_samples > 0 and att_samples < num_samples:
            env[:att_samples] = np.sin(np.linspace(0, np.pi / 2, att_samples)) ** 1.5
        if rel_samples > 0 and rel_samples < num_samples:
            env[-rel_samples:] = np.cos(np.linspace(0, np.pi / 2, rel_samples)) ** 2
            
        vel_scale = (velocity / 127.0) ** 1.2
        signal = signal * env * vel_scale
        
        return signal

def create_score_data():
    """
    Partitura: 'Curupayty: La Trinchera y el Fuego'
    Compás: 4/4, Tempo: 96 BPM.
    Tonalidad: Re menor (D minor) con modulación dramática y resolución heroica.
    """
    bpm = 96
    beat_dur = 60.0 / bpm  # 0.625s por negra
    
    # Estructura de notas: [(nota, duracion_en_beats, velocidad)]
    
    # 1. CONTRABAJO (Pulso de marcha de guerra, bajo pedal continuo y pesado)
    cb_notes = [
        # Intro (Compases 1-4)
        ('D2', 1.0, 95), ('D2', 1.0, 85), ('F2', 1.0, 90), ('E2', 1.0, 85),
        ('D2', 1.0, 95), ('D2', 1.0, 85), ('G2', 1.0, 90), ('A2', 1.0, 90),
        ('Bb1', 1.0, 100), ('Bb1', 1.0, 90), ('A1', 1.0, 95), ('A1', 1.0, 85),
        ('D2', 2.0, 95), ('D2', 2.0, 90),
        # Tema A (Compases 5-8)
        ('D2', 1.0, 90), ('D2', 1.0, 80), ('F2', 1.0, 85), ('E2', 1.0, 80),
        ('D2', 1.0, 90), ('D2', 1.0, 80), ('C2', 1.0, 85), ('Bb1', 1.0, 90),
        ('G1', 1.0, 95), ('A1', 1.0, 90), ('Bb1', 1.0, 95), ('C2', 1.0, 95),
        ('D2', 4.0, 100),
        # Clímax y Desarrollo (Compases 9-12)
        ('Bb1', 2.0, 105), ('C2', 2.0, 105),
        ('D2', 2.0, 105), ('F2', 2.0, 105),
        ('G1', 2.0, 110), ('A1', 2.0, 110),
        ('D2', 4.0, 115),
        # Coda Triunfal (Compases 13-16)
        ('Bb1', 1.0, 100), ('C2', 1.0, 105), ('D2', 2.0, 110),
        ('G1', 1.0, 100), ('A1', 1.0, 105), ('D2', 2.0, 110),
        ('D2', 4.0, 120),
        ('D2', 4.0, 110)
    ]
    
    # 2. VIOLONCHELO (Ostinato dramático y contracanto expresivo)
    vc_notes = [
        # Intro
        ('D3', 0.5, 85), ('A2', 0.5, 75), ('D3', 0.5, 80), ('F3', 0.5, 85),
        ('E3', 0.5, 80), ('D3', 0.5, 75), ('C#3', 0.5, 80), ('A2', 0.5, 75),
        ('D3', 0.5, 85), ('A2', 0.5, 75), ('D3', 0.5, 80), ('F3', 0.5, 85),
        ('G3', 0.5, 85), ('F3', 0.5, 80), ('E3', 0.5, 80), ('C#3', 0.5, 80),
        ('Bb2', 1.0, 90), ('D3', 1.0, 85), ('A2', 1.0, 90), ('C#3', 1.0, 85),
        ('D3', 2.0, 90), ('A2', 2.0, 85),
        # Tema A (Canta con los violines en contrapunto)
        ('F3', 1.0, 85), ('A3', 1.0, 85), ('D4', 2.0, 90),
        ('C4', 1.0, 85), ('Bb3', 1.0, 85), ('A3', 2.0, 85),
        ('G3', 1.0, 90), ('Bb3', 1.0, 85), ('A3', 1.0, 90), ('G3', 1.0, 85),
        ('F3', 2.0, 90), ('E3', 2.0, 85),
        # Clímax
        ('D3', 0.5, 95), ('F3', 0.5, 90), ('A3', 0.5, 95), ('D4', 0.5, 100),
        ('E4', 1.0, 105), ('F4', 1.0, 110),
        ('F4', 0.5, 105), ('E4', 0.5, 100), ('D4', 1.0, 105), ('C4', 1.0, 100), ('Bb3', 1.0, 100),
        ('G3', 1.0, 105), ('Bb3', 1.0, 105), ('A3', 2.0, 110),
        ('D4', 4.0, 115),
        # Coda
        ('G3', 1.0, 95), ('A3', 1.0, 100), ('Bb3', 2.0, 105),
        ('E3', 1.0, 95), ('G3', 1.0, 100), ('F#3', 2.0, 105),
        ('D3', 4.0, 115),
        ('D3', 4.0, 100)
    ]
    
    # 3. VIOLA (Textura armónica interior, síncopas y ritmo de atril)
    va_notes = [
        # Intro
        ('F3', 1.0, 75), ('A3', 1.0, 75), ('F3', 1.0, 75), ('A3', 1.0, 75),
        ('F3', 1.0, 75), ('G3', 1.0, 75), ('E3', 1.0, 75), ('A3', 1.0, 75),
        ('D3', 1.0, 80), ('F3', 1.0, 80), ('E3', 1.0, 80), ('A3', 1.0, 80),
        ('F3', 2.0, 85), ('E3', 2.0, 80),
        # Tema A
        ('A3', 1.0, 80), ('D4', 1.0, 80), ('F4', 1.0, 85), ('E4', 1.0, 80),
        ('D4', 1.0, 80), ('C4', 1.0, 80), ('D4', 1.0, 85), ('F4', 1.0, 85),
        ('E4', 1.0, 85), ('D4', 1.0, 80), ('C#4', 1.0, 85), ('E4', 1.0, 85),
        ('A3', 2.0, 85), ('D4', 2.0, 85),
        # Clímax
        ('F4', 1.0, 95), ('G4', 1.0, 95), ('A4', 2.0, 100),
        ('Bb4', 1.0, 105), ('A4', 1.0, 100), ('G4', 1.0, 95), ('F4', 1.0, 95),
        ('E4', 1.0, 100), ('D4', 1.0, 95), ('C#4', 2.0, 105),
        ('F4', 4.0, 110),
        # Coda
        ('D4', 1.0, 90), ('E4', 1.0, 95), ('F#4', 2.0, 100),
        ('Bb3', 1.0, 90), ('C4', 1.0, 95), ('A3', 2.0, 100),
        ('F#3', 4.0, 110),
        ('F#3', 4.0, 95)
    ]
    
    # 4. VIOLÍN II (Armonización en terceras y contramelodía brillante)
    v2_notes = [
        # Intro (espera 2 compases y entra en compás 3)
        ('A3', 4.0, 70),
        ('A3', 2.0, 75), ('C#4', 2.0, 75),
        ('D4', 1.0, 80), ('F4', 1.0, 80), ('E4', 1.0, 80), ('G4', 1.0, 85),
        ('F4', 2.0, 85), ('A4', 2.0, 85),
        # Tema A
        ('F4', 1.0, 85), ('A4', 1.0, 85), ('A4', 1.5, 90), ('G4', 0.5, 80),
        ('F4', 1.0, 85), ('E4', 1.0, 80), ('F4', 2.0, 85),
        ('D4', 1.0, 85), ('G4', 1.0, 85), ('F4', 1.0, 85), ('E4', 1.0, 80),
        ('D4', 2.0, 85), ('C#4', 2.0, 85),
        # Clímax
        ('D4', 1.0, 95), ('F4', 1.0, 100), ('F4', 1.0, 100), ('E4', 1.0, 95),
        ('F4', 1.0, 100), ('A4', 1.0, 105), ('G4', 1.0, 100), ('F4', 1.0, 95),
        ('Bb4', 1.0, 105), ('A4', 1.0, 100), ('E4', 2.0, 100),
        ('A4', 4.0, 115),
        # Coda
        ('G4', 1.0, 95), ('A4', 1.0, 100), ('A4', 2.0, 105),
        ('E4', 1.0, 95), ('G4', 1.0, 100), ('D4', 2.0, 105),
        ('A4', 4.0, 115),
        ('A4', 4.0, 100)
    ]
    
    # 5. VIOLÍN I (Tema Principal: Épico, Lírico y Triunfal)
    v1_notes = [
        # Intro
        ('D4', 2.0, 80), ('F4', 1.0, 85), ('E4', 1.0, 80),
        ('D4', 2.0, 85), ('A4', 2.0, 90),
        ('Bb4', 1.5, 90), ('A4', 0.5, 85), ('G4', 1.0, 85), ('F4', 1.0, 85),
        ('E4', 2.0, 90), ('A4', 2.0, 95),
        # Tema Principal Heroico (Curupayty)
        ('A4', 1.0, 90), ('D5', 1.5, 100), ('E5', 0.5, 95), ('F5', 1.0, 100),
        ('E5', 1.0, 95), ('D5', 1.0, 90), ('A4', 2.0, 95),
        ('G4', 1.0, 90), ('Bb4', 1.5, 95), ('C5', 0.5, 95), ('D5', 1.0, 100),
        ('C#5', 2.0, 100), ('A4', 2.0, 95),
        # Clímax (Agudo con vibrato intenso)
        ('D5', 1.0, 105), ('F5', 1.0, 110), ('A5', 2.0, 115),
        ('G5', 1.0, 110), ('F5', 1.0, 105), ('E5', 1.0, 105), ('D5', 1.0, 100),
        ('G5', 1.0, 110), ('F5', 1.0, 105), ('E5', 1.0, 100), ('C#5', 1.0, 100),
        ('D5', 4.0, 120),
        # Coda Triunfal (Resolución a Re Mayor heroico)
        ('Bb4', 1.0, 100), ('C5', 1.0, 105), ('D5', 2.0, 110),
        ('G4', 1.0, 100), ('A4', 1.0, 105), ('F#5', 2.0, 115),
        ('D5', 4.0, 125),
        ('D5', 4.0, 105)
    ]
    
    parts = {
        "Violin I": {"instrument": "violin", "notes": v1_notes, "pan": -0.35, "gm_program": 40},
        "Violin II": {"instrument": "violin", "notes": v2_notes, "pan": -0.15, "gm_program": 40},
        "Viola": {"instrument": "viola", "notes": va_notes, "pan": 0.0, "gm_program": 41},
        "Violoncello": {"instrument": "cello", "notes": vc_notes, "pan": 0.25, "gm_program": 42},
        "Double Bass": {"instrument": "contrabass", "notes": cb_notes, "pan": 0.40, "gm_program": 43}
    }
    
    return parts, bpm, beat_dur

def export_midi(parts, bpm, output_file):
    """Genera archivo MIDI estándar Type 1 multitrack."""
    mid = MidiFile(type=1)
    ticks_per_beat = 480
    mid.ticks_per_beat = ticks_per_beat
    
    # Pista de Tempo y Metadatos
    tempo_track = MidiTrack()
    mid.tracks.append(tempo_track)
    tempo_track.append(MetaMessage('track_name', name='Curupayty: La Trinchera y el Fuego'))
    tempo_track.append(MetaMessage('set_tempo', tempo=mido.bpm2tempo(bpm)))
    tempo_track.append(MetaMessage('time_signature', numerator=4, denominator=4))
    tempo_track.append(MetaMessage('end_of_track'))
    
    for channel, (name, p_data) in enumerate(parts.items()):
        track = MidiTrack()
        mid.tracks.append(track)
        track.append(MetaMessage('track_name', name=name))
        track.append(Message('program_change', program=p_data['gm_program'], channel=channel, time=0))
        
        # Paneo estéreo MIDI
        pan_val = int(np.clip((p_data['pan'] + 1.0) / 2.0 * 127, 0, 127))
        track.append(Message('control_change', control=10, value=pan_val, channel=channel, time=0))
        
        for note_name, dur_beats, vel in p_data['notes']:
            midi_num = parse_note(note_name)
            duration_ticks = int(dur_beats * ticks_per_beat)
            
            # Note ON
            track.append(Message('note_on', note=midi_num, velocity=vel, channel=channel, time=0))
            # Note OFF
            track.append(Message('note_off', note=midi_num, velocity=0, channel=channel, time=duration_ticks))
            
        track.append(MetaMessage('end_of_track'))
        
    mid.save(output_file)

def synthesize_ensemble(parts, beat_dur, synth, output_wav):
    """Renderiza todas las voces a audio estéreo con paneo y mezcla."""
    # Calcular duración total
    max_duration = 0
    for p in parts.values():
        total_beats = sum(n[1] for n in p['notes'])
        max_duration = max(max_duration, total_beats * beat_dur)
    
    # 2.5 segundos extra para la cola de reverb
    total_samples = int((max_duration + 3.0) * SAMPLE_RATE)
    stereo_mix = np.zeros((total_samples, 2), dtype=np.float32)
    
    for name, p_data in parts.items():
        inst = p_data['instrument']
        pan = p_data['pan']
        left_gain = math.cos((pan + 1.0) * math.pi / 4.0)
        right_gain = math.sin((pan + 1.0) * math.pi / 4.0)
        
        inst_track = np.zeros(total_samples, dtype=np.float32)
        cur_sample = 0
        
        for note_name, dur_beats, vel in p_data['notes']:
            midi_num = parse_note(note_name)
            dur_sec = dur_beats * beat_dur
            note_audio = synth.render_note(midi_num, dur_sec, velocity=vel, instrument=inst)
            
            end_sample = cur_sample + len(note_audio)
            if end_sample <= total_samples:
                inst_track[cur_sample:end_sample] += note_audio
            cur_sample += int(dur_sec * SAMPLE_RATE)
            
        # Paneo estéreo
        stereo_mix[:, 0] += inst_track * left_gain
        stereo_mix[:, 1] += inst_track * right_gain
        
    # Normalización suave antes de reverb
    peak = np.max(np.abs(stereo_mix))
    if peak > 0:
        stereo_mix /= peak
        stereo_mix *= 0.82
        
    # Guardar WAV crudo
    int_audio = (stereo_mix * 32767).astype(np.int16)
    scipy.io.wavfile.write(output_wav, SAMPLE_RATE, int_audio)

def master_with_hall_reverb(input_wav, output_mp3):
    """Aplica reverberación de sala sinfónica (hall reverb) y masteriza con ffmpeg."""
    import subprocess
    cmd = [
        "ffmpeg", "-y",
        "-i", input_wav,
        "-af",
        # Reverberación de sala de conciertos sinfónica
        "aecho=0.8:0.88:40|70|110|160:0.4|0.3|0.2|0.15,loudnorm=I=-16:TP=-1.5:LRA=11",
        "-c:a", "libmp3lame",
        "-b:a", "320k",
        output_mp3
    ]
    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

def main():
    print("=" * 60)
    print("   ENSAMBLE CURUPAYTY — COMPOSITOR DE CUERDAS")
    print("   Obra: 'Curupayty: La Trinchera y el Fuego'")
    print("   Plantilla: Violines I, Violines II, Violas, Celli, C.Bajos")
    print("=" * 60)
    
    os.makedirs("assets/audio", exist_ok=True)
    os.makedirs("output", exist_ok=True)
    
    parts, bpm, beat_dur = create_score_data()
    
    # 1. Exportar Partitura MIDI
    midi_path = "output/curupayty_cuerdas_score.mid"
    print("🎼 Generando archivo MIDI multitrack para partituras...")
    export_midi(parts, bpm, midi_path)
    print(f"   ✅ Guardado: {os.path.abspath(midi_path)}")
    
    # 2. Síntesis acústica de cuerdas
    print("🎻 Sintetizando quinteto de cuerdas con modelado acústico...")
    synth = BowedStringSynthesizer(sample_rate=SAMPLE_RATE)
    raw_wav = "output/curupayty_cuerdas_raw.wav"
    synthesize_ensemble(parts, beat_dur, synth, raw_wav)
    
    # 3. Masterización con acústica de sala de conciertos
    final_mp3 = "output/curupayty_obra_cuerdas.mp3"
    print("🏛️  Aplicando acústica sinfónica (Hall Reverb) y masterización...")
    master_with_hall_reverb(raw_wav, final_mp3)
    
    # Copiar a assets/audio para que el video pueda usarla directamente
    shutil_copy = "assets/audio/curupayty_cuerdas.mp3"
    import shutil
    shutil.copyfile(final_mp3, shutil_copy)
    if os.path.exists(raw_wav):
        os.remove(raw_wav)
        
    print("=" * 60)
    print(f"🎉 OBRA PARA CUERDAS GENERADA:")
    print(f"   🎧 Audio Master:   {os.path.abspath(final_mp3)}")
    print(f"   🎼 Partitura MIDI: {os.path.abspath(midi_path)}")
    print(f"   🎵 Pista para video: {shutil_copy}")
    print("=" * 60)

if __name__ == "__main__":
    main()
