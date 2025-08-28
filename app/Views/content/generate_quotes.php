<?= $this->extend("template"); ?>
<?= $this->section("content"); ?>
<div class="row">
  <div class="col-lg-12">
    <div class="content-page">
      <div class="box-wrapper">
        <div class="top-content">
          <div class="title-content">
            <h1>Generate Quotes</h1>
          </div>
        </div>
        <div class="box-generate">
          <button class="btn btn-success" id="btn-generate">Generate</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#btn-generate').click(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "<?= base_url(); ?>generate-video-motivation",
      data: "data",
      dataType: "JSON",
      success: function(data) {

      }
    });
  });
</script>
<?= $this->endsection(); ?>