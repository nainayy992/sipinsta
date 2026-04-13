<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories
        foreach ([
            ['id_kategori' => 1, 'nama_kategori' => 'Fiksi'],
            ['id_kategori' => 2, 'nama_kategori' => 'Sejarah'],
        ] as $cat) {
            DB::table('kategori')->updateOrInsert(['id_kategori' => $cat['id_kategori']], array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 2. Seed Books
        $books = [
            // Fiction
            [
                'id_kategori' => 1,
                'judul_buku' => 'Ancika: Dia yang Bersamaku Tahun 1995',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2021,
                'stok' => 50,
                'foto' => 'Ancika.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Azzamine',
                'pengarang' => 'Sophie Aulia',
                'penerbit' => 'Bukune',
                'tahun_terbit' => 2022,
                'stok' => 50,
                'foto' => 'azzamine.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Bintang',
                'pengarang' => 'Tere Liye',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2017,
                'stok' => 50,
                'foto' => 'Bintang.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Dilan 1990',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2014,
                'stok' => 50,
                'foto' => 'dilan 1990.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Dilan 1991',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2015,
                'stok' => 50,
                'foto' => 'dilan 1991.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Home for Allie',
                'pengarang' => 'Nessy Clar',
                'penerbit' => 'Loveable',
                'tahun_terbit' => 2023,
                'stok' => 50,
                'foto' => 'home for allie.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Hujan',
                'pengarang' => 'Tere Liye',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2016,
                'stok' => 50,
                'foto' => 'hujan.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Laut Bercerita',
                'pengarang' => 'Leila S. Chudori',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2017,
                'stok' => 50,
                'foto' => 'Laut Bercerita.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Sabtu Bersama Bapak',
                'pengarang' => 'Adhitya Mulya',
                'penerbit' => 'GagasMedia',
                'tahun_terbit' => 2014,
                'stok' => 50,
                'foto' => 'sabtu bapak.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Seporsi Mie Ayam',
                'pengarang' => 'Various Authors',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2020,
                'stok' => 50,
                'foto' => 'seporsi mie ayam.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Suara dari Dilan',
                'pengarang' => 'Pidi Baiq',
                'penerbit' => 'Pastel Books',
                'tahun_terbit' => 2016,
                'stok' => 50,
                'foto' => 'suara dilan.jpg'
            ],

            [
                'id_kategori' => 1,
                'judul_buku' => 'Haru Mahameru',
                'pengarang' => 'Balqi Alquna',
                'penerbit' => 'Mahaka Publishing',
                'tahun_terbit' => 2018,
                'stok' => 50,
                'foto' => 'haru mahameru.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Kisah Tanah Jawa',
                'pengarang' => 'Om Hao',
                'penerbit' => 'GagasMedia',
                'tahun_terbit' => 2018,
                'stok' => 50,
                'foto' => 'kisah tanah jawa.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Pulang',
                'pengarang' => 'Leila S. Chudori',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 2012,
                'stok' => 50,
                'foto' => 'pulang.jpg'
            ],
            [
                'id_kategori' => 1,
                'judul_buku' => 'Perahu Kertas',
                'pengarang' => 'Dee Lestari',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2009,
                'stok' => 50,
                'foto' => 'perahu kertas.jpg'
            ],

            // History
            [
                'id_kategori' => 2,
                'judul_buku' => 'Strategi Menjinakkan Diponegoro',
                'pengarang' => 'Peter Carey',
                'penerbit' => 'Kompas',
                'tahun_terbit' => 2014,
                'stok' => 50,
                'foto' => 'strategi menjinakkan diponegoro.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Perang di Kerajaan Jawa',
                'pengarang' => 'Peter Carey',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 2015,
                'stok' => 50,
                'foto' => 'perang di kerajaan jawa.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Majapahit',
                'pengarang' => 'Slamet Muljana',
                'penerbit' => 'LKiS',
                'tahun_terbit' => 2010,
                'stok' => 50,
                'foto' => 'majapahit.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Sang Juragan Teh',
                'pengarang' => 'Hella S. Haasse',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2015,
                'stok' => 50,
                'foto' => 'sang juragan teh.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Sejarah Raja Jawa',
                'pengarang' => 'Purwadi',
                'penerbit' => 'Media Abadi',
                'tahun_terbit' => 2007,
                'stok' => 50,
                'foto' => 'sejarah raja jawa.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Misteri Borobudur',
                'pengarang' => 'Senali P. K.',
                'penerbit' => 'Inti Medina',
                'tahun_terbit' => 2010,
                'stok' => 50,
                'foto' => 'misteri borobudur.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Tenggelamnya Kapal Van der Wijck',
                'pengarang' => 'Hamka',
                'penerbit' => 'Bulan Bintang',
                'tahun_terbit' => 1938,
                'stok' => 50,
                'foto' => 'van der wijck.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Semua untuk Hindia',
                'pengarang' => 'Iksaka Banu',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2014,
                'stok' => 50,
                'foto' => 'Semua Untuk Hindia.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Negara Kekuasaan Jawa',
                'pengarang' => 'Soemarsaid Moertono',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 2017,
                'stok' => 50,
                'foto' => 'negara kekuasaan jawa.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Madilog',
                'pengarang' => 'Tan Malaka',
                'penerbit' => 'Narasi',
                'tahun_terbit' => 1943,
                'stok' => 50,
                'foto' => 'madilog.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Sejarah Indonesia Modern',
                'pengarang' => 'M.C. Ricklefs',
                'penerbit' => 'Serambi',
                'tahun_terbit' => 1981,
                'stok' => 50,
                'foto' => 'indonesia modern.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Habis Gelap Terbitlah Terang',
                'pengarang' => 'R.A. Kartini',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => 1911,
                'stok' => 50,
                'foto' => 'habis gelap terbitlah.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Dunia yang Disembunyikan',
                'pengarang' => 'Various',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2019,
                'stok' => 50,
                'foto' => 'dunia yang disembunyikan.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Bumi Manusia',
                'pengarang' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahun_terbit' => 1980,
                'stok' => 50,
                'foto' => 'bumi manusia.jpg'
            ],
            [
                'id_kategori' => 2,
                'judul_buku' => 'Bukan 350 Tahun Dijajah',
                'pengarang' => 'G.J. Resink',
                'penerbit' => 'Komunitas Bambu',
                'tahun_terbit' => 2012,
                'stok' => 50,
                'foto' => 'bukan 350 tahun.jpg'
            ],
        ];

        foreach ($books as $book) {
            DB::table('buku')->updateOrInsert(['judul_buku' => $book['judul_buku']], array_merge($book, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
