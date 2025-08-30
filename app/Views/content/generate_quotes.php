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
        <div class="main-content">
          <div class="loading-content hidden" id="loading-content">
            <div class="bars">
              <div class="bar"></div>
              <div class="bar"></div>
              <div class="bar"></div>
            </div>
          </div>
          <div class="box-generate">
            <button class="btn btn-success" id="btn-generate">Generate</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#btn-generate').click(function(e) {
    $('#loading-content').removeClass('hidden');
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "<?= base_url(); ?>generate-video-quotes",
      data: {
        q: 1
      },
      dataType: "JSON",
      success: function(data) {
        $('#loading-content').addClass('hidden');
        console.log(data);
        showPopup("success", "Sukses", "Quotes Berhasil Digenerate");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#loading-content').addClass('hidden');
        showPopup("no_internet", "Error", "Terjadi Kesalahan Jaringan. Jaringan lemot/terputus");
      }
    });
  });
</script>
<?= $this->endsection(); ?>