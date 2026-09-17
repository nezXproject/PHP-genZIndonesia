# GenZ PHP 🚀

Bahasa pemrograman kecil berbasis PHP dengan sintaks ekspresif dan modern. Dibuat untuk proof of concept yang rapi, mudah dipelajari, dan gampang dikembangkan.

```
gas nama = "Skena";
cetak "Halo, " + nama;
```

---

## Daftar Isi

- [Fitur](#fitur)
- [Instalasi](#instalasi)
- [Cara Menjalankan](#cara-menjalankan)
- [Sintaks Lengkap](#sintaks-lengkap)
  - [Variabel](#variabel)
  - [Tipe Data](#tipe-data)
  - [Cetak Output](#cetak-output)
  - [Operator](#operator)
  - [Kondisi (If / Else)](#kondisi-if--else)
  - [Loop](#loop)
  - [Fungsi](#fungsi)
  - [Komentar](#komentar)
- [Contoh Program](#contoh-program)
- [Cara Kerja Bahasa Ini](#cara-kerja-bahasa-ini)
- [Struktur Proyek](#struktur-proyek)
- [Menambah Fitur Baru](#menambah-fitur-baru)
- [Roadmap](#roadmap)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## Fitur

- ✅ Variabel dinamis (`gas`)
- ✅ Tipe data: angka, string, boolean
- ✅ Operasi aritmatika & perbandingan
- ✅ Operator logika (`dan`, `atau`, `bukan`)
- ✅ Kondisi `cek` / `cekLagi` / `kalauNggak`
- ✅ Loop `putar` (while) dan `gaspol` (for)
- ✅ Fungsi rekursif dengan `mantap` dan `balikin`
- ✅ Error handling dengan pesan lokasi baris
- ✅ Sintaks GenZ-friendly dan ekspresif

---

## Instalasi

### Prasyarat

- PHP **8.1** atau lebih baru
- Composer

Cek versi:

```bash
php --version
composer --version
```

### Langkah Install

```bash
git clone https://github.com/<username>/genz-php.git
cd genz-php
composer install
```

Jadikan executable (opsional, Linux/macOS):

```bash
chmod +x bin/genz
```

Setelah itu kamu bisa pakai `./bin/genz` atau `php bin/genz`.

---

## Cara Menjalankan

### Menjalankan file `.pgz`

```bash
php bin/genz examples/skena.pgz
```

Atau jika sudah di-`chmod`:

```bash
./bin/genz examples/skena.pgz
```

### Output yang Diharapkan

```
Halo, Skena
Umur: 17
Grade: B
--- While loop ---
Putaran ke-0
Putaran ke-1
Putaran ke-2
--- For loop ---
i = 1
i = 2
i = 3
i = 4
i = 5
Faktorial 5: 120
10 + 20 = 30
```

---

## Sintaks Lengkap

### Variabel

Deklarasi pakai keyword `gas`. Tipe data dideteksi otomatis.

```
gas nama = "Budi";
gas umur = 20;
gas aktif = gasTerus;
gas pi = 3.14;
```

Variabel bisa di-reassign:

```
gas x = 10;
gas x = x + 5;   // sekarang x = 15
cetak x;         // 15
```

### Tipe Data

| Tipe | Contoh | Keterangan |
|---|---|---|
| Angka (int) | `42`, `-7` | Bilangan bulat |
| Angka (float) | `3.14`, `-0.5` | Bilangan desimal |
| String | `"halo"`, `'dunia'` | Pakai kutip satu atau dua |
| Boolean | `gasTerus`, `gabisa` | `true` / `false` |

Escape sequence di string: `\n`, `\t`, `\"`, `\\`.

```
cetak "Baris satu\nBaris dua";
```

### Cetak Output

```
cetak "Halo, dunia";
cetak 42;
cetak gasTerus;
cetak "Nilai: " + 100;
```

Boolean dicetak sebagai `gasTerus` / `gabisa`. Nilai `null` dicetak sebagai `kosong`.

### Operator

#### Aritmatika

| Operator | Keterangan | Contoh |
|---|---|---|
| `+` | Tambah / concat string | `2 + 3` → `5`, `"a" + "b"` → `"ab"` |
| `-` | Kurang | `10 - 4` → `6` |
| `*` | Kali | `3 * 4` → `12` |
| `/` | Bagi | `10 / 2` → `5` |

#### Perbandingan

| Operator | Keterangan |
|---|---|
| `==` | Sama dengan |
| `!=` | Tidak sama dengan |
| `<` | Kurang dari |
| `>` | Lebih dari |
| `<=` | Kurang dari atau sama |
| `>=` | Lebih dari atau sama |

#### Logika

| GenZ | Alternatif | Keterangan |
|---|---|---|
| `dan` | `&&` | AND |
| `atau` | `\|\|` | OR |
| `bukan` | `!` | NOT |

Contoh:

```
gas x = gasTerus;
gas y = gabisa;

cetak x dan y;      // gabisa
cetak x atau y;     // gasTerus
cetak bukan x;      // gabisa
```

#### Urutan Prioritas (dari tertinggi)

1. `()` — grouping
2. `bukan` / `!` — unary
3. `*` `/`
4. `+` `-`
5. `<` `>` `<=` `>=`
6. `==` `!=`
7. `dan` / `&&`
8. `atau` / `||`

### Kondisi (If / Else)

```
cek (kondisi) {
    // blok jika benar
}
```

Dengan `else if` dan `else`:

```
cek (nilai >= 90) {
    cetak "Grade: A";
} cekLagi (nilai >= 80) {
    cetak "Grade: B";
} cekLagi (nilai >= 70) {
    cetak "Grade: C";
} kalauNggak {
    cetak "Grade: D";
}
```

Keyword:

| GenZ | Arti |
|---|---|
| `cek` | if |
| `cekLagi` | else if |
| `kalauNggak` | else |

### Loop

#### While — `putar`

```
gas counter = 0;
putar (counter < 5) {
    cetak "Putaran ke-" + counter;
    gas counter = counter + 1;
}
```

#### For — `gaspol`

Format: `gaspol (var = start; end) { ... }` — inklusif (termasuk nilai akhir).

```
gaspol (i = 1; 5) {
    cetak "i = " + i;
}
// Output: i = 1, i = 2, i = 3, i = 4, i = 5
```

Loop bisa di-nested:

```
gaspol (i = 1; 3) {
    gaspol (j = 1; 3) {
        cetak i + " x " + j + " = " + (i * j);
    }
}
```

### Fungsi

Definisi pakai `mantap`, return pakai `balikin`.

```
mantap tambah(a, b) {
    balikin a + b;
}

cetak tambah(3, 4);   // 7
```

Fungsi rekursif:

```
mantap faktorial(n) {
    cek (n <= 1) {
        balikin 1;
    }
    balikin n * faktorial(n - 1);
}

cetak faktorial(5);   // 120
```

Fungsi tanpa return mengembalikan `kosong` (null).

```
mantap sapa(nama) {
    cetak "Halo, " + nama;
}

sapa("Skena");   // Halo, Skena
```

Setiap pemanggilan fungsi punya scope sendiri — variabel di dalam fungsi tidak bocor ke luar.

### Komentar

```
// Ini komentar satu baris
gas x = 10;   // komentar di akhir baris
```

---

## Contoh Program

### 1. Hello GenZ

```
gas nama = "Skena";
cetak "Halo, " + nama;
```

### 2. FizzBuzz

```
gaspol (i = 1; 15) {
    cek (i % 3 == 0 dan i % 5 == 0) {
        cetak "FizzBuzz";
    } cekLagi (i % 3 == 0) {
        cetak "Fizz";
    } cekLagi (i % 5 == 0) {
        cetak "Buzz";
    } kalauNggak {
        cetak i;
    }
}
```

> Catatan: operator `%` (modulo) belum ada di versi ini — lihat [Roadmap](#roadmap).

### 3. Deret Fibonacci

```
mantap fib(n) {
    cek (n <= 1) {
        balikin n;
    }
    balikin fib(n - 1) + fib(n - 2);
}

gaspol (i = 0; 10) {
    cetak fib(i);
}
```

### 4. Menghitung Total

```
gas total = 0;
gaspol (i = 1; 100) {
    gas total = total + i;
}
cetak "Total 1..100 = " + total;   // 5050
```

---

## Cara Kerja Bahasa Ini

GenZ PHP menggunakan pendekatan **tree-walking interpreter** — teknik klasik yang dipakai banyak bahasa pemrograman kecil (seperti Lox, Monkey, dan YapLang). Pipeline-nya tiga tahap:

```
Kode .pgz
    │
    ▼
┌──────────┐
│  LEXER   │  Pecah teks jadi token
└──────────┘
    │
    ▼
┌──────────┐
│  PARSER  │  Token → AST (Abstract Syntax Tree)
└──────────┘
    │
    ▼
┌──────────────┐
│ INTERPRETER  │  Telusuri AST & eksekusi
└──────────────┘
    │
    ▼
   Output
```

### 1. Lexer (`src/Lexer.php`)

Membaca kode karakter per karakter, mengelompokkannya menjadi token.

Contoh:

```
gas x = 10 + 5;
```

Menjadi:

```
[GAS, IDENTIFIER(x), =, NUMBER(10), +, NUMBER(5), ;, EOF]
```

Lexer juga mengenali:
- Keyword (`gas`, `cek`, `putar`, ...)
- Operator (`+`, `==`, `&&`, ...)
- Literal (angka, string)
- Komentar (`// ...`)

### 2. Parser (`src/Parser.php`)

Mengubah token menjadi **AST**. Setiap statement jadi node.

Contoh di atas jadi:

```
Program
└── Assignment
    ├── name: "x"
    └── value: BinaryOp
        ├── left:  Literal(10)
        ├── op:    "+"
        └── right: Literal(5)
```

Parser memakai **recursive descent** dengan urutan prioritas operator (precedence climbing) — dari `||` (paling rendah) hingga ke `primary` (paling tinggi).

### 3. Interpreter (`src/Interpreter.php`)

Menelusuri AST secara rekursif dan mengeksekusi tiap node.

- `Assignment` → simpan nilai ke scope
- `BinaryOp` → evaluasi kiri & kanan, lalu apply operator
- `IfStatement` → evaluasi kondisi, jalankan blok yang cocok
- `WhileStatement` → loop selama kondisi truthy
- `ForStatement` → loop dari `start` sampai `end`
- `FunctionCall` → buat scope baru, ikat parameter, jalankan body
- `ReturnStatement` → lempar sinyal `ReturnSignal` yang ditangkap pemanggil fungsi

### Kenapa pendekatan ini?

- **Mudah dipahami** — setiap tahap terpisah dan bisa di-test sendiri
- **Gampang dikembangkan** — tambah fitur cukup tambah node, rule, dan case
- **Tidak ada trik** — tidak pakai `eval()` atau regex ajaib untuk eksekusi
- **Portabel** — murni PHP, tidak butuh ekstensi khusus

---

## Struktur Proyek

```
genz-php/
├── bin/
│   └── genz                  # CLI entry point
├── examples/
│   ├── skena.pgz             # Contoh lengkap semua fitur
│   ├── fizzbuzz.pgz          # (opsional)
│   └── fibonacci.pgz         # (opsional)
├── src/
│   ├── Lexer.php             # Tokenisasi
│   ├── Parser.php            # Parsing → AST
│   ├── Interpreter.php       # Evaluasi AST
│   ├── Token.php             # Representasi token
│   └── Nodes/
│       ├── Node.php          # Interface dasar
│       ├── Program.php
│       ├── Literal.php
│       ├── Variable.php
│       ├── Assignment.php
│       ├── BinaryOp.php
│       ├── UnaryOp.php
│       ├── PrintStatement.php
│       ├── Block.php
│       ├── IfStatement.php
│       ├── WhileStatement.php
│       ├── ForStatement.php
│       ├── FunctionDecl.php
│       ├── FunctionCall.php
│       └── ReturnStatement.php
├── composer.json
└── README.md
```

---

## Menambah Fitur Baru

Misal mau tambah operator **modulo (`%`)**. Langkahnya:

### 1. Lexer

Tambah `%` ke karakter operator:

```php
if (str_contains('+-*/%=<>!(){};,', $char)) {
```

### 2. Parser

Tambah `%` di level `factor()` (sama seperti `*` dan `/`):

```php
while ($this->is('*') || $this->is('/') || $this->is('%')) {
    $op = $this->advance()->type;
    $node = new Nodes\BinaryOp($node, $op, $this->unary());
}
```

### 3. Interpreter

Tambah case di `evaluateBinaryOp()`:

```php
'%' => $right == 0 ? throw new \RuntimeException("Modulo nol!") : $left % $right,
```

Selesai. Pola ini berlaku untuk fitur apa pun: **Lexer → Parser → Interpreter → Nodes**.

Untuk fitur yang butuh node baru (misal array), tambah file di `src/Nodes/`, daftarkan di `Parser`, dan tambah case di `Interpreter::execute` / `evaluate`.

---

## Roadmap

Fitur yang direncanakan:

- [ ] Operator `%` (modulo)
- [ ] Array / list
- [ ] Loop `for-each` (`gaspol item dalam list`)
- [ ] `break` dan `continue`
- [ ] String interpolation (`"Halo, ${nama}"`)
- [ ] Built-in function: `panjang()`, `tipe()`, `upper()`, `lower()`
- [ ] Import / module (`serap "file.pgz"`)
- [ ] Class / object ala GenZ
- [ ] REPL interaktif (`php bin/genz repl`)
- [ ] Error message dengan kolom (bukan cuma baris)
- [ ] Unit test dengan PHPUnit

---

## Kontribusi

Pull request sangat diterima. Untuk perubahan besar, buka issue dulu untuk diskusi.

Panduan singkat:

1. Fork repo
2. Buat branch fitur: `git checkout -b fitur/array`
3. Commit: `git commit -m "Tambah dukungan array"`
4. Push: `git push origin fitur/array`
5. Buka Pull Request

Pastikan kode mengikuti gaya PSR-12 dan setiap fitur baru punya contoh di `examples/`.

---

## Lisensi

MIT License. Bebas dipakai, dimodifikasi, dan didistribusikan.

---

Dibuat dengan oleh nezXproject Indonesia.
