<?php

namespace Database\Seeders;

use App\Models\FirmanteActa;
use App\Models\Obra;
use App\Models\Pagina;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Super Administrador', 'description' => 'Acceso y control total del sistema']
        );

        $comisionRole = Role::firstOrCreate(
            ['name' => 'comision'],
            ['display_name' => 'Comisión Evaluadora', 'description' => 'Evaluación de partituras y gestión de asamblea']
        );

        $creadorRole = Role::firstOrCreate(
            ['name' => 'creador'],
            ['display_name' => 'Socio Creador / Autor', 'description' => 'Compositores e instrumentistas del ensamble']
        );

        $firmanteRole = Role::firstOrCreate(
            ['name' => 'firmante'],
            ['display_name' => 'Firmante Fundacional', 'description' => 'Aspirante adherido al acta fundacional']
        );

        // 2. Permisos
        $permissions = [
            // Firmas
            ['name' => 'firmas.ver', 'display_name' => 'Ver padrón de firmas', 'module' => 'Firmas'],
            ['name' => 'firmas.exportar', 'display_name' => 'Exportar padrón a PDF/Excel', 'module' => 'Firmas'],
            ['name' => 'firmas.gestionar', 'display_name' => 'Ratificar o revisar firmas', 'module' => 'Firmas'],
            // Roles
            ['name' => 'roles.ver', 'display_name' => 'Ver roles y permisos', 'module' => 'Roles'],
            ['name' => 'roles.gestionar', 'display_name' => 'Crear y editar roles', 'module' => 'Roles'],
            // Obras
            ['name' => 'obras.ver', 'display_name' => 'Ver catálogo de obras', 'module' => 'Obras'],
            ['name' => 'obras.crear', 'display_name' => 'Registrar obra inédita', 'module' => 'Obras'],
            ['name' => 'obras.aprobar', 'display_name' => 'Aprobar obra para catálogo', 'module' => 'Obras'],
            ['name' => 'obras.gestionar', 'display_name' => 'Gestionar licencias y partituras', 'module' => 'Obras'],
            // CMS
            ['name' => 'paginas.ver', 'display_name' => 'Ver páginas institucionales', 'module' => 'CMS'],
            ['name' => 'paginas.editar', 'display_name' => 'Editar páginas institucionales', 'module' => 'CMS'],
        ];

        foreach ($permissions as $p) {
            $perm = Permission::firstOrCreate(['name' => $p['name']], $p);
            $adminRole->permissions()->syncWithoutDetaching([$perm->id]);
        }

        // 3. Usuario Administrador Principal
        $admin = User::firstOrCreate(
            ['email' => 'admin@curupayty.com'],
            [
                'name' => 'Administrador Curupayty',
                'password' => Hash::make('curupayty2026'),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // 4. Usuario Creador de Muestra
        $creador = User::firstOrCreate(
            ['email' => 'compositor@curupayty.com'],
            [
                'name' => 'Agustín Barrios (Compositor)',
                'password' => Hash::make('curupayty2026'),
            ]
        );
        $creador->roles()->syncWithoutDetaching([$creadorRole->id]);

        // 5. Obras Inéditas en el Catálogo
        Obra::firstOrCreate(
            ['titulo' => 'Atypu: Tempestad Barroca'],
            [
                'user_id' => $creador->id,
                'subtitulo' => 'Presto con Fuoco al estilo Vivaldi',
                'genero' => 'Cuerdas Barrocas Virtuosas',
                'duracion' => '0:31',
                'ano_creacion' => 2026,
                'plantilla_instrumental' => 'Quinteto de Cuerdas (Vln 1, Vln 2, Vla, Vc, Cb)',
                'registro_dinapi' => 'PY-2026-DINAPI-0089',
                'registro_apa' => 'APA-DECL-4412',
                'registro_aie' => 'AIE-CONEXOS-998',
                'estado' => 'en_catalogo',
                'descripcion' => 'Obra rápida a 138 BPM con arpegios bariolage virtuosos y contrapunto continuo en Sol menor con resolución en Sol mayor.',
                'audio_path' => 'media/obras/curupayty_tempestad_vivaldi.mp3',
                'partitura_pdf_path' => 'media/obras/curupayty_tempestad_partitura.pdf',
                'midi_path' => 'media/obras/curupayty_tempestad_vivaldi.mid',
                'precio_licencia' => 150.00,
                'reproducciones' => 142,
            ]
        );

        Obra::firstOrCreate(
            ['titulo' => 'Curupayty: La Trinchera y el Fuego'],
            [
                'user_id' => $creador->id,
                'subtitulo' => 'Opus 1 — Himno Institucional',
                'genero' => 'Poema Sinfónico para Cuerdas',
                'duracion' => '0:43',
                'ano_creacion' => 2026,
                'plantilla_instrumental' => 'Orquesta de Cuerdas',
                'registro_dinapi' => 'PY-2026-DINAPI-0012',
                'registro_apa' => 'APA-DECL-3980',
                'registro_aie' => 'AIE-CONEXOS-741',
                'estado' => 'en_catalogo',
                'descripcion' => 'Marcha heroica y solemne en Re menor inspirada en la resistencia histórica del 22 de setiembre de 1866.',
                'audio_path' => 'media/obras/curupayty_obra_cuerdas.mp3',
                'partitura_pdf_path' => null,
                'midi_path' => 'media/obras/curupayty_cuerdas_score.mid',
                'precio_licencia' => 200.00,
                'reproducciones' => 280,
            ]
        );

        // 6. Firmantes de Demostración del Acta Fundacional
        $firmantesDemo = [
            [
                'nombre' => 'Mateo',
                'apellido' => 'Benítez Rojas',
                'cedula' => '4.852.190',
                'direccion' => 'Av. Mariscal López 1420',
                'ciudad' => 'Asunción',
                'instrumento' => 'Violonchelo',
                'sueno_musical' => 'Poder vivir de mis composiciones orquestales para cine nacional sin tener que esperar el visto bueno de ningún director vitalicio.',
            ],
            [
                'nombre' => 'Sofía',
                'apellido' => 'Alcaraz Galeano',
                'cedula' => '5.104.882',
                'direccion' => 'Calle Palma y 14 de Mayo',
                'ciudad' => 'Luque',
                'instrumento' => 'Violín I',
                'sueno_musical' => 'Estrenar mi concierto para violín y orquesta de cuerdas en un colectivo autogestionado con derechos protegidos.',
            ],
            [
                'nombre' => 'Rodrigo',
                'apellido' => 'Enciso Fleitas',
                'cedula' => '3.912.445',
                'direccion' => 'Barrio San Blas',
                'ciudad' => 'Encarnación',
                'instrumento' => 'Viola / Arreglador',
                'sueno_musical' => 'Romper el monopolio de las orquestas tradicionales y exportar partituras paraguayas al mundo entero.',
            ]
        ];

        // Trazo simulado en base64 para firmas de prueba
        $dummySignature = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="120"><path d="M 20 60 Q 80 10, 140 70 T 260 50 Q 200 110, 100 80" stroke="#e5a93c" stroke-width="3" fill="none"/></svg>');

        foreach ($firmantesDemo as $fd) {
            $userFirmante = User::firstOrCreate(
                ['email' => Str::slug($fd['nombre']) . '.' . Str::slug($fd['apellido']) . '@curupayty.com'],
                [
                    'name' => "{$fd['nombre']} {$fd['apellido']}",
                    'password' => Hash::make('curupayty2026'),
                ]
            );
            $userFirmante->roles()->syncWithoutDetaching([$firmanteRole->id]);

            FirmanteActa::firstOrCreate(
                ['cedula' => $fd['cedula']],
                array_merge($fd, [
                    'user_id' => $userFirmante->id,
                    'firma_digital' => $dummySignature,
                    'codigo_verificacion' => 'CPY-' . strtoupper(Str::random(8)),
                    'estado' => 'pendiente_asamblea',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
                ])
            );
        }

        // 7. Páginas Institucionales (CMS)
        Pagina::updateOrCreate(
            ['slug' => 'manifiesto'],
            [
                'titulo' => 'Manifiesto de Ruptura',
                'subtitulo' => 'Ensamble CURUPAYTY · Atypu — Declaración de Creación Soberana',
                'contenido' => <<<'MARKDOWN'
*Curupayty: la mayor victoria militar de la historia paraguaya (22 de setiembre de 1866). Un ejército pequeño, en trincheras, resistió y derrotó a una fuerza aliada varias veces superior en número. No es una batalla de derrota heroica — es la prueba histórica de que lo pequeño y bien plantado le puede ganar a lo grande y hegemónico.*

*Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro.*

---

## MANIFIESTO DE RUPTURA

Durante generaciones, la música académica paraguaya se organizó alrededor de una mentira cómoda: que un director es dueño de una orquesta, que los músicos son ejecutantes intercambiables, y que "tocar bien lo de otros" es el techo de una carrera. Esa mentira produjo generaciones de intérpretes brillantes que nunca se atrevieron a firmar una partitura propia.

**Eso termina acá.**

CURUPAYTY no nace para sumar un director más al escalafón. Nace para desterrar el modelo del **tendota** —así se nombra en guaraní a quien concentra en sí todo el poder, de forma permanente y sin rendir cuentas a nadie— y del músico-empleado que le debe obediencia. En su lugar, instala una comunidad de creadores que se deben respeto técnico, no sumisión jerárquica. Cada persona que integra CURUPAYTY entra sabiendo una cosa desde el primer día: **acá no venimos a interpretar el pasado. Venimos a firmar el futuro.**

Y una segunda cosa, igual de innegociable: **el talento no se regala.** CURUPAYTY no es una sociedad de conciertos benéficos que aplauden y no alimentan a nadie. Es una sociedad económica de creadores que componen, arreglan, orquestan y producen para cine, cortometraje, video y catálogo comercial — y que cobran por cada una de esas cosas, siempre, sin excepción y sin vergüenza.

---

> **CURUPAYTY no le pide a sus socios que toquen mejor lo que ya existe.**  
> Le pide que se animen a escribir lo que todavía no existe — y que cobren por hacerlo. Esa es la ruptura. Todo lo demás es estructura al servicio de esa idea.
MARKDOWN,
                'meta_descripcion' => 'Manifiesto de Ruptura del Ensamble Curupayty · Atypu.',
                'orden' => 1,
                'activa' => true,
            ]
        );

        Pagina::updateOrCreate(
            ['slug' => 'estatuto'],
            [
                'titulo' => 'Estatuto Fundacional y Modelo Económico',
                'subtitulo' => 'Normas de Organización Comunitaria y Retribución del Colectivo',
                'contenido' => <<<'MARKDOWN'
*Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro.*

---

## 1. IDENTIDAD Y NATURALEZA

**1.1 Qué es CURUPAYTY**  
Un colectivo orquestal autogestionado de creadores-intérpretes. No un elenco que ejecuta repertorio ajeno: una comunidad que produce, en exclusiva, obra inédita de sus propios miembros.

**1.2 La condición de socio es la condición de autor**  
No hay categoría de "músico raso". Todo integrante activo tiene derecho pleno a presentar obra propia ante la comisión interna. Nadie de afuera de la vida comunitaria del proyecto puede proponer repertorio.

**1.3 Autenticidad, no ortodoxia**  
El único filtro real es la autoría verificable y el carácter inédito de la obra. CURUPAYTY no exige un estilo, una escuela o una estética. Exige que la obra sea genuinamente de quien la firma. Puede rearmonizarse, revisarse y reinterpretarse en colectivo — pero nace de un socio.

**1.4 La libertad individual no se toca**  
Fuera de CURUPAYTY, cada socio hace de su vida musical lo que quiera: tocar Mozart, Beethoven, Bach, Mangoré, integrar otras orquestas, dar recitales de repertorio clásico. Eso no le compete al ensamble. **Pero dentro de CURUPAYTY, la regla es absoluta: solo se ejecuta obra inédita de autoría de los socios.** Esa frontera es la que le da sentido a todo lo demás.

---

## 2. PRINCIPIO ECONÓMICO CENTRAL: TODO SE REMUNERA

Este es el artículo fundacional que distingue a CURUPAYTY de cualquier orquesta o coral tradicional, y va antes que cualquier otra cosa porque es el que hay que entender primero:

- CURUPAYTY **no existe** para preparar conciertos que no generan ingreso a sus socios.
- CURUPAYTY existe para que sus miembros **ganen dinero con su talento**: componiendo, arreglando, orquestando, produciendo música para cine, cortometraje, publicidad y video, y vendiendo su catálogo.
- **Nada es gratuito.** Ninguna partitura, ninguna grabación, ningún arreglo sale del colectivo sin una contraprestación económica definida.
- Este principio se explica a cada aspirante desde el primer contacto, sin letra chica: quien entra a CURUPAYTY entra a una sociedad de creadores que producen para vivir de eso, no a un pasatiempo cultural.

---

## 3. DESTIERRO DEL TENDOTA: EL PROYECTO COMO UNIDAD DE PODER

**3.1 Qué es un tendota, y por qué se destierra**  
En guaraní, *tendota* nombra a quien concentra en sí todo el poder de manera permanente — el que manda porque manda, sin necesidad de justificarlo ni de compartirlo nunca. Ese es el modelo que CURUPAYTY destierra: no la idea de liderazgo en sí, sino la idea de un liderazgo dueño, vitalicio y sin rendición de cuentas. Aquí no se abole el mando — se abole que alguien lo tenga para siempre.

**3.2 El Proyecto como unidad básica de trabajo (modelo Pixar)**  
En lugar de un tendota permanente, CURUPAYTY organiza su vida creativa en **Proyectos**. Un socio puede presentar un Proyecto para, por ejemplo:
- Arreglar u orquestar una obra propia.
- Organizar un concierto construido enteramente sobre su propio repertorio.
- Producir y grabar una obra para el catálogo.
- Componer música para cine, cortometraje o video.

**3.3 Durante el Proyecto, quien lo presenta es líder absoluto de esa obra**  
Igual que un director de Pixar sobre su propia película: mientras dura el Proyecto, la última palabra artística sobre esa obra específica es de quien la propuso. Puede dirigir su propia obra, decidir su forma final, y su criterio artístico no se somete a votación — aunque sí pasa por el Braintrust (Sección 4), que aconseja pero nunca impone.

**3.4 Liderazgo con soporte total, no liderazgo en soledad**  
Ese liderazgo no significa quedarse solo. Al contrario: durante el Proyecto, el líder tiene **derecho** a recibir soporte pleno de la comunidad y asistencia constante mientras dure el proceso — igual que un director de Pixar cuenta con todo el estudio detrás suyo.

**3.5 El sistema de gestión organiza el Proyecto de punta a punta**  
El sistema de gestión de CURUPAYTY asigna roles específicos a cada miembro disponible para ese Proyecto: quién arma las particelas, quién coordina ensayos, quién gestiona difusión, quién graba, quién produce. Quien lidera el Proyecto no tiene que mendigar colaboración puerta por puerta: la estructura se la organiza.

**3.6 El poder es rotativo, nunca acumulativo**  
Terminado el Proyecto, termina también ese liderazgo puntual. El mismo socio puede liderar su propio Proyecto hoy, y estar asignado a un rol de apoyo en el Proyecto de otro compañero mañana. Nadie es tendota de todos los proyectos — todos son, por turno, líderes del suyo.

**3.7 Conciencia de derechos**  
Parte del trabajo fundacional de CURUPAYTY es pedagógico: cada socio debe entender, desde el ingreso, que tiene derecho a firmar, a cobrar, a liderar su propio Proyecto y a decidir sobre su obra. El statu quo que hay que romper no es solo institucional — es mental. Muchos músicos brillantes nunca se animaron a componer porque nadie les dijo que podían. CURUPAYTY existe, en parte, para decírselo.

---

## 4. BRAINTRUST CURUPAYTY: RETROALIMENTACIÓN SIN EGO NI JERARQUÍA

Adoptado del modelo de retroalimentación de Pixar, adaptado a la vida orquestal:

- **La crítica es sobre la obra, nunca sobre la persona.** Se discuten problemas de balance, densidad, textura o dificultad de montaje — nunca el prestigio de quien la escribió.
- **El colectivo sugiere, el autor decide.** Nadie impone un cambio; se ofrecen alternativas de instrumentación y observaciones de atril, y el creador conserva la última palabra artística.
- **Iteración viva:** lecturas de prueba, ajuste de particelas, nuevos ensayos, hasta que la obra esté lista para su producción final.

---

## 5. PEDAGOGÍA COLECTIVA: EL QUE SABE, ENSEÑA

Nadie privatiza el conocimiento como símbolo de estatus. Talleres internos permanentes en:

- Orquestación y escritura instrumental (rangos, texturas, combinaciones tímbricas)
- Arreglo y rearmonización (armonía moderna, contrapunto, conducción de voces)
- Interpretación y práctica de atril (articulación, arcada, ritmos contemporáneos)
- Dirección orquestal (técnica de batuta y comunicación gestual para quien quiera dirigir su propia obra)

---

## 6. FORMALIZACIÓN LEGAL: QUE CADA OBRA COBRE LO SUYO

Es requisito, no sugerencia, que todo socio pase por la instrucción formal para:

- **Registrar** sus obras ante la **DINAPI** (Dirección Nacional de Propiedad Intelectual).
- **Afiliarse** a la **APA** (Asociación Paraguaya de Autores) para declarar la autoría de sus composiciones.
- **Afiliarse** a **AIE** (entidad de artistas intérpretes o ejecutantes) para cobrar derechos conexos por su interpretación.

Sin este paso, la obra no genera ingreso real fuera del ensamble. Por eso CURUPAYTY lo trata como parte obligatoria del proceso de creación, no como trámite opcional.

---

## 7. DISTRIBUCIÓN GLOBAL Y TIENDA DIGITAL

**7.1 Streaming**  
Toda producción grabada de CURUPAYTY se sube a plataformas globales: Spotify, Apple Music y equivalentes, generando regalías por reproducción para autores e intérpretes.

**7.2 Tienda oficial de partituras**  
El portal web de CURUPAYTY incluye una tienda donde se vende, sin excepción, todo el catálogo:

- Partituras generales (full score) y particelas digitales imprimibles.
- Álbumes y pistas en alta resolución.
- Licencias de uso para cine, publicidad y contenido audiovisual.

**Ninguna partitura sale gratis del ensamble.** Cada descarga es una venta.

**7.3 Seguridad del material**  
- Módulo exclusivo de carga para partituras maestras y maquetas, con cifrado en tránsito y en reposo, y autenticación de dos factores.
- Marcado de agua dinámico (nombre, fecha, atril) en cada descarga de particela para trazabilidad.
- Acceso por roles y enlaces con expiración programada.

---

## 8. ADMISIÓN: NO ES SOLO UNA AUDICIÓN

El ingreso exige, además de nivel instrumental, la aprobación de una evaluación sobre la filosofía del colectivo:

- Comprensión real de que no hay jefes ni empleados, sino creadores corresponsables.
- Apertura genuina a enseñar, aprender y recibir crítica franca bajo la dinámica de Braintrust.
- Compromiso ético con la creación inédita: el trabajo del conjunto se consagra a obra propia, nunca a repertorio ajeno.
- Aceptación explícita del principio económico central: se está entrando a una sociedad para generar ingresos con talento propio, no a un espacio de conciertos sin retorno.

---

## 9. MODELO ECONÓMICO: REPARTO DE INGRESOS

Los ingresos por venta de partituras, descargas, streaming y licencias se distribuyen así:

| Destino del Fondo | % Asignado |
|:---|:---:|
| **Autor de la obra vendida o licenciada** | **40%** |
| **Fondo de intérpretes que grabaron la obra** | **25%** |
| **Fondo de estímulo a nuevas producciones** | **10%** |
| **Mantenimiento operativo (servidores, seguridad, pasarelas de pago)** | **25%** |

Este reparto se revisa y ratifica en asamblea de socios; ningún porcentaje se modifica sin consenso colectivo.

---

## 10. FINANCIAMIENTO EXTERNO SIN INJERENCIA

- Se aceptan auspicios, subsidios y donaciones **solo** si no imponen condición alguna.
- Ningún aporte externo decide qué obra se toca, quién dirige o quién estrena.
- Ningún patrocinio puede forzar al ensamble a tocar repertorio ajeno a su identidad de creación inédita.
- Las cuentas de fondos externos se auditan entre todos los socios, con transparencia total.

---

## 11. LA COMISIÓN NO ES UN TRIBUNAL

La comisión de evaluación de partituras rota entre los propios socios y no censura estilos. Verifica autoría y condición inédita, revisa si la edición es montable con la plantilla disponible, y —si hace falta— coordina tutoría entre pares para pulir una obra antes del ensayo.

---

### Cierre

**CURUPAYTY no le pide a sus socios que toquen mejor lo que ya existe.** Le pide que se animen a escribir lo que todavía no existe — y que cobren por hacerlo. Esa es la ruptura. Todo lo demás es estructura al servicio de esa idea.
MARKDOWN,
                'meta_descripcion' => 'Estatuto fundacional, principios comunitarios y modelo económico 40/25/10/25.',
                'orden' => 2,
                'activa' => true,
            ]
        );

        Pagina::updateOrCreate(
            ['slug' => 'asamblea'],
            [
                'titulo' => 'Calendario de la Asamblea Fundacional',
                'subtitulo' => 'Convocatoria Oficial a los Socios Firmantes del Acta',
                'contenido' => <<<'MARKDOWN'
La Asamblea General Constitutiva de **Ensamble Curupayty · Atypu** se llevará a cabo con todos los firmantes registrados que hayan rubricado el acta digital táctil.

---

### Orden del Día de la Asamblea:

1. **Lectura y Ratificación Solemne del Manifiesto de Ruptura y del Estatuto Fundacional.**
2. **Elección de Comisiones Rotativas:** Comisión de Braintrust y tutoría de partituras, comisión técnica de grabación y comisiones de gestión audiovisual.
3. **Aprobación del Primer Catálogo de Obras Inéditas** para cine, cortometrajes, series y plataformas de streaming.
4. **Fijación del Calendario Oficial de Ensayos y Grabaciones de Estudio.**
5. **Acreditación de Membresías y Asignación de Roles de Producción.**

> *"Curupayty es la actitud. Atypu es la forma en que se organiza esa actitud puertas adentro."*
MARKDOWN,
                'meta_descripcion' => 'Fechas y orden del día de la Asamblea Fundacional de Curupayty · Atypu.',
                'orden' => 3,
                'activa' => true,
            ]
        );
    }
}
