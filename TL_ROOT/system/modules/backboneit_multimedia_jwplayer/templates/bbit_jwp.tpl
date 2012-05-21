<!-- indexer::stop -->
<div style="width: <?php echo $this->config['width']; ?>px; height: <?php echo $this->config['height']; ?>px;">
<div id="<?php echo $this->id; ?>"></div>
</div>
<script>
jwplayer(<?php echo json_encode($this->id); ?>).setup(<?php echo json_encode($this->config); ?>);
</script>
<!-- indexer::continue -->