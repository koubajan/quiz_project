<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // 1. Algoritmické myšlení
        $q1 = Question::create([
            'text' => "Co se stane po vykonání algoritmu?\n\nx = 0\nopakuj 3×:\n  x = x + 2",
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'section' => 'Algoritmické myšlení'
        ]);
        $q1->answers()->createMany([
            ['text' => '3', 'is_correct' => false],
            ['text' => '4', 'is_correct' => false],
            ['text' => '6', 'is_correct' => true],
            ['text' => '8', 'is_correct' => false],
        ]);

        // 2. Smyčky & podmínky
        $q2 = Question::create([
            'text' => "Kolikrát se rozsvítí LED?\n\nopakuj 5×:\n  pokud (náhodné číslo > 7)\n    LED ON",
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'section' => 'Smyčky & podmínky'
        ]);
        $q2->answers()->createMany([
            ['text' => 'Vždy 5x', 'is_correct' => false],
            ['text' => 'Nikdy', 'is_correct' => false],
            ['text' => '0 až 5x podle náhody', 'is_correct' => true],
            ['text' => 'Přesně 2.5x', 'is_correct' => false],
        ]);

        // 3. Časová složitost
        $q3 = Question::create([
            'text' => "Který algoritmus je rychlejší pro hodně dat?",
            'type' => 'multiple_choice',
            'difficulty' => 'hard',
            'section' => 'Časová složitost'
        ]);
        $q3->answers()->createMany([
            ['text' => 'Projdu seznam jednou', 'is_correct' => true],
            ['text' => 'Pro každý prvek projdu celý seznam', 'is_correct' => false],
            ['text' => 'Náhodně vybírám', 'is_correct' => false],
            ['text' => 'Počítám ručně', 'is_correct' => false],
        ]);

        // 4. Hledání chyby (debug)
        $q4 = Question::create([
            'text' => "Kde je chyba v algoritmu?\n\ni = 0\nopakuj dokud i <= 10:\n  i = i + 1\n\n(Cíl: opakovat 10x)",
            'type' => 'multiple_choice',
            'difficulty' => 'medium',
            'section' => 'Hledání chyby (debug)'
        ]);
        $q4->answers()->createMany([
            ['text' => 'Algoritmus nikdy neskončí (nekonečná smyčka)', 'is_correct' => false],
            ['text' => 'Smyčka proběhne 11x místo 10x (off-by-one)', 'is_correct' => true],
            ['text' => 'Proměnná i není definována', 'is_correct' => false],
            ['text' => 'Žádná chyba', 'is_correct' => false],
        ]);

        // 5. Reprezentace dat
        $q5 = Question::create([
            'text' => "Jak je nejlépe uložit souřadnice bodu?",
            'type' => 'multiple_choice',
            'difficulty' => 'easy',
            'section' => 'Reprezentace dat'
        ]);
        $q5->answers()->createMany([
            ['text' => 'text', 'is_correct' => false],
            ['text' => 'pole', 'is_correct' => true],
            ['text' => 'obrázek', 'is_correct' => false],
            ['text' => 'barva', 'is_correct' => false],
        ]);
    }
}
