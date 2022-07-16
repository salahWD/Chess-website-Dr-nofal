<script src="https://kit.fontawesome.com/782595b931.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
<!-- swiper lib -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<!-- custom lib -->
<script src="<?= JS_URL;?>/script.js"></script>
<!-- custom script -->
<?php if (isset($custom_script) && !empty($custom_script)):?>
  <script src="<?= JS_URL;?>/<?= $custom_script;?>.js"></script>
<?php endif;?>
</body>
</html>