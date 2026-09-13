"""
Ensamble Curupayty — Compositor Virtuoso de Cuerdas Barrocas
Estilo: Presto con Fuoco (Inspirado en 'Las Cuatro Estaciones' de Vivaldi: Verano / Invierno)
Plantilla: Violines I (Solista/Tutti), Violines II, Violas, Violonchelos, Contrabajos

Características:
- Tempo Presto a 138 BPM.
- Semicorcheas virtuosas, arpegios bariolage y contrapunto vertiginoso.
- Sonido brillante, ágil y 'vivo': ataque de arco spiccato/détaché con brillo de madera.
- Salida en MIDI (.mid) y Audio Masterizado (.mp3).
"""

import os
import math
import numpy as np
import scipy.signal
import scipy.io.wavfile
import mido
from mido import Message, MidiFile, MidiTrack, MetaMessage

SAMPLE_RATE = 44100

def note_to_freq(midi_note):
    return 440.0 * (2.0 ** ((midi_note - 69.0) / 12.0))

def parse_note(name):
    names = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
    enharmonics = {'Db': 'C#', 'Eb': 'D#', 'Gb': 'F#', 'Ab': 'G#', 'Bb': 'A#'}
    note = name[:-1]
    octave = int(name[-1])
    if note in enharmonics:
        note = enharmonics[note]
    semitone = names.index(note)
    return 12 * (octave + 1) + semitone

class VirtuosoStringSynthesizer:
    """Sintetizador con ataque ágil, mordida de arco (spiccato) y armónicos vivos y brillantes."""
    def __init__(self, sample_rate=44100):
        self.sr = sample_rate

    def render_note(self, midi_note, duration_sec, velocity=90, instrument="violin"):
        f0 = note_to_freq(midi_note)
        num_samples = int(duration_sec * self.sr)
        if num_samples <= 0:
            return np.zeros(1, dtype=np.float32)
        
        t = np.linspace(0, duration_sec, num_samples, endpoint=False)
        
        # Vibrato vivo y rápido estilo barroco
        vib_rate = 6.0 if instrument in ["violin", "viola"] else 5.2
        vib_delay = np.clip((t - 0.08) / 0.15, 0, 1)
        # Notas muy cortas (<0.2s) tienen menos vibrato y más ataque seco
        vib_depth = 0.007 * vib_delay if duration_sec > 0.18 else 0.002
        pitch_mod = 1.0 + vib_depth * np.sin(2 * np.pi * vib_rate * t)
        
        phase = np.cumsum(2 * np.pi * f0 * pitch_mod / self.sr)
        
        # Síntesis armónica con más brillo (más armónicos superiores vivos)
        signal = np.zeros(num_samples, dtype=np.float32)
        max_harmonics = min(36, int((self.sr / 2.1) / f0))
        
        for n in range(1, max_harmonics + 1):
            harm_freq = f0 * n
            if harm_freq >= self.sr / 2:
                break
            
            # Brillo y formantes vivos (ecualización brillante para que no suene apagado)
            formant_boost = 1.0
            if instrument == "violin":
                # Resonancia de cuerpo (600 Hz) y brillo de puente (2.8 kHz - 4.5 kHz)
                formant_boost += 1.4 * np.exp(-((harm_freq - 600) ** 2) / (2 * 180 ** 2))
                formant_boost += 1.8 * np.exp(-((harm_freq - 3200) ** 2) / (2 * 700 ** 2))
                formant_boost += 1.2 * np.exp(-((harm_freq - 5000) ** 2) / (2 * 1000 ** 2))
            elif instrument == "viola":
                formant_boost += 1.5 * np.exp(-((harm_freq - 420) ** 2) / (2 * 160 ** 2))
                formant_boost += 1.4 * np.exp(-((harm_freq - 2600) ** 2) / (2 * 600 ** 2))
            elif instrument == "cello":
                formant_boost += 1.8 * np.exp(-((harm_freq - 240) ** 2) / (2 * 100 ** 2))
                formant_boost += 1.5 * np.exp(-((harm_freq - 1600) ** 2) / (2 * 500 ** 2))
            elif instrument == "contrabass":
                formant_boost += 2.2 * np.exp(-((harm_freq - 100) ** 2) / (2 * 60 ** 2))
                formant_boost += 1.3 * np.exp(-((harm_freq - 800) ** 2) / (2 * 300 ** 2))
            
            # Caída más brillante que la orquestal estándar (exponente 0.76 en vez de 0.88)
            amp = (1.0 / (n ** 0.76)) * formant_boost
            signal += amp * np.sin(n * phase)
            
        # Transiente de ataque de arco (mordida de cerda de caballo inicial)
        attack_noise = np.random.normal(0, 0.08, num_samples).astype(np.float32)
        noise_env = np.exp(-t * 80.0) # decae en ~30ms
        signal += attack_noise * noise_env
        
        # Envolvente de articulación ágil (spiccato / détaché)
        # Ataque rápido (20-35ms) para que cada semicorchea se sienta precisa
        att_time = min(0.028, duration_sec * 0.25)
        rel_time = min(0.045, duration_sec * 0.35)
        
        att_samples = int(att_time * self.sr)
        rel_samples = int(rel_time * self.sr)
        
        env = np.ones(num_samples, dtype=np.float32)
        if att_samples > 0 and att_samples < num_samples:
            env[:att_samples] = np.linspace(0.1, 1.0, att_samples) ** 1.3
        if rel_samples > 0 and rel_samples < num_samples:
            env[-rel_samples:] = np.linspace(1.0, 0.05, rel_samples) ** 1.8
            
        vel_scale = (velocity / 127.0) ** 1.15
        return signal * env * vel_scale

