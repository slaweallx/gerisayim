<?php
// index.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Tarihe Kalan/Zaman Geçen Süre Hesaplama</title>
  <meta name="description" content="Belirlenen tarihe kalan gün veya geçen zamanı hesaplama aracı">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h1 class="mb-4 text-center">Tarih Süre Hesaplama</h1>

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

              // Tarihler arasındaki farkı hesapla
              $diff    = $currentDate->diff($targetDate);
              $days    = $diff->days;
              $hours   = $diff->h;
              $minutes = $diff->i;

              // Geçmiş tarih mi?
              if ($targetDate < $currentDate) {
                  echo '<div class="alert alert-info">';
                  echo "<strong>Geçen süre:</strong> $days gün, $hours saat, $minutes dakika önce";
                  echo '</div>';
              } else {
                  // Gelecek tarih
                  echo '<div class="alert alert-success">';
                  echo "<strong>Kalan süre:</strong> $days gün, $hours saat, $minutes dakika";
                  echo '</div>';
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

            <div class="col-12">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-clock"></i> Hesapla
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
