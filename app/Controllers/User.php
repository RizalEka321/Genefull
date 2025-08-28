<?php

namespace App\Controllers;

use App\Models\MUser;

class User extends BaseController
{
    protected $r;
    protected $v;
    protected $u;


    public function __construct()
    {
        $this->r = service('request');
        $this->v = \Config\Services::validation();
        $this->u = new MUser();
    }

    public function index()
    {
        $data = [
            "title" => "Management User"
        ];
        return view('content/user', $data);
    }

    function list_data()
    {

        if ($this->r->getMethod(true) == 'POST') {
            $lists = $this->u->getDatatables();
            $data = [];
            $no = $this->r->getPost("start");
            foreach ($lists as $k) {
                $btn = '
                <div class="btn-group btn-group-sm">
                    <a class="btn btn-danger "  href="javascript:void(0)" onClick="delete_data(' . "'" . enc($k->id_user) . "'" . ')">
                        <i class="bx bx-trash"></i>
                    </a>
                    <a class="btn btn-warning"  href="javascript:void(0)" onClick="edit_data(' . "'" . enc($k->id_user) . "'" . ')">
                        <i class="bx bxs-edit"></i>
                    </a>
                </div>';
                $no++;
                $row = [];
                $row[] = $btn;
                $row[] = $no;
                $row[] = $k->nama;
                $row[] = dec($k->username);
                $row[] = dec($k->password);
                $data[] = $row;
            }
            $output = [
                "draw" => $this->r->getPost('draw'),
                "recordsTotal" => $this->u->countAll(),
                "recordsFiltered" => $this->u->countFiltered(),
                "data" => $data
            ];
            echo json_encode($output);
        }
    }

    function save()
    {
        $validasi = [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required'
        ];
        $this->v->setRules($validasi);
        $isV = $this->v->withRequest($this->r)->run();
        if ($isV) {
            $data = [
                'nama' => $this->r->getPost('nama'),
                'username' => enc($this->r->getPost('username')),
                'password' => enc($this->r->getPost('password')),
                'status' => 1
            ];
            $this->u->insert($data);
            echo json_encode(['status' => TRUE]);
        } else {
            echo json_encode(['status' => FALSE]);
        }
    }

    function edit()
    {
        $id = $this->request->getPost('q');
        $k = $this->u->where('id_user', dec($id))->get()->getRow();
        $data['nama'] = $k->nama;
        $data['username'] = dec($k->username);
        $data['password'] = dec($k->password);
        echo json_encode($data);
    }

    function update()
    {
        $id = $this->request->getGet('q');
        $validasi = [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
        ];
        $this->v->setRules($validasi);
        $isV = $this->v->withRequest($this->r)->run();
        if ($isV) {
            $data = [
                'nama' => $this->r->getPost('nama'),
                'username' => enc($this->r->getPost('username')),
                'password' => enc($this->r->getPost('password')),
                'status' => 1
            ];
            $this->u->update(dec($id), $data);
            echo json_encode(['status' => TRUE]);
        } else {
            echo json_encode(['status' => FALSE]);
        }
    }

    function delete()
    {
        $id = $this->request->getPost('q');
        $this->u->delete(dec($id));
        $this->u->reset_id();
        echo json_encode(['status' => TRUE]);
    }
}