def create_vivaldi_score():
    """
    Composición: 'Atypu: Tempestad Barroca' (Estilo Vivaldi - Cuatro Estaciones)
    Tonalidad: Sol menor (G minor).
    Tempo: 138 BPM (Presto con Fuoco).
    Compás: 4/4.
    """
    bpm = 138
    beat_dur = 60.0 / bpm  # ~0.434s por negra, semicorchea = 0.25 beats (~0.108s)

    # Helper para secuencias rápidas de semicorcheas (0.25 beats)
    def s16(note_list, vel=100):
        return [(n, 0.25, vel) for n in note_list]

    # Helper para corcheas (0.5 beats)
    def e8(note_list, vel=95):
        return [(n, 0.5, vel) for n in note_list]

    # --- 1. CONTRABAJOS (Motor rítmico implacable barroco) ---
    cb_notes = []
    # Compases 1-4: Ostinato en Sol menor
    for _ in range(2):
        cb_notes += e8(['G1', 'G1', 'G1', 'G1', 'G1', 'G1', 'G1', 'G1'], vel=105)
        cb_notes += e8(['Eb1', 'Eb1', 'Eb1', 'Eb1', 'D1', 'D1', 'D1', 'D1'], vel=105)
    # Compases 5-8: Círculo de quintas barroco
    cb_notes += e8(['C2', 'C2', 'C2', 'C2', 'F1', 'F1', 'F1', 'F1'], vel=100)
    cb_notes += e8(['Bb1', 'Bb1', 'Bb1', 'Bb1', 'Eb1', 'Eb1', 'Eb1', 'Eb1'], vel=100)
    cb_notes += e8(['A1', 'A1', 'A1', 'A1', 'D1', 'D1', 'D1', 'D1'], vel=105)
    cb_notes += [('G1', 2.0, 110), ('D1', 2.0, 105)]
    # Compases 9-12: Tempestad (Golpes rápidos staccato tutti)
    for _ in range(2):
        cb_notes += [('G1', 0.5, 115), ('G1', 0.5, 105), ('G1', 1.0, 110), ('C2', 0.5, 115), ('C2', 0.5, 105), ('D1', 1.0, 115)]
    cb_notes += e8(['Eb1', 'Eb1', 'D1', 'D1', 'C1', 'C1', 'D1', 'D1'], vel=110)
    cb_notes += [('G1', 4.0, 120)]
    # Compases 13-16: Clímax y Coda virtuosa (Escala ascendente y acorde triunfal)
    cb_notes += e8(['G1', 'A1', 'Bb1', 'C2', 'D2', 'C2', 'Bb1', 'A1'], vel=110)
    cb_notes += e8(['G1', 'Bb1', 'D2', 'G2', 'D2', 'Bb1', 'G1', 'D1'], vel=115)
    cb_notes += [('G1', 1.0, 120), ('D1', 1.0, 120), ('G1', 2.0, 125)]
    cb_notes += [('G1', 4.0, 125)]

    # --- 2. VIOLONCHELOS (Contrapunto ágil y bajo continuo virtuoso) ---
    vc_notes = []
    # Compases 1-4: Arpegios de bajo continuo barroco
    for _ in range(2):
        vc_notes += s16(['G2', 'Bb2', 'D3', 'G3', 'Bb2', 'D3', 'G2', 'Bb2', 'G2', 'Bb2', 'D3', 'G3', 'Bb2', 'D3', 'G2', 'Bb2'], vel=95)
        vc_notes += s16(['Eb2', 'G2', 'Bb2', 'Eb3', 'G2', 'Bb2', 'Eb2', 'G2', 'D2', 'F#2', 'A2', 'D3', 'F#2', 'A2', 'D2', 'F#2'], vel=95)
    # Compases 5-8: Círculo de quintas
    vc_notes += s16(['C2', 'Eb2', 'G2', 'C3', 'Eb2', 'G2', 'C2', 'Eb2', 'F2', 'A2', 'C3', 'F3', 'A2', 'C3', 'F2', 'A2'], vel=95)
    vc_notes += s16(['Bb1', 'D2', 'F2', 'Bb2', 'D2', 'F2', 'Bb1', 'D2', 'Eb2', 'G2', 'Bb2', 'Eb3', 'G2', 'Bb2', 'Eb2', 'G2'], vel=95)
    vc_notes += s16(['A1', 'C#2', 'E2', 'A2', 'C#2', 'E2', 'A1', 'C#2', 'D2', 'F#2', 'A2', 'D3', 'F#2', 'A2', 'D2', 'F#2'], vel=100)
    vc_notes += [('G2', 2.0, 105), ('F#2', 2.0, 100)]
    # Compases 9-12: Tempestad tutti
    for _ in range(2):
        vc_notes += [('G2', 0.5, 110), ('Bb2', 0.5, 105), ('G2', 1.0, 110), ('C3', 0.5, 110), ('Eb3', 0.5, 105), ('D3', 1.0, 115)]
    vc_notes += s16(['Eb3', 'D3', 'C3', 'Bb2', 'A2', 'G2', 'F#2', 'E2', 'D2', 'E2', 'F#2', 'G2', 'A2', 'Bb2', 'C3', 'D3'], vel=110)
    vc_notes += [('G2', 4.0, 115)]
    # Compases 13-16: Coda
    vc_notes += s16(['G2', 'Bb2', 'D3', 'G3', 'A2', 'C3', 'D3', 'F#3', 'Bb2', 'D3', 'G3', 'Bb3', 'C3', 'Eb3', 'G3', 'C4'], vel=110)
    vc_notes += s16(['D3', 'F#3', 'A3', 'D4', 'C3', 'D3', 'Bb2', 'D3', 'A2', 'D3', 'G2', 'D3', 'F#2', 'D3', 'D2', 'F#2'], vel=115)
    vc_notes += [('G2', 1.0, 120), ('D2', 1.0, 115), ('G2', 2.0, 120)]
    vc_notes += [('G2', 4.0, 125)]

    # --- 3. VIOLAS (Contratiempo rápido y textura armónica viva) ---
    va_notes = []
    # Compases 1-4
    for _ in range(2):
        va_notes += e8(['D3', 'G3', 'D3', 'G3', 'D3', 'G3', 'D3', 'G3'], vel=90)
        va_notes += e8(['Bb3', 'Eb4', 'Bb3', 'Eb4', 'A3', 'D4', 'A3', 'D4'], vel=90)
    # Compases 5-8: Círculo armónico
    va_notes += e8(['G3', 'C4', 'G3', 'C4', 'A3', 'F4', 'A3', 'F4'], vel=90)
    va_notes += e8(['F3', 'Bb3', 'F3', 'Bb3', 'G3', 'Eb4', 'G3', 'Eb4'], vel=90)
    va_notes += e8(['E3', 'A3', 'E3', 'A3', 'F#3', 'D4', 'F#3', 'D4'], vel=95)
    va_notes += [('Bb3', 2.0, 100), ('A3', 2.0, 95)]
    # Compases 9-12: Tempestad
    for _ in range(2):
        va_notes += [('D4', 0.5, 105), ('G4', 0.5, 100), ('D4', 1.0, 105), ('Eb4', 0.5, 105), ('G4', 0.5, 100), ('F#4', 1.0, 110)]
    va_notes += s16(['G3', 'A3', 'Bb3', 'C4', 'D4', 'Eb4', 'F4', 'G4', 'F#4', 'E4', 'D4', 'C4', 'Bb3', 'A3', 'G3', 'F#3'], vel=105)
    va_notes += [('D4', 4.0, 115)]
    # Compases 13-16
    va_notes += s16(['D4', 'G4', 'Bb4', 'G4', 'F#4', 'A4', 'D4', 'F#4', 'G4', 'Bb4', 'D4', 'G4', 'G4', 'C4', 'Eb4', 'G4'], vel=105)
    va_notes += s16(['F#4', 'A4', 'D4', 'A4', 'E4', 'G4', 'C#4', 'G4', 'D4', 'F#4', 'C4', 'F#4', 'B3', 'D4', 'A3', 'C#4'], vel=110)
    va_notes += [('B3', 1.0, 115), ('A3', 1.0, 115), ('B3', 2.0, 120)]
    va_notes += [('B3', 4.0, 120)]

    # --- 4. VIOLINES II (Contracanto enérgico en terceras y diálogo) ---
    v2_notes = []
    # Compases 1-4: Espera 1 compás y entra en bariolage
    v2_notes += [('G4', 4.0, 75)]
    v2_notes += s16(['G4', 'D4', 'Bb4', 'D4', 'G4', 'D4', 'Bb4', 'D4', 'F#4', 'D4', 'A4', 'D4', 'F#4', 'D4', 'A4', 'D4'], vel=95)
    v2_notes += s16(['G4', 'D4', 'Bb4', 'D4', 'G4', 'D4', 'Bb4', 'D4', 'G4', 'D4', 'Bb4', 'D4', 'G4', 'D4', 'Bb4', 'D4'], vel=95)
    v2_notes += s16(['G4', 'Eb4', 'C5', 'Eb4', 'G4', 'Eb4', 'C5', 'Eb4', 'F#4', 'D4', 'A4', 'D4', 'F#4', 'D4', 'A4', 'D4'], vel=100)
    # Compases 5-8: Diálogo con Violín I
    v2_notes += s16(['Eb4', 'G4', 'C5', 'G4', 'Eb4', 'G4', 'C5', 'G4', 'F4', 'A4', 'C5', 'A4', 'F4', 'A4', 'C5', 'A4'], vel=95)
    v2_notes += s16(['D4', 'F4', 'Bb4', 'F4', 'D4', 'F4', 'Bb4', 'F4', 'Eb4', 'G4', 'Bb4', 'G4', 'Eb4', 'G4', 'Bb4', 'G4'], vel=95)
    v2_notes += s16(['C#4', 'E4', 'A4', 'E4', 'C#4', 'E4', 'A4', 'E4', 'D4', 'F#4', 'A4', 'F#4', 'D4', 'F#4', 'A4', 'F#4'], vel=100)
    v2_notes += [('G4', 2.0, 105), ('F#4', 2.0, 105)]
    # Compases 9-12: Tempestad
    for _ in range(2):
        v2_notes += [('Bb4', 0.5, 110), ('D5', 0.5, 105), ('Bb4', 1.0, 110), ('C5', 0.5, 110), ('Eb5', 0.5, 105), ('A4', 1.0, 115)]
    v2_notes += s16(['Bb4', 'A4', 'G4', 'F4', 'Eb4', 'D4', 'C4', 'Bb3', 'A3', 'G3', 'F#3', 'E3', 'D4', 'F#4', 'A4', 'C5'], vel=110)
    v2_notes += [('Bb4', 4.0, 120)]
    # Compases 13-16: Coda brillante
    v2_notes += s16(['Bb4', 'D5', 'G5', 'D5', 'A4', 'D5', 'F#5', 'D5', 'G4', 'D5', 'Bb4', 'D5', 'C5', 'Eb5', 'G5', 'Eb5'], vel=115)
    v2_notes += s16(['A4', 'D5', 'F#5', 'D5', 'Bb4', 'D5', 'G5', 'D5', 'A4', 'D5', 'F#5', 'D5', 'G4', 'D5', 'A4', 'D5'], vel=120)
    v2_notes += [('G4', 1.0, 120), ('F#4', 1.0, 120), ('G4', 2.0, 125)]
    v2_notes += [('G4', 4.0, 125)]

    # --- 5. VIOLÍN I (Solista Virtuoso — Estilo Las Cuatro Estaciones) ---
    v1_notes = []
    # Compases 1-4: Entrada de furia virtuosa en semicorcheas
    v1_notes += s16(['G5', 'D5', 'Bb4', 'D5', 'G5', 'D5', 'Bb4', 'D5', 'G5', 'D5', 'Bb4', 'D5', 'G5', 'D5', 'Bb4', 'D5'], vel=110)
    v1_notes += s16(['Bb5', 'G5', 'Eb5', 'G5', 'Bb5', 'G5', 'Eb5', 'G5', 'A5', 'F#5', 'D5', 'F#5', 'A5', 'F#5', 'D5', 'F#5'], vel=115)
    v1_notes += s16(['G5', 'D5', 'Bb4', 'D5', 'G5', 'D5', 'Bb4', 'D5', 'Bb5', 'G5', 'D5', 'G5', 'D6', 'Bb5', 'G5', 'D5'], vel=115)
    v1_notes += s16(['C6', 'G5', 'Eb5', 'G5', 'C6', 'G5', 'Eb5', 'G5', 'A5', 'F#5', 'D5', 'F#5', 'D6', 'A5', 'F#5', 'D5'], vel=120)
    # Compases 5-8: Tema virtuoso de cascada (Vivaldi storm)
    v1_notes += s16(['G5', 'Eb5', 'C5', 'Eb5', 'G5', 'C6', 'Eb6', 'C6', 'A5', 'F5', 'C5', 'F5', 'A5', 'C6', 'F6', 'C6'], vel=115)
    v1_notes += s16(['F5', 'D5', 'Bb4', 'D5', 'F5', 'Bb5', 'D6', 'Bb5', 'G5', 'Eb5', 'Bb4', 'Eb5', 'G5', 'Bb5', 'Eb6', 'Bb5'], vel=115)
    v1_notes += s16(['E5', 'C#5', 'A4', 'C#5', 'E5', 'A5', 'C#6', 'A5', 'F#5', 'D5', 'A4', 'D5', 'F#5', 'A5', 'D6', 'A5'], vel=120)
    v1_notes += [('Bb5', 1.0, 120), ('A5', 1.0, 115), ('G5', 1.0, 110), ('F#5', 1.0, 115)]
    # Compases 9-12: Tempestad con llamadas de relámpago (Tutti Fortissimo)
    for _ in range(2):
        v1_notes += [('G5', 0.5, 125), ('D6', 0.5, 120), ('G5', 1.0, 125), ('C6', 0.5, 125), ('Eb6', 0.5, 120), ('D6', 1.0, 127)]
    v1_notes += s16(['Eb6', 'D6', 'C6', 'Bb5', 'A5', 'G5', 'F#5', 'E5', 'D5', 'C5', 'Bb4', 'A4', 'G4', 'A4', 'Bb4', 'C5'], vel=125)
    v1_notes += [('D5', 4.0, 125)]
    # Compases 13-16: Cadencia Final Virtuosa (Bariolage acelerado y resolución triunfal en Sol Mayor)
    v1_notes += s16(['G5', 'D5', 'Bb5', 'D5', 'A5', 'D5', 'C6', 'D5', 'Bb5', 'D5', 'D6', 'D5', 'C6', 'Eb5', 'Eb6', 'Eb5'], vel=125)
    v1_notes += s16(['D6', 'D5', 'C6', 'D5', 'Bb5', 'D5', 'A5', 'D5', 'G5', 'D5', 'F#5', 'D5', 'G5', 'D5', 'A5', 'D5'], vel=127)
    v1_notes += [('B5', 1.0, 127), ('A5', 1.0, 120), ('G5', 2.0, 127)]
    # Gran acorde final radiante en Sol Mayor (Tierce de Picardie viva)
    v1_notes += [('B5', 4.0, 127)]

    parts = {
        "Violin I (Solo/Tutti)": {"instrument": "violin", "notes": v1_notes, "pan": -0.38, "gm_program": 40},
        "Violin II": {"instrument": "violin", "notes": v2_notes, "pan": -0.16, "gm_program": 40},
        "Viola": {"instrument": "viola", "notes": va_notes, "pan": 0.02, "gm_program": 41},
        "Violoncello": {"instrument": "cello", "notes": vc_notes, "pan": 0.22, "gm_program": 42},
        "Double Bass": {"instrument": "contrabass", "notes": cb_notes, "pan": 0.38, "gm_program": 43}
    }

    return parts, bpm, beat_dur

