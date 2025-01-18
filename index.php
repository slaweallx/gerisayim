<?php
// index.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Tarihe Kalan Süre Hesaplama</title>
  <meta name=description content="Belirlenen tarihe kalan gün ve saati hesaplama aracı">
  <!-- Open Graph Temel Meta Etiketleri -->
  <meta property="og:title" content="Tarihe Kalan Süre Hesaplama" />
  <meta property="og:description" content="Seçtiğiniz tarihe ne kadar zaman kaldığını kolayca öğrenin." />
  <meta property="og:image" content="https://example.com/images/index_og.png" /> <!-- Sizin sabit bir görseliniz olabilir -->
  <meta property="og:url" content="https://example.com/index.php" />
  <meta property="og:type" content="website" />

  <!-- Twitter Card Meta Etiketleri -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Tarihe Kalan Süre Hesaplama" />
  <meta name="twitter:description" content="Seçtiğiniz tarihe ne kadar zaman kaldığını kolayca öğrenin." />
  <meta name="twitter:image" content="https://example.com/images/index_og.png" />

  <!-- Bootstrap CSS (CDN veya yerel) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons (opsiyonel) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h1 class="mb-4 text-center">Tarihe Kalan Süre Hesaplama</h1>

      <?php
      // Form gönderildiyse:
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $day   = isset($_POST['day']) ? (int) $_POST['day'] : 0;
          $month = isset($_POST['month']) ? (int) $_POST['month'] : 0;
          $year  = isset($_POST['year']) ? (int) $_POST['year'] : 0;

          // Geçerli tarih mi?
          if (checkdate($month, $day, $year)) {
              $currentDate = new DateTime('now');
              $targetDate  = new DateTime("$year-$month-$day 00:00:00");

              if ($targetDate < $currentDate) {
                  echo '<div class="alert alert-danger">Girdiğiniz tarih geçmişte! Lütfen ileri bir tarih giriniz.</div>';
              } else {
                  // Farkı hesapla
                  $diff    = $currentDate->diff($targetDate);
                  $days    = $diff->days;
                  $hours   = $diff->h;
                  $minutes = $diff->i;

                  // Ekrana yaz
                  echo '<div class="alert alert-success">';
                  echo "<strong>Kalan süre:</strong> $days gün, $hours saat, $minutes dakika";
                  echo '</div>';

                  // Kaydet seçili mi?
                  if (isset($_POST['save'])) {
                      $uniqueID = uniqid();
                      $jsonFile = "json/$uniqueID.json";
                      $phpFile  = "countdown_$uniqueID.php";

                      // JSON içeriği
                      $data = [
                          'day'   => $day,
                          'month' => $month,
                          'year'  => $year
                      ];
                      file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

                      // Oluşturulacak geri sayım sayfasının içeriği
                      // Bu sayfa, her çağrıldığında JSON'dan hedef tarihi okur,
                      // bugüne göre kalan süreyi hesaplar ve dinamik meta etiketlerini ekler.
                      $phpContent = <<<EOT
<?php
  // JSON dosyasını oku
  \$jsonData = json_decode(file_get_contents('$jsonFile'), true);
  \$day   = \$jsonData['day'];
  \$month = \$jsonData['month'];
  \$year  = \$jsonData['year'];

  // Şu anki tarih ve hedef tarihi oluştur
  \$currentDate = new DateTime('now');
  \$targetDate  = new DateTime("\$year-\$month-\$day 00:00:00");
  \$diff        = \$currentDate->diff(\$targetDate);

  // Kalan süre verileri
  \$days    = \$diff->days; 
  \$hours   = \$diff->h;    
  \$minutes = \$diff->i;    

  // Baslik ve aciklama icin basit bir metin hazirlayalim
  \$pageTitle = "Tarihe Kalan Süre: \$day/\$month/\$year";
  \$pageDescription = "Hedef tarihe ( \$day/\$month/\$year ) kalan sure: \$days gün, \$hours saat, \$minutes dakika";

  // Dinamik görsel (generate_image.php) linki
  // Parametreleri ekleyerek, her ziyaret edildiğinde KALAN SÜRE yazılı bir resim üretiyor.
  \$ogImageUrl = "https://example.com/generate_image.php?day=\$day&month=\$month&year=\$year&days=\$days&hours=\$hours&minutes=\$minutes";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?php echo \$pageTitle; ?></title>

  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?php echo \$pageTitle; ?>" />
  <meta property="og:description" content="<?php echo \$pageDescription; ?>" />
  <meta property="og:image" content="<?php echo \$ogImageUrl; ?>" />
  <meta property="og:url" content="<?php echo 'https://'.\$_SERVER['HTTP_HOST'].\$_SERVER['REQUEST_URI']; ?>" />
  <meta property="og:type" content="website" />

  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?php echo \$pageTitle; ?>" />
  <meta name="twitter:description" content="<?php echo \$pageDescription; ?>" />
  <meta name="twitter:image" content="<?php echo \$ogImageUrl; ?>" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 text-center">
            <h1 class="mb-4"><?php echo \$pageTitle; ?> <div class="alert alert-danger"><a class="btn btn-primary" href="https://rootali.net/proje/syc/">Yeni hesap yap</a></div></h1>

      <?php if (\$targetDate < \$currentDate): ?>
        <div class="alert alert-danger">Bu tarih geçmişte kalmış.</div>
      <?php else: ?>
        <div class="alert alert-info">
          <strong>Kalan süre:</strong> <?php echo "\$days gün, \$hours saat, \$minutes dakika"; ?>
        </div>
      <?php endif; ?>

      <div class="mt-4">
        <!-- Paylaşım butonları -->
        <a class="btn btn-primary" 
           href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://'.\$_SERVER['HTTP_HOST'].\$_SERVER['REQUEST_URI']); ?>" 
           target="_blank">
          <i class="bi bi-facebook"></i> Facebook'ta Paylaş
        </a>
        
        <a class="btn btn-info text-white" 
           href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://'.\$_SERVER['HTTP_HOST'].\$_SERVER['REQUEST_URI']); ?>&text=Süre+Paylaşımı" 
           target="_blank">
          <i class="bi bi-twitter"></i> Twitter'da Paylaş
        </a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
EOT;

                      // countdown_xxxxx.php dosyasını oluştur
                      file_put_contents($phpFile, $phpContent);

                      // Oluşturulan sayfanın linkini göster
                      $fullLink = 'https://'.$_SERVER['HTTP_HOST'].'/'.$phpFile;
                      echo '<div class="alert alert-warning mt-3">';
                      echo 'Kaydedilen sayfa: <a href="'.$phpFile.'" target="_blank">'.$fullLink.'</a>';
                      echo '</div>';
                  }
              }
          } else {
              echo '<div class="alert alert-danger">Lütfen geçerli bir tarih giriniz.</div>';
          }
      }
      ?>

      <!-- Form Kısmı -->
      <div class="card">
        <div class="card-body">
          <form method="POST" action="" class="row g-3">
            <div class="col-4">
              <label for="day" class="form-label">Gün</label>
              <input type="number" class="form-control" id="day" name="day" required placeholder="GG">
            </div>
            <div class="col-4">
              <label for="month" class="form-label">Ay</label>
              <input type="number" class="form-control" id="month" name="month" required placeholder="AA">
            </div>
            <div class="col-4">
              <label for="year" class="form-label">Yıl</label>
              <input type="number" class="form-control" id="year" name="year" required placeholder="YYYY">
            </div>

            <div class="col-12 form-check">
              <input class="form-check-input" type="checkbox" id="save" name="save">
              <label class="form-check-label" for="save">
                Tarihi Kaydet (Paylaşmak için)
              </label>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-success">
                <i class="bi bi-hourglass-split"></i> Hesapla
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
