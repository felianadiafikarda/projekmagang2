<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\PaperAuthor;
use App\Models\User;

class PaperAuthorSeeder extends Seeder
{
    public function run()
    {
        $userId = \App\Models\User::where('email', 'author1@gmail.com')->value('id');


        if (!$userId) {
            dd("TIDAK ADA USER DI DATABASE! Buat user dulu.");
        }

        $pdfFiles = [
            'papers/applsci-11-11246-v2.pdf',
            'papers/UMKM.pdf',
            'papers/2200018159-Felia Nadia Fikarda-Tugas Kapita Selekta.pdf',
            'papers/Logbook Felia.pdf',
            'papers/17659-Article Text-21153-1-2-20210518.pdf',
        ];

        $titles = [
            'K-Means-Based Nature-Inspired Metaheuristic Algorithms',
            'Business Model Canvas UMKM',
            'Analisis Tipe Data dalam Dataset Adult',
            'Logbook Manajemen Proyek Teknologi Informasi',
            'Context-Guided BERT for ABSA'
        ];

        $abstracts = [
            'Studi sistematis mengenai perkembangan algoritma K-Means berbasis metaheuristik.',
            'Analisis model bisnis UMKM berbasis Business Model Canvas.',
            'Tugas kapita selekta terkait klasifikasi menggunakan dataset Adult.',
            'Dokumentasi kegiatan pembuatan sistem informasi pengecoran logam.',
            'Penelitian mengenai peningkatan model BERT dalam Aspect-Based Sentiment Analysis.'
        ];

        $keywords = [
            'k-means, clustering, metaheuristic',
            'umkm, business model, digital',
            'adult dataset, naive bayes, classification',
            'logbook, project management, mpti',
            'bert, absa, nlp'
        ];

        for ($i = 0; $i < 5; $i++) {

            $paper = Paper::create([
                'user_id'   => $userId,
                'judul'     => $titles[$i],
                'abstrak'   => $abstracts[$i],
                'keywords'  => $keywords[$i],
                'file_path' => $pdfFiles[$i],
            ]);

            PaperAuthor::create([
                'paper_id'      => $paper->id,
                'is_primary'    => 1,
                'email'         => 'author1@gmail.com',
                'first_name'    => 'Author',
                'last_name'     => 'Test',
                'organization'  => 'Universitas Teknik Komputer',
                'country'       => 'Indonesia',
            ]);
        }
    }
}