def export_midi_file(parts, bpm, filename):
    mid = MidiFile(type=1)
    ticks_per_beat = 480
    mid.ticks_per_beat = ticks_per_beat

    tempo_track = MidiTrack()
    mid.tracks.append(tempo_track)
    tempo_track.append(MetaMessage('track_name', name='Atypu: Tempestad Barroca (Presto)'))
    tempo_track.append(MetaMessage('set_tempo', tempo=mido.bpm2tempo(bpm)))
    tempo_track.append(MetaMessage('time_signature', numerator=4, denominator=4))
    tempo_track.append(MetaMessage('end_of_track'))

    for ch, (name, p_data) in enumerate(parts.items()):
        track = MidiTrack()
        mid.tracks.append(track)
        track.append(MetaMessage('track_name', name=name))
        track.append(Message('program_change', program=p_data['gm_program'], channel=ch, time=0))
        
        pan_val = int(np.clip((p_data['pan'] + 1.0) / 2.0 * 127, 0, 127))
        track.append(Message('control_change', control=10, value=pan_val, channel=ch, time=0))

        for note_name, dur_beats, vel in p_data['notes']:
            midi_num = parse_note(note_name)
            dur_ticks = int(dur_beats * ticks_per_beat)
            track.append(Message('note_on', note=midi_num, velocity=vel, channel=ch, time=0))
            track.append(Message('note_off', note=midi_num, velocity=0, channel=ch, time=dur_ticks))

        track.append(MetaMessage('end_of_track'))

    mid.save(filename)

