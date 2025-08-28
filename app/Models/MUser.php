<?php

namespace App\Models;

use CodeIgniter\Model;

class MUser extends Model
{
  protected $table = 'user';
  protected $primaryKey = 'id_user';
  protected $allowedFields = ['id_user', 'username', 'password', 'nama', 'status'];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $dateFormat    = 'datetime';

  protected $column_search = ['nama', 'username', 'password'];
  protected $column_order = ['id_user', 'username', 'password', 'nama'];
  protected $order = ['id_user' => 'ASC'];

  protected $db;
  protected $dt;

  public function __construct()
  {
    parent::__construct();
    $this->db = db_connect();
    $this->dt = $this->db->table($this->table . ' a');
  }

  private function getDatatablesQuery()
  {
    $i = 0;
    foreach ($this->column_search as $item) {
      if (isset($_POST['search']['value']) && $_POST['search']['value']) {
        if ($i === 0) {
          $this->dt->groupStart();
          $this->dt->like($item, $_POST['search']['value']);
        } else {
          $this->dt->orLike($item, $_POST['search']['value']);
        }
        if ($i === count($this->column_search) - 1) {
          $this->dt->groupEnd();
        }
      }
      $i++;
    }

    if (isset($_POST['order'])) {
      $col_index = $_POST['order']['0']['column'];
      $dir = $_POST['order']['0']['dir'];
      $this->dt->orderBy($this->column_order[$col_index], $dir);
    } else {
      $this->dt->orderBy(key($this->order), $this->order[key($this->order)]);
    }
  }

  public function getDatatables()
  {
    $this->getDatatablesQuery();
    if (isset($_POST['length']) && $_POST['length'] != -1) {
      $this->dt->limit($_POST['length'], $_POST['start']);
    }
    $query = $this->dt->get();
    return $query->getResult();
  }

  public function countFiltered()
  {
    $this->getDatatablesQuery();
    return $this->dt->countAllResults(false);
  }

  public function countAll()
  {
    return $this->db->table($this->table)->countAllResults();
  }

  function reset_id()
  {
    $this->db->query('ALTER TABLE ' . $this->table . ' AUTO_INCREMENT = 1');
  }
}
