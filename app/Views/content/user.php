<?= $this->extend("template"); ?>
<?= $this->section("content"); ?>
<div class="row">
  <div class="col-lg-12">
    <div class="content-page">
      <div class="box-wrapper">
        <div class="top-content">
          <div class="title-content">
            <h1>Management User</h1>
          </div>
          <div class="box-button">
            <div class="btn-group">
              <button class="btn btn-primary" id="btn-create"><i class='bx bx-plus'></i>Create</button>
            </div>
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
          <div class="box-input hidden" id="input">
            <form id="form-add" method="post" role="form" class="was-validated" action="#">
              <div class="row input-form">
                <div class="judul-input">
                  <i class="bx bxs-circle bx-xs"></i>
                  <h3 id="judul-form">Tambah User</h3>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama"
                      required>
                    <div class="valid-feedback"><i>*valid</i> </div>
                    <div class="invalid-feedback"><i>*required</i> </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username"
                      required>
                    <div class="valid-feedback"><i>*valid</i> </div>
                    <div class="invalid-feedback"><i>*required</i> </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-2">
                    <label class="form-label">Password</label>
                    <input type="text" class="form-control" name="password" id="password"
                      required>
                    <div class="valid-feedback"><i>*valid</i> </div>
                    <div class="invalid-feedback"><i>*required</i> </div>
                  </div>
                </div>
              </div>
              <div class="button-form">
                <div id="btn-group" class="btn-group btn-kebawah">
                  <button id="btn-save" type="submit" class="btn btn-primary">
                    <i class="bx bxs-save"></i>Save
                  </button>
                  <button id="btn-reset" type="reset" onClick="reset_form()" class="btn btn-danger">
                    <i class="bx bx-reset"></i> Reset
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="box-data" id="data">
            <table id="table" class="table table-bordered">
              <thead>
                <tr>
                  <th>Aksi</th>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Username</th>
                  <th>Password</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  let type = 0;

  $('#btn-create').click(function(e) {
    e.preventDefault();
    $('#loading-content').removeClass('hidden');
    $('#btn-create').prop('disabled', true);

    setTimeout(function() {
      if (type == 0) {
        add();
      } else {
        back();
      }
      $('#loading-content').addClass('hidden');
      $('#btn-create').prop('disabled', false);
    }, 300);
  });

  function add() {
    type = 1;
    reset_form();
    $('#data').addClass('hidden');
    $('#input').removeClass('hidden');
    $('#nama').focus();
    $('#btn-create').removeClass('btn-primary').addClass('btn-danger');
    $('#btn-create').html('<i class="bx bx-arrow-back"></i> Back');
  }

  function back() {
    type = 0;
    $('#input').addClass('hidden');
    $('#data').removeClass('hidden');
    $('#btn-create').removeClass('btn-danger').addClass('btn-primary');
    $('#btn-create').html('<i class="bx bx-plus"></i> Create');
  }

  var table = $('#table').DataTable({
    "searching": true,
    "processing": false,
    "responsive": true,
    "serverSide": true,
    'paging': true,
    'lengthChange': true,
    'ordering': false,
    'info': false,
    'autoWidth': false,
    "language": {
      'paginate': {
        'first': '<i class="bx bx-chevrons-left"></i>',
        'previous': '<i class="bx bxs-left-arrow-alt"></i>',
        'next': '<i class="bx bxs-right-arrow-alt"></i>',
        'last': '<i class="bx bx-chevrons-right"></i>'
      }
    },
    "aLengthMenu": [
      [10, 25, 50],
      [10, 25, 50]
    ],
    "ajax": {
      "url": "<?php echo site_url('list-user') ?>",
      "type": "POST",
    },
    "columnDefs": [{
        className: "text-center",
        targets: [0],
        width: '10%',
        orderable: false,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).css({
            'vertical-align': 'middle',
          });
        }
      }, {
        targets: 1,
        className: "text-center",
        width: '5%',
        orderable: false,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).css({
            'vertical-align': 'middle',
          });
        }
      }, {
        targets: 2,
        orderable: false,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).css({
            'vertical-align': 'middle',
          });
        }
      },
      {
        targets: [3, 4],
        width: '25%',
        orderable: false,
        createdCell: function(td, cellData, rowData, row, col) {
          $(td).css({
            'vertical-align': 'middle',
          });
        }
      }
    ],
  });

  function reload() {
    table.ajax.reload(null, false);
  }

  function reset_form() {
    $('#form-add')[0].reset();
    $('#btn-save').html('<i class = "bx bxs-save" > </i>Save');
    $('#form-add').attr('action', "<?php echo base_url('save-user'); ?>");
    $('#nama').focus();
    $('#judul-form').text("Tambah User");
  }

  $(function() {
    $('#form-add').submit(function(evt) {
      $('#loading-content').removeClass('hidden');
      evt.preventDefault();
      evt.stopImmediatePropagation();
      var url = $(this).attr('action');
      var formData = new FormData($(this)[0]);
      $.ajax({
        url: url,
        type: "POST",
        dataType: "JSON",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          $('#loading-content').addClass('hidden');
          if (data.status) {
            showPopup("success", "Sukses", "User Berhasil Disimpan");
            back();
            reset_form();
            reload();
          } else {
            console.log('gagal');
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $('#loading-content').addClass('hidden');
          showPopup("no_internet", "Error", "Terjadi Kesalahan Jaringan. Jaringan lemot/terputus");
        }
      });
    });
  });

  function edit_data(id) {
    $('#loading-content').removeClass('hidden');
    $.ajax({
      url: "<?php echo site_url('edit-user') ?>",
      type: "POST",
      data: {
        q: id
      },
      dataType: "JSON",
      success: function(data) {
        $('#loading-content').addClass('hidden');
        add();
        $('#form-add').attr('action', "<?php echo base_url('update-user?q='); ?>" + id);
        $('#nama').val(data.nama);
        $('#username').val(data.username);
        $('#password').val(data.password);
        $('#btn-save').html('<i class = "bx bxs-save" > </i>Update');
        $('#nama').focus();
        $('#judul-form').text("Edit User");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#loading-content').addClass('hidden');
        showPopup("no_internet", "Upss", "Terjadi Kesalahan Jaringan. Jaringan lemot/terputus");
      }
    });
  }

  function delete_data(x) {
    showConfirm(
      "Hapus Data",
      "Apakah Anda yakin ingin menghapus user ini?",
      function() {
        $('#loading-content').removeClass('hidden');
        $.ajax({
          url: "<?php echo site_url('delete-user') ?>",
          type: "POST",
          data: {
            q: x
          },
          dataType: "JSON",
          success: function(data) {
            $('#loading-content').addClass('hidden');
            if (data.status) {
              reload();
              showPopup("success", "Sukses", "User Berhasil Dihapus");
            } else {
              showPopup("error", "Error", "User Gagal Dihapus");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $('#loading-content').addClass('hidden');
            showPopup("no_internet", "Upss", "Terjadi Kesalahan Jaringan. Jaringan lemot/terputus");
          }
        });
      }
    );
  }
</script>
<?= $this->endsection(); ?>