def synthesize_vivaldi_ensemble(parts, beat_dur, synth, output_wav):
    max_duration = max(sum(n[1] for n in p['notes']) * beat_dur for p in parts.values())
    total_samples = int((max_duration + 2.5) * SAMPLE_RATE)
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

        stereo_mix[:, 0] += inst_track * left_gain
        stereo_mix[:, 1] += inst_track * right_gain

    # Normalización con pegada y presencia
    peak = np.max(np.abs(stereo_mix))
    if peak > 0:
        stereo_mix = (stereo_mix / peak) * 0.88

    int_audio = (stereo_mix * 32767).astype(np.int16)
    scipy.io.wavfile.write(output_wav, SAMPLE_RATE, int_audio)

def master_vivaldi_sound(input_wav, output_mp3):
    import subprocess
    # Ecualización brillante (air boost a 4.5kHz, graves limpios) + reverb barroca viva (reflexiones cortas y rápidas)
    filter_chain = (
        "equalizer=f=3800:t=q:w=1.2:g=3.5,"
        "equalizer=f=6000:t=q:w=1.5:g=2.5,"
        "aecho=0.85:0.75:25|50|85:0.35|0.22|0.12,"
        "loudnorm=I=-15:TP=-1.0:LRA=9"
    )
    cmd = [
        "ffmpeg", "-y",
        "-i", input_wav,
        "-af", filter_chain,
        "-c:a", "libmp3lame",
        "-b:a", "320k",
        output_mp3
    ]
    subprocess.run(cmd, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)

