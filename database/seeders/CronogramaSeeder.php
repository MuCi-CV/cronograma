<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Section;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class CronogramaSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data as $stageOrder => $stageData) {
            $stage = Stage::create([
                'name'  => $stageData['name'],
                'year'  => $stageData['year'],
                'order' => $stageOrder + 1,
            ]);

            foreach ($stageData['sections'] as $sectionOrder => $sectionData) {
                $section = Section::create([
                    'stage_id' => $stage->id,
                    'name'     => $sectionData['name'],
                    'order'    => $sectionOrder + 1,
                ]);

                $itemOrder = 1;
                foreach ($sectionData['objetivos'] as $text) {
                    Item::create([
                        'section_id' => $section->id,
                        'type'       => 'objetivo',
                        'text'       => $text,
                        'order'      => $itemOrder++,
                        'active'     => true,
                    ]);
                }
                foreach ($sectionData['metas'] as $text) {
                    Item::create([
                        'section_id' => $section->id,
                        'type'       => 'meta',
                        'text'       => $text,
                        'order'      => $itemOrder++,
                        'active'     => true,
                    ]);
                }
            }
        }
    }

    private function getData(): array
    {
        return [
            // ETAPA 1 — 2026
            [
                'name' => 'Etapa 1 — Planificación, Definición y Propagación',
                'year' => 2026,
                'sections' => [
                    [
                        'name' => 'Marzo 2026',
                        'objetivos' => [
                            'Inicio formal del proyecto paisajístico',
                            'Definición conceptual del sistema de jardines y ecorregiones',
                            'Presentación inicial del enfoque ecosistémico',
                        ],
                        'metas' => [
                            'Primeras reuniones técnicas interdisciplinarias',
                            'Integración entre arquitectura, ecología y paisajismo',
                            'Inicio de análisis del terreno y circulación del visitante',
                        ],
                    ],
                    [
                        'name' => 'Abril 2026',
                        'objetivos' => [
                            'Conservación y rescate vegetal del terreno',
                            'Identificación de especies prioritarias',
                        ],
                        'metas' => [
                            'Traslado de árboles del terreno al Parque Caballero',
                            'Monitoreo de adaptación y supervivencia',
                            'Registro de especies existentes',
                        ],
                    ],
                    [
                        'name' => 'Mayo 2026',
                        'objetivos' => [
                            'Consolidar equipo técnico paisajístico',
                            'Avanzar en diseño frontal y narrativa vegetal',
                        ],
                        'metas' => [
                            'Incorporación de la paisajista María José "Tuti" Vargas',
                            'Continuidad de consultoría ecológica con Pepe',
                            'Desarrollo de paisajismo de acceso principal',
                            'Definición preliminar de ecorregiones y jardines temáticos',
                            'Primeros lineamientos de especies por sector',
                        ],
                    ],
                    [
                        'name' => 'Junio 2026',
                        'objetivos' => [
                            'Iniciar estrategia de propagación vegetal',
                            'Establecer total de ejemplares para los jardines del MuCi',
                        ],
                        'metas' => [
                            'Identificación de viveros aliados',
                            'Definición de especies prioritarias',
                            'Inicio de colecta de semillas y material vegetal',
                            'Primeros viajes técnicos de reconocimiento y colecta',
                            'Posibles destinos de colecta: Chaco, Pantanal, Cerrado, Humedales, Bosque Atlántico del Alto Paraná',
                        ],
                    ],
                    [
                        'name' => 'Julio 2026',
                        'objetivos' => [
                            'Fortalecer producción vegetal y banco de especies',
                        ],
                        'metas' => [
                            'Continuación de viajes de colecta',
                            'Inicio de reproducción de plantines',
                            'Propagación experimental de especies nativas',
                            'Desarrollo de protocolos de germinación y manejo',
                            'Viaje al Pantanal para colecta de frutos y semillas',
                        ],
                    ],
                    [
                        'name' => 'Agosto 2026',
                        'objetivos' => [
                            'Preparación operativa del terreno',
                        ],
                        'metas' => [
                            'Inicio de adecuación de suelos',
                            'Definición de sistemas de riego',
                            'Delimitación física de jardines y senderos',
                            'Inicio de plantaciones piloto',
                        ],
                    ],
                    [
                        'name' => 'Septiembre 2026',
                        'objetivos' => [
                            'Primeras implantaciones en terreno',
                        ],
                        'metas' => [
                            'Plantación inicial de especies estructurales',
                            'Verificación técnica con equipo de arquitectura',
                            'Ajustes de diseño según comportamiento real del sitio',
                        ],
                    ],
                    [
                        'name' => 'Octubre 2026',
                        'objetivos' => [
                            'Consolidar producción vegetal interna',
                        ],
                        'metas' => [
                            'Producción intensiva de plantines',
                            'Desarrollo del vivero operativo',
                            'Incremento de especies disponibles para implantación',
                        ],
                    ],
                    [
                        'name' => 'Noviembre 2026',
                        'objetivos' => [
                            'Expansión progresiva de jardines',
                        ],
                        'metas' => [
                            'Continuidad de plantaciones',
                            'Evaluación de adaptación de especies',
                            'Ajustes ecológicos y paisajísticos',
                        ],
                    ],
                    [
                        'name' => 'Diciembre 2026',
                        'objetivos' => [
                            'Cierre de primera etapa técnica',
                        ],
                        'metas' => [
                            'Evaluación anual del proyecto',
                            'Registro fotográfico y técnico',
                            'Proyección de expansión 2027',
                        ],
                    ],
                ],
            ],

            // ETAPA 2 — 2027
            [
                'name' => 'Etapa 2 — Implementación y Consolidación',
                'year' => 2027,
                'sections' => [
                    [
                        'name' => 'Paisajismo y jardines',
                        'objetivos' => [
                            'Consolidar ecorregiones principales',
                            'Consolidación del sistema de jardines temáticos',
                        ],
                        'metas' => [
                            'Desarrollo completo del Jardín Pantanal',
                            'Desarrollo completo del Jardín Cerrado',
                            'Desarrollo completo del Jardín Chaco',
                            'Desarrollo completo del Jardín Medicinal',
                            'Desarrollo completo del Bosque Comestible',
                        ],
                    ],
                    [
                        'name' => 'Producción vegetal',
                        'objetivos' => [
                            'Fortalecer autonomía del vivero',
                        ],
                        'metas' => [
                            'Producción propia sostenida de especies nativas',
                            'Banco de semillas y propagación permanente',
                            'Intercambio con viveros e instituciones aliadas',
                        ],
                    ],
                    [
                        'name' => 'Infraestructura verde',
                        'objetivos' => [
                            'Integrar experiencia educativa y recorrido del visitante',
                        ],
                        'metas' => [
                            'Sistemas de compostaje operativo',
                            'Señalética interpretativa',
                            'Senderos educativos',
                            'Áreas de observación y descanso',
                        ],
                    ],
                    [
                        'name' => 'Educación y ciencia',
                        'objetivos' => [
                            'Incrementar biodiversidad funcional',
                        ],
                        'metas' => [
                            'Integración de jardines con programas educativos',
                            'Actividades de ciencia al aire libre',
                            'Experiencias sensoriales e interpretativas',
                        ],
                    ],
                    [
                        'name' => 'Investigación y monitoreo',
                        'objetivos' => [
                            'Monitorear y documentar el desarrollo ecológico',
                        ],
                        'metas' => [
                            'Monitoreo ecológico de especies implantadas',
                            'Registro fenológico y adaptación vegetal',
                            'Evaluación de polinizadores y biodiversidad',
                        ],
                    ],
                ],
            ],

            // ETAPA 3 — 2028
            [
                'name' => 'Etapa 3 — Maduración y Experiencia Museográfica Viva',
                'year' => 2028,
                'sections' => [
                    [
                        'name' => 'Consolidación ecológica',
                        'objetivos' => [
                            'Consolidar el paisaje como experiencia museográfica viva',
                        ],
                        'metas' => [
                            'Estabilización de ecorregiones implantadas',
                            'Mayor cobertura vegetal y resiliencia ecológica',
                            'Incremento de fauna asociada (aves, polinizadores, insectos)',
                        ],
                    ],
                    [
                        'name' => 'Experiencia del visitante',
                        'objetivos' => [
                            'Posicionar al MuCi como referencia regional en paisajismo educativo',
                        ],
                        'metas' => [
                            'Recorridos interpretativos completamente operativos',
                            'Experiencias inmersivas en jardines',
                            'Actividades nocturnas y astronómicas vinculadas al paisaje',
                        ],
                    ],
                    [
                        'name' => 'Producción y sostenibilidad',
                        'objetivos' => [
                            'Lograr autonomía productiva y sostenibilidad del sistema',
                        ],
                        'metas' => [
                            'Vivero plenamente operativo',
                            'Compostaje integrado al mantenimiento',
                            'Reducción progresiva de dependencia externa',
                        ],
                    ],
                    [
                        'name' => 'Vinculación institucional',
                        'objetivos' => [
                            'Consolidar alianzas científicas y educativas',
                        ],
                        'metas' => [
                            'Alianzas científicas y educativas activas',
                            'Participación en redes de conservación y paisajismo',
                            'Intercambio de especies y conocimientos con instituciones aliadas',
                        ],
                    ],
                ],
            ],
        ];
    }
}
