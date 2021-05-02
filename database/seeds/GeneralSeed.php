<?php

namespace Database\Seeders;

use App\Aspecto;
use App\Caracteristica;
use App\Factor;
use App\Facultad;
use App\Indicador;
use App\Plan;
use App\Programa;
use App\Proyecto;
use App\TipoFactor;
use App\Universidad;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GeneralSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Universidad::create([
            'nombre' => 'Universidad de Investigación y Desarrollo',
            'descripcion' => 'La Universidad de Investigación y Desarrollo - UDI -, es una institución universitaria de carácter privado, joven, con alto nivel de desarrollo y forjada en beneficio de la formación del pueblo Santandereano y Colombiano.',
            'mision' => 'La Universidad de Investigación y Desarrollo –UDI-, comprometida con la calidad de la educación superior, tiene como propósito formar profesionales integrales con pensamiento universal y crítico, desde los conceptos de Hombre, Sociedad, Educación y Desarrollo,  que a través de la ciencia, la  investigación y la tecnología contribuyan en el desarrollo humano, económico y social de la región y del país; profesionales éticos y responsables, con capacidades de emprendimiento, liderazgo,  creatividad,  innovación, pasión por el trabajo inteligente; conocedores y respetuosos de los principios constitucionales, los derechos humanos, el valor de la palabra y de las personas.',
            'vision' => 'En el año 2027, la Universidad de Investigación y Desarrollo -UDI- será reconocida en el ámbito nacional e internacional por su excelencia académica, avance científico y tecnológico, sentido humanístico y social, teniendo como premisa el fortalecimiento en los campos del conocimiento, la investigación, la extensión, la internacionalización y la innovación en los procesos educativos, articulados en la formación integradora de profesionales éticos, con valores humanos, comprometidos con la construcción y el desarrollo de la sociedad colombiana.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Facultad::create([
            'nombre' => 'Ingeniería',
            'descripcion' => 'La Facultad de Ingenierías de la Universitaria de Investigación y Desarrollo –UDI-, parte de la concepción de la Ingeniería como la aplicación del conocimiento científico y tecnológico de las ciencias naturales, en el desarrollo de diseños, modelos y métodos que resuelven problemas y atiende las necesidades de la sociedad; para orientar las actividades académicas, investigativas y de extensión, incentivando el trabajo colaborativo, interdisciplinario y el aporte a la solución de problemas del entorno.',
            'id_universidad' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Programa::create([
            'nombre' => 'Ingeniería de Sistemas',
            'descripcion' => 'De acuerdo con ACOFI, los Ingenieros de Sistemas utilizan sus conocimientos, habilidades y destrezas para diagnosticar, diseñar, construir, evaluar y mantener sistemas y procesos de información con el apoyo de las tecnologías informáticas, ayudando a las organizaciones y empresas a lograr el mayor beneficio posible en su equipo, el talento humano y en los procesos, todo dentro de un marco administrativo, empresarial y humanista. Es en esta definición que se enmarca la propuesta del programa de Ingeniería de sistemas de la UDI',
            'id_facultad' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Plan::create([
            'nombre' => 'Plan de mejoramiento para Ingeniería de Sistemas',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'objetivo_general' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'objetivos_especificos' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'fecha_inicio' => Carbon::now()->format('Y-m-d'),
            'fecha_final' => '2021-06-26',
            'progreso' => 0,
            'id_programa' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Proyecto::create([
            'nombre' => 'Proyecto Asistencia',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'objetivo_general' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'objetivos_especificos' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'progreso' => 0,
            'peso' => 30,
            'id_plan' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TipoFactor::create([
            'nombre' => 'Factores Estratégicos',
            'descripcion' => 'Proporcionan directrices a los diferentes factores y son responsabilidad de los órganos de gobierno de la institución principalmente.',
            'porcentaje' => 10,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TipoFactor::create([
            'nombre' => 'Factores Misionales',
            'descripcion' => 'Determinan la razón de ser de la institución y apuntan al cumplimiento de los objetivos de la educación superior.',
            'porcentaje' => 70,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        TipoFactor::create([
            'nombre' => 'Factores de Apoyo',
            'descripcion' => 'Brindan soporte a los Factores Misionales',
            'porcentaje' => 20,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Factor::create([
            'codigo' => '1',
            'nombre' => 'Misión, Visión y Proyecto Institucional y de Programa',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'id_tipo_factor' => 2,
            'id_proyecto' => 1,
            'peso' => 20,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Caracteristica::create([
            'codigo' => '1.1',
            'nombre' => 'Misión, Visión y Proyecto Institucional.',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'id_factor' => 1,
            'peso' => 20,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Aspecto::create([
            'codigo' => '1.1.1',
            'nombre' => 'Mejoramiento de Misión',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'id_caracteristica' => 1,
            'peso' => 20,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Indicador::create([
            'codigo' => '1.1.1.1',
            'nombre' => 'Indicador de peso integral',
            'descripcion' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'id_aspecto' => 1,
            'peso' => 20,
            'progreso' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