def main():
    print("=" * 65)
    print("   ENSAMBLE CURUPAYTY — OBRA VIRTUOSA DE CUERDAS")
    print("   'Atypu: Tempestad Barroca' (Estilo Vivaldi - Cuatro Estaciones)")
    print("   Tempo: 138 BPM Presto con Fuoco | Tonalidad: Sol menor")
    print("=" * 65)

    os.makedirs("output", exist_ok=True)
    os.makedirs("assets/audio", exist_ok=True)

    parts, bpm, beat_dur = create_vivaldi_score()

    # 1. Exportar MIDI
    midi_path = "output/curupayty_tempestad_vivaldi.mid"
    print("🎼 Generando archivo MIDI virtuoso para partituras...")
    export_midi_file(parts, bpm, midi_path)
    print(f"   ✅ MIDI guardado: {os.path.abspath(midi_path)}")

    # 2. Sintetizar cuerdas ágiles y vivas
    print("🎻 Sintetizando cuerdas con ataque spiccato y brillo armónico...")
    synth = VirtuosoStringSynthesizer(sample_rate=SAMPLE_RATE)
    raw_wav = "output/curupayty_tempestad_raw.wav"
    synthesize_vivaldi_ensemble(parts, beat_dur, synth, raw_wav)

    # 3. Masterización brillante
    final_mp3 = "output/curupayty_tempestad_vivaldi.mp3"
    print("🏛️  Aplicando acústica barroca viva y masterización a 320 kbps...")
    master_vivaldi_sound(raw_wav, final_mp3)

    if os.path.exists(raw_wav):
        os.remove(raw_wav)

    # Copiar a assets/audio
    import shutil
    shutil.copyfile(final_mp3, "assets/audio/curupayty_tempestad_vivaldi.mp3")

    print("=" * 65)
    print("⚡ OBRA VIVALDIANA COMPLETADA CON ÉXITO:")
    print(f"   🎧 Audio Master:   {os.path.abspath(final_mp3)}")
    print(f"   🎼 Partitura MIDI: {os.path.abspath(midi_path)}")
    print("=" * 65)

if __name__ == "__main__":
    main()
