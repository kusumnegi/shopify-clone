<link rel="stylesheet" type="text/css" href="../assest/css/footer.css">
<footer class="pt-5 shopify-footer-section container">
  <?php
  include '../admin/config/db.php';
  // Fetch all footer rows
  $footerData = [];
  $query = "SELECT * FROM footer";
  $result = $conn->query($query);
  while ($row = $result->fetch_assoc()) {
    $footerData[$row['type']][] = $row;
  }

  // Optional: Handle email submission
  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['info_email'])) {
    $email = trim($_POST['info_email']);
    if (!empty($email)) {
      $stmt = $conn->prepare("INSERT INTO footer (type, text) VALUES ('email', ?)");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->close();
    }
  }
  ?>
  <!-- ------------ footer template section started here....!  ------------ -->
  <div class="shopify-footer-template-section row my-5 container mx-auto">
    <?php foreach (['template1', 'template2'] as $templateType): ?>
      <?php if (!empty($footerData[$templateType][0])):
        $item = $footerData[$templateType][0]; ?>
        <div class="col-md-6 mb-4 <?= $templateType ?>">
          <center>
            <img src="../admin/uploads/footer/templates/<?= htmlspecialchars($item['image']) ?>" class="img-fluid shopshy-footer-templates-img">
            <p class="shopify-footer-template-text"><?= htmlspecialchars($item['text']) ?></p>
          </center>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <!-- ------------ footer teplate section ended here....!  ------------ -->

  <!-- ------------ footer info section started here....!  ------------ -->
  <div class="shopify-footer-info-column   container ">
    <!-- ------------ footer info column started here....!  ------------ -->
    <div class="row ">
      <div class="col-md-4 my-2">
        <h4 class="mb-1 text-dark">Quick Links</h4>
        <?php if (!empty($footerData['quick_link'])): ?>
          <?php foreach ($footerData['quick_link'] as $link): ?>
            <a class="shopify-footer-links " href="<?= htmlspecialchars($link['link']) ?>"><?= htmlspecialchars($link['text']) ?></a><br>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="col-md-4 my-2 info">
        <h4 class="mb-2">Info</h4>
        <?php if (!empty($footerData['info_link'])): ?>
          <?php foreach ($footerData['info_link'] as $link): ?>
            <a class="shopify-footer-links " href="<?= htmlspecialchars($link['link']) ?>"><?= htmlspecialchars($link['text']) ?></a><br>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="col-md-4 my-2">
        <h4 class="mb-2">Our Mission</h4>
        <p class="shopify-footer-mission"><?= !empty($footerData['mission'][0]['text']) ? htmlspecialchars($footerData['mission'][0]['text']) : '' ?></p>
      </div>
    </div>
    <!-- ------------ footer info column ended here....!  ------------ -->

    <!-- ------------ footer socail section started here....!  ------------ -->
    <center>
      <div class="row container  mt-lg-5  ">
        <div class="col-md-6 p-0">
          <form method="POST">

            <div class="shopify-footer-info-email row row-cols-2 p-lg-3 justify-content-between mt-2">
              <input type="email" name="info_email" class="infos col-10 " placeholder="Enter your email" required>
              <button type="submit" class="btn col-2">
                <i class="fa-solid fa-arrow-right" style="font-size: 20px; color:#212529;"></i>
              </button>
            </div>

          </form>
        </div>

        <div class="col-md-6 shopify-footer-socail-links-container d-flex flex-end  ">
          <?php if (!empty($footerData['social'])): ?>
            <?php foreach ($footerData['social'] as $social): ?>
              <a href="<?= htmlspecialchars($social['link']) ?>" class="shopify-footer-socail-links p-lg-3 p-2 mt-2 m-lg-0 mx-2" target="_blank">
                <i class="<?= htmlspecialchars($social['text']) ?>"></i>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </center>
  </div>
  <center class="copyright mb-2 mt-lg-5 mt-3 px-3">© 2025, theme-dawn-demo Powered by Shopify</center>

  <!-- ------------ footer socail section ended here....!  ------------ -->
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>