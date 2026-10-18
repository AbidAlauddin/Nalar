<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SmaLearningMaterialSeeder extends Seeder
{
    /**
     * Seed SMA learning material categories and initial SMA learning materials idempotently.
     */
    public function run(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->orderBy('id')->first();
        $teacher1 = $this->resolveTeacher('teacher01@nalar.com', 'Nalar Teacher 01');
        $teacher2 = $this->resolveTeacher('teacher02@nalar.com', 'Nalar Teacher 02');

        $categoriesData = [
            [
                'name' => 'Matematika',
                'slug' => 'matematika-sma',
                'description' => 'Pembelajaran konsep aljabar, fungsi, geometri, trigonometri, statistika, dan kalkulus dasar jenjang SMA.',
            ],
            [
                'name' => 'Fisika',
                'slug' => 'fisika-sma',
                'description' => 'Besaran fisika, mekanika gerak, fluida, termodinamika, gelombang, dan kelistrikan jenjang SMA.',
            ],
            [
                'name' => 'Kimia',
                'slug' => 'kimia-sma',
                'description' => 'Struktur atom, sistem periodik, ikatan kimia, stoikiometri, laju reaksi, dan kesetimbangan kimia SMA.',
            ],
            [
                'name' => 'Biologi',
                'slug' => 'biologi-sma',
                'description' => 'Keanekaragaman hayati, biologi sel, fisiologi jaringan dan organ, metabolisme, serta genetika SMA.',
            ],
            [
                'name' => 'Bahasa Indonesia',
                'slug' => 'bahasa-indonesia-sma',
                'description' => 'Teks laporan hasil observasi, eksposisi, argumentasi, karya tulis ilmiah, dan apresiasi sastra Indonesia SMA.',
            ],
            [
                'name' => 'Bahasa Inggris',
                'slug' => 'bahasa-inggris-sma',
                'description' => 'Pemahaman bacaan akademik, analytical exposition, explanation text, dan komunikasi tertulis jenjang SMA.',
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi-sma',
                'description' => 'Prinsip dasar ekonomi, keseimbangan pasar, pendapatan nasional, kebijakan fiskal-moneter, dan akuntansi dasar SMA.',
            ],
            [
                'name' => 'Sejarah',
                'slug' => 'sejarah-sma',
                'description' => 'Konsep berpikir diakronis dan sinkronis, pergerakan nasional, proklamasi kemerdekaan, dan sejarah Indonesia modern.',
            ],
            [
                'name' => 'Informatika',
                'slug' => 'informatika-sma',
                'description' => 'Berpikir komputasional, algoritma dan pemrograman, struktur data dasar, serta literasi sistem digital SMA.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $item) {
            $category = MaterialCategory::firstOrNew(['slug' => $item['slug']]);
            $category->fill([
                'user_id' => $category->user_id ?? $superAdmin?->id,
                'name' => $item['name'],
                'education_level' => MaterialCategory::LEVEL_SMA,
                'description' => $item['description'],
                'is_active' => true,
            ]);
            $category->save();

            $categories[$item['slug']] = $category;
        }

        $materialsData = [
            [
                'category_slug' => 'matematika-sma',
                'author' => $teacher1,
                'title' => 'Eksponen dan Logaritma dalam Pemodelan Pertumbuhan',
                'slug' => 'eksponen-dan-logaritma-dalam-pemodelan-pertumbuhan',
                'grade_level' => Material::GRADE_10,
                'estimated_minutes' => 20,
                'image_path' => '/images/materials/eksponen-logaritma.svg',
                'image_caption' => 'Grafik perbandingan kurva fungsi eksponen y = a^x dan inversnya fungsi logaritma y = ^a log x terhadap garis simetri y = x.',
                'description' => 'Memahami sifat-sifat bilangan berpangkat (eksponen), hubungan inversnya dengan logaritma, serta penerapannya pada pertumbuhan populasi dan peluruhan.',
                'content' => <<<'TEXT'
1. Pengertian Eksponen
Eksponen adalah bentuk perkalian berulang dari suatu bilangan pokok (basis) sebanyak pangkat yang ditentukan. Jika a adalah bilangan real dan n adalah bilangan bulat positif, maka:
a^n = a × a × a × ... × a (sebanyak n faktor)
dengan a disebut bilangan pokok (basis) dan n disebut pangkat (eksponen).

2. Sifat-Sifat Utama Eksponen
Untuk a > 0, b > 0, serta m dan n bilangan real, berlaku sifat-sifat berikut:
- Perkalian basis sama: a^m × a^n = a^(m + n)
- Pembagian basis sama: a^m / a^n = a^(m - n)
- Perpangkatan eksponen: (a^m)^n = a^(m × n)
- Eksponen nol: a^0 = 1 (untuk a ≠ 0)
- Eksponen negatif: a^(-n) = 1 / a^n

3. Konsep Dasar Logaritma
Logaritma merupakan invers (kebalikan) dari eksponen. Jika a^c = b dengan a > 0 dan a ≠ 1, maka bentuk logaritmanya ditulis sebagai:
^a log b = c
Artinya, c adalah pangkat yang harus diberikan pada basis a untuk menghasilkan bilangan b.

4. Penerapan pada Pertumbuhan dan Peluruhan
Fungsi eksponen f(t) = N_0 × (1 + r)^t sering digunakan untuk memodelkan pertumbuhan populasi bakteri atau bunga majemuk, sedangkan f(t) = N_0 × (1 - r)^t digunakan untuk memodelkan peluruhan zat radioaktif. Logaritma membantu kita menentukan waktu (t) yang dibutuhkan hingga suatu populasi mencapai jumlah tertentu.
TEXT,
            ],
            [
                'category_slug' => 'fisika-sma',
                'author' => $teacher1,
                'title' => 'Hukum Newton tentang Gerak dan Penerapannya pada Dinamika Partikel',
                'slug' => 'hukum-newton-tentang-gerak-dan-dinamika-partikel',
                'grade_level' => Material::GRADE_10,
                'estimated_minutes' => 25,
                'image_path' => '/images/materials/hukum-newton.svg',
                'image_caption' => 'Diagram benda bebas (free-body diagram) yang menunjukkan vektor gaya tarik (F), gaya gesek (f), gaya normal (N), dan gaya berat (w = m·g).',
                'description' => 'Menganalisis hubungan antara gaya, massa, dan percepatan benda melalui Hukum I, II, dan III Newton beserta diagram benda bebas.',
                'content' => <<<'TEXT'
1. Hukum I Newton (Kelembaman / Inersia)
Setiap benda akan tetap berada dalam keadaan diam atau bergerak lurus beraturan (kecepatan konstan) kecuali ada resultan gaya luar yang bekerja padanya:
ΣF = 0
Kecenderungan benda mempertahankan keadaan geraknya disebut inersia dan bergantung pada massa benda.

2. Hukum II Newton
Percepatan (a) yang dialami suatu benda berbanding lurus dengan resultan gaya (ΣF) yang bekerja pada benda tersebut dan berbanding terbalik dengan massanya (m):
ΣF = m × a
Satuan gaya dalam Sistem Internasional (SI) adalah Newton (N), di mana 1 N = 1 kg·m/s².

3. Hukum III Newton (Aksi-Reaksi)
Ketika benda pertama mengerjakan gaya (F_aksi) pada benda kedua, benda kedua akan mengerjakan gaya balasan (F_reaksi) pada benda pertama yang besarnya sama namun arahnya berlawanan:
F_aksi = -F_reaksi
Pasangan gaya aksi-reaksi selalu bekerja pada dua benda yang berbeda.

4. Langkah Analisis Diagram Benda Bebas
- Identifikasi benda yang ditinjau sebagai titik materi.
- Gambarkan seluruh gaya yang bekerja pada benda (gaya berat w = m·g, gaya normal N, gaya gesek f, atau tegangan tali T).
- Uraikan gaya pada sumbu-x dan sumbu-y, lalu terapkan ΣF_x = m·a_x dan ΣF_y = m·a_y.
TEXT,
            ],
            [
                'category_slug' => 'kimia-sma',
                'author' => $teacher2,
                'title' => 'Termokimia: Perubahan Entalpi dan Hukum Hess',
                'slug' => 'termokimia-perubahan-entalpi-dan-hukum-hess',
                'grade_level' => Material::GRADE_11,
                'estimated_minutes' => 20,
                'image_path' => '/images/materials/termokimia-entalpi.svg',
                'image_caption' => 'Diagram tingkat energi perbandingan reaksi eksoterm (melepas kalor, ΔH < 0) dan reaksi endoterm (menyerap kalor, ΔH > 0).',
                'description' => 'Mempelajari perpindahan kalor antara sistem dan lingkungan, reaksi eksoterm serta endoterm, dan penentuan perubahan entalpi (ΔH).',
                'content' => <<<'TEXT'
1. Sistem dan Lingkungan
Dalam termokimia, bagian reaksi kimia yang sedang diamati disebut sistem, sedangkan segala sesuatu di luar sistem disebut lingkungan. Interaksi keduanya melibatkan pertukaran energi (kalor) maupun materi.

2. Reaksi Eksoterm dan Endoterm
- Reaksi Eksoterm: Kalor berpindah dari sistem ke lingkungan. Suhu lingkungan meningkat dan nilai perubahan entalpi bernilai negatif (ΔH < 0). Contoh: reaksi pembakaran hidrokarbon.
- Reaksi Endoterm: Sistem menyerap kalor dari lingkungan. Suhu lingkungan menurun dan nilai perubahan entalpi bernilai positif (ΔH > 0). Contoh: dekomposisi termal kalsium karbonat.

3. Penentuan Perubahan Entalpi (ΔH)
Perubahan entalpi reaksi dapat dihitung melalui empat pendekatan:
- Kalorimetri: q = m × c × ΔT
- Data Entalpi Pembentukan Standar (ΔH°f): ΔH_reaksi = Σ ΔH°f(produk) - Σ ΔH°f(reaktan)
- Hukum Hess: Perubahan entalpi reaksi hanya bergantung pada keadaan awal dan keadaan akhir, tidak bergantung pada jalannya reaksi.
- Energi Ikatan Rata-Rata: ΔH_reaksi = Σ Energi ikatan pemutusan (kiri) - Σ Energi ikatan pembentukan (kanan).
TEXT,
            ],
            [
                'category_slug' => 'biologi-sma',
                'author' => $teacher2,
                'title' => 'Struktur dan Fungsi Sel sebagai Unit Dasar Kehidupan',
                'slug' => 'struktur-dan-fungsi-sel-unit-dasar-kehidupan',
                'grade_level' => Material::GRADE_11,
                'estimated_minutes' => 20,
                'image_path' => '/images/materials/struktur-sel.svg',
                'image_caption' => 'Skema struktur sel eukariotik beserta organel utama: nukleus, mitokondria, kloroplas, dan membran plasma selektif permeabel.',
                'description' => 'Mengenal perbedaan sel prokariotik dan eukariotik, organel-organel sel tumbuhan dan hewan, serta mekanisme transpor membran.',
                'content' => <<<'TEXT'
1. Konsep Dasar Sel
Sel merupakan unit struktural dan fungsional terkecil dari makhluk hidup. Berdasarkan ada tidaknya membran inti, sel dibagi menjadi:
- Sel Prokariotik: Tidak memiliki membran inti (contoh: bakteri dan Archaea).
- Sel Eukariotik: Memiliki membran inti sejati serta organel bermembran (contoh: sel tumbuhan, hewan, jamur, dan protista).

2. Organel Utama Sel Eukariotik
- Nukleus (Inti Sel): Mengatur seluruh aktivitas sel dan menyimpan materi genetik (DNA).
- Mitokondria: Tempat berlangsungnya respirasi seluler aerob untuk menghasilkan energi dalam bentuk ATP.
- Ribosom: Tempat sintesis protein.
- Retikulum Endoplasma (RE): RE kasar membantu transpor protein; RE halus berperan dalam sintesis lipid dan detoksifikasi.
- Badan Golgi: Memodifikasi, mengemas, dan mendistribusikan protein atau lipid.
- Kloroplas (khusus tumbuhan): Mengandung klorofil untuk proses fotosintesis.

3. Transpor Melalui Membran Sel
- Transpor Pasif (tanpa energi ATP): Difusi sederhana, difusi terfasilitasi, dan osmosis.
- Transpor Aktif (membutuhkan ATP): Pompa ion natrium-kalium, endositosis, dan eksositosis.
TEXT,
            ],
            [
                'category_slug' => 'bahasa-indonesia-sma',
                'author' => $teacher1,
                'title' => 'Menyusun Karya Ilmiah: Struktur, Kaidah Kebahasaan, dan Sitasi',
                'slug' => 'menyusun-karya-ilmiah-struktur-dan-kaidah-kebahasaan',
                'grade_level' => Material::GRADE_11,
                'estimated_minutes' => 15,
                'image_path' => '/images/materials/karya-ilmiah.svg',
                'image_caption' => 'Alur sistematika penulisan karya tulis ilmiah dari Bab I Pendahuluan hingga Bab V Penutup dan Daftar Pustaka.',
                'description' => 'Panduan sistematis menyusun karya tulis ilmiah di jenjang SMA mulai dari perumusan masalah, kerangka teori, metode penelitian, hingga daftar pustaka.',
                'content' => <<<'TEXT'
1. Hakikat Karya Ilmiah
Karya ilmiah adalah tulisan yang memaparkan hasil pengamatan, kajian pustaka, atau penelitian yang disusun secara sistematis menggunakan metode ilmiah dan bahasa Indonesia baku.

2. Struktur Utama Karya Ilmiah
- Bagian Awal: Judul, abstrak, dan kata pengantar.
- Bab I Pendahuluan: Latar belakang masalah, rumusan masalah, tujuan penelitian, dan manfaat penelitian.
- Bab II Landasan Teori: Tinjauan pustaka dan konsep-konsep relevan yang mendasari analisis.
- Bab III Metode Penelitian: Jenis penelitian, teknik pengumpulan data, dan metode analisis data.
- Bab IV Hasil dan Pembahasan: Pemaparan temuan data dan analisis kritis menjawab rumusan masalah.
- Bab V Penutup: Simpulan dan saran konstruktif.
- Daftar Pustaka: Rujukan sumber buku, jurnal, dan publikasi ilmiah yang dikutip.

3. Kaidah Kebahasaan
- Menggunakan kata bermakna denotatif (lugas, tidak ambigu).
- Menghindari opini subjektif tanpa dukungan data.
- Konsisten menggunakan kalimat efektif dan istilah keilmuan sesuai EYD.
TEXT,
            ],
            [
                'category_slug' => 'informatika-sma',
                'author' => $teacher2,
                'title' => 'Berpikir Komputasional, Algoritma Pencarian, dan Pengurutan Data',
                'slug' => 'berpikir-komputasional-algoritma-pencarian-dan-pengurutan',
                'grade_level' => Material::GRADE_12,
                'estimated_minutes' => 25,
                'image_path' => '/images/materials/algoritma-pencarian.svg',
                'image_caption' => 'Ilustrasi pencarian bagi dua (Binary Search) pada larik terurut yang memangkas separuh ruang pencarian pada setiap iterasi.',
                'description' => 'Memahami empat pilar berpikir komputasional serta cara kerja algoritma Linear Search, Binary Search, Bubble Sort, dan Merge Sort.',
                'content' => <<<'TEXT'
1. Empat Pilar Berpikir Komputasional
- Dekomposisi: Memecah persoalan kompleks menjadi sub-persoalan yang lebih kecil dan mudah dikelola.
- Pengenalan Pola: Menemukan kemiripan atau pola berulang di antara permasalahan.
- Abstraksi: Menyaring informasi penting dan mengabaikan detail yang tidak relevan.
- Perancangan Algoritma: Menyusun langkah-langkah logis dan berurutan untuk menyelesaikan masalah.

2. Algoritma Pencarian (Searching)
- Linear Search (Pencarian Beruntun): Memeriksa elemen satu per satu dari awal hingga akhir. Dapat digunakan pada data yang belum terurut dengan kompleksitas waktu O(n).
- Binary Search (Pencarian Bagi Dua): Membagi ruang pencarian menjadi dua bagian secara berulang. Wajib digunakan pada data yang sudah terurut dengan kompleksitas waktu O(log n).

3. Algoritma Pengurutan (Sorting)
- Bubble Sort / Selection Sort / Insertion Sort: Algoritma pengurutan dasar dengan perbandingan berpasangan, memiliki kompleksitas rata-rata O(n²).
- Merge Sort & Quick Sort: Menggunakan pendekatan divide and conquer (pecah dan taklukkan) sehingga jauh lebih efisien untuk kumpulan data besar dengan kompleksitas O(n log n).
TEXT,
            ],
            [
                'category_slug' => 'ekonomi-sma',
                'author' => $teacher1,
                'title' => 'Mekanisme Pasar: Permintaan, Penawaran, dan Harga Keseimbangan',
                'slug' => 'mekanisme-pasar-permintaan-penawaran-dan-harga-keseimbangan',
                'grade_level' => Material::GRADE_10,
                'estimated_minutes' => 20,
                'image_path' => '/images/materials/mekanisme-pasar.svg',
                'image_caption' => 'Kurva keseimbangan pasar (ekuilibrium) pada perpotongan kurva permintaan (D) dan kurva penawaran (S).',
                'description' => 'Mempelajari hukum permintaan dan penawaran, faktor penggeser kurva, serta pembentukan titik ekuilibrium pasar.',
                'content' => <<<'TEXT'
1. Hukum Permintaan (Law of Demand)
Dengan asumsi ceteris paribus (faktor-faktor lain dianggap tetap), apabila harga suatu barang atau jasa naik maka jumlah barang yang diminta konsumen akan turun. Sebaliknya, apabila harga turun maka jumlah barang yang diminta akan meningkat.

2. Hukum Penawaran (Law of Supply)
Dengan asumsi ceteris paribus, apabila harga suatu barang naik maka jumlah barang yang ditawarkan produsen akan meningkat, dan apabila harga turun maka jumlah yang ditawarkan akan berkurang.

3. Keseimbangan Pasar (Market Equilibrium)
Harga dan kuantitas keseimbangan tercapai ketika jumlah barang yang diminta sama dengan jumlah barang yang ditawarkan:
Q_d = Q_s  atau  P_d = P_s
Pada titik ekuilibrium ini tidak terjadi kelebihan permintaan (shortage) maupun kelebihan penawaran (surplus).
TEXT,
            ],
            [
                'category_slug' => 'sejarah-sma',
                'author' => $teacher2,
                'title' => 'Perjuangan Mempertahankan Kemerdekaan Indonesia melalui Diplomasi dan Fisik',
                'slug' => 'perjuangan-mempertahankan-kemerdekaan-indonesia-diplomasi-dan-fisik',
                'grade_level' => Material::GRADE_12,
                'estimated_minutes' => 20,
                'image_path' => '/images/materials/perjuangan-kemerdekaan.svg',
                'image_caption' => 'Lini masa tonggak perjuangan fisik dan diplomasi Indonesia dari Proklamasi 17 Agustus 1945 hingga pengakuan kedaulatan KMB 1949.',
                'description' => 'Mengkaji rangkaian perjuangan bangsa Indonesia pasca-Proklamasi 1945 hingga pengakuan kedaulatan pada Konferensi Meja Bundar 1949.',
                'content' => <<<'TEXT'
1. Latar Belakang Kedatangan Sekutu dan NICA
Setelah Proklamasi Kemerdekaan 17 Agustus 1945, bangsa Indonesia menghadapi tantangan baru dengan kedatangan pasukan Sekutu (AFNEI) yang diboncengi oleh NICA (Netherlands Indies Civil Administration) yang ingin menegakkan kembali kekuasaan kolonial.

2. Perjuangan Fisik di Berbagai Daerah
- Pertempuran Surabaya (10 November 1945) yang menjadi tonggak Hari Pahlawan.
- Pertempuran Ambarawa dan Palagan Bandung Lautan Api.
- Serangan Umum 1 Maret 1949 di Yogyakarta yang membuktikan kepada dunia internasional bahwa Republik Indonesia dan TNI masih tegak berdiri.

3. Jalur Perjuangan Diplomasi
- Perundingan Linggarjati (1946): Pengakuan de facto wilayah RI meliputi Jawa, Sumatera, dan Madura.
- Perundingan Renville (1948) dan Roem-Royen (1949).
- Konferensi Meja Bundar (KMB) di Den Haag (1949): Menghasilkan pengakuan kedaulatan Indonesia pada 27 Desember 1949.
TEXT,
            ],
        ];

        foreach ($materialsData as $item) {
            $category = $categories[$item['category_slug']] ?? null;
            if (! $category) {
                continue;
            }

            $material = Material::firstOrNew(['slug' => $item['slug']]);
            $material->fill([
                'material_category_id' => $category->id,
                'user_id' => $item['author']->id,
                'title' => $item['title'],
                'education_level' => Material::LEVEL_SMA,
                'grade_level' => $item['grade_level'],
                'description' => $item['description'],
                'image_path' => $material->image_path ?? $item['image_path'],
                'image_caption' => $material->image_caption ?? $item['image_caption'],
                'content' => $item['content'],
                'status' => Material::STATUS_PUBLISHED,
                'estimated_minutes' => $item['estimated_minutes'],
                'published_at' => $material->published_at ?? now(),
            ]);
            $material->save();
        }
    }

    private function resolveTeacher(string $email, string $name): User
    {
        $existing = User::where('email', $email)->first();
        if ($existing) {
            return $existing;
        }

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(TeacherStudentSeeder::DEFAULT_PASSWORD),
        ]);
        $user->email_verified_at = now();
        $user->assignRole(UserRole::Teacher);
        $user->save();

        return $user;
    }
}
