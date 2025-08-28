<?php

namespace App\Controllers;

class GenerateQuotes extends BaseController
{
  protected $r;
  protected $v;
  protected $u;

  public function __construct()
  {
    $this->r = service('request');
    $this->v = \Config\Services::validation();
  }

  public function index()
  {
    $data = [
      "title" => "Generate Quotes"
    ];
    return view('content/generate_quotes', $data);
  }

  public function generate()
  {
    // $this->get_video();
    $quotes = $this->get_quote();
    $photo = $this->get_photo();
    $result = $this->edit_photo($photo, $quotes, "Seseorang");
    return $this->response->setJSON([
      'status' => true,
      'hasil' => $result
    ]);
    // if ($quotes != "gagal") {
    //   return $this->response->setJSON([
    //     'status' => true,
    //     'quotes' => $quotes
    //   ]);
    // } else {
    //   return $this->response->setJSON([
    //     'status' => false,
    //   ]);
    // }
  }

  private function get_quote()
  {
    $token = getenv('HF_TOKEN');
    $url = 'https://router.huggingface.co/v1/chat/completions';

    $data = [
      "messages" => [
        [
          "role" => "user",
          "content" => "Buatkan 1 quotes"
        ]
      ],
      "model" => "deepseek-ai/DeepSeek-V3.1:fireworks-ai",
      "stream" => false
    ];

    $payload = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: Bearer $token",
      "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
      $result = json_decode($response, true);
      $content = $result['choices'][0]['message']['content'] ?? '';

      if (preg_match('/\*\*"(.*?)"\*\*/s', $content, $matches)) {
        $quotes = $matches[1];
      } else {
        $quotes = $content;
      }
      return $quotes;
    } else {
      return "gagal";
    }
  }

  private function get_video()
  {
    $api = getenv('PIXABAY_API');
    $kategori = ['nature', 'travel', 'city', 'ocean', 'mountain'];
    $searchQuery = getRandomItem($kategori);
    $perPage = 10;

    if (!is_dir(FCPATH . 'proses/video')) mkdir(FCPATH . 'proses/video', 0755, true);
    if (!is_dir(FCPATH . 'proses/output')) mkdir(FCPATH . 'proses/output', 0755, true);

    $firstRes = pixabayApiRequest($api, "video", $searchQuery, $perPage, 1);
    $totalHits = $firstRes['totalHits'] ?? 0;

    if (!$totalHits) {
      return ['status' => 'error', 'message' => 'Tidak ada hasil video ditemukan.'];
    }

    $totalPages = ceil($totalHits / $perPage);
    $page = rand(1, $totalPages);
    $res = $page === 1 ? $firstRes : pixabayApiRequest($api, "video", $searchQuery, $perPage, $page);

    $videos = $res['hits'] ?? [];
    if (!$videos) {
      return ['status' => 'error', 'message' => 'Tidak ada video ditemukan.'];
    }

    $portraitCandidates = array_filter($videos, function ($video) {
      foreach ($video['videos'] as $file) {
        if ($file['width'] >= 720 && $file['height'] >= 1280) return true;
      }
      return false;
    });

    if (!$portraitCandidates) {
      return ['status' => 'error', 'message' => 'Tidak ada video portrait yang cukup besar.'];
    }

    $selectedVideo = $portraitCandidates[array_rand($portraitCandidates)];
    $videoFiles = $selectedVideo['videos'];
    $videoFile = null;

    foreach ($videoFiles as $file) {
      if (isset($file['width'], $file['height']) && $file['width'] >= 720 && $file['height'] >= 1280) {
        if (isset($file['quality']) && $file['quality'] === 'hd') {
          $videoFile = $file;
          break;
        } elseif (!$videoFile) {
          $videoFile = $file;
        }
      }
    }

    if (!$videoFile) {
      return ['status' => 'error', 'message' => 'Tidak ada file video yang sesuai.'];
    }

    $videoUrl = $videoFile['url'];

    $inputPath = FCPATH . 'proses/video/background.mp4';

    // Download video dengan cURL
    $fp = fopen($inputPath, 'w+');
    $ch = curl_init($videoUrl);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    if (!curl_exec($ch)) {
      $error = curl_error($ch);
      curl_close($ch);
      fclose($fp);
      return ['status' => 'error', 'message' => $error];
    }

    curl_close($ch);
    fclose($fp);

    return [
      'status' => 'success',
      'searchQuery' => $searchQuery,
      'page' => $page,
      'videoUrl' => $videoUrl,
      'savedAs' => $inputPath
    ];
  }

  private function edit_video($videoUrl)
  {
    // Masih Kendala Server
  }

  private function get_photo()
  {
    $api = getenv('PIXABAY_API');
    $kategori = ['nature', 'travel', 'city', 'ocean', 'mountain'];
    $searchQuery = getRandomItem($kategori);
    $perPage = 10;

    // Pastikan folder ada
    if (!is_dir(FCPATH . 'proses/photo')) mkdir(FCPATH . 'proses/photo', 0755, true);
    if (!is_dir(FCPATH . 'proses/output')) mkdir(FCPATH . 'proses/output', 0755, true);

    // Ambil data foto dari Pixabay
    $firstRes = pixabayApiRequest($api, "photo", $searchQuery, $perPage, 1);
    $totalHits = $firstRes['totalHits'] ?? 0;

    if (!$totalHits) {
      return ['status' => 'error', 'message' => 'Tidak ada hasil foto ditemukan.'];
    }

    $totalPages = ceil($totalHits / $perPage);
    $page = rand(1, $totalPages);
    $res = $page === 1 ? $firstRes : pixabayApiRequest($api, "photo", $searchQuery, $perPage, $page);

    $photos = $res['hits'] ?? [];
    if (!$photos) {
      return ['status' => 'error', 'message' => 'Tidak ada foto ditemukan.'];
    }

    // Filter foto portrait (tinggi >= lebar)
    $portraitCandidates = array_filter($photos, function ($photo) {
      return isset($photo['imageWidth'], $photo['imageHeight']) && $photo['imageHeight'] >= $photo['imageWidth'];
    });

    if (!$portraitCandidates) {
      return ['status' => 'error', 'message' => 'Tidak ada foto portrait yang cukup besar.'];
    }

    // Pilih foto random
    $selectedPhoto = $portraitCandidates[array_rand($portraitCandidates)];
    $photoUrl = $selectedPhoto['largeImageURL'] ?? $selectedPhoto['webformatURL'] ?? null;

    if (!$photoUrl) {
      return ['status' => 'error', 'message' => 'URL foto tidak tersedia.'];
    }

    $inputPath = FCPATH . 'proses/photo/background.jpg';

    // Download foto dengan cURL
    $fp = fopen($inputPath, 'w+');
    $ch = curl_init($photoUrl);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    if (!curl_exec($ch)) {
      $error = curl_error($ch);
      curl_close($ch);
      fclose($fp);
      return ['status' => 'error', 'message' => $error];
    }

    curl_close($ch);
    fclose($fp);

    return $photoUrl;

    // return [
    //   'status' => 'success',
    //   'searchQuery' => $searchQuery,
    //   'page' => $page,
    //   'photoUrl' => $photoUrl,
    //   'savedAs' => $inputPath
    // ];
  }

  private function edit_photo($photoUrl, $quotes, $author)
  {
    // Ukuran TikTok (1080 x 1920)
    $width = 1080;
    $height = 1920;

    // Ambil gambar latar belakang
    $image = imagecreatefromstring(file_get_contents($photoUrl));
    $bgWidth = imagesx($image);
    $bgHeight = imagesy($image);

    // Resize agar sesuai frame TikTok
    $bgResized = imagecreatetruecolor($width, $height);
    imagecopyresampled($bgResized, $image, 0, 0, 0, 0, $width, $height, $bgWidth, $bgHeight);

    // Warna teks
    $white = imagecolorallocate($bgResized, 255, 255, 255);
    $black = imagecolorallocate($bgResized, 0, 0, 0);

    // Font TTF
    $font = FCPATH . 'assets/font/Oswald-Regular.ttf';

    // Fungsi outline
    $drawTextWithOutline = function ($img, $size, $x, $y, $colorMain, $colorOutline, $font, $text) {
      $outlineSize = 4; // ketebalan outline
      for ($ox = -$outlineSize; $ox <= $outlineSize; $ox++) {
        for ($oy = -$outlineSize; $oy <= $outlineSize; $oy++) {
          imagettftext($img, $size, 0, $x + $ox, $y + $oy, $colorOutline, $font, $text);
        }
      }
      imagettftext($img, $size, 0, $x, $y, $colorMain, $font, $text);
    };

    // Wrap text
    $lines = [];
    $words = explode(' ', $quotes);
    $line = '';
    foreach ($words as $word) {
      $testLine = $line . ' ' . $word;
      $bbox = imagettfbbox(48, 0, $font, trim($testLine));
      $lineWidth = $bbox[2] - $bbox[0];
      if ($lineWidth < $width * 0.75) {
        $line = $testLine;
      } else {
        $lines[] = trim($line);
        $line = $word;
      }
    }
    $lines[] = trim($line);

    // Posisi teks di tengah layar
    $lineHeight = 80;
    $textBlockHeight = count($lines) * $lineHeight;
    $y = ($height - $textBlockHeight) / 2;

    // Cetak teks quote dengan outline
    foreach ($lines as $line) {
      $bbox = imagettfbbox(48, 0, $font, $line);
      $textWidth = $bbox[2] - $bbox[0];
      $x = ($width - $textWidth) / 2;

      $drawTextWithOutline($bgResized, 45, $x, $y, $white, $black, $font, $line);
      $y += $lineHeight;
    }

    // Cetak nama author di bawah quote
    $authorText = "- $author -";
    $bbox = imagettfbbox(40, 0, $font, $authorText);
    $authorWidth = $bbox[2] - $bbox[0];
    $x = ($width - $authorWidth) / 2;

    $drawTextWithOutline($bgResized, 38, $x, $y + 50, $white, $black, $font, $authorText);

    // Simpan hasil
    $fileName = FCPATH . 'proses/output/quote.png';
    imagepng($bgResized, $fileName);
    imagedestroy($bgResized);

    return $fileName;
  }
}
