<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

/* Admin.php 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'email',
        'username',
        'password',
        'role'
    ];

    protected $hidden = ['password'];
}

/* Buku.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $fillable = [
        'id_kategori',
        'judul_buku',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'stok',
        'foto'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }
}

/* DetailPeminjaman.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use App\Models\Buku;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id_detail';
    protected $fillable = [
        'id_peminjaman',
        'id_buku',
        'jumlah'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}

/* Kategori.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['nama_kategori'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}

/* Peminjaman.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = [
        'id_siswa',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}

/* Siswa.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $fillable = [
        'nis',
        'nama_siswa',
        'kelas',
        'jurusan',
        'password'
    ];

    protected $hidden = ['password'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_siswa', 'id_siswa');
    }
}

/* User.php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    
      @var list<string>
     
    protected $fillable = [
        'name',
        'email',
        'username',
        'nis',
        'role',
        'password',
    ];

    
     @var list<string>
     
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
     @return array<string, string>
     
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'nis', 'nis');
    }
}

/* Migration:create_kategori_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->integer('id_kategori')->autoIncrement();
            $table->string('nama_kategori', 100);
            $table->timestamps();
            $table->primary('id_kategori');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};

/* admin_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->integer('id_admin')->autoIncrement();
            $table->string('email', 150)->unique();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('role', ['admin', 'siswa']);
            $table->timestamps();
            $table->primary('id_admin');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};

/* siswa_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->integer('id_siswa')->autoIncrement();
            $table->string('nis', 20)->unique();
            $table->string('nama_siswa', 100);
            $table->string('kelas', 20);
            $table->string('jurusan', 50);
            $table->string('password', 255);
            $table->timestamps();
            $table->primary('id_siswa');
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};

/* buku_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->integer('id_buku')->autoIncrement();
            $table->integer('id_kategori');
            $table->string('judul_buku', 150);
            $table->string('pengarang', 100);
            $table->string('penerbit', 100);
            $table->year('tahun_terbit');
            $table->integer('stok');
            $table->timestamps();
            $table->primary('id_buku');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};

/* peminjaman_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->integer('id_peminjaman')->autoIncrement();
            $table->integer('id_siswa');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->enum('status', ['Dipinjam', 'Dikembalikan']);
            $table->timestamps();
            $table->primary('id_peminjaman');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

/* detail_peminjaman_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->integer('id_detail')->autoIncrement();
            $table->integer('id_peminjaman');
            $table->integer('id_buku');
            $table->integer('jumlah');
            $table->timestamps();
            $table->primary('id_detail');
            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->onDelete('cascade');
            $table->foreign('id_buku')->references('id_buku')->on('buku')->onDelete('cascade');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};

/* add-foto-to-buku_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('tahun_terbit');
        });
    }

    
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};

/* users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('nis')->unique()->nullable();
            $table->string('role')->default('siswa'); // admin, siswa
